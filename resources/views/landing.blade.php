<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pawtopia</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            font-family: 'Poppins', sans-serif;
        }

        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 40px;
        }

        .hero {
            position: relative;
            background-color: #ffe1b3;
            height: 600px;
            width: 100%;
            border-bottom-left-radius: 50% 65%;
            border-bottom-right-radius: 50% 65%;
            overflow: hidden;
        }
        

        .hero-content {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            gap: 50px;
            padding-top: 30px;
            max-width: 1200px;
            margin: 0 auto;
            padding-left: 40px;
        }

        .hero-image {
            height: 600px;
            z-index: 2;
            margin-top: -30px;
        }

        .hero-text {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .hero-text h1 {
            font-size: 65px;
            margin: 0;
            line-height: 1.1;
            color: #8A6552;
            margin-left: -20px;
            margin-top: -30px;
            text-align: center;
        }

        .hero-text .cozy {
            color: #8A6552;
            letter-spacing: 2px;
        }

        .hero-text p {
            font-weight: 500;
            color: #5C3B28;
            margin: 15px 5px;
        }

        .cta-action {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background-color: #ffffff;
            border-radius: 50px;
            color: #F9B17A;
            text-decoration: none;
            font-weight: bold;
            transition: background 0.3s ease;
        }

        .cta-action:hover {
            background-color: #f98e3cff;
            color: white;
        }

        .about {
            padding: 80px 80px 40px 80px;
            background-color: #fff;
            font-family: 'Poppins', sans-serif;
            margin-top: 100px;
        }

        .about h2 {
            font-size: 30px;
            color: #8A6552;
            margin-bottom: -15px;
            text-align: left;
        }

        .about-container {
            max-width: 1100px;
            margin: 0 auto;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: stretch;
            gap: 60px;
        }

        .about-text {
            flex: 1 1 480px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .about-images {
            flex: 1 1 420px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: flex-end;
            margin-left: auto;
        }

        .about-images img {
            width: 220px;
            max-width: 100%;
            height: auto;
            object-fit: contain;
        }

        .about-images-row {
            display: flex;
            gap: 20px;
        }

        .about-images-row + .about-images-row {
            margin-top: 20px;
        }

        .highlight {
            color: #E07A5F;
            font-weight: 600;
        }

        .gambar {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 200px;
            max-width: 100%;
        }

        .gambar img {
            max-width: 900px;
            width: 100%;
            height: auto;
            display: block;
        }

        .services {
            padding: 80px 40px;
            text-align: center;
            font-family: 'Poppins', sans-serif;
            margin-top: 100px;
            margin-bottom: -20px;
        }

        .services-container {
            max-width: 1000px;
            margin: 0 auto;
        }

        .services h2 {
            font-size: 38px; /* lebih besar */
            color: #8A6552;
            margin-bottom: 40px;
        }

        .paw-icon {
            font-size: 32px; /* lebih besar */
            vertical-align: middle;
        }

        .services-list {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }

        .service-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 180px; /* lebih lebar */
            padding: 20px;
        }

        .service-item img {
            width: 110px; /* lebih besar */
            height: 110px;
            margin-bottom: 15px;
        }

        .service-item p {
            font-size: 18px; /* lebih besar */
            color: #5C3B28;
            font-weight: 600;
            margin: 0;
        }

         .testimonials-carousel {
            padding: 80px 20px;
            text-align: center;
            background: white;
            position: relative;
        }

        .testimonials-carousel h2 {
            font-size: 24px;
            color: #8A6552;
            margin-bottom: 50px;
        }

        .heart {
            font-size: 18px;
            vertical-align: middle;
        }

        .carousel-container {
            width: 100%;
            max-width: 1000px;
            margin: 0 auto;
            position: relative;
            display: flex;
            align-items: center;
            gap: 40px;
        }

        .carousel {
            flex: 1;
            position: relative;
            overflow: hidden;
            padding: 0;
        }

        .carousel-track {
            display: flex;
            transition: transform 0.5s cubic-bezier(.4,2,.6,1);
            gap: 30px;
            width: max-content;
        }

        .card {
            flex: 0 0 280px;
            background-color: #f7f1ee;
            border-radius: 20px;
            padding: 30px 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            opacity: 0.7;
            transform: scale(0.9);
            text-align: center;
        }

        .card.active {
            opacity: 1;
            transform: scale(1);
            background-color: #fde4d4;
            box-shadow: 0 8px 25px rgba(224, 122, 95, 0.15);
        }

        .card h4 {
            color: #8A6552;
            font-size: 18px;
            font-weight: 600;
            margin: 0 0 10px 0;
        }

        .stars {
            color: #E07A5F;
            font-size: 16px;
            margin: 10px 0 15px 0;
            letter-spacing: 2px;
        }

        .card p {
            color: #5C3B28;
            font-size: 14px;
            line-height: 1.5;
            margin: 0;
            font-style: italic;
        }

        .carousel-btn {
            background: #E07A5F;
            color: #fff;
            border: none;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            font-size: 30px;
            cursor: pointer;
            z-index: 10;
            box-shadow: 0 4px 15px rgba(224, 122, 95, 0.3);
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .carousel-btn:hover {
            background: #c86b52;
            transform: scale(1.1);
        }

        .carousel-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: scale(1);
        }

        @media (max-width: 900px) {
            .carousel-container {
                gap: 20px;
                padding: 0 20px;
            }
            
            .carousel-btn {
                width: 40px;
                height: 40px;
                font-size: 16px;
            }
        }

        @media (max-width: 768px) {
            .carousel-container {
                gap: 15px;
                padding: 0 10px;
            }
            
            .card {
                flex: 0 0 250px;
            }
            
            .carousel-btn {
                width: 35px;
                height: 35px;
                font-size: 14px;
            }
        }

        @media (max-width: 600px) {
            .carousel-container {
                flex-direction: column;
                gap: 20px;
            }
            
            .carousel-controls {
                display: flex;
                gap: 20px;
                justify-content: center;
            }
        }

        @media (max-width: 600px) {
            .carousel-container {
                flex-direction: column;
                gap: 20px;
            }
        }

        .cozy-container {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 60px 80px;
            font-family: 'Poppins', sans-serif;
            margin-bottom: -100px;
            margin-top: 100px;
        }

        .cozy-left {
            flex: 1;
            text-align: center;
        }

        .cozy-left p {
            font-size: 20px;
            font-weight: 600;
            color: #6b4c3b;
            line-height: 1.5;
            margin-bottom: 30px;
            max-width: 480px;
            margin-left: auto;
            margin-right: auto;
        }

        .book-btn {
            background-color: #FFE0B5;
            color: #d12f62;
            font-weight: 700;
            font-size: 16px;
            border: none;
            padding: 15px 30px;
            border-radius: 999px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .book-btn:hover {
            background-color: #f98e3cff;
            color: white;
        }

        .cozy-right {
            flex: 1;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 400px;
            max-width: 420px;
            margin-left: 40px;
            margin-right: -100px;
        }

        .cozy-right img {
            height: 100%;
            object-fit: cover;
            transform: translateX(-20px);
        }

        @media (max-width: 768px) {
            .cozy-container {
            flex-direction: column;
            padding: 40px 20px;
        }

        .cozy-right {
            width: 100%;
            height: 300px;
            margin: 30px 0 0;
            border-radius: 0;
            border-top-left-radius: 100px;
            border-bottom-right-radius: 100px;
        }

        .cozy-left p {
            font-size: 18px;
            }

        
        }

        /* Fade-in animation */
@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(40px);}
    to { opacity: 1; transform: translateY(0);}
}
.hero-content, .about-container, .services-container, .gambar, .testimonials-carousel, .cozy-container {
    animation: fadeInUp 1s ease;
}

