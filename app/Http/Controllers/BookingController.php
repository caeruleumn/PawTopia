<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Member;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    /**
     * Tampilkan form booking
     */
    public function create()
    {
        return view('booking');
    }

    /**
     * Simpan booking baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'pet_id' => 'required|exists:pets,id',
            'service_type' => 'required|string|in:grooming,boarding,veterinary,training',
            'booking_date' => 'required|date|after_or_equal:today',
            'booking_time' => 'required|date_format:H:i',
            'duration_days' => 'required|integer|min:1|max:30',
            'drop_off_type' => 'required|in:owner,daycare',
            'pick_up_type' => 'required|in:owner,daycare',
            'distance_km' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Get pet information
        $pet = \App\Models\Pet::findOrFail($validated['pet_id']);
        
        // Verify pet ownership
        $currentMember = Auth::guard('member')->user();
        if (!$currentMember) {
            \Log::error('Booking attempt without login');
            return response()->json([
                'message' => 'You must be logged in to make a booking.'
            ], 401);
        }
        
        // Verify member exists in database
        $memberCheck = \App\Models\Member::find($currentMember->id);
        if (!$memberCheck) {
            \Log::error('Member ID from session does not exist in database', [
                'session_member_id' => $currentMember->id,
                'session_member_name' => $currentMember->name
            ]);
            
            // Clear corrupted session and force re-login
            Auth::guard('member')->logout();
            return response()->json([
                'message' => 'Session corrupted. Please login again.',
                'redirect' => route('login')
            ], 401);
        }
        
        $currentMemberId = $memberCheck->id;
        $petMemberId = $pet->member_id;
        
        // Debug logging
        \Log::info('Booking ownership check', [
            'pet_id' => $pet->id,
            'pet_name' => $pet->name,
            'pet_member_id' => $petMemberId,
            'pet_member_id_type' => gettype($petMemberId),
            'current_member_id' => $currentMemberId,
            'current_member_id_type' => gettype($currentMemberId),
            'current_member_name' => $currentMember->name,
            'comparison_strict' => ($petMemberId === $currentMemberId ? 'MATCH' : 'MISMATCH'),
            'comparison_loose' => ($petMemberId == $currentMemberId ? 'MATCH' : 'MISMATCH'),
        ]);
        
        // Use explicit integer casting to avoid type mismatch
        if ((int)$petMemberId !== (int)$currentMemberId) {
            \Log::warning('Pet ownership verification failed', [
                'pet_id' => $pet->id,
                'pet_member_id' => $petMemberId,
                'current_member_id' => $currentMemberId,
                'user' => $currentMember->name
            ]);
            return response()->json([
                'message' => 'Unauthorized: This pet does not belong to you.',
                'debug' => [
                    'pet_owner_id' => $petMemberId,
                    'your_id' => $currentMemberId
                ]
            ], 403);
        }
        
        \Log::info('Pet ownership verified successfully', [
            'pet_id' => $pet->id,
            'member_id' => $currentMemberId
        ]);

        // Calculate pricing
        $basePrice = 50000; // Rp 50,000 per day
        $durationDays = $validated['duration_days'];
        $deliveryFee = 0;
        
        // Calculate delivery fee if applicable
        if ($validated['drop_off_type'] === 'daycare' || $validated['pick_up_type'] === 'daycare') {
            $distance = (float) ($validated['distance_km'] ?? 0);
            $feePerTrip = max(20000, $distance * 10000);
            
            if ($validated['drop_off_type'] === 'daycare') {
                $deliveryFee += $feePerTrip;
            }
            if ($validated['pick_up_type'] === 'daycare') {
                $deliveryFee += $feePerTrip;
            }
        }
        
        $totalPrice = ($basePrice * $durationDays) + $deliveryFee;

        // Create booking and transaction in a database transaction
        DB::beginTransaction();
        try {
            $booking = Booking::create([
                'member_id' => $currentMemberId,
                'pet_id' => $pet->id,
                'service_type' => $validated['service_type'],
                'pet_name' => $pet->name,
                'pet_type' => $pet->type,
                'booking_date' => $validated['booking_date'],
                'booking_time' => $validated['booking_time'],
                'duration_days' => $durationDays,
                'drop_off_type' => $validated['drop_off_type'],
                'pick_up_type' => $validated['pick_up_type'],
                'distance_km' => $validated['distance_km'] ?? null,
                'base_price' => $basePrice,
                'delivery_fee' => $deliveryFee,
                'notes' => $validated['notes'],
                'status' => 'pending',
                'total_price' => $totalPrice
            ]);

            // Create transaction record (payment pending)
            $transaction = Transaction::create([
                'order_id' => Transaction::generateOrderId(),
                'member_id' => $currentMemberId,
                'transactable_type' => Booking::class,
                'transactable_id' => $booking->id,
                'gross_amount' => $totalPrice,
                'transaction_status' => 'pending',
                'expired_at' => now()->addHours(24),
            ]);

            DB::commit();

            // Jika request dari AJAX/JSON, kembalikan JSON supaya UI bisa menampilkan modal sukses tanpa redirect
            if ($request->expectsJson() || $request->ajax()) {
                $booking->load('member', 'pet', 'transaction');
                return response()->json([
                    'success' => true,
                    'message' => 'Booking berhasil dibuat! Silakan selesaikan pembayaran.',
                    'booking' => $booking,
                    'transaction' => $transaction,
                ], 201);
            }

            return redirect()->route('booking.success', $booking->id)
                             ->with('success', 'Booking berhasil dibuat! Silakan selesaikan pembayaran.');
                             
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Booking creation failed: ' . $e->getMessage());
            
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create booking: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->withErrors(['error' => 'Failed to create booking. Please try again.']);
        }
    }

    /**
     * Tampilkan detail booking
     */
    public function show(Booking $booking)
    {
        // Pastikan user hanya bisa lihat booking miliknya sendiri
        if (Auth::guard('member')->user()->id !== $booking->member_id) {
            abort(403, 'Unauthorized access');
        }

        $booking->load('transaction');
        return view('booking.show', compact('booking'));
    }

    /**
     * Tampilkan halaman sukses booking
     */
    public function success(Booking $booking)
    {
        $booking->load('transaction');
        return view('booking.success', compact('booking'));
    }

    /**
     * Cancel booking
     */
    public function cancel(Booking $booking)
    {
        // Pastikan user hanya bisa cancel booking miliknya sendiri
        if (Auth::guard('member')->user()->id !== $booking->member_id) {
            abort(403, 'Unauthorized access');
        }

        // Hanya bisa cancel jika status masih pending
        if ($booking->status !== 'pending') {
            return back()->with('error', 'Booking tidak bisa dibatalkan karena sudah dikonfirmasi.');
        }

        $booking->update(['status' => 'cancelled']);

        return back()->with('success', 'Booking berhasil dibatalkan.');
    }

    // ========== ADMIN METHODS ==========

    /**
     * Tampilkan semua booking untuk admin
     */
    public function adminIndex()
    {
        $bookings = Booking::with('member')
                          ->latest()
                          ->paginate(15);

        return view('admin.managebooking', compact('bookings'));
    }

    /**
     * API: Create booking by admin from Manage Booking page.
     */
    public function adminStore(Request $request)
    {
        $validated = $request->validate([
            'customer' => 'required|string|max:255',
            'phone' => 'required|string|max:50',
            'email' => 'nullable|email',
            'pet' => 'required|string|max:255',
            'petType' => 'required|string|max:50',
            'checkin' => 'required|date',
            'checkout' => 'required|date|after_or_equal:checkin',
            'status' => 'required|in:pending,confirmed,checked-in,completed,cancelled,on-pickup',
            'service' => 'nullable|string|max:100',
            'price' => 'required|numeric|min:0',
        ]);

        // Find or create member by phone (primary) or email
        $member = Member::query()
            ->when($validated['phone'] ?? null, fn($q) => $q->orWhere('phone', $validated['phone']))
            ->when($validated['email'] ?? null, fn($q) => $q->orWhere('email', $validated['email']))
            ->first();

        if (!$member) {
            $member = Member::create([
                'name' => $validated['customer'],
                'phone' => $validated['phone'],
                'email' => $validated['email'] ?? null,
                'role' => 'customer',
            ]);
        } else {
            // Update member basic info if changed
            $member->fill([
                'name' => $validated['customer'],
                'phone' => $validated['phone'],
            ]);
            if (!empty($validated['email'])) {
                $member->email = $validated['email'];
            }
            $member->save();
        }

        // Build notes to include delivery/service info if provided
        $notes = $request->input('notes');
        if ($validated['service'] ?? null) {
            $notes = trim(((string) $notes) . (empty($notes) ? '' : "\n") . 'Service: ' . $validated['service']);
        }

        // Hitung durasi menginap berdasarkan check-in dan check-out (inklusif)
        $checkinDate = Carbon::parse($validated['checkin']);
        $checkoutDate = Carbon::parse($request->input('checkout', $validated['checkin']));
        $durationDays = $checkinDate->diffInDays($checkoutDate) + 1;

        // Create booking (map checkin -> booking_date, default booking_time)
        $booking = Booking::create([
            'member_id' => $member->id,
            // Treat all admin-created entries here as boarding by default
            'service_type' => 'boarding',
            'pet_name' => $validated['pet'],
            'pet_type' => strtolower($validated['petType']),
            'booking_date' => $validated['checkin'],
            'booking_time' => '09:00:00',
            'duration_days' => $durationDays,
            'notes' => $notes,
            'status' => $validated['status'],
            'total_price' => $validated['price'],
        ]);

        $booking->load('member');

        return response()->json([
            'message' => 'Booking created successfully',
            'booking' => $booking,
        ], 201);
    }

    /**
     * API: List bookings untuk admin (JSON) dengan filter & pagination.
     */
    public function adminList(Request $request)
    {
        $query = Booking::with('member')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        if ($request->filled('q')) {
            $search = $request->string('q');
            $query->where(function ($q) use ($search) {
                $q->where('pet_name', 'like', "%{$search}%")
                  ->orWhere('pet_type', 'like', "%{$search}%")
                  ->orWhere('service_type', 'like', "%{$search}%");
            });
        }

        $perPage = (int) $request->input('per_page', 10);
        $bookings = $query->paginate($perPage);

        return response()->json($bookings);
    }

    /**
     * API: Detail 1 booking untuk admin (JSON)
     */
    public function adminShow(Booking $booking)
    {
        $booking->load('member');
        return response()->json($booking);
    }

    /**
     * API: Update booking by admin
     */
    public function adminUpdate(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'customer' => 'required|string|max:255',
            'phone' => 'required|string|max:50',
            'email' => 'nullable|email',
            'pet' => 'required|string|max:255',
            'petType' => 'required|string|max:50',
            'checkin' => 'required|date',
            'checkout' => 'required|date|after_or_equal:checkin',
            'status' => 'required|in:pending,confirmed,checked-in,completed,cancelled,on-pickup',
            'service' => 'nullable|string|max:100',
            'price' => 'required|numeric|min:0',
        ]);

        // Update linked member (by id)
        $booking->load('member');
        $member = $booking->member;
        if ($member) {
            $member->name = $validated['customer'];
            $member->phone = $validated['phone'];
            if (!empty($validated['email'])) {
                $member->email = $validated['email'];
            }
            $member->save();
        }

        $notes = $request->input('notes');
        if ($validated['service'] ?? null) {
            $notes = trim(((string) $notes) . (empty($notes) ? '' : "\n") . 'Service: ' . $validated['service']);
        }

        // Hitung durasi menginap berdasarkan check-in dan check-out (inklusif)
        $checkinDate = Carbon::parse($validated['checkin']);
        $checkoutDate = Carbon::parse($request->input('checkout', $validated['checkin']));
        $durationDays = $checkinDate->diffInDays($checkoutDate) + 1;

        $booking->update([
            'pet_name' => $validated['pet'],
            'pet_type' => strtolower($validated['petType']),
            'booking_date' => $validated['checkin'],
            'notes' => $notes,
            'duration_days' => $durationDays,
            'status' => $validated['status'],
            'total_price' => $validated['price'],
        ]);

        $booking->load('member');

        return response()->json([
            'message' => 'Booking updated successfully',
            'booking' => $booking,
        ]);
    }

    /**
     * Update status booking (admin only)
     */
    public function updateStatus(Request $request, Booking $booking)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,checked-in,completed,cancelled,on-pickup'
        ]);

        $booking->update(['status' => $request->status]);

        return back()->with('success', 'Status booking berhasil diupdate.');
    }

    /**
     * Hapus booking (admin only)
     */
    public function destroy(Booking $booking)
    {
        $booking->delete();

        return back()->with('success', 'Booking berhasil dihapus.');
    }
}
