@php
    $activeColor = '#4B2E2B'; // warna teks aktif
    $inactiveColor = '#4B2E2B'; // warna teks nonaktif
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
        }

        .sidebar {
            width: 250px;
            background: #fff;
            height: 100vh;
            padding: 20px;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.05);
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
        }

        .logo {
            display: flex;
            align-items: center;
            margin-bottom: 40px;
        }

        .logo img {
            height: 30px;
            margin-right: 10px;
        }

        .logo span {
            font-weight: 700;
            font-size: 20px;
        }

        .logo .paw {
            color: #F28C48;
        }

        .logo .topia {
            color: #4B2E2B;
        }

        ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        ul li a {
            display: flex;
            align-items: center;
            padding: 10px 15px;
            border-radius: 8px;
            font-weight: 500;
            text-decoration: none;
            color: #4B2E2B;
            position: relative;
            transition: background 0.4s ease, color 0.4s ease, box-shadow 0.4s ease, transform 0.3s ease;
        }

        ul li a i {
            margin-right: 10px;
            transition: transform 0.3s ease;
        }

        ul li a:hover {
            background: #FFE5CC;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.08);
            transform: translateX(5px);
        }

        ul li a:hover i {
            transform: rotate(8deg) scale(1.1);
            color: #F28C48;
        }

        /* Efek garis vertikal + transisi smooth */
        ul li a.active {
            background: #FDD8A8;
            color: #5C3B28;
            font-weight: 600;
            box-shadow: inset 3px 0 0 transparent;
        }

        ul li a.active::before {
            content: "";
            position: absolute;
            left: 0;
            top: 0;
            width: 3px;
            height: 100%;
            background: #F28C48;
            transform: scaleY(0);
            transform-origin: top;
            transition: transform 0.4s ease;
        }

        ul li a.active::before {
            transform: scaleY(1);
        }

        button.logout {
            display: flex;
            align-items: center;
            padding: 10px 15px;
            border-radius: 8px;
            background: transparent;
            border: none;
            color: #ff1900ff;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            text-align: left;
            margin-top: 70px;
            font-size: 16px;
            transition: background 0.3s, color 0.3s, transform 0.2s;
        }

        button.logout i {
            margin-right: 10px;
            font-size: 18px;
            transition: transform 0.3s ease;
        }

        button.logout:hover {
            background: #FFE5CC;
            color: #d10000;
            transform: scale(1.04);
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.08);
        }

        button.logout:hover i {
            transform: rotate(-8deg) scale(1.1);
        }
    </style>
</head>

<body>

    <aside class="sidebar">
        <!-- Logo -->
        <div class="logo">
            <img src="{{ asset('images/logo.svg') }}" alt="Pawtopia Logo">
        </div>

        <!-- Menu -->
        <ul>
            <li>
                <a href="{{ url('/admin/dashboard') }}" class="{{ Request::is('admin/dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="{{ url('/admin/productmanagement') }}"
                    class="{{ Request::is('admin/productmanagement*') ? 'active' : '' }}">
                    <i class="fas fa-box"></i> Products
                </a>
            </li>
            <li>
                <a href="{{ url('/admin/customer') }}"
                    class="{{ Request::is('admin/customer*') ? 'active' : '' }}">
                    <i class="fas fa-person"></i> Customer
                </a>
            </li>
            <li>
                <a href="{{ url('/admin/managebooking') }}"
                    class="{{ Request::is('admin/managebooking*') ? 'active' : '' }}">
                    <i class="fas fa-edit"></i> Bookings
                </a>
            </li>
            <li>
                <a href="{{ url('/admin/schedule') }}" class="{{ Request::is('admin/schedule*') ? 'active' : '' }}">
                    <i class="fas fa-calendar-alt"></i>  Schedule
                </a>
            </li>
            <li>
                <a href="{{ url('/admin/feedback') }}" class="{{ Request::is('admin/feedback*') ? 'active' : '' }}">
                    <i class="fas fa-comments"></i> Feedback
                </a>
            </li>
            <li>
                <a href="{{ url('/admin/testimoni') }}" class="{{ Request::is('admin/testimoni*') ? 'active' : '' }}">
                    <i class="fas fa-chart-line"></i> Testimoni
                </a>
            </li>
        </ul>

        <button class="logout" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="fas fa-sign-out-alt"></i> Logout
        </button>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
    </aside>

    <script>
        // Add active class to current nav item
        document.addEventListener('DOMContentLoaded', function() {
            const currentPath = window.location.pathname;
            const navLinks = document.querySelectorAll('.sidebar a');
            
            navLinks.forEach(link => {
                if (link.getAttribute('href') === currentPath) {
                    link.classList.add('active');
                }
            });
        });
    </script>
</body>

</html>
