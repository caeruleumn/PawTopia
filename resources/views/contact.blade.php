<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pawtopia - Contact Us</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            background-color: #fff8f3;
            color: #5C3B28;
        }

        /* Hero section */
        .contact-hero {
            background-color: #ffe1b3;
            padding: 80px 20px;
            text-align: center;
            border-bottom-left-radius: 50% 65%;
            border-bottom-right-radius: 50% 65%;
        }
        .contact-hero h1 {
            font-size: 50px;
            color: #8A6552;
            margin: 0;
        }
        .contact-hero p {
            font-size: 18px;
            margin-top: 10px;
            color: #5C3B28;
        }

        /* Contact container */
        .contact-container {
            max-width: 1100px;
            margin: 80px auto;
            display: flex;
            flex-wrap: wrap;
            gap: 50px;
            padding: 0 20px;
            animation: fadeInUp 1s ease;
        }

        /* Contact info */
        .contact-info {
            flex: 1 1 350px;
            background: #fde4d4;
            border-radius: 20px;
            box-shadow: 0 4px 20px rgba(224, 122, 95, 0.1);
            position: relative;
            overflow: hidden; 
        }
        .contact-info h2 {
            font-size: 26px;
            color: #8A6552;
            margin-bottom: 20px;
            padding: 30px 30px 0 30px;
            text-align: center;
        }
        .contact-info p {
            margin: 10px 0;
            font-size: 16px;
            padding: 0 30px;
        }
        .contact-info p span {
            font-weight: 600;
            color: #E07A5F;
        }
        .contact-info img {
            width: 0px;
            margin-bottom: -10px; 
            object-fit: contain;
            transition: transform 0.3s;
        }

        /* Contact form */
        .contact-form {
            flex: 1 1 500px;
            background: #fff;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 4px 20px rgba(224, 122, 95, 0.1);
        }
        .contact-form h2 {
            font-size: 26px;
            color: #8A6552;
            margin-bottom: 20px;
        }
        .contact-form input,
        .contact-form textarea {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #f5d1b2;
            border-radius: 12px;
            font-family: 'Poppins', sans-serif;
            font-size: 15px;
            margin-bottom: 15px;
            transition: all 0.3s ease;
        }
        .contact-form input:focus,
        .contact-form textarea:focus {
            outline: none;
            border-color: #E07A5F;
            box-shadow: 0 0 8px rgba(224, 122, 95, 0.2);
        }
        .contact-form button {
            background-color: #FFE0B5;
            color: #d12f62;
            font-weight: 700;
            font-size: 16px;
            border: none;
            padding: 14px 30px;
            border-radius: 999px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .contact-form button:hover {
            background-color: #f98e3cff;
            color: white;
            transform: scale(1.05);
        }

        /* Animation */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(40px);}
            to { opacity: 1; transform: translateY(0);}
        }

        /* Responsive */
        @media (max-width: 768px) {
            .contact-container {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    @include('layouts.navbar')

    <!-- Hero -->
    <section class="contact-hero">
        <h1>Contact Us 🐾</h1>
        <p>We'd love to hear from you! Send us a message or reach out directly.</p>
    </section>

    <!-- Contact section -->
    <section class="contact-container">
        <!-- Info -->
        <div class="contact-info">
            <h2>Get in Touch</h2>
            <p><span>📍 Address:</span> Jl. Pawtopia No. 123, Pet City</p>
            <p><span>📞 Phone:</span> +62 812 3456 7890</p>
            <p><span>📧 Email:</span> hello@pawtopia.com</p>
            <p><span>⏰ Hours:</span> Mon-Sat, 9 AM - 6 PM</p>
            <img src="{{ asset('images/kucinganjing.png') }}" alt="Contact Us" style="width: 100%; border-radius: 20px; box-shadow: 0 4px 20px rgba(224, 122, 95, 0.1);">
        </div>

        <!-- Form -->
        <div class="contact-form">
            <h2>Send Us a Message</h2>
            <form action="#" method="POST">
                <input type="text" name="name" placeholder="Your Name" required>
                <input type="email" name="email" placeholder="Your Email" required>
                <textarea name="message" rows="6" placeholder="Your Message" required></textarea>
                <button type="submit">Send Message</button>
            </form>
        </div>
    </section>

    @include('layouts.footer')
</body>
</html>
