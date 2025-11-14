<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;

class PaymentController extends Controller
{
    public function __construct()
    {
        // Set Midtrans configuration
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');

        // Temporary debug log for Midtrans configuration
        Log::info('Midtrans config in constructor', [
            'server_key_prefix' => substr(Config::$serverKey, 0, 15),
            'is_production' => Config::$isProduction,
        ]);
    }

    /**
     * Create payment for booking
     */
    public function createBookingPayment(Booking $booking)
    {
        try {
            // Check if user is authenticated
            if (!Auth::guard('member')->check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please login first'
                ], 401);
            }

            $currentMemberId = Auth::guard('member')->user()->id;
            
            // Validate booking belongs to authenticated user (with type casting)
            if ((int)$booking->member_id !== (int)$currentMemberId) {
                \Log::warning('Unauthorized payment attempt', [
                    'booking_id' => $booking->id,
                    'booking_member_id' => $booking->member_id,
                    'booking_member_type' => gettype($booking->member_id),
                    'current_member_id' => $currentMemberId,
                    'current_member_type' => gettype($currentMemberId),
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized: This booking does not belong to you',
                    'debug' => [
                        'booking_member_id' => $booking->member_id,
                        'your_member_id' => $currentMemberId,
                    ]
                ], 403);
            }

            // Check if booking already has a paid transaction
            if ($booking->transaction && $booking->transaction->isPaid()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Booking sudah dibayar'
                ], 400);
            }

            // Check if there's pending transaction, use it instead of creating new one
            $transaction = $booking->transaction()->pending()->first();

            if (!$transaction) {
                // Create new transaction
                $transaction = DB::transaction(function () use ($booking) {
                    return Transaction::create([
                        'order_id' => Transaction::generateOrderId(),
                        'member_id' => $booking->member_id,
                        'transactable_type' => Booking::class,
                        'transactable_id' => $booking->id,
                        'gross_amount' => $booking->total_price,
                        'transaction_status' => 'pending',
                        'expired_at' => now()->addHours(24),
                    ]);
                });
            }

            // Load relationships
            $booking->load(['member', 'pet']);
            $member = $booking->member;
            $pet = $booking->pet;

            // Prepare transaction details for Midtrans
            $params = [
                'transaction_details' => [
                    'order_id' => $transaction->order_id,
                    'gross_amount' => (int) $transaction->gross_amount,
                ],
                'customer_details' => [
                    'first_name' => $member->name,
                    'email' => $member->email ?? 'member@pawtopia.com',
                    'phone' => $member->phone ?? '08123456789',
                ],
                'item_details' => [
                    [
                        'id' => 'BOOKING-' . $booking->id,
                        'price' => (int) $booking->total_price,
                        'quantity' => 1,
                        'name' => ucfirst($booking->service_type) . ' - ' . ($pet ? $pet->name : 'Pet') . ' (' . $booking->duration_days . ' hari)',
                    ],
                ],
                'enabled_payments' => [
                    'credit_card', 'gopay', 'shopeepay', 'qris', 
                    'bca_va', 'bni_va', 'bri_va', 'permata_va', 
                    'other_va', 'indomaret', 'alfamart'
                ],
                'callbacks' => [
                    'finish' => route('payment.history'),
                    'pending' => route('payment.history'),
                    'error' => route('payment.history'),
                ],
            ];

            // Get Snap Token from Midtrans
            $snapToken = Snap::getSnapToken($params);

            // Update transaction with snap token
            $transaction->update(['snap_token' => $snapToken]);

            return response()->json([
                'success' => true,
                'snap_token' => $snapToken,
                'order_id' => $transaction->order_id,
            ]);

        } catch (\Exception $e) {
            Log::error('Payment creation failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat pembayaran: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Handle Midtrans notification callback
     */
    public function handleNotification(Request $request)
    {
        try {
            // Create notification instance
            $notification = new Notification();

            // Get transaction details
            $transactionStatus = $notification->transaction_status;
            $fraudStatus = $notification->fraud_status;
            $orderId = $notification->order_id;
            $transactionId = $notification->transaction_id;
            $paymentType = $notification->payment_type;

            // Find transaction by order_id
            $transaction = Transaction::where('order_id', $orderId)->first();

            if (!$transaction) {
                return response()->json([
                    'success' => false,
                    'message' => 'Transaction not found'
                ], 404);
            }

            // Prepare payment details
            $paymentDetails = [
                'transaction_id' => $transactionId,
                'payment_type' => $paymentType,
                'transaction_status' => $transactionStatus,
                'fraud_status' => $fraudStatus,
                'notification_data' => $notification->getResponse(),
            ];

            // Update transaction based on status
            if ($transactionStatus == 'capture') {
                if ($fraudStatus == 'accept') {
                    $this->handleSuccess($transaction, $transactionId, $paymentDetails);
                }
            } elseif ($transactionStatus == 'settlement') {
                $this->handleSuccess($transaction, $transactionId, $paymentDetails);
            } elseif ($transactionStatus == 'pending') {
                $transaction->update([
                    'payment_type' => $paymentType,
                    'transaction_id' => $transactionId,
                    'payment_details' => $paymentDetails,
                ]);
            } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
                $this->handleFailed($transaction, $transactionStatus, $paymentDetails);
            }

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            Log::error('Payment notification failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Handle successful payment
     */
    private function handleSuccess(Transaction $transaction, $transactionId, $paymentDetails)
    {
        DB::transaction(function () use ($transaction, $transactionId, $paymentDetails) {
            // Mark transaction as paid
            $transaction->markAsPaid($transactionId, $paymentDetails);

            // Update booking status to confirmed if it's a booking
            if ($transaction->transactable_type === Booking::class) {
                $booking = $transaction->transactable;
                if ($booking && $booking->status === 'pending') {
                    $booking->update(['status' => 'confirmed']);
                }
            }
        });
    }

    /**
     * Handle failed payment
     */
    private function handleFailed(Transaction $transaction, $status, $paymentDetails)
    {
        $transaction->markAsFailed($status, $paymentDetails);
    }

    /**
     * Show payment success page
     */
    public function success($orderId)
    {
        $transaction = Transaction::where('order_id', $orderId)
            ->with(['transactable', 'member'])
            ->firstOrFail();

        // Validate user
        if ($transaction->member_id !== Auth::guard('member')->id()) {
            abort(403, 'Unauthorized access');
        }

        return view('payment.success', compact('transaction'));
    }

    /**
     * Show payment failed page
     */
    public function failed($orderId)
    {
        $transaction = Transaction::where('order_id', $orderId)
            ->with(['transactable', 'member'])
            ->firstOrFail();

        // Validate user
        if ($transaction->member_id !== Auth::guard('member')->id()) {
            abort(403, 'Unauthorized access');
        }

        return view('payment.failed', compact('transaction'));
    }

    /**
     * Show transaction history
     */
    public function history()
    {
        $member = Auth::guard('member')->user();
        
        $transactions = Transaction::where('member_id', $member->id)
            ->with('transactable')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('payment.history', compact('transactions'));
    }

    /**
     * Get transaction details
     */
    public function show($orderId)
    {
        $transaction = Transaction::where('order_id', $orderId)
            ->with(['transactable', 'member'])
            ->firstOrFail();

        // Validate user
        if ($transaction->member_id !== Auth::guard('member')->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => $transaction
        ]);
    }
}
