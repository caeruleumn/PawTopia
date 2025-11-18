@include('layouts.navbar')

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Payment Success - Pawtopia</title>
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

        .success-icon {
            width: 120px;
            height: 120px;
            background-color: #4CAF50;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 30px;
            animation: scaleIn 0.5s ease-out;
        }

        @keyframes scaleIn {
            from { transform: scale(0); }
            to { transform: scale(1); }
        }

        .success-icon svg {
            width: 70px;
            height: 70px;
            stroke: white;
            stroke-width: 3;
            stroke-linecap: round;
            stroke-linejoin: round;
            fill: none;
        }

        .success-title {
            font-size: 32px;
            font-weight: 700;
            color: #8A6552;
            margin-bottom: 15px;
        }

        .success-subtitle {
            font-size: 18px;
            color: #999;
            margin-bottom: 30px;
        }

        .payment-details {
            background-color: #fbe1c3;
            border-radius: 15px;
            padding: 30px;
            margin: 30px 0;
            text-align: left;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px dashed #d4b896;
        }

        .detail-row:last-child {
            border-bottom: none;
            font-weight: 600;
            font-size: 18px;
            padding-top: 20px;
            margin-top: 10px;
            border-top: 2px solid #8A6552;
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

        .status-success {
            background-color: #d4edda;
            color: #155724;
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
        <div class="success-icon">
            <svg viewBox="0 0 52 52">
                <path d="M14 27l8 8L38 19"/>
            </svg>
        </div>

        <h1 class="success-title">Payment Successful! 🎉</h1>
        <p class="success-subtitle">Your payment has been processed successfully</p>

        <div class="payment-details">
            <div class="detail-row">
                <span class="detail-label">Order ID</span>
                <span class="detail-value">{{ $transaction->order_id }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Payment Type</span>
                <span class="detail-value">{{ strtoupper(str_replace('_', ' ', $transaction->payment_type ?? 'N/A')) }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Transaction Date</span>
                <span class="detail-value">{{ $transaction->paid_at ? $transaction->paid_at->format('d M Y, H:i') : $transaction->created_at->format('d M Y, H:i') }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Status</span>
                <span class="detail-value">
                    <span class="status-badge status-success">PAID</span>
                </span>
            </div>

            @if($transaction->transactable_type === 'App\Models\Booking')
                <div class="detail-row">
                    <span class="detail-label">Service</span>
                    <span class="detail-value">{{ ucfirst($transaction->transactable->service_type) }} - {{ $transaction->transactable->pet_name }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Booking Date</span>
                    <span class="detail-value">{{ $transaction->transactable->booking_date->format('d M Y') }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Duration</span>
                    <span class="detail-value">{{ $transaction->transactable->duration_days }} days</span>
                </div>
            @endif

            <div class="detail-row">
                <span class="detail-label">Total Amount</span>
                <span class="detail-value">Rp {{ number_format($transaction->gross_amount, 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="button-group">
            @if($transaction->transactable_type === 'App\Models\Booking')
                <a href="{{ route('booking.show', $transaction->transactable->id) }}" class="btn btn-primary">View Booking Details</a>
            @endif
            <a href="{{ route('payment.history') }}" class="btn btn-secondary">View All Transactions</a>
        </div>

        <p class="info-text">
            A confirmation email has been sent to {{ $transaction->member->email }}
        </p>
    </div>

    @include('layouts.footer')
</body>
</html>
