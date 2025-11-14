<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>PawTopia Member Registration</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: sans-serif;
            background-color: #fff;
        }
        .hero {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            background: #fff0eb;
            border-radius: 24px;
            padding: 40px 50px;
            width: 90%;
            max-width: 1300px;
            margin: 40px auto;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
            gap: 30px;
        }
        .hero-left {
            flex: 1;
        }
        .hero-right {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }
        .hero-text h1 {
            font-size: 80px;
            font-weight: 700;
            line-height: 1.2;
            margin: 0 0 10px;
            color: #5C4033;
        }
        .hero-text h1 .paw {
            color: #F07F62;
        }
        .hero-text p {
            font-size: 20px;
            line-height: 1.5;
            color: #5C4033;
            margin-bottom: 20px;
        }
        .hero-buttons {
            display: flex;
            gap: 12px;
            margin-bottom: 20px;
        }
        .btn {
            padding: 10px 24px;
            border-radius: 30px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: 0.3s ease;
            border: none;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .btn.register {
            background-color: #5C4033;
            color: white;
        }
        .btn.login {
            background-color: white;
            color: #5C4033;
            border: 2px solid #5C4033;
        }
        .btn.register:hover {
            background-color: #7b5b4b;
        }
        .btn.login:hover {
            background-color: #f9f5f3;
        }
        .btn.active {
            background-color: #5C4033;
            color: white;
            border: none;
        }
        .btn.inactive {
            background-color: white;
            color: #5C4033;
            border: 2px solid #5C4033;
        }
        .hero-image img {
            margin-top: 20px;
            max-width: 100%;
            height: auto;
        }
        .form-card {
            background: #fff;
            padding: 20px;
            border-radius: 20px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            width: 100%;
        }
        .form-card h2 {
            text-align: center;
            font-size: 24px;
            margin-bottom: 25px;
            color: #333;
        }
        .form-section {
            margin-bottom: 20px;
        }
        .form-section h3 {
            font-size: 16px;
            margin-bottom: 10px;
            color: #444;
        }
        .form-group {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        .form-group input {
            flex: 1 1 45%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 10px;
            font-size: 14px;
        }
        .register-button {
            display: block;
            width: 100%;
            padding: 12px;
            font-size: 16px;
            background-color: #6d4c41;
            color: white;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            margin-top: 15px;
        }
        .form-login {
            text-align: center;
        }
        .form-login p {
            margin-bottom: 30px;
            color: #666;
            font-size: 14px;
        }
        .form-group-horizontal {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
        }
        .form-group-horizontal label {
            width: 130px;
            font-weight: bold;
            color: #444;
            text-align: left;
        }
        .form-group-horizontal input {
            flex: 1;
            padding: 12px 16px;
            font-size: 14px;
            border-radius: 10px;
            border: 2px solid #ccc;
            background-color: #fdfdfd;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.05);
        }
        .vipaws-section {
            position: relative;
            overflow: hidden;
            padding: 60px 20px;
            text-align: center;
            border-radius: 20px;
            margin-top: 40px;
        }
        .vipaws-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('{{ asset('images/vipaws-bg.jpg') }}');
            background-size: cover;
            background-position: center;
            filter: blur(10px);
            opacity: 0.5;
            z-index: 1;
        }
        .vipaws-content {
            position: relative;
            z-index: 2;
        }
        .vipaws-title {
            font-size: 28px;
            font-weight: bold;
            color: #5c4033;
            margin-bottom: 30px;
        }
        .vipaws-title span {
            color: #F07F62;
        }
        .vipaws-grid {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }
        .vipaws-box {
            background: white;
            padding: 20px;
            width: 250px;
            border-radius: 20px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: transform 0.2s;
        }
        .vipaws-box:hover {
            transform: translateY(-5px);
        }
        .vipaws-box img {
            width: 40px;
            margin-bottom: 10px;
        }
        .vipaws-box h4 {
            font-size: 16px;
            color: #5c4033;
            margin-bottom: 5px;
            font-weight: 600;
        }
        .vipaws-box p {
            font-size: 14px;
            color: #5c4033;
        }
    </style>
</head>
<body>

@include('layouts.navbar')
<div class="hero">
    <div class="hero-left">
        <div class="hero-text">
            <h1><span class="paw">Paw</span>Topia<br>Member</h1>
            <p>Join our Pet Daycare family and enjoy<br>the best services for your beloved pets</p>
        </div>
        <div class="hero-image">
            <img src="{{ asset('images/hero-pets.png') }}" alt="Pets">
        </div>
    </div>

    <div class="hero-right">
        <div class="hero-buttons">
            <button id="btn-register" class="btn active" onclick="showRegister()">Register</button>
            <button id="btn-login" class="btn inactive" onclick="showLogin()">Login</button>
        </div>

        <div id="form-register" class="form-card" style="display: block; margin-top: 10px;">
            <div id="form-register" class="form-card" style="display: block; margin-top: 10px;">
            <form method="POST" action="{{ route('register') }}">
                @csrf
                <h2>New Pawtner 🐾</h2>
                <div class="form-section">
                    <h3>Owner Information</h3>
                    <div class="form-group">
                        <input type="text" name="owner_name" placeholder="Full Name" required>
                        <input type="email" name="email" placeholder="Email" required>
                        <input type="text" name="phone" placeholder="Phone Number" required>
                        <input type="text" name="address" placeholder="Full Address" required>
                    </div>
                </div>
                <p style="font-size: 13px; color: #666; text-align: center; margin: 10px 0;">You'll add your pet information in the next step</p>
                <button type="submit" class="register-button">Continue to Pet Registration</button>
            </form>
        </div>
        </div>

        <div id="form-login" class="form-card form-login" style="display: none; margin-top: 10px;">
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <h2>Paw In 🐾</h2>
                <p>Join the fun — your pet journey starts here!</p>
                <div class="form-section">
                    <div class="form-group-horizontal">
                        <label for="login-email">Email</label>
                        <input type="email" id="login-email" name="email" placeholder="Enter your email" required>
                    </div>
                    <div class="form-group-horizontal">
                        <label for="login-phone">Phone Number</label>
                        <input type="text" id="login-phone" name="phone" placeholder="Enter your phone number" required>
                    </div>
                </div>
                <button type="submit" class="register-button">Sign In</button>
            </form>
        </div>
    </div>
</div>

<!-- VIPaws Section -->
<div class="vipaws-section">
        <div class="vipaws-bg"></div>
        <div class="vipaws-content">
            <h2 class="vipaws-title"><span>VIP</span>aws</h2>
            <div class="vipaws-grid">
                <div class="vipaws-box">
                    <img src="{{ asset('images/Dollar Bag.png') }}" alt="discount" />
                    <h4>Exclusive Member Discount</h4>
                    <p>up to 20% on all services</p>
                </div>
                <div class="vipaws-box">
                    <img src="{{ asset('images/Trophy.png') }}" alt="booking" />
                    <h4>Priority Booking</h4>
                    <p>Easy booking access</p>
                </div>
                <div class="vipaws-box">
                    <img src="{{ asset('images/Cat Footprint.png') }}" alt="daycare" />
                    <h4>1 Free Daycare</h4>
                    <p>for every 5 visits</p>
                </div>
            </div>
        </div>
    </div>

    @include('layouts.footer')

<script>
function showRegister() {
    document.getElementById("form-register").style.display = "block";
    document.getElementById("form-login").style.display = "none";
    document.getElementById("btn-register").classList.add("active");
    document.getElementById("btn-register").classList.remove("inactive");
    document.getElementById("btn-login").classList.add("inactive");
    document.getElementById("btn-login").classList.remove("active");
}
function showLogin() {
    document.getElementById("form-register").style.display = "none";
    document.getElementById("form-login").style.display = "block";
    document.getElementById("btn-login").classList.add("active");
    document.getElementById("btn-login").classList.remove("inactive");
    document.getElementById("btn-register").classList.add("inactive");
    document.getElementById("btn-register").classList.remove("active");
}
window.onload = function () { showRegister(); };
</script>

</body>
</html>
