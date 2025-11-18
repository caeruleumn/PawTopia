@php
    $activeColor = '#5C3B28';  // Coklat (aktif)
    $inactiveColor = '#F4A261'; // Orange (nonaktif)
@endphp

<nav
    style="display: flex; justify-content: space-between; align-items: center; padding: 20px; font-family: 'Poppins', sans-serif;">
    <!-- Logo -->
    <div style="display: flex; align-items: center;">
        <img src="{{ asset('images/logo.svg') }}" alt="Pawtopia Logo" style="height: 30px; margin-right: 10px;">
    </div>

    <!-- Menu -->
    <ul style="list-style: none; display: flex; gap: 25px; margin: 0;">
        <li>
            <a href="{{ url('/') }}" style="text-decoration: none; font-weight: {{ Request::is('/') ? '700' : '500' }}; 
                      color: {{ Request::is('/') ? $activeColor : $inactiveColor }};">
                Home
            </a>
        </li>
        <li>
            <a href="{{ url('/calendar') }}" style="text-decoration: none; font-weight: {{ (Request::is('booking') || Request::is('calendar2')) ? '700' : '500' }}; 
                      color: {{ (Request::is('booking') || Request::is('calendar')) ? $activeColor : $inactiveColor }};">
                Booking
            </a>
        </li>
        <li>
            <a href="{{ url('/shop') }}" style="text-decoration: none; font-weight: {{ Request::is('shop') ? '700' : '500' }}; 
                      color: {{ Request::is('shop') ? $activeColor : $inactiveColor }};">
                Our Catalogue
            </a>
        </li>
        <li>
            <a href="{{ url('/contact') }}" style="text-decoration: none; font-weight: {{ Request::is('contact') ? '700' : '500' }}; 
                      color: {{ Request::is('contact') ? $activeColor : $inactiveColor }};">
                Contact
            </a>
        </li>
        <li>
            <a href="{{ url('/payment/history') }}" style="text-decoration: none; font-weight: {{ Request::is('payment/history') ? '700' : '500' }}; 
                      color: {{ Request::is('payment/history') ? $activeColor : $inactiveColor }};">
                History
            </a>
        </li>

        @guest('member')
            <li>
                <a href="{{ url('/register') }}" style="text-decoration: none; font-weight: {{ Request::is('register') ? '700' : '500' }}; 
                          color: {{ Request::is('member') ? $activeColor : $inactiveColor }};">
                Member
            </a>
            </li>
        @endguest

        @auth('member')
            <li>
                <a href="{{ url('/profile') }}" style="text-decoration: none; font-weight: {{ Request::is('profile') ? '700' : '500' }}; 
                      color: {{ Request::is('profile') ? $activeColor : $inactiveColor }};">
                Profile
                </a>
            </li>
        @endauth
    </ul>
</nav>