/* Hero image slide-in */
@keyframes slideInLeft {
    from { opacity: 0; transform: translateX(-60px);}
    to { opacity: 1; transform: translateX(0);}
}
.hero-image {
    animation: slideInLeft 1.2s cubic-bezier(.4,2,.6,1);
}

/* Hero text fade-in */
@keyframes fadeIn {
    from { opacity: 0;}
    to { opacity: 1;}
}
.hero-text {
    animation: fadeIn 1.5s;
}

/* Button hover bounce */
.cta-action, .book-btn {
    transition: transform 0.2s cubic-bezier(.4,2,.6,1), background 0.3s;
}
.cta-action:hover, .book-btn:hover {
    transform: scale(1.08);
}

/* Card hover pop */
.card {
    transition: transform 0.3s cubic-bezier(.4,2,.6,1), box-shadow 0.3s;
}
.card:hover {
    transform: scale(1.04);
    box-shadow: 0 12px 32px rgba(224, 122, 95, 0.18);
}

/* Service item slide-in */
@keyframes slideInUp {
    from { opacity: 0; transform: translateY(40px);}
    to { opacity: 1; transform: translateY(0);}
}
.service-item {
    opacity: 0;
    animation: slideInUp 0.8s forwards;
}
.service-item:nth-child(1) { animation-delay: 0.1s;}
.service-item:nth-child(2) { animation-delay: 0.2s;}
.service-item:nth-child(3) { animation-delay: 0.3s;}
.service-item:nth-child(4) { animation-delay: 0.4s;}
.service-item:nth-child(5) { animation-delay: 0.5s;}

