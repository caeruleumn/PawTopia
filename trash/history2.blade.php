<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Booking History</title>
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: #F7F5F2; /* softer background to avoid clash */
      margin: 0;
      padding: 0; /* keep navbar spacing consistent */
      color: #5a3b2e;
    }

    .container {
      width: 90%;
      margin: 0 auto;
      background: #fff;
      border-radius: 16px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      padding: 30px;
      text-align: center;
    }

    .title {
      font-size: 18px;
      font-weight: 600;
      color: #674337;
      margin-bottom: 25px;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .title img {
      width: 24px;
      height: 24px;
    }

    .empty-state {
      padding: 40px 20px;
    }

    .empty-state img {
      width: 60px;
      height: 60px;
      margin-bottom: 20px;
      opacity: 0.7;
    }

    .empty-state h2 {
      font-size: 16px;
      font-weight: 600;
      margin-bottom: 8px;
      color: #3d2c27;
    }

    .empty-state p {
      font-size: 14px;
      color: #9C6F4B;
      margin-bottom: 20px;
    }

    .btn-book {
      display: inline-block;
      padding: 10px 30px;
      border-radius: 20px;
      font-size: 14px;
      font-weight: 600;
      text-decoration: none;
      background: #c43d4b;
      color: #FFE0B5;
      transition: background 0.2s;
    }

    .btn-book:hover {
      background: #a9323e;
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

    <div class="empty-state">
      <!-- Removed missing reminder icon -->
      <h2>Oops, no bookings yet!</h2>
      <p>Plan your first paw-some experience today.</p>
      @auth('member')
        <a href="{{ route('booking') }}" class="btn-book">Book</a>
      @else
        <a href="{{ route('register.page') }}" class="btn-book">Become a Member</a>
      @endauth
    </div>
  </div>
  </div>
  @include('layouts.footer')
</body>
</html>
