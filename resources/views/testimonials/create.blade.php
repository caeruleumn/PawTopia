@include('layouts.navbar')

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Write Testimonial - Pawtopia</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #fff8f3;
            margin: 0;
            padding: 0;
            color: #5C3B28;
        }
        .container {
            max-width: 800px;
            margin: 40px auto 60px;
            padding: 30px;
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        }
        .page-header {
            text-align: center;
            margin-bottom: 25px;
        }
        .page-title {
            font-size: 28px;
            font-weight: 700;
            color: #8A6552;
            margin-bottom: 8px;
        }
        .page-subtitle {
            font-size: 14px;
            color: #9b7a63;
        }
        .form-group {
            margin-bottom: 18px;
        }
        label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 6px;
            color: #5C3B28;
        }
        select, textarea, input[type="number"] {
            width: 100%;
            padding: 10px 12px;
            border-radius: 10px;
            border: 2px solid #f5d1b2;
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
            box-sizing: border-box;
        }
        select:focus, textarea:focus, input[type="number"]:focus {
            outline: none;
            border-color: #E07A5F;
            box-shadow: 0 0 6px rgba(224, 122, 95, 0.3);
        }
        textarea {
            resize: vertical;
            min-height: 120px;
        }
        .rating-options {
            display: flex;
            gap: 8px;
            margin-top: 6px;
        }
        .rating-pill {
            flex: 1;
            text-align: center;
            padding: 8px 0;
            border-radius: 999px;
            border: 1px solid #f3c3a3;
            cursor: pointer;
            font-size: 13px;
            color: #8A6552;
            background: #fff7ef;
        }
        .rating-pill.active {
            background: #f89f73;
            color: #fff;
            border-color: #f27d4f;
        }
        .actions {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }
        .btn-primary {
            flex: 1;
            padding: 12px;
            border-radius: 12px;
            border: none;
            background: #E07A5F;
            color: #fff;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
        }
        .btn-secondary {
            flex: 1;
            padding: 12px;
            border-radius: 12px;
            border: 2px solid #E07A5F;
            background: #fff;
            color: #E07A5F;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
        }
        .info-box {
            font-size: 13px;
            color: #9b7a63;
            background: #fff4ec;
            border-radius: 12px;
            padding: 10px 12px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="page-header">
        <h1 class="page-title">Share Your Experience 🐾</h1>
        <p class="page-subtitle">Tell us how your completed booking went. Your feedback helps other pet parents.</p>
    </div>

    <div class="info-box" id="noBookingsInfo" style="display:none;">
        You don't have any completed bookings available for testimonials yet.
    </div>

    <form id="testimonialForm">
        <div class="form-group">
            <label for="bookingSelect">Select Completed Booking</label>
            <select id="bookingSelect" name="booking_id" required>
                <option value="">Loading your completed bookings...</option>
            </select>
        </div>

        <div class="form-group">
            <label>Rating</label>
            <input type="hidden" name="rating" id="ratingInput" value="5" required>
            <div class="rating-options">
                <div class="rating-pill active" data-value="5">5 ★</div>
                <div class="rating-pill" data-value="4">4 ★</div>
                <div class="rating-pill" data-value="3">3 ★</div>
                <div class="rating-pill" data-value="2">2 ★</div>
                <div class="rating-pill" data-value="1">1 ★</div>
            </div>
        </div>

        <div class="form-group">
            <label for="message">Your Testimonial</label>
            <textarea id="message" name="message" placeholder="Share your experience with our service..." required></textarea>
        </div>

        <div class="actions">
            <button type="button" class="btn-secondary" onclick="window.location.href='{{ route('payment.history') }}'">Back to History</button>
            <button type="submit" class="btn-primary" id="submitBtn">Submit Testimonial</button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const bookingSelect = document.getElementById('bookingSelect');
        const noBookingsInfo = document.getElementById('noBookingsInfo');
        const ratingPills = document.querySelectorAll('.rating-pill');
        const ratingInput = document.getElementById('ratingInput');
        const submitBtn = document.getElementById('submitBtn');

        // Handle rating selection
        ratingPills.forEach(pill => {
            pill.addEventListener('click', function() {
                ratingPills.forEach(p => p.classList.remove('active'));
                this.classList.add('active');
                ratingInput.value = this.dataset.value;
            });
        });

        // Load completed bookings without testimonial
        fetch('{{ route('testimonials.bookings') }}', {
            headers: {
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            bookingSelect.innerHTML = '';
            if (!data.success || !data.bookings || data.bookings.length === 0) {
                noBookingsInfo.style.display = 'block';
                const opt = document.createElement('option');
                opt.value = '';
                opt.textContent = 'No completed bookings available';
                bookingSelect.appendChild(opt);
                bookingSelect.disabled = true;
                submitBtn.disabled = true;
                return;
            }

            const params = new URLSearchParams(window.location.search);
            const preselectedId = params.get('booking_id');

            const placeholder = document.createElement('option');
            placeholder.value = '';
            placeholder.textContent = 'Choose a booking...';
            bookingSelect.appendChild(placeholder);

            data.bookings.forEach(b => {
                const opt = document.createElement('option');
                opt.value = b.id;
                opt.textContent = b.label;
                if (preselectedId && String(preselectedId) === String(b.id)) {
                    opt.selected = true;
                }
                bookingSelect.appendChild(opt);
            });
        })
        .catch(err => {
            console.error('Failed to load bookings:', err);
            bookingSelect.innerHTML = '<option value="">Failed to load bookings</option>';
        });

        // Handle form submit
        document.getElementById('testimonialForm').addEventListener('submit', function(e) {
            e.preventDefault();

            if (!bookingSelect.value) {
                alert('Please select a booking.');
                return;
            }

            submitBtn.disabled = true;
            submitBtn.textContent = 'Submitting...';

            const formData = new FormData(this);
            const token = document.querySelector('meta[name="csrf-token"]').content;

            fetch('{{ route('testimonials.store') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert('Thank you for your testimonial!');
                    window.location.href = '{{ route('payment.history') }}';
                } else {
                    alert(data.message || 'Failed to submit testimonial.');
                }
            })
            .catch(err => {
                console.error('Error:', err);
                alert('An error occurred. Please try again.');
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Submit Testimonial';
            });
        });
    });
</script>

@include('layouts.footer')
</html>