/* Animasi hover gambar dan icon */
.service-item img:hover,
.about-images img:hover,
.gambar img:hover {
    transform: scale(1.12) rotate(-2deg);
    transition: transform 0.3s cubic-bezier(.4,2,.6,1);
}

/* Animasi hover judul dan teks */
.hero-text h1:hover,
.about h2:hover {
    color: #E07A5F;
    transform: scale(1.05);
    transition: color 0.3s, transform 0.3s cubic-bezier(.4,2,.6,1);
}
   /* Why Choose Section */
   .why-choose {
        text-align: center;
        padding: 40px 20px;
        background-color: #f9f9f9;
    }
    .why-choose h2 {
        font-size: 25px;
        font-weight: 780;
        margin-bottom: 20px;
    }
    .why-choose span {
        color: #F6A892;
    }
 .features-container {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
        max-width: 1000px;
        margin: 0 auto;
    }

    .feature-card {
        background-color: #fff;
        border-radius: 16px;
        padding: 20px;
        display: flex;
        flex-direction: column;
        gap: 10px;
        box-shadow: 0px 4px 8px rgba(0,0,0,0.05);
        text-align: left;
    }

    .feature-icon img {
    width: 40px;  /* atur sesuai kebutuhan */
    height: auto; /* supaya proporsinya tetap terjaga */
}


    .feature-title {
        font-weight: 600;
        font-size: 18px;
        color: #674337;
    }

    .feature-text {
        font-weight: 400;
        font-size: 14px;
        color: #9C6F4B;
        line-height: 1.5;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .features-container {
            grid-template-columns: 1fr;
        }
    }
    </style>
