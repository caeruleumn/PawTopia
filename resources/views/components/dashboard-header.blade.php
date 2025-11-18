@props([
    'title' => 'Dashboard',
    'subtitle' => 'Overview',
    'icon' => 'home',
])

<div class="dashboard-header">
    <div class="header-left">
        <span class="header-title">{{ $title }}</span>
        <div class="header-subtitle">{{ $subtitle }}</div>
    </div>
    <div class="header-profile">
        <!-- NOTIFICATION ICON -->
        <div class="notification-icon" onclick="toggleNotificationModal()">
            <img src="{{ asset('images/notif.svg') }}" alt="Notifications" class="notification-img">
            <span class="badge" id="notificationBadge">3</span>
        </div>

        <!-- PROFILE INFO -->
        <div class="profile-info">
            <div class="profile-details">
                <span class="profile-name">{{ Auth::user()->name ?? 'Admin' }}</span>
                <span class="profile-email">{{ Auth::user()->email ?? 'admin@example.com' }}</span>
            </div>
        </div>
    </div>
</div>
