@include('layouts.navbar')

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Payment Failed - Pawtopia</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #fff;
            margin: 0;
            padding: 0;
            color: #674337;
        }

        .container {
            max-width: 700px;
            margin: 60px auto;
            padding: 40px;
            text-align: center;
        }

        .failed-icon {
            width: 120px;
            height: 120px;
            background-color: #f44336;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 30px;
            animation: shake 0.5s ease-out;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-10px); }
            75% { transform: translateX(10px); }
        }

        .failed-icon svg {
            width: 70px;
            height: 70px;
            stroke: white;
            stroke-width: 3;
            stroke-linecap: round;
            stroke-linejoin: round;
            fill: none;
        }

        .failed-title {
            font-size: 32px;
            font-weight: 700;
            color: #8A6552;
            margin-bottom: 15px;
        }

        .failed-subtitle {
            font-size: 18px;
            color: #999;
            margin-bottom: 30px;
        }

        .payment-details {
            background-color: #ffe5e5;
            border-radius: 15px;
            padding: 30px;
            margin: 30px 0;
            text-align: left;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px dashed #ffb3b3;
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-label {
            color: #8A6552;
        }

        .detail-value {
            font-weight: 600;
            color: #674337;
        }

        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
        }

        .status-failed {
            background-color: #f8d7da;
            color: #721c24;
        }

        .reason-box {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            text-align: left;
            border-radius: 5px;
        }

        .reason-box strong {
            color: #8A6552;
        }

        .button-group {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 30px;
        }

        .btn {
            padding: 12px 30px;
            border-radius: 25px;
            border: none;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background-color: #8A6552;
            color: white;
        }

        .btn-primary:hover {
            background-color: #6c4f3d;
            transform: translateY(-2px);
        }

        .btn-secondary {
            background-color: #fbe1c3;
            color: #8A6552;
        }

        .btn-secondary:hover {
            background-color: #f5d4a8;
        }

        .info-text {
            font-size: 14px;
            color: #999;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="failed-icon">
            <svg viewBox="0 0 52 52">
                <line x1="16" y1="16" x2="36" y2="36"/>
                <line x1="36" y1="16" x2="16" y2="36"/>
            </svg>
        </div>

        <h1 class="failed-title">Payment Failed</h1>
        <p class="failed-subtitle">Unfortunately, your payment could not be processed</p>

        <div class="reason-box">
            <strong>Reason:</strong> 
            @if($transaction->transaction_status === 'cancel')
                Payment was cancelled by user
            @elseif($transaction->transaction_status === 'expire')
                Payment session has expired
            @elseif($transaction->transaction_status === 'deny')
                Payment was declined
            @else
                Payment could not be completed
            @endif
        </div>

        <div class="payment-details">
            <div class="detail-row">
                <span class="detail-label">Order ID</span>
                <span class="detail-value">{{ $transaction->order_id }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Transaction Date</span>
                <span class="detail-value">{{ $transaction->created_at->format('d M Y, H:i') }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Status</span>
                <span class="detail-value">
                    <span class="status-badge status-failed">{{ strtoupper($transaction->transaction_status) }}</span>
                </span>
            </div>

            @if($transaction->transactable_type === 'App\Models\Booking')
                <div class="detail-row">
                    <span class="detail-label">Service</span>
                    <span class="detail-value">{{ ucfirst($transaction->transactable->service_type) }} - {{ $transaction->transactable->pet_name }}</span>
                </div>
            @endif

            <div class="detail-row">
                <span class="detail-label">Amount</span>
                <span class="detail-value">Rp {{ number_format($transaction->gross_amount, 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="button-group">
            @if($transaction->transactable_type === 'App\Models\Booking' && $transaction->transaction_status !== 'settlement')
                <button class="btn btn-primary" onclick="retryPayment()">Retry Payment</button>
            @endif
            <a href="{{ route('home') }}" class="btn btn-secondary">Back to Home</a>
        </div>

        <p class="info-text">
            Need help? Contact our support team at support@pawtopia.com
        </p>
    </div>

    <script>
        async function retryPayment() {
            try {
                const response = await fetch('{{ route("payment.booking", $transaction->transactable->id ?? 0) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                const data = await response.json();

                if (data.success && data.snap_token) {
                    // Open Midtrans Snap
                    snap.pay(data.snap_token, {
                        onSuccess: function(result) {
                            window.location.href = '/payment/success/' + data.order_id;
                        },
                        onPending: function(result) {
                            alert('Payment is pending. Please complete your payment.');
                        },
                        onError: function(result) {
                            alert('Payment failed. Please try again.');
                        },
                        onClose: function() {
                            console.log('Payment popup closed');
                        }
                    });
                } else {
                    alert('Failed to create payment: ' + (data.message || 'Unknown error'));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            }
        }
    </script>

    <!-- Midtrans Snap -->
    <script src="https://app.{{ config('midtrans.is_production') ? '' : 'sandbox.' }}midtrans.com/snap/snap.js" 
            data-client-key="{{ config('midtrans.client_key') }}"></script>

    @include('layouts.footer')
</body>
</html>