</head>
<body>
    @include('layouts.navbar')
    <section class="hero">
        <div class="hero-content">
            <img src="{{ asset('images/dog-cat.png') }}" class="hero-image" alt="Pets">
            <div class="hero-text">
                <h1>PAWFECTLY<br><span class="cozy">COZY</span></h1>
                <p>Your pet's happy place</p>
                <a href="{{ route('register') }}" class="cta-action">
                    <span>Get Started</span>
                </a>
            </div>
        </div>
    </section>

    <section class="about">
        <div class="about-container">
            <div class="about-text">
                <h2>ABOUT US</h2>
                <br>
                <p>
                    Welcome to <span class="highlight">Pawtopia</span>, your pet's favorite place away from home. We're more than just a pet daycare — we're a 
                    <span class="highlight">cozy, safe, and joyful space where pets can play freely, nap soundly, and feel genuinely loved</span>.
                </p>
                <p>
                    Whether you're at work, out of town, or just need a break, Pawtopia ensures your furry companion is in good hands. 
                    With a team of <span class="highlight">trained and passionate caregivers</span>, we provide personalized care tailored to each pet's needs.
                </p>
                <p>
                    From playful dogs to cuddly cats, every guest at Pawtopia is treated like family.
                </p>
            </div>

            <div class="about-images">
                <div class="about-images-row">
                    <img src="{{ asset('images/kucing1.svg') }}" alt="Cat">
                    <img src="{{ asset('images/merah.svg') }}" alt="Red Shape">
                </div>
                <div class="about-images-row">
                    <img src="{{ asset('images/cream.svg') }}" alt="Cream Shape">
                    <img src="{{ asset('images/anjing1.svg') }}" alt="Dog">
                </div>
            </div>
        </div>
    </section>

    <section class="services">
        <div class="services-container">
            <h2>Our Services <span class="paw-icon">🐾</span></h2>
            <div class="services-list">
                <div class="service-item">
                    <img src="{{ asset('images/playtime.svg') }}" alt="Playtime galore">
                    <p>Playtime galore</p>
                </div>
                <div class="service-item">
                    <img src="{{ asset('images/nap.svg') }}" alt="Cozy nap spots">
                    <p>Cozy nap spots</p>
                </div>
                <div class="service-item">
                    <img src="{{ asset('images/treats.svg') }}" alt="Yummy bites & licks">
                    <p>Yummy bites & licks</p>
                </div>
                <div class="service-item">
                    <img src="{{ asset('images/social.svg') }}" alt="Safe pet socializing">
                    <p>Safe pet socializing</p>
                </div>
                <div class="service-item">
                    <img src="{{ asset('images/pickup.svg') }}" alt="Pick-up & drop-off">
                    <p>Pick-up & drop-off</p>
                </div>
            </div>
        </div>
    </section>
        
    <section>
        <div class="gambar">
            <img src="{{ asset('images/gambar.svg') }}" alt="Dog">
        </div>      
    </section>


    <section class="testimonials-carousel">
        <h2>What Pet Owners Say <span class="heart">❤️</span></h2>
        <div class="carousel-container">
            <button class="carousel-btn prev">‹</button>
            <div class="carousel">
                <div class="carousel-track">
                    @forelse($testimonials ?? [] as $testimonial)
                        <div class="card">
                            <h4>{{ $testimonial->member->name ?? 'Anonymous Pet Parent' }}</h4>
                            <div class="stars">{!! str_repeat('★', (int) $testimonial->rating) !!}</div>
                            <p>"{{ $testimonial->message }}"</p>
                            @if($testimonial->booking)
                                <small style="color: #999; font-size: 12px; margin-top: 8px; display: block;">
                                    {{ $testimonial->booking->service_type }} service
                                </small>
                            @endif
                        </div>
                    @empty
                        <div class="card">
                            <h4>Pawtopia Guest</h4>
                            <div class="stars">★★★★★</div>
                            <p>"We can't wait to collect more happy stories from our lovely pet parents!"</p>
                        </div>
                    @endforelse
                </div>
            </div>
            <button class="carousel-btn next">›</button>
        </div>
    </section>
<!-- Why Choose Pawtopia -->
<div class="why-choose">
    <h2 class="section-title">Why Choose <span>Paw</span>topia ?</h2>
    <div class="features-container">
    <div class="feature-card">
        <div class="feature-icon">
    <img src="{{ asset('images/Heart with dog paw.svg') }}" alt="Professional Care Icon">
</div>
        <div class="feature-title">Professional Care</div>
        <div class="feature-text">Our trained staff provides professional care and attention to your beloved pets throughout the day</div>
    </div>

    <div class="feature-card">
        <div class="feature-icon">
    <img src="{{ asset('images/Schedule.svg') }}" alt="Flexible Scheduling Icon">
