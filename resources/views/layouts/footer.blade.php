<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pawtopia Footer</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

  <!-- ===== GLOBAL STYLE ===== -->
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #ffffff;
    }
  </style>
</head>
<body>

  <!-- ===== FOOTER SECTION ===== -->
  <style>
    .footer {
      background-color: #8b5e3c;
      color: #fffbe9;
      padding-top: 30px;
      position: relative;
      min-height: 320px;
      height: auto;
    }
    /* Logo */
    .footerLogo {
      margin: 0 0 30px 30px;
    }
    .footerLogoBox {
      background-color: #ffffff;
      border-radius: 25px;
      padding: 6px 14px;
      display: inline-flex;
      align-items: center;
      transition: transform 0.3s ease;
    }
    .footerLogoBox:hover { transform: translateY(-2px); }
    .footerLogoImg { height: 28px; width: auto; }

    /* Opening Hours */
    .footerInfo {
      position: absolute;
      top: 50%; left: 50%;
      transform: translate(-50%, -50%);
      text-align: center;
    }
    .footerInfoTitle {
      font-size: 16px;
      font-weight: 600;
      margin-bottom: 8px;
    }
    .footerInfoText { font-size: 14px; margin: 3px 0; }
    .footerSocials {
      margin-top: 14px;
      display: flex;
      gap: 14px;
      justify-content: center;
    }
    .footerSocialIcon { width: 20px; height: 20px; }

    /* Navigation */
    .footerNav {
      position: absolute;
      top: 60px;
      right: 40px;
      display: flex;
      flex-direction: column;
      align-items: flex-end;
      gap: 12px;
    }
    .footerNavLink {
      font-size: 15px;
      text-decoration: none;
      color: inherit;
      padding: 6px 14px;
      border-radius: 20px;
      transition: color 0.3s ease, background-color 0.3s ease;
    }
    .footerNavLink:hover {
      background-color: rgba(255, 213, 142, 0.15);
      color: #ffd58e;
    }
    .footerCta {
      background-color: #fbe0b6;
      color: #3e2a20;
      padding: 6px 14px;
      border-radius: 20px;
      font-size: 15px;
      font-weight: 600;
      border: none;
      cursor: pointer;
      transition: all 0.3s ease;
    }
    .footerCta:hover {
      background-color: #ffd58e;
      transform: scale(1.05);
    }

    /* Decoration Image */
    .footerDecorationImg {
      position: absolute;
      bottom: 0; left: 0;
      height: 220px;
      width: auto;
    }

    /* Copyright */
    .footerCopyright {
      position: absolute;
      left: 50%; bottom: 10px;
      transform: translateX(-50%);
      font-size: 13px;
      text-align: center;
    }

    /* Responsive */
    @media (max-width: 768px) {
      .footerInfo {
        position: static;
        transform: none;
        margin: 20px 0;
      }
      .footerNav {
        position: static;
        align-items: center;
        margin-top: 10px;
      }
      .footerDecorationImg { display: none; }
      .footerCopyright {
        position: static;
        margin-top: 10px;
      }
    }
  </style>

  <footer class="footer">
    <div class="footerLogo">
      <div class="footerLogoBox">
        <img src="{{ asset('images/logo.svg') }}" alt="Pawtopia Logo" class="footerLogoImg">
      </div>
    </div>

    <div class="footerInfo">
      <h4 class="footerInfoTitle">Opening Hours</h4>
      <p class="footerInfoText">Mon – Fri: <strong>08:00 – 18:00</strong></p>
      <p class="footerInfoText">Sat – Sun: <strong>09:00 – 16:00</strong></p>
      <div class="footerSocials">
        <a href="#"><img src="{{ asset('images/ig.svg') }}" alt="Instagram" class="footerSocialIcon"></a>
        <a href="#"><img src="{{ asset('images/x.svg') }}" alt="X" class="footerSocialIcon"></a>
        <a href="#"><img src="{{ asset('images/yt.svg') }}" alt="YouTube" class="footerSocialIcon"></a>
        <a href="#"><img src="{{ asset('images/tiktok.svg') }}" alt="TikTok" class="footerSocialIcon"></a>
      </div>
    </div>

    <nav class="footerNav">
      <a href="{{ route('home') }}" class="footerNavLink">Home</a>
      <a href="{{ route('calendar') }}" class="footerNavLink">Booking</a>
      <a href="{{ route('contact') }}" class="footerNavLink">Contact</a>
      @auth('member')
        <a href="{{ route('history') }}" class="footerNavLink">My Bookings</a>
      @else
        <a href="{{ route('register.page') }}" class="footerNavLink">Login / Register</a>
      @endauth
    </nav>

    <img src="{{ asset('images/footer.svg') }}" alt="Footer Decoration" class="footerDecorationImg">

    <div class="footerCopyright">
      &copy; 2025 Pawtopia. All paws reserved 🐾 | Designed with love &amp; treats.
    </div>
  </footer>

  <!-- ===== FEEDBACK MODAL ===== -->
  <style>
    .modal {
      display: none;
      position: fixed;
      z-index: 999;
      inset: 0;
      background-color: rgba(0,0,0,0.5);
    }
    .modal-content {
      background-color: #FFE0B5;
      margin: 10% auto;
      padding: 20px;
      border-radius: 20px;
      width: 400px;
      text-align: center;
      color: #8A6552; /* Tambahkan ini */
    }
    .close-btn {
      float: right;
      font-size: 24px;
      cursor: pointer;
    }
    .stars {
      font-size: 28px;
      margin: 15px 0;
      cursor: pointer;
      user-select: none;
      color: #ccc;
    }
    .stars span {
      transition: color 0.2s ease, transform 0.2s ease;
      display: inline-block;
    }
    .stars span.active { color: #E07A5F; transform: scale(1.2); }
    .stars span:hover { color: #FFB703; transform: scale(1.3); }
    textarea {
      width: 100%;
      padding: 10px;
      margin: 10px 0;
      border-radius: 10px;
      border: none;
      font-family: 'Poppins', sans-serif;
    }
    .submit-feedback {
      background-color: #8A6552;
      color: white;
      padding: 10px 20px;
      border-radius: 10px;
      border: none;
      cursor: pointer;
    }
    .submit-feedback:hover { background-color: #6c4f3d; }
  </style>

  <div id="feedbackModal" class="modal">
    <div class="modal-content">
      <span class="close-btn">&times;</span>
      <h2>We'd love to hear your feedback!</h2>
      <p>Help us make Pawtopia even better for you and your furry friends.</p>
      <div class="stars" id="starContainer">
        <span data-value="1">★</span>
        <span data-value="2">★</span>
        <span data-value="3">★</span>
        <span data-value="4">★</span>
        <span data-value="5">★</span>
      </div>
      <textarea id="feedbackText" placeholder="What can we do to make your next visit even better?" required></textarea>
      <button class="submit-feedback" id="submitFeedbackBtn">Submit My Feedback</button>
    </div>
  </div>

  <!-- ===== THANK YOU MODAL ===== -->
  <div id="thankYouModal" class="modal">
    <div class="modal-content">
      <span class="close-btn" id="closeThankYou">&times;</span>
      <h2>Thank you for your feedback!</h2>
      <p>Your thoughts help us create a better experience for you and your furry companions. 💖</p>
      <img src="{{ asset('images/footer.gif') }}" alt="Thank You" style="width: 250px; height: 250px; margin-top: 50px;">
    </div>
  </div>

  <script>
    const modal = document.getElementById("feedbackModal");
    const closeBtn = modal.querySelector(".close-btn");
    const submitBtn = document.getElementById("submitFeedbackBtn");
    const thankYouModal = document.getElementById("thankYouModal");
    const closeThankYou = document.getElementById("closeThankYou");

    // Modal can be opened programmatically if needed
    closeBtn.addEventListener("click", () => { modal.style.display = "none"; });
    window.addEventListener("click", (e) => {
      if (e.target === modal) modal.style.display = "none";
      if (e.target === thankYouModal) thankYouModal.style.display = "none";
    });

    const stars = document.querySelectorAll("#starContainer span");
    let selectedRating = 0;
    stars.forEach(star => {
      star.addEventListener("click", () => {
        selectedRating = parseInt(star.dataset.value);
        stars.forEach(s => {
          s.classList.toggle("active", parseInt(s.dataset.value) <= selectedRating);
          s.style.color = parseInt(s.dataset.value) <= selectedRating ? "#E07A5F" : "#ccc";
        });
      });
      star.addEventListener("mouseenter", () => {
        stars.forEach(s => {
          s.style.color = parseInt(s.dataset.value) <= parseInt(star.dataset.value) ? "#FFB703" : "#ccc";
        });
      });
      star.addEventListener("mouseleave", () => {
        stars.forEach(s => {
          s.style.color = parseInt(s.dataset.value) <= selectedRating ? "#E07A5F" : "#ccc";
        });
      });
    });

    submitBtn.addEventListener("click", async () => {
      const feedbackText = document.getElementById("feedbackText").value;
      
      if (!selectedRating || !feedbackText.trim()) {
        alert("Please provide a rating and feedback message.");
        return;
      }

      try {
        // Get CSRF token from meta tag
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        
        const response = await fetch('/feedback', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token,
            'X-Requested-With': 'XMLHttpRequest'
          },
          body: JSON.stringify({
            rating: selectedRating,
            message: feedbackText
          })
        });

        const data = await response.json();
        
        if (response.ok && data.success) {
          modal.style.display = "none";
          thankYouModal.style.display = "block";
          
          // Reset form
          selectedRating = 0;
          document.getElementById("feedbackText").value = '';
          stars.forEach(s => {
            s.classList.remove("active");
            s.style.color = "#ccc";
          });
        } else {
          console.error('Server response:', data);
          console.error('Response status:', response.status);
          alert("Error: " + (data.message || "Something went wrong. Please try again."));
        }
      } catch (error) {
        console.error('Error:', error);
        alert("Network error. Please check your connection and try again.");
      }
    });
    closeThankYou.addEventListener("click", () => {
      thankYouModal.style.display = "none";
    });
  </script>

</body>
</html>