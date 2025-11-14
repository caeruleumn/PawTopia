<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Booking History</title>
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: #F7F5F2; /* match site background to avoid clash */
      margin: 0;
      padding: 0; /* avoid affecting navbar spacing */
      color: #5a3b2e;
    }

    .container {
      width: 90%;
      margin: 0 auto;
      background: #fff;
      border-radius: 20px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.08);
      padding: 20px;
      overflow: hidden; /* biar radius kepotong rapi */
    }

    .title {
      font-size: 20px;
      font-weight: 600;
      color: #674337;
      margin-bottom: 20px;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      font-size: 14px;
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 4px 12px rgba(0,0,0,0.12); /* 👉 shadow buat tabel */
      background: #fff; /* pastikan background solid biar shadow kelihatan */
    }

    thead th {
      background: #F6A892;
      color: #674337;
      font-weight: 600;
      padding: 12px;
      text-align: center;
    }

    tbody tr {
      background: #fff8f5;
      transition: background 0.2s ease;
    }

    tbody tr:hover {
      background: #ffece5;
    }

    tbody td {
      padding: 14px;
      text-align: center;
      border-bottom: 1px solid #F2E1C9; /* only bottom border for cleaner look */
    }

    /* Rounded corners hanya di pinggir tabel */
    thead tr:first-child th:first-child {
      border-top-left-radius: 20px;
    }
    thead tr:first-child th:last-child {
      border-top-right-radius: 20px;
    }
    tbody tr:last-child td:first-child {
      border-bottom-left-radius: 20px;
    }
    tbody tr:last-child td:last-child {
      border-bottom-right-radius: 20px;
    }

    /* Status Badges */
    /* Status Badges (match other pages) */
    .status {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      height: 34px;
      min-width: 140px;
      padding: 0 16px;
      border-radius: 22px;
      font-size: 12px;
      font-weight: 700;
      letter-spacing: .5px;
      text-transform: uppercase;
      box-sizing: border-box;
    }

    .status.ongoing {
      background: linear-gradient(135deg, #FFE0B2, #F9D9A7);
      color: #6B4F3A;
      border: 1px solid #F5CBA7;
    }

    .status.complete {
      background: linear-gradient(135deg, #E2E3E5, #F8F9FA);
      color: #383D41;
      border: 1px solid #DEE2E6;
    }

    /* Buttons */
    .btn {
      display: inline-block;
      padding: 6px 16px;
      border-radius: 20px;
      font-size: 13px;
      font-weight: 500;
      text-decoration: none;
      transition: 0.2s;
    }

    .btn-rate {
      background: #ff8a65;
      color: #fff;
    }

    .btn-rate:hover {
      background: #ff7043;
    }

    .btn-disabled {
      background: #e0e0e0;
      color: #888;
      cursor: not-allowed;
      pointer-events: none;
    }

    .btn-pay {
      background: linear-gradient(135deg, #F07F62 0%, #E66A4E 100%);
      color: white;
      padding: 8px 16px;
      border-radius: 6px;
      border: none;
      font-size: 12px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      box-shadow: 0 2px 8px rgba(240, 127, 98, 0.3);
    }

    .btn-pay:hover {
      transform: translateY(-1px);
      box-shadow: 0 4px 12px rgba(240, 127, 98, 0.4);
    }

    .btn-pay:disabled {
      background: #ccc;
      cursor: not-allowed;
      transform: none;
    }

    .payment-status {
      display: inline-block;
      padding: 4px 12px;
      border-radius: 12px;
      font-size: 11px;
      font-weight: 600;
      text-transform: uppercase;
    }

    .payment-status.pending {
      background: #fff3cd;
      color: #856404;
      border: 1px solid #ffc107;
    }

    .payment-status.settlement,
    .payment-status.capture {
      background: #d4edda;
      color: #155724;
      border: 1px solid #28a745;
    }

    .payment-status.cancel,
    .payment-status.expire,
    .payment-status.failure {
      background: #f8d7da;
      color: #721c24;
      border: 1px solid #dc3545;
    }

    /* Responsive Style */
    @media (max-width: 768px) {
      table, thead, tbody, th, td, tr {
        display: block;
      }

      thead {
        display: none;
      }

      tr {
        margin-bottom: 15px;
        border-radius: 16px;
        padding: 12px;
        background: #fff8f5;
        box-shadow: 0 2px 6px rgba(0,0,0,0.05);
      }

      td {
        border: none;
        padding: 10px 0;
        text-align: left;
      }

      td::before {
        content: attr(data-label);
        font-weight: 600;
        color: #ff8a65;
        display: block;
        margin-bottom: 3px;
        font-size: 13px;
      }
    }
  </style>
</head>
  <body>
    @include('layouts.navbar')
    <div class="page-wrapper" style="padding: 30px;">
      <div class="container">
      <div class="title">
        Booking History
      </div>

      <table id="bookingTable">
        <thead>
          <tr>
            <th>No</th>
            <th>Service</th>
            <th>Pet</th>
            <th>Date</th>
            <th>Time</th>
            <th>Status</th>
            <th>Payment</th>
            <th>Price</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($bookings as $index => $booking)
            @php
              $isComplete = strtolower($booking->booking_status) === 'completed';
              $statusClass = $isComplete ? 'complete' : 'ongoing';
            @endphp
            <tr>
              <td data-label="No">{{ $index + 1 }}</td>
              <td data-label="Service">{{ ucfirst($booking->service_type) }}</td>
              <td data-label="Pet">{{ $booking->pet ? $booking->pet->name : 'N/A' }} ({{ $booking->pet ? ucfirst($booking->pet->type) : 'N/A' }})</td>
              <td data-label="Date">{{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}</td>
              <td data-label="Time">{{ \Carbon\Carbon::parse($booking->booking_time)->format('H:i') }}</td>
              <td data-label="Status">
                <span class="status {{ $statusClass }}">{{ ucfirst($booking->booking_status) }}</span>
              </td>
              <td data-label="Payment">
                @if($booking->transaction)
                  <span class="payment-status {{ strtolower($booking->transaction->transaction_status) }}">
                    {{ ucfirst($booking->transaction->transaction_status) }}
                  </span>
                @else
                  <span class="payment-status pending">No Transaction</span>
                @endif
              </td>
              <td data-label="Price">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
              <td data-label="Actions">
                <div style="display: flex; gap: 8px; justify-content: center; flex-wrap: wrap;">
                  <a href="{{ route('booking.show', $booking) }}" class="btn btn-rate" style="background: #17a2b8; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 12px;">View</a>
                  
                  @if($booking->transaction && $booking->transaction->transaction_status === 'pending' && $booking->booking_status === 'pending')
                    <button 
                      type="button" 
                      class="btn-pay" 
                      onclick="payNow({{ $booking->id }}, '{{ $booking->transaction->order_id }}')"
                    >
                      💳 Pay Now
                    </button>
                  @endif
                  
                  @if($booking->booking_status === 'pending')
                    <form action="{{ route('booking.cancel', $booking) }}" method="POST" style="display: inline;">
                      @csrf
                      <button type="submit" class="btn btn-disabled" style="background: #dc3545; color: white; padding: 6px 12px; border-radius: 4px; border: none; font-size: 12px; cursor: pointer;" onclick="return confirm('Are you sure you want to cancel this booking?')">Cancel</button>
                    </form>
                  @endif
                  @if($isComplete)
                    <button type="button" class="btn btn-rate" style="background: #ff8a65; color: #fff; padding: 6px 12px; border-radius: 4px; border: none; font-size: 12px; cursor: pointer;" onclick="openTestimonialModal({{ $booking->id }}, '{{ addslashes(ucfirst($booking->service_type)) }}')">Give Testimonial</button>
                  @endif
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="9" style="text-align:center; padding:20px; color:#9C6F4B;">
                <div style="padding: 40px;">
                  
                  <div style="font-size: 18px; font-weight: 600; margin-bottom: 10px;">No bookings yet</div>
                  <div style="font-size: 14px; color: #9C6F4B;">You haven't made any bookings yet. Start by booking a service for your pet!</div>
                  <a href="{{ route('booking') }}" style="display: inline-block; margin-top: 15px; padding: 10px 20px; background: #E57300; color: white; text-decoration: none; border-radius: 8px; font-weight: 600;">Make a Booking</a>
                </div>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    </div>
    @include('layouts.footer')
    
    <!-- Testimonial Modal -->
    <div id="testimonialModal" style="display:none; position: fixed; inset: 0; background: rgba(0,0,0,0.4); z-index: 1000; align-items: center; justify-content: center;">
      <div style="background: #fff; width: 90%; max-width: 500px; border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,0.2); overflow: hidden;">
        <div style="padding: 16px 20px; background: #F6A892; color: #674337; display:flex; align-items:center; justify-content: space-between;">
          <div style="font-weight: 700;">Give Testimonial</div>
          <button type="button" onclick="closeTestimonialModal()" style="background: transparent; border: none; font-size: 20px; cursor: pointer; color:#674337;">×</button>
        </div>
        <form id="testimonialForm" style="padding: 16px 20px;">
          @csrf
          <input type="hidden" name="booking_id" id="t_booking_id" />
          <div style="margin-bottom: 12px; color:#674337;">
            <div style="font-size: 13px; margin-bottom: 6px;">Service</div>
            <input type="text" id="t_service" disabled style="width:100%; padding:10px 12px; border:1px solid #ead9c8; border-radius:10px; background:#fff8f5; color:#674337;" />
          </div>
          <div style="margin-bottom: 12px; color:#674337;">
            <div style="font-size: 13px; margin-bottom: 6px;">Rating</div>
            <select name="rating" id="t_rating" required style="width:100%; padding:10px 12px; border:1px solid #ead9c8; border-radius:10px; background:#fff; color:#674337;">
              @for ($i = 5; $i >= 1; $i--)
                <option value="{{ $i }}">{{ $i }} - {{ str_repeat('★', $i) }}</option>
              @endfor
            </select>
          </div>
          <div style="margin-bottom: 12px; color:#674337;">
            <div style="font-size: 13px; margin-bottom: 6px;">Message</div>
            <textarea name="message" id="t_message" rows="4" maxlength="1000" required placeholder="Tulis pengalaman Anda..." style="width:100%; padding:10px 12px; border:1px solid #ead9c8; border-radius:10px; background:#fff; color:#674337;"></textarea>
          </div>
          <div style="display:flex; gap: 10px; justify-content: flex-end;">
            <button type="button" onclick="closeTestimonialModal()" style="padding: 10px 16px; border-radius: 10px; border:1px solid #ccc; background:#f5f5f5; cursor: pointer;">Cancel</button>
            <button type="submit" style="padding: 10px 16px; border-radius: 10px; border:none; background:#ff8a65; color:#fff; font-weight:600; cursor: pointer;">Submit</button>
          </div>
          <div id="t_error" style="margin-top:10px; color:#b00020; display:none;"></div>
          <div id="t_success" style="margin-top:10px; color:#2e7d32; display:none;"></div>
        </form>
      </div>
    </div>

    <script>
      function openTestimonialModal(bookingId, service) {
        document.getElementById('t_booking_id').value = bookingId;
        document.getElementById('t_service').value = service;
        document.getElementById('t_message').value = '';
        document.getElementById('t_rating').value = '5';
        document.getElementById('t_error').style.display = 'none';
        document.getElementById('t_success').style.display = 'none';
        document.getElementById('testimonialModal').style.display = 'flex';
      }
      function closeTestimonialModal() {
        document.getElementById('testimonialModal').style.display = 'none';
      }

      // Handle form submit via AJAX to avoid navigating to JSON response
      const testimonialForm = document.getElementById('testimonialForm');
      testimonialForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        const rating = document.getElementById('t_rating').value;
        const message = document.getElementById('t_message').value;
        const bookingId = document.getElementById('t_booking_id').value;
        const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const err = document.getElementById('t_error');
        const ok = document.getElementById('t_success');
        err.style.display = 'none';
        ok.style.display = 'none';
        try {
          const res = await fetch("{{ route('feedback.store') }}", {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': csrf,
              'Accept': 'application/json'
            },
            body: JSON.stringify({ rating: parseInt(rating, 10), message, booking_id: parseInt(bookingId, 10) })
          });
          const data = await res.json();
          if (res.ok && data.success) {
            ok.textContent = data.message || 'Thank you for your feedback!';
            ok.style.display = 'block';
            // Optionally refresh after short delay so landing page can show it later
            setTimeout(() => {
              closeTestimonialModal();
            }, 1000);
          } else {
            err.textContent = data.message || 'Failed to submit feedback.';
            err.style.display = 'block';
          }
        } catch (ex) {
          err.textContent = 'Network error. Please try again.';
          err.style.display = 'block';
        }
      });

      // Pay Now function for Midtrans
      async function payNow(bookingId, orderId) {
        try {
          const btn = event.target;
          const originalText = btn.innerHTML;
          btn.disabled = true;
          btn.innerHTML = '⏳ Processing...';

          console.log('Processing payment for booking:', bookingId);

          // Create payment snap token
          const response = await fetch(`/payment/booking/${bookingId}`, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
          });

          const data = await response.json();

          if (!response.ok || !data.success) {
            throw new Error(data.message || 'Failed to create payment');
          }

          console.log('Payment response:', data);

          // Open Midtrans Snap popup
          if (data.snap_token) {
            window.snap.pay(data.snap_token, {
              onSuccess: function(result) {
                console.log('Payment success:', result);
                alert('Payment successful!');
                window.location.reload(); // Refresh to update status
              },
              onPending: function(result) {
                console.log('Payment pending:', result);
                alert('Payment is being processed. Please check your payment status.');
                window.location.reload();
              },
              onError: function(result) {
                console.error('Payment error:', result);
                alert('Payment failed. Please try again.');
                btn.disabled = false;
                btn.innerHTML = originalText;
              },
              onClose: function() {
                console.log('Payment popup closed');
                btn.disabled = false;
                btn.innerHTML = originalText;
              }
            });
          } else {
            throw new Error('No snap token received');
          }

        } catch (error) {
          console.error('Payment error:', error);
          alert('Failed to process payment: ' + error.message);
          if (event && event.target) {
            event.target.disabled = false;
            event.target.innerHTML = '💳 Pay Now';
          }
        }
      }
    </script>

    <!-- Midtrans Snap Script -->
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
  </body>
  </html>
