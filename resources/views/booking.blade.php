@include ('layouts.navbar')

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Pet Daycare Booking</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
        /* ===== GLOBAL STYLES ===== */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #fff;
            margin: 0;
            padding: 0;
            color: #674337;
        }

        .container {
            max-width: 700px;
            margin: auto;
            padding: 20px;
        }

        /* ===== HERO SECTION ===== */
        .hero {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 40px;
            background: linear-gradient(to right, #fbe1c3, #fff);
        }

        .hero-text {
            max-width: 60%;
            text-align: center;
            margin: 0 auto;
        }

        .hero-title {
            font-size: 50px;
            font-weight: 750;
            color: #8A6552;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .hero-title img {
            width: 20px;
        }

        .hero-subtitle {
            margin-top: 8px;
            font-size: 20px;
            color: #8A6552;
            font-weight: 400;
        }

        .hero-image img {
            display: block;
            max-height: 180px;
        }

        /* ===== FORM SECTIONS ===== */
        .section {
            margin-top: 20px;
            padding: 20px;
            border: 1px solid #f0e6e2;
            border-radius: 10px;
        }

        .section-title {
            display: flex;
            align-items: center;
            font-weight: 600;
            font-size: 16px;
            margin-bottom: 15px;
        }

        .section-title img {
            width: 30px;
            height: auto;
            margin-right: 8px;
        }

        .form-group {
            display: flex;
            gap: 20px;
            margin-bottom: 15px;
        }

        .form-field {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .form-field label {
            margin-bottom: 5px;
            font-weight: 500;
            font-size: 14px;
            color: #674337;
        }

        .form-field input {
            padding: 8px;
            border: 1px solid #8A6552;
            border-radius: 6px;
            font-size: 14px;
        }

        .form-group input {
            flex: 1;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #ddd;
            font-family: inherit;
            font-size: 14px;
        }

        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            font-family: 'Poppins', sans-serif;
            resize: none;
            box-sizing: border-box;
        }

        .form-group textarea:focus {
            outline: none;
            border-color: #ddd;
        }

        /* ===== COST ESTIMATION ===== */
        .cost {
            margin-top: 20px;
            text-align: center;
            padding: 20px;
            background: #FFE0B5;
            border-radius: 12px;
            font-size: 14px;
        }

        .cost-title {
            display: flex;
            justify-content: center;
            align-items: center;
            font-weight: 600;
            font-size: 16px;
            margin-bottom: 15px;
        }

        .cost strong {
            font-size: 16px;
            display: block;
            margin-bottom: 5px;
        }

        /* ===== BUTTONS ===== */
        .submit-btn {
            display: block;
            margin: 20px auto 10px;
            padding: 12px 30px;
            border: none;
            border-radius: 8px;
            background: #E07A5F;
            color: white;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
        }

        .note {
            text-align: center;
            font-size: 12px;
            color: #8A6552;
        }
        /* ===== MODAL STYLES ===== */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            justify-content: center;
            align-items: center;
            font-family: 'Poppins', sans-serif;
        }

        .modal-content {
            background: white;
            padding: 20px;
            border-radius: 15px;
            text-align: center;
            max-width: 700px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
            position: relative;
            margin: 20px;
            box-sizing: border-box;
        }

        .close-btn {
            position: absolute;
            top: 15px;
            right: 15px;
            cursor: pointer;
            font-size: 18px;
            color: #999;
        }

        .modal-content img {
            height: 60px;
            margin-bottom: 15px;
        }

        .modal-title {
            font-weight: 700;
            font-size: 18px;
            margin-bottom: 8px;
        }

        .modal-desc {
            font-size: 14px;
            color: #8A6552;
            margin-bottom: 25px;
        }

        /* ===== BOOKING INFO GRID ===== */
        .booking-info {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px 20px;
            margin-bottom: 20px;
            justify-items: center;
        }

        .info-card {
            background: #fff;
            border-radius: 10px;
            padding: 12px 16px;
            box-shadow: 2px 2px 0px #f4a28c;
            border: 1px solid #eee;
            width: 100%;
            max-width: 200px;
            text-align: center;
            box-sizing: border-box;
        }

        .info-card .title {
            font-weight: 600;
            font-size: 13px;
            margin-bottom: 5px;
        }

        .info-card .subtitle {
            font-size: 14px;
            font-weight: 400;
            color: #8A6552;
        }

        .info-card .subtitle i {
            font-size: 12px;
            color: #8A6552;
        }

        /* Payment Summary */
        .payment-summary {
            background: linear-gradient(135deg, #fff5e6 0%, #ffe0b3 100%);
            border-radius: 15px;
            padding: 25px;
            margin: 25px 0;
            border: 2px solid #ffd58e;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }

        .summary-title {
            font-size: 18px;
            font-weight: 700;
            color: #8A6552;
            margin-bottom: 18px;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            font-size: 15px;
            color: #674337;
        }

        .summary-row.total {
            font-size: 22px;
            font-weight: 700;
            color: #5C4033;
            padding-top: 15px;
            border-top: 2px dashed #d4b896;
            margin-top: 10px;
        }

        /* Modal Actions */
        .modal-actions {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-top: 25px;
        }

        .btn-pay-now {
            background: linear-gradient(135deg, #4CAF50, #45a049);
            color: white;
            padding: 16px 30px;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            box-shadow: 0 4px 15px rgba(76, 175, 80, 0.3);
        }

        .btn-pay-now:hover {
            background: linear-gradient(135deg, #45a049, #3d8b40);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(76, 175, 80, 0.4);
        }

        .btn-pay-now:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .btn-later {
            background: transparent;
            color: #8A6552;
            padding: 14px 30px;
            border: 2px solid #8A6552;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-later:hover {
            background: #8A6552;
            color: white;
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            .booking-info {
                grid-template-columns: 1fr;
            }
            .modal-actions {
                padding: 0 10px;
            }
            .modal-content {
                padding: 25px 15px;
            }
        }
    
</style>
</head>
<body>

<!-- Header -->
    <section class="hero">
    <div class="hero-text">
      <div class="hero-title">
        Pet Daycare Booking 
        <span>🐾</span>
      </div>
      <div class="hero-subtitle">The best place for your beloved pets</div>
    </div>
    <div class="hero-image">
      <img src="{{ asset('images/cute.png') }}" alt="cat and dog">
    </div>
  </section>

<div class="container">
    <form method="POST" action="{{ route('booking.store') }}">
        @csrf
        <input type="hidden" name="service_type" value="boarding">
    <!-- Booking Schedule -->
    <div class="section">
        <div class="section-title"><img src="{{ asset('images/Schedule.svg') }}" alt="">Booking Schedule</div>
        <div class="form-group">
            <div class="form-field">
                <label>Booking Date</label>
                <input type="date" id="booking_date" name="booking_date" required>
            </div>

            <div class="form-field">
                <label>Time</label>
                <input type="time" id="booking_time" name="booking_time" required>
            </div>
        </div>
        
    </div>

    <!-- Pet Information -->
    <div class="section">
        <div class="section-title"><img src="{{ asset('images/Pets.svg') }}" alt="">Select Your Pet</div>
        <div class="form-group">
            <div class="form-field" style="flex: 1 1 100%;">
                <label>Choose Pet</label>
                <select id="petSelect" name="pet_id" required style="padding:12px; border: 2px solid #9C6F4B; border-radius: 6px; font-size: 14px; width: 100%;">
                    <option value="">Loading your pets...</option>
                </select>
                <p style="font-size: 12px; color: #666; margin-top: 8px;">Don't see your pet? <a href="{{ route('register.pets') }}" style="color: #F07F62;">Add a new pet</a></p>
            </div>
        </div>
    </div>

    <!-- Duration -->
    <div class="section">
        <div class="section-title"><img src="{{ asset('images/Schedule.svg') }}" alt="">Boarding Duration</div>
        <div class="form-group">
            <div class="form-field">
                <label>Number of Days</label>
                <input type="number" id="durationDays" name="duration_days" value="1" min="1" required style="padding:8px; border: 1px solid #9C6F4B; border-radius: 6px;">
            </div>
        </div>
    </div>

    <!-- Delivery Options -->
    <div class="section">
        <div class="section-title"><img src="{{ asset('images/In Transit.svg') }}" alt="">Delivery Options</div>
        <div class="form-group">
            <div class="form-field">
                <label style="font-weight: 600; margin-bottom: 10px;">Drop-off (Start of boarding)</label>
                <label style="display: flex; align-items: center; margin-bottom: 8px;">
                    <input type="radio" name="drop_off_type" value="owner" checked onchange="updatePricing()" style="margin-right: 8px;"> I will drop off (Free)
                </label>
                <label style="display: flex; align-items: center;">
                    <input type="radio" name="drop_off_type" value="daycare" onchange="updatePricing()" style="margin-right: 8px;"> Daycare pickup (Fee applies)
                </label>
            </div>
            <div class="form-field">
                <label style="font-weight: 600; margin-bottom: 10px;">Pick-up (End of boarding)</label>
                <label style="display: flex; align-items: center; margin-bottom: 8px;">
                    <input type="radio" name="pick_up_type" value="owner" checked onchange="updatePricing()" style="margin-right: 8px;"> I will pick up (Free)
                </label>
                <label style="display: flex; align-items: center;">
                    <input type="radio" name="pick_up_type" value="daycare" onchange="updatePricing()" style="margin-right: 8px;"> Daycare delivery (Fee applies)
                </label>
            </div>
        </div>
        <div class="form-group" id="distanceField" style="display: none; margin-top: 15px;">
            <div class="form-field">
                <label>Distance from Daycare (km)</label>
                <input type="number" id="distanceKm" name="distance_km" step="0.1" min="0" placeholder="Enter distance" onchange="updatePricing()" style="padding:8px; border: 1px solid #9C6F4B; border-radius: 6px;">
                <p style="font-size: 12px; color: #666; margin-top: 5px;">Delivery fee: Rp 10,000/km (min. Rp 20,000)</p>
            </div>
        </div>
    </div>

    <!-- Special Notes -->
    <div class="section">
        <div class="section-title"><img src="{{ asset('images/Edit.svg') }}" alt="">Special Notes</div>
        <div class="form-group">
            <textarea name="notes" placeholder="Pet Special Instructions: Allergies/Health Issues" rows="5"></textarea>
        </div>
    </div>

    <!-- Cost Estimation -->
    <div class="cost" style="background: #fff0eb; padding: 20px; border-radius: 12px; border: 2px solid #f4a28c;">
        <div class="cost-title" style="font-size: 18px; font-weight: 600; margin-bottom: 15px; color: #5C4033;">💰 Price Summary</div>
        <div style="display: flex; flex-direction: column; gap: 10px; font-size: 14px;">
            <div style="display: flex; justify-content: space-between;">
                <span>Base Rate (Rp 50,000/day):</span>
                <span id="basePrice" style="font-weight: 600;">Rp 50,000</span>
            </div>
            <div style="display: flex; justify-content: space-between;">
                <span>Duration:</span>
                <span id="durationDisplay" style="font-weight: 600;">1 day(s)</span>
            </div>
            <div style="display: flex; justify-content: space-between;" id="deliveryFeeRow">
                <span>Delivery Fee:</span>
                <span id="deliveryFee" style="font-weight: 600; color: #F07F62;">Rp 0</span>
            </div>
            <hr style="border: none; border-top: 2px dashed #ddd; margin: 10px 0;">
            <div style="display: flex; justify-content: space-between; font-size: 18px;">
                <span style="font-weight: 700;">Total:</span>
                <span id="totalPrice" style="font-weight: 700; color: #5C4033;">Rp 50,000</span>
            </div>
        </div>
    </div>

    <!-- Submit Button -->
    <button type="submit" class="submit-btn">Submit Booking</button>
    <div class="note">Our team will contact you within 24 hours for confirmation</div>
</form>
</div>
<!-- Modal -->
<div class="modal" id="bookingModal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeModal()">×</span>
        <img src="{{ asset('images/confirm.svg') }}" alt="confirm">
        <div class="modal-title">🎉 Booking Confirmed!</div>
        <div class="modal-desc">Complete payment to secure your reservation</div>

        <div class="booking-info"> 
            <div class="info-card"> 
                <div class="title">Owner Name</div>
                <div class="subtitle" id="ownerName">-</div>
            </div> 
            <div class="info-card">
                <div class="title">Pet Details</div>
                <div class="subtitle" id="petDetails">-</div>
            </div> 
            <div class="info-card">
                <div class="title">Service Date</div>
                <div class="subtitle" id="servicePeriod">-</div> 
            </div>
            <div class="info-card"> 
                <div class="title">Contact</div>
                <div class="subtitle" id="ownerContact">-</div>
            </div>
        </div>

        <!-- Payment Summary -->
        <div class="payment-summary">
            <div class="summary-title">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                    <line x1="1" y1="10" x2="23" y2="10"></line>
                </svg>
                Payment Summary
            </div>
            <div class="summary-row">
                <span>Boarding Fee:</span>
                <span id="modalBasePrice" style="font-weight: 600;">Rp 0</span>
            </div>
            <div class="summary-row">
                <span>Duration:</span>
                <span id="modalDuration" style="font-weight: 600;">0 days</span>
            </div>
            <div class="summary-row" id="modalDeliveryRow" style="display: none;">
                <span>Delivery Fee:</span>
                <span id="modalDeliveryFee" style="font-weight: 600; color: #F07F62;">Rp 0</span>
            </div>
            <div class="summary-row total">
                <span>Total Amount:</span>
                <span id="modalTotalPrice">Rp 0</span>
            </div>
        </div>

        <!-- Payment Buttons -->
        <div class="modal-actions">
            <button class="btn-pay-now" id="btnPayNow" onclick="processPayment()">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                    <line x1="1" y1="10" x2="23" y2="10"></line>
                </svg>
                Pay Now with Midtrans
            </button>
            <button class="btn-later" onclick="payLater()">
                Pay Later - View My Bookings
            </button>
        </div>

        <p style="font-size: 13px; color: #999; margin-top: 20px; text-align: center;">
            ⏰ Payment must be completed within 24 hours
        </p>
    </div>
</div>

<script>
    // Make current logged-in member available to JS (name, phone)
    const currentMember = @json(optional(auth('member')->user())->only(['name','phone']));
    
    // Store booking and transaction data for payment
    let currentBooking = null;
    let currentTransaction = null;

    // Intercept form submit to send as AJAX and show success modal
    (function(){
      const form = document.querySelector('form[action="{{ route('booking.store') }}"]');
      if (!form) return;
      form.addEventListener('submit', async function(e){
        e.preventDefault();
        const fd = new FormData(form);
        
        // Get selected pet info
        const petSelect = document.getElementById('petSelect');
        const selectedOption = petSelect.options[petSelect.selectedIndex];
        
        const payload = {
          pet_id: fd.get('pet_id'),
          service_type: fd.get('service_type'),
          booking_date: fd.get('booking_date'),
          booking_time: fd.get('booking_time'),
          duration_days: fd.get('duration_days'),
          drop_off_type: fd.get('drop_off_type'),
          pick_up_type: fd.get('pick_up_type'),
          distance_km: fd.get('distance_km') || null,
          notes: fd.get('notes') || null,
        };

        console.log('Booking payload:', payload);

        const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        try {
          const res = await fetch("{{ route('booking.store') }}", {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': csrf || '',
              'Accept': 'application/json'
            },
            body: JSON.stringify(payload)
          });

          if (res.redirected) {
            window.location.href = res.url;
            return;
          }
          
          if (!res.ok) {
            const data = await res.json().catch(()=>({ message: 'Failed to submit booking'}));
            console.error('Booking failed:', data);
            
            if (data.redirect) {
              alert(data.message);
              window.location.href = data.redirect;
              return;
            }
            
            alert('Booking failed: ' + (data.message || 'Server error'));
            return;
          }

          // Success -> save data and show modal
          const data = await res.json();
          console.log('Server response:', data);
          
          if(data.success && data.booking && data.transaction) {
            currentBooking = data.booking;
            currentTransaction = data.transaction;
            openModal(payload, data.booking, data.transaction);
          } else {
            alert('Booking failed: ' + (data.message || 'Server error'));
          }
        } catch(err) {
          console.error('Network error:', err);
          alert('Network error. Please try again.');
        }
      });
    })();

    function capitalize(s){ return (s||'').charAt(0).toUpperCase() + (s||'').slice(1); }

    function formatDate(dateStr){
        try {
            const d = new Date(dateStr);
            return d.toLocaleDateString('id-ID', { day:'2-digit', month:'2-digit', year:'numeric' });
        } catch { return dateStr; }
    }

    function openModal(payload, booking, transaction){
        // Get selected pet name from dropdown
        const petSelect = document.getElementById('petSelect');
        const selectedOption = petSelect.options[petSelect.selectedIndex];
        const petName = selectedOption.dataset.petName || selectedOption.textContent;
        
        // Fill dynamic info
        document.getElementById('ownerName').textContent = currentMember?.name || '-';
        document.getElementById('ownerContact').textContent = currentMember?.phone || '-';
        document.getElementById('petDetails').innerHTML = petName || '-';
        
        const duration = payload?.duration_days || 1;
        const serviceText = `${formatDate(payload?.booking_date)} - ${duration} day(s)`;
        document.getElementById('servicePeriod').textContent = serviceText || '-';

        // Fill payment summary from booking data
        if (booking) {
            const basePrice = booking.base_price * booking.duration_days;
            document.getElementById('modalBasePrice').textContent = formatRupiah(basePrice);
            document.getElementById('modalDuration').textContent = `${booking.duration_days} day(s)`;
            
            if (booking.delivery_fee > 0) {
                document.getElementById('modalDeliveryRow').style.display = 'flex';
                document.getElementById('modalDeliveryFee').textContent = formatRupiah(booking.delivery_fee);
            } else {
                document.getElementById('modalDeliveryRow').style.display = 'none';
            }
            
            document.getElementById('modalTotalPrice').textContent = formatRupiah(booking.total_price);
        }

        document.getElementById('bookingModal').style.display = 'flex';
    }
    
    function closeModal(){
        document.getElementById("bookingModal").style.display = "none";
    }
    
    window.onclick = function(e){
        if(e.target == document.getElementById("bookingModal")){
            closeModal();
        }
    }
    
    // Process payment with Midtrans
    async function processPayment() {
        if (!currentBooking || !currentTransaction) {
            alert('Booking data not found. Please try again.');
            return;
        }

        const btnPayNow = document.getElementById('btnPayNow');
        btnPayNow.disabled = true;
        btnPayNow.textContent = 'Loading payment...';

        try {
            // Get snap token from payment controller
            const response = await fetch(`/payment/booking/${currentBooking.id}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();

            if (!response.ok || !data.success) {
                throw new Error(data.message || 'Failed to create payment');
            }

            // Open Midtrans Snap popup
            snap.pay(data.snap_token, {
                onSuccess: function(result) {
                    console.log('Payment success:', result);
                    window.location.href = `/payment/history`;
                },
                onPending: function(result) {
                    console.log('Payment pending:', result);
                    alert('Payment is pending. Please complete your payment.');
                    window.location.href = `/payment/history`;
                },
                onError: function(result) {
                    console.log('Payment error:', result);
                    alert('Payment failed. Please try again.');
                    btnPayNow.disabled = false;
                    btnPayNow.innerHTML = `<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                        <line x1="1" y1="10" x2="23" y2="10"></line>
                    </svg> Pay Now with Midtrans`;
                },
                onClose: function() {
                    console.log('Payment popup closed');
                    btnPayNow.disabled = false;
                    btnPayNow.innerHTML = `<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                        <line x1="1" y1="10" x2="23" y2="10"></line>
                    </svg> Pay Now with Midtrans`;
                }
            });
        } catch (error) {
            console.error('Payment error:', error);
            alert('Error: ' + error.message);
            btnPayNow.disabled = false;
            btnPayNow.innerHTML = `<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                <line x1="1" y1="10" x2="23" y2="10"></line>
            </svg> Pay Now with Midtrans`;
        }
    }
    
    // Pay later - redirect to history
    function payLater() {
        window.location.href = '/payment/history';
    }

    // Load user's pets
    async function loadPets() {
        try {
            const response = await fetch('/pets');
            const data = await response.json();
            
            const petSelect = document.getElementById('petSelect');
            petSelect.innerHTML = '<option value="">Select your pet</option>';
            
            if (data.success && data.pets.length > 0) {
                data.pets.forEach(pet => {
                    const option = document.createElement('option');
                    option.value = pet.id;
                    option.textContent = `${pet.name} (${pet.type === 'dog' ? '🐕' : '🐈'} ${pet.type})`;
                    option.dataset.petName = pet.name;
                    option.dataset.petType = pet.type;
                    petSelect.appendChild(option);
                });
            } else {
                petSelect.innerHTML = '<option value="">No pets registered</option>';
            }
        } catch (error) {
            console.error('Failed to load pets:', error);
        }
    }

    // Update pricing based on delivery options
    function updatePricing() {
        const dropOffType = document.querySelector('input[name="drop_off_type"]:checked').value;
        const pickUpType = document.querySelector('input[name="pick_up_type"]:checked').value;
        const distanceField = document.getElementById('distanceField');
        const distanceKm = parseFloat(document.getElementById('distanceKm').value) || 0;
        const durationDays = parseInt(document.getElementById('durationDays').value) || 1;
        
        // Show/hide distance field
        if (dropOffType === 'daycare' || pickUpType === 'daycare') {
            distanceField.style.display = 'block';
        } else {
            distanceField.style.display = 'none';
        }
        
        // Calculate prices
        const baseRate = 50000;
        const basePriceTotal = baseRate * durationDays;
        
        let deliveryFeeTotal = 0;
        if (distanceKm > 0) {
            const feePerTrip = Math.max(20000, distanceKm * 10000);
            if (dropOffType === 'daycare') deliveryFeeTotal += feePerTrip;
            if (pickUpType === 'daycare') deliveryFeeTotal += feePerTrip;
        }
        
        const totalPrice = basePriceTotal + deliveryFeeTotal;
        
        // Update display
        document.getElementById('basePrice').textContent = formatRupiah(basePriceTotal);
        document.getElementById('durationDisplay').textContent = `${durationDays} day(s)`;
        document.getElementById('deliveryFee').textContent = formatRupiah(deliveryFeeTotal);
        document.getElementById('totalPrice').textContent = formatRupiah(totalPrice);
    }

    function formatRupiah(number) {
        return 'Rp ' + number.toLocaleString('id-ID');
    }

    // Auto-fill booking date from URL parameter
    document.addEventListener('DOMContentLoaded', function() {
        // Load pets
        loadPets();
        
        // Auto-fill date and duration from URL
        const urlParams = new URLSearchParams(window.location.search);
        const dateParam = urlParams.get('date');
        const durationParam = urlParams.get('duration');
        
        if (dateParam) {
            const bookingDateInput = document.getElementById('booking_date');
            if (bookingDateInput) {
                bookingDateInput.value = dateParam;
                bookingDateInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
        
        if (durationParam) {
            const durationInput = document.getElementById('durationDays');
            if (durationInput) {
                durationInput.value = durationParam;
            }
        }
        
        // Listen to duration changes
        document.getElementById('durationDays').addEventListener('input', updatePricing);
        
        // Initial pricing
        updatePricing();
    });
</script>

<!-- Midtrans Snap.js -->
<script src="https://app.{{ config('midtrans.is_production') ? '' : 'sandbox.' }}midtrans.com/snap/snap.js" 
        data-client-key="{{ config('midtrans.client_key') }}"></script>

@include ('layouts.footer')

</body>
</html>
