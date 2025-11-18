@include('layouts.navbar')

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Transaction History - Pawtopia</title>
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
            max-width: 1000px;
            margin: 60px auto;
            padding: 40px;
        }

        .page-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .page-title {
            font-size: 36px;
            font-weight: 700;
            color: #8A6552;
            margin-bottom: 10px;
        }

        .page-subtitle {
            font-size: 16px;
            color: #999;
        }

        .transactions-list {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .transaction-card {
            background-color: #fbe1c3;
            border-radius: 15px;
            padding: 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .transaction-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }

        .transaction-info {
            flex: 1;
        }

        .transaction-order {
            font-size: 14px;
            color: #8A6552;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .transaction-title {
            font-size: 18px;
            font-weight: 600;
            color: #674337;
            margin-bottom: 8px;
        }

        .transaction-details {
            font-size: 14px;
            color: #999;
            display: flex;
            gap: 20px;
        }

        .transaction-amount {
            text-align: right;
            margin: 0 20px;
        }

        .amount-value {
            font-size: 24px;
            font-weight: 700;
            color: #8A6552;
            margin-bottom: 5px;
        }

        .amount-date {
            font-size: 13px;
            color: #999;
        }

        .transaction-status {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
        }

        .status-badge {
            display: inline-block;
            padding: 8px 20px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            text-align: center;
            min-width: 100px;
        }

        .status-settlement {
            background-color: #d4edda;
            color: #155724;
        }

        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-failed {
            background-color: #f8d7da;
            color: #721c24;
        }

        .action-btn {
            padding: 8px 20px;
            border-radius: 20px;
            border: none;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s ease;
            background-color: #8A6552;
            color: white;
        }

        .action-btn:hover {
            background-color: #6c4f3d;
            transform: translateY(-2px);
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }

        .empty-icon {
            font-size: 80px;
            margin-bottom: 20px;
        }

        .empty-title {
            font-size: 24px;
            font-weight: 600;
            color: #8A6552;
            margin-bottom: 10px;
        }

        .empty-text {
            font-size: 16px;
            color: #999;
            margin-bottom: 30px;
        }

        .pagination {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 30px;
        }

        .pagination a,
        .pagination span {
            padding: 10px 15px;
            border-radius: 10px;
            text-decoration: none;
            color: #8A6552;
            background-color: #fbe1c3;
            transition: all 0.3s ease;
        }

        .pagination a:hover {
            background-color: #f5d4a8;
        }

        .pagination .active {
            background-color: #8A6552;
            color: white;
        }

        @media (max-width: 768px) {
            .transaction-card {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            .transaction-amount {
                text-align: left;
                margin: 0;
            }

            .transaction-status {
                align-items: flex-start;
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="page-header">
            <h1 class="page-title">Transaction History 💳</h1>
            <p class="page-subtitle">View all your payment transactions</p>
        </div>

        @if($transactions->count() > 0)
            <div class="transactions-list">
                @foreach($transactions as $transaction)
                    <div class="transaction-card">
                        <div class="transaction-info">
                            <div class="transaction-order">#{{ $transaction->order_id }}</div>
                            <div class="transaction-title">
                                @if($transaction->transactable_type === 'App\Models\Booking')
                                    {{ ucfirst($transaction->transactable->service_type) }} - {{ $transaction->transactable->pet_name }}
                                @else
                                    Transaction
                                @endif
                            </div>
                            <div class="transaction-details">
                                <span>{{ $transaction->created_at->format('d M Y') }}</span>
                                @if($transaction->payment_type)
                                    <span>{{ strtoupper(str_replace('_', ' ', $transaction->payment_type)) }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="transaction-amount">
                            <div class="amount-value">Rp {{ number_format($transaction->gross_amount, 0, ',', '.') }}</div>
                            @if($transaction->paid_at)
                                <div class="amount-date">Paid: {{ $transaction->paid_at->format('d M Y') }}</div>
                            @endif
                        </div>

                        <div class="transaction-status">
                            <span class="status-badge 
                                @if($transaction->transaction_status === 'settlement')
                                    status-settlement
                                @elseif($transaction->transaction_status === 'pending')
                                    status-pending
                                @else
                                    status-failed
                                @endif
                            ">
                                {{ strtoupper($transaction->transaction_status) }}
                            </span>

                            @if($transaction->transaction_status === 'settlement')
                                @if($transaction->transactable_type === 'App\Models\Booking')
                                    <a href="{{ route('booking.show', $transaction->transactable->id) }}" class="action-btn">
                                        View Booking
                                    </a>
                                @endif
                            @elseif($transaction->transaction_status === 'pending')
                                <button class="action-btn" onclick="payNow('{{ $transaction->snap_token }}', '{{ $transaction->order_id }}')">
                                    Pay Now
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="pagination">
                {{ $transactions->links() }}
            </div>
        @else
            <div class="empty-state">
                <div class="empty-icon">📭</div>
                <h2 class="empty-title">No Transactions Yet</h2>
                <p class="empty-text">You haven't made any transactions yet. Start booking our services!</p>
                <a href="{{ route('booking') }}" class="action-btn">Book Now</a>
            </div>
        @endif
    </div>

    <script>
        function payNow(snapToken, orderId) {
            if (!snapToken) {
                alert('Payment session has expired. Please create a new booking.');
                return;
            }

            snap.pay(snapToken, {
                onSuccess: function(result) {
                    window.location.href = '/payment/success/' + orderId;
                },
                onPending: function(result) {
                    alert('Payment is pending. Please complete your payment.');
                },
                onError: function(result) {
                    window.location.href = '/payment/failed/' + orderId;
                },
                onClose: function() {
                    console.log('Payment popup closed');
                }
            });
        }
    </script>

    <!-- Midtrans Snap -->
    <script src="https://app.{{ config('midtrans.is_production') ? '' : 'sandbox.' }}midtrans.com/snap/snap.js" 
            data-client-key="{{ config('midtrans.client_key') }}"></script>

    @include('layouts.footer')
</body>
</html>
