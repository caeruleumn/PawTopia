@props([
    'title' => 'Dashboard',
    'subtitle' => 'Overview',
    'icon' => 'home',
])

@php
    $adminUser = Auth::guard('admin')->user()
        ?? Auth::guard('member')->user()
        ?? Auth::user();

    $adminName = optional($adminUser)->name ?? 'Admin';
    $adminEmail = optional($adminUser)->email ?? 'admin@example.com';
@endphp

<div class="dashboard-header">
    <div class="header-left">
        <span class="header-title">{{ $title }}</span>
        <div class="header-subtitle">{{ $subtitle }}</div>
    </div>
    <div class="header-profile">
        <div class="notification-icon" onclick="toggleNotificationModal()">
            <img src="{{ asset('images/notif.svg') }}" alt="Notifications" class="notification-img">
            <span class="badge" id="notificationBadge">3</span>
        </div>

        <div class="profile-info">
            <div class="profile-details">
                <span class="profile-name">{{ $adminName }}</span>
                <span class="profile-email">{{ $adminEmail }}</span>
            </div>
        </div>
    </div>
</div>