</div>
        <div class="feature-title">Flexible Scheduling</div>
        <div class="feature-text">Book appointments easily with our online system. Choose from various time slots that fit your schedule</div>
    </div>

    <div class="feature-card">
        <div class="feature-icon">
    <img src="{{ asset('images/Tennis Ball.svg') }}" alt="Fun Activities Icon">
</div>
        <div class="feature-title">Fun Activities</div>
        <div class="feature-text">Your pets will enjoy various activities, games, and social interaction with other friendly pets</div>
    </div>

    <div class="feature-card">
        <div class="feature-icon">
    <img src="{{ asset('images/In Transit.svg') }}" alt="Pet Shuttle Icon">
</div>
        <div class="feature-title">Pet Shuttle</div>
        <div class="feature-text">Pet pick-up and drop-off available for your convenience — safe, easy, and on time!</div>
    </div>
</div>
</div>
<section class="cozy-container">
    <div class="cozy-left">
        <p>Your pet deserves the best—reserve their cozy stay today!</p>
        <a href="{{ route('booking') }}" class="book-btn">Book a Spot</a>
    </div>
    <div class="cozy-right">
        <img src="{{ asset('images/orang.svg') }}" alt="Woman holding a cat" />
    </div>
</section>


    @include ('layouts.footer')

    <script>
        const carousel = document.querySelector('.carousel');
        const track = document.querySelector('.carousel-track');
        const cards = document.querySelectorAll('.card');
        const prevBtn = document.querySelector('.carousel-btn.prev');
        const nextBtn = document.querySelector('.carousel-btn.next');
        let activeIndex = Math.min(1, Math.max(0, cards.length - 1)); // center-ish start, safe for 0/1 cards

        function updateCarousel() {
            if (!cards || cards.length === 0) {
                // No cards: disable controls and do nothing
                prevBtn.disabled = true;
                nextBtn.disabled = true;
                return;
            }
            // Update cards
            cards.forEach((card, idx) => {
                card.classList.toggle('active', idx === activeIndex);
            });

            // Update button states
            prevBtn.disabled = activeIndex === 0;
            nextBtn.disabled = activeIndex === cards.length - 1;

            // Center the active card in the carousel
            const cardElem = cards[activeIndex];

            // Calculate position of card relative to track
            const cardCenterInTrack = cardElem.offsetLeft + cardElem.offsetWidth / 2;
            const carouselCenter = carousel.offsetWidth / 2;

            // Move track so card center is in carousel center
            let offset = carouselCenter - cardCenterInTrack;

            // Limit so track doesn't go out of bounds
            const maxOffset = 0;
            const minOffset = carousel.offsetWidth - track.scrollWidth;
            if (offset > maxOffset) offset = maxOffset;
            if (offset < minOffset) offset = minOffset;

            track.style.transform = `translateX(${offset}px)`;
        }

        prevBtn.addEventListener('click', () => {
            activeIndex = Math.max(0, activeIndex - 1);
            updateCarousel();
        });

        nextBtn.addEventListener('click', () => {
            activeIndex = Math.min(cards.length - 1, activeIndex + 1);
            updateCarousel();
        });

        // Auto-play functionality
        let autoPlayInterval;
        
        function startAutoPlay() {
            if (!cards || cards.length <= 1) return; // no autoplay if 0/1 cards
            autoPlayInterval = setInterval(() => {
                if (activeIndex < cards.length - 1) {
                    activeIndex++;
                } else {
                    activeIndex = 0;
                }
                updateCarousel();
            }, 4000);
        }

        function stopAutoPlay() {
            clearInterval(autoPlayInterval);
        }

        // Pause auto-play on hover
        const carouselContainer = document.querySelector('.carousel-container');
        carouselContainer.addEventListener('mouseenter', stopAutoPlay);
        carouselContainer.addEventListener('mouseleave', startAutoPlay);

        // Initial setup
        updateCarousel();
        startAutoPlay();

        // Handle window resize
        window.addEventListener('resize', updateCarousel);
    </script>

</body>
</html>