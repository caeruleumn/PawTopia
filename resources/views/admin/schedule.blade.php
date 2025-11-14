@extends('layouts.app')
@include ('layoutadmin.navbar')
@section('content')
<div class="dashboard-root">


    <!-- Main Content -->
    <main class="dashboard-main">
        <x-dashboard-header 
            title="Schedule"
            subtitle="Schedule Calendar & Daily Capacity Management"
            icon="calendar"
        />

        

        <!-- Calendar Section Title (outside the calendar card) -->
        <div class="calendar-section-title">Schedule Calendar</div>

        <!-- Calendar Section -->
        <div class="calendar-card">
            <div class="calendar-header">
                <button class="nav-btn nav-btn-compact" id="prevMonth" aria-label="Previous Month">
                    <img class="nav-img" src="{{ asset('images/kiri.svg') }}" alt="Prev">
                </button>
                <div class="calendar-title">
                    <span id="currentMonth">August 2025</span>
                </div>
                <button class="nav-btn nav-btn-compact" id="nextMonth" aria-label="Next Month">
                    <img class="nav-img" src="{{ asset('images/kanan.svg') }}" alt="Next">
                </button>
            </div>
            <div class="calendar-weekdays" id="calendarWeekdays">
                <div class="weekday">Mon</div>
                <div class="weekday">Tue</div>
                <div class="weekday">Wed</div>
                <div class="weekday">Thu</div>
                <div class="weekday">Fri</div>
                <div class="weekday">Sat</div>
                <div class="weekday">Sun</div>
            </div>
            <div class="calendar-days" id="calendarGrid">
                <!-- Calendar will be populated by JavaScript -->
            </div>
            <div class="calendar-legend">
                <div class="legend-item">
                    <span class="legend-dot available"></span>
                    <span>Available - Angka menunjukkan total booking</span>
                </div>
                <div class="legend-item">
                    <span class="legend-dot busy"></span>
                    <span>Full - Kapasitas penuh (25+)</span>
                </div>
            </div>
        </div>

        <!-- Pet Boarding Schedule Management -->
        <div class="content-card">
            <div class="content-header">
                <div class="content-info">
                    <div class="content-title">Daily Boarding Schedule</div>
                    <div class="content-subtitle">Manage capacity and bookings for pet boarding</div>
                </div>
                <div class="content-controls">
                    <input type="date" id="filterDate" class="filter-select" style="min-width:200px;" />
                    
                   
                    
                    <button id="addScheduleBtn" class="action-btn btn-edit">
                        <i class="bi bi-plus"></i> Add New Schedule
                    </button>
                </div>
            </div>

            <!-- Schedule Table -->
            <div class="table-container">
                <table class="schedule-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Date</th>
                            <th>Capacity</th>
                            <th>Booked</th>
                            <th>Remaining</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="scheduleTableBody">
                        <!-- Schedule rows will be populated by JavaScript -->
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="pagination-wrapper">
                <div id="schedulePagination" class="pagination-container"></div>
                <div id="pageInfo" class="page-info"></div>
            </div>

            <!-- No Schedule Message -->
            <div id="noScheduleMessage" style="display:none;text-align:center;padding:40px 20px;color:#9B9B9B;">
                <i class="bi bi-calendar-x" style="font-size:3rem;margin-bottom:16px;display:block;"></i>
                <h3 style="margin:0 0 8px 0;color:#8A6552;">No Boarding Schedule</h3>
                <p style="margin:0;font-size:1rem;">No pet boarding sessions scheduled for today.</p>
            </div>
        </div>
    </main>
</div>

<!-- Add/Edit Schedule Modal -->
<div id="scheduleModal" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.5);z-index:1000;align-items:center;justify-content:center;">
    <div style="background:#fff;border-radius:20px;padding:32px;width:90%;max-width:500px;position:relative;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px;">
            <h3 style="margin:0;color:#8A6552;font-size:1.3rem;font-weight:700;">Add Boarding Schedule</h3>
            <button id="closeModal" style="background:none;border:none;font-size:1.5rem;color:#8A6552;cursor:pointer;padding:0;width:30px;height:30px;display:flex;align-items:center;justify-content:center;">×</button>
        </div>

        <form id="scheduleForm">
            <div style="display:grid;grid-template-columns:1fr;gap:12px;margin-bottom:20px;">
                <div>
                    <label style="display:block;color:#8A6552;font-weight:600;margin-bottom:8px;">Date</label>
                    <input type="date" id="scheduleDate" style="width:100%;background:#FAFAFA;border:2px solid #FFE0B5;border-radius:12px;padding:12px;font-size:0.9rem;outline:none;">
                </div>
            </div>

            <div style="margin-bottom:20px;">
                <label style="display:block;color:#8A6552;font-weight:600;margin-bottom:8px;">Pet Capacity</label>
                <input type="number" id="petCapacity" min="1" value="20" style="width:100%;background:#FAFAFA;border:2px solid #FFE0B5;border-radius:12px;padding:12px;font-size:0.9rem;outline:none;">
            </div>

            <div style="display:flex;gap:12px;justify-content:flex-end;">
                <button type="button" id="cancelBtn" style="background:#f8f9fa;color:#6c757d;border:2px solid #dee2e6;border-radius:12px;padding:12px 24px;font-weight:600;cursor:pointer;">Cancel</button>
                <button type="submit" style="background:#CA2E55;color:#fff;border:none;border-radius:12px;padding:12px 24px;font-weight:600;cursor:pointer;">Save Schedule</button>
            </div>
        </form>
    </div>
</div>
<!-- MODAL NOTIFICATION -->
<div class="notification-modal" id="notificationModal">
    <div class="notification-content">
        <div class="notification-header">
            <h3>Notifications</h3>
            <button onclick="toggleNotificationModal()">&times;</button>
        </div>

        <div id="notificationList">
            <div class="notification-item unread">
                <h4>New Booking</h4>
                <p>A user made a booking today</p>
            </div>
            <div class="notification-item unread">
                <h4>Payment Received</h4>
                <p>Transaction #123 successful</p>
            </div>
            <div class="notification-item">
                <h4>New Testimonial</h4>
                <p>A customer left a review</p>
            </div>
        </div>

        <div class="notification-footer">
            <button onclick="markAllAsRead()">Mark All as Read</button>
        </div>
    </div>
</div>

<style>
    /* Root & Header (mirror testimoni/feedback) */
    .dashboard-root {
        display: flex;
        min-height: 100vh;
        background: #EAE6E1;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .dashboard-main {
        flex: 1;
        padding: 40px 32px 32px 32px;
        max-width: 100%;
        /* Offset sama seperti halaman Testimoni */
        margin-left: 250px;
    }

    .dashboard-header {
        background: #fff;
        border-radius: 24px;
        padding: 24px 32px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 32px;
        box-shadow: 0 8px 32px rgba(230, 161, 93, 0.1);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    .header-profile {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .notification-icon {
            position: relative;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            transition: all 0.2s ease;
        }
        
        .notification-img {
            width: 24px;
            height: 24px;
            object-fit: contain;
        }

        .notification-icon:hover {
            background: #f5f5f5;
        }

        .notification-icon .badge {
            position: absolute;
            top: -6px;
            right: -8px;
            background: #E63946;
            color: #fff;
            font-size: 0.7rem;
            font-weight: 600;
            padding: 2px 6px;
            border-radius: 50%;
            min-width: 18px;
            height: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .profile-info {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 6px 16px 6px 6px;
            border-radius: 50px;
            background: #fff;
            border: 1px solid rgba(0, 0, 0, 0.05);
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .profile-info:hover {
            background: #f9f9f9;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .profile-info img {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #E57300;
        }

        .profile-details {
            display: flex;
            flex-direction: column;
            line-height: 1.2;
        }

        .profile-name {
            font-weight: 600;
            color: #333;
            font-size: 0.9rem;
            white-space: nowrap;
        }

        .profile-email {
            font-size: 0.75rem;
            color: #888;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 180px;
        }

        .profile-details {
            line-height: 1.2;
        }

        .profile-name {
            font-weight: 700;
            color: #6B4F3A;
            font-size: 1rem;
            text-transform: capitalize;
        }

        .profile-role {
            font-size: 0.85rem;
            color: #A97B5D;
            font-weight: 500;
        }

        .profile-notification {
            position: absolute;
            top: -5px;
            right: -5px;
            cursor: pointer;
        }

        .notification-icon {
            font-size: 20px;
            color: #6B4F3A;
        }

        .notification-badge {
            position: absolute;
            top: -5px;
            right: -8px;
            background: #E57300;
            color: #fff;
            font-size: 10px;
            font-weight: bold;
            padding: 2px 5px;
            border-radius: 50%;
            min-width: 16px;
            height: 16px;
            text-align: center;
            line-height: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
         /* Notification Modal */
         .notification-modal {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.3);
            justify-content: flex-end;
            z-index: 2000;
        }

        .notification-modal.show {
            display: flex;
        }

        .notification-content {
            width: 380px;
            background: #fff;
            height: 100%;
            padding: 20px;
            overflow-y: auto;
            box-shadow: -2px 0 12px rgba(0,0,0,0.1);
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from { transform: translateX(100%); }
            to { transform: translateX(0); }
        }

        .notification-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
            padding-bottom: 16px;
            border-bottom: 1px solid #f0f0f0;
        }

        .notification-header h3 {
            margin: 0;
            font-size: 1.3rem;
            font-weight: 600;
            color: #4B2E2B;
        }

        .notification-header button {
            background: transparent;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #666;
            line-height: 1;
            padding: 4px 8px;
            border-radius: 4px;
            transition: all 0.2s ease;
        }

        .notification-header button:hover {
            background: #f5f5f5;
            color: #333;
        }

        .notification-item {
            padding: 14px 16px;
            border-radius: 8px;
            background: #fafafa;
            margin-bottom: 10px;
            transition: all 0.2s ease;
            border-left: 3px solid transparent;
        }

        .notification-item:hover {
            background: #f5f5f5;
        }

        .notification-item.unread {
            background: #FFF6E9;
            border-left-color: #F28C48;
        }

        .notification-item h4 {
            margin: 0 0 4px 0;
            font-size: 0.95rem;
            font-weight: 600;
            color: #333;
        }

        .notification-item p {
            margin: 0;
            font-size: 0.85rem;
            color: #666;
            line-height: 1.4;
        }

        .notification-footer {
            text-align: center;
            margin-top: 20px;
            padding-top: 16px;
            border-top: 1px solid #f0f0f0;
        }

        .notification-footer button {
            background: #F28C48;
            border: none;
            color: #fff;
            font-weight: 600;
            padding: 8px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.9rem;
            transition: all 0.2s ease;
        }

        .notification-footer button:hover {
            background: #e07732;
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(242, 140, 72, 0.3);
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .notification-content {
                width: 100%;
                max-width: 100%;
            }
            
            .profile-info span {
                display: none;
            }
            
            .profile-info {
                padding: 4px;
                margin-left: 4px;
            }
        }


    /* Responsive: stack under sidebar on smaller screens */
    @media (max-width: 992px) {
        .dashboard-main {
            margin-left: 0;
            padding: 24px 16px;
        }
    }

    .header-left { display:flex; flex-direction:column; gap:4px; }
    .header-title { font-size:2.2rem; font-weight:800; color:#6B4F3A; letter-spacing:-0.02em; }
    .header-subtitle { color:#A97B5D; font-size:1rem; font-weight:500; }

    .header-profile { display:flex; align-items:center; gap:20px; }
    .profile-info { display:flex; align-items:center; gap:12px; padding:9px 20px; border-radius:20px; background:linear-gradient(135deg,#F7F5F2,#FFEEE5); border:1px solid rgba(169,123,93,0.1); position:relative; }
    .profile-image { width:44px; height:44px; border-radius:50%; border:3px solid #E57300; object-fit:cover; box-shadow:0 4px 12px rgba(229,115,0,0.2); }
    .profile-details { line-height:1.2; }
    .profile-name { font-weight:700; color:#6B4F3A; font-size:1rem; text-transform:capitalize; }
    .profile-role { font-size:0.85rem; color:#A97B5D; font-weight:500; }
    .profile-notification { position:absolute; top:-5px; right:-5px; cursor:pointer; }
    .notification-icon { font-size:20px; color:#6B4F3A; }
    .notification-badge { position:absolute; top:-5px; right:-8px; background:#E57300; color:#fff; font-size:10px; font-weight:bold; padding:2px 5px; border-radius:50%; min-width:16px; height:16px; text-align:center; line-height:12px; display:flex; align-items:center; justify-content:center; }

    /* Calendar card (mirror dashboard) */
    .calendar-card { background:#FFE0B2; border-radius:24px; padding:28px; box-shadow:0 12px 40px rgba(230,161,93,0.12); margin-bottom:32px; border:1px solid rgba(255,224,178,0.5); }
    .calendar-section-title { text-align:center; font-weight:900; font-size:2rem; color:#6B4F3A; margin: 6px 0 18px; letter-spacing:-0.02em; position:relative; }
    .calendar-section-title::after { content:''; display:block; width:140px; height:4px; border-radius:4px; background:linear-gradient(90deg,#E6A35D,#F0C28A); margin:10px auto 0; opacity:0.8; }
    .calendar-main-title { font-weight:800; font-size:1.6rem; color:#6B4F3A; margin-bottom:16px; letter-spacing:-0.02em; text-align:center; }
    .calendar-header { display:grid; grid-template-columns: 40px 1fr 40px; align-items:center; margin-bottom:16px; }
    .calendar-title {
        display:inline-flex;
        align-items:center;
        gap:8px;
        text-align:center;
        background: rgba(255,255,255,0.9);
        border: 1px solid rgba(169,123,93,0.15);
        border-radius: 999px;
        padding: 6px 12px;
        box-shadow: 0 4px 14px rgba(230,161,93,0.18);
        justify-self:center;
    }
    .calendar-title span {
        font-weight:900;
        font-size:1.9rem;
        color:#6B4F3A;
        display:inline-block;
        letter-spacing:-0.02em;
        line-height:1;
        white-space: nowrap;
    }
    .calendar-subtitle { font-size:1rem; color:#A97B5D; font-weight:600; margin-top:6px; width:100%; text-align:center; }
    .view-toggle { display:flex; background:rgba(255,255,255,0.7); border:1px solid rgba(255,255,255,0.4); border-radius:12px; overflow:hidden; }
    .toggle-btn { background:transparent; border:none; padding:10px 14px; color:#6B4F3A; font-weight:700; cursor:pointer; transition:all .2s ease; }
    .toggle-btn.active { background:#fff; }
    .calendar-nav { display:flex; gap:8px; }
    .nav-btn { background:transparent; border:none; border-radius:8px; padding:4px; color:#6B4F3A; cursor:pointer; transition:all .15s ease; width:28px; height:28px; display:inline-flex; align-items:center; justify-content:center; flex: 0 0 auto; }
    .nav-btn-compact { width:26px; height:26px; padding:4px; }
    .nav-img { width:18px; height:18px; object-fit:contain; display:block; filter: drop-shadow(0 1px 1px rgba(0,0,0,0.05)); }
    .nav-btn:hover { background:rgba(255,255,255,0.85); transform:translateY(-1px); box-shadow:0 2px 8px rgba(0,0,0,0.06); }
    .capacity-btn { background:#E57300; color:#fff; border:none; border-radius:12px; padding:10px 14px; font-weight:700; cursor:pointer; }

    .calendar-weekdays { display:grid; grid-template-columns:repeat(7,1fr); gap:4px; margin-bottom:8px; }
    .weekday { text-align:center; color:#A97B5D; font-size:0.85rem; font-weight:700; padding:6px 0; text-transform:uppercase; letter-spacing:.5px; }
    .calendar-days { display:grid; grid-template-columns:repeat(7,1fr); gap:3px; }

    .calendar-day { height:80px; display:flex; flex-direction:column; align-items:flex-start; justify-content:flex-start; color:#6B4F3A; border-radius:10px; cursor:pointer; transition:all .2s ease; position:relative; background:rgba(255,255,255,0.6); padding:8px; border:1px solid rgba(255,255,255,0.4); }
    .calendar-day:hover { background:#fff; transform:scale(1.05); box-shadow:0 4px 12px rgba(107,79,58,0.15); }
    .calendar-day.other-month { color:#ccc; background:transparent; border:1px dashed rgba(255,255,255,0.5); }
    .calendar-day.selected { 
        background:#FFE0B5 !important; 
        border:2px solid #E57300 !important; 
        box-shadow:0 4px 16px rgba(229,115,0,0.35);
        transform:scale(1.02);
    }
    .calendar-day.selected .day-number {
        background:#E57300;
        color:#fff;
    }
    
    /* Background colors based on booking status - ONLY FULL is RED */
    .calendar-day.bg-busy { 
        background:linear-gradient(135deg, rgba(244, 67, 54, 0.25), rgba(229, 57, 53, 0.3)) !important; 
        border:2px solid #E53935 !important;
        box-shadow:0 4px 16px rgba(229, 57, 53, 0.4);
    }
    .calendar-day.bg-busy:hover {
        background:linear-gradient(135deg, rgba(244, 67, 54, 0.35), rgba(229, 57, 53, 0.4)) !important;
        transform:scale(1.05);
    }
    
    .calendar-event { position:absolute; bottom:8px; left:8px; right:8px; display:flex; gap:6px; align-items:center; }
    .day-number { font-weight:800; background:rgba(255,255,255,0.85); color:#6B4F3A; border-radius:8px; padding:2px 6px; box-shadow:0 2px 6px rgba(0,0,0,0.05); }
    
    /* Today label */
    .today-label {
        position:absolute;
        top:8px;
        right:8px;
        background:#E57300;
        color:#fff;
        font-size:0.65rem;
        font-weight:700;
        padding:2px 6px;
        border-radius:4px;
        box-shadow:0 2px 6px rgba(229,115,0,0.3);
        text-transform:uppercase;
        letter-spacing:0.5px;
    }
    
    /* Booking count style - angka kecil di pojok */
    .booking-count { 
        position:absolute; 
        bottom:8px; 
        right:8px; 
        background:rgba(107, 79, 58, 0.9); 
        color:#fff; 
        font-size:0.8rem; 
        font-weight:800; 
        border-radius:50%; 
        width:24px; 
        height:24px; 
        display:flex; 
        align-items:center; 
        justify-content:center; 
        box-shadow:0 3px 8px rgba(0,0,0,0.25);
    }
    .calendar-day.bg-busy .booking-count { 
        background:#E53935 !important;
        box-shadow:0 3px 10px rgba(229, 57, 53, 0.5);
        font-size:0.85rem;
    }
    
    .dot { width:8px; height:8px; border-radius:50%; }
    .dot.available { background:#4CAF50; }
    .dot.moderate { background:#FF9800; }
    .dot.busy { background:#E53935; }

    .calendar-legend { display:flex; justify-content:center; gap:24px; padding-top:12px; border-top:1px solid rgba(169,123,93,0.2); margin-top:12px; }
    .legend-item { display:flex; align-items:center; gap:8px; font-size:0.9rem; color:#6B4F3A; font-weight:600; }
    .legend-dot { width:28px; height:28px; border-radius:6px; display:inline-block; border:1px solid; }
    .legend-dot.available { background:rgba(255,255,255,0.6); border-color:rgba(255,255,255,0.4); }
    .legend-dot.busy { background:linear-gradient(135deg, rgba(244, 67, 54, 0.25), rgba(229, 57, 53, 0.3)); border:2px solid #E53935; }

    /* Content card & Schedule table (mirror feedback aesthetics) */
    .content-card { background:linear-gradient(135deg,#fff 0%,#fefefe 100%); border-radius:24px; padding:32px; box-shadow:0 12px 40px rgba(230,161,93,0.08); border:1px solid rgba(255,255,255,0.5); margin-bottom:32px; }
    .content-header { display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:24px; gap:24px; }
    .content-info { flex:1; }
    .content-title { font-weight:800; font-size:1.6rem; color:#6B4F3A; margin-bottom:6px; }
    .content-subtitle { color:#A97B5D; font-size:1rem; font-weight:500; }
    .content-controls { display:flex; align-items:center; gap:12px; flex-wrap:wrap; }
    .filter-select { background:linear-gradient(135deg,#FFE0B2,#FFCC99); border:1px solid rgba(169,123,93,0.2); border-radius:12px; padding:10px 14px; color:#6B4F3A; font-weight:600; cursor:pointer; }
    .action-btn { border:none; border-radius:8px; padding:10px 16px; font-size:0.9rem; font-weight:700; cursor:pointer; transition:all .2s ease; }
    .btn-edit { background:#E57300; color:#fff; }

    .table-container { overflow-x:auto; border-radius:16px; overflow:hidden; }
    .schedule-table { width:100%; min-width:800px; background:#fff; border-collapse:collapse; }
    .schedule-table thead tr { background:linear-gradient(135deg,#FFE0B2,#F9D9A7); }
    .schedule-table th { color:#6B4F3A; padding:20px 16px; font-weight:700; text-align:left; font-size:0.9rem; text-transform:uppercase; letter-spacing:.5px; border-bottom:2px solid rgba(169,123,93,0.1); }
    .schedule-table th:first-child { text-align:center; width:60px; }
    .schedule-table th:nth-child(3), .schedule-table th:nth-child(4), .schedule-table th:nth-child(5), .schedule-table th:nth-child(6), .schedule-table th:nth-child(7) { text-align:center; }
    .schedule-table td { padding:20px 16px; border-bottom:1px solid rgba(240,240,240,0.8); color:#6B4F3A; vertical-align:middle; font-weight:500; }
    .schedule-table td:first-child { text-align:center; font-weight:600; color:#E57300; }
    .schedule-table td:nth-child(3), .schedule-table td:nth-child(4), .schedule-table td:nth-child(5), .schedule-table td:nth-child(6), .schedule-table td:nth-child(7) { text-align:center; }
    .schedule-table tbody tr { transition:all .2s ease; }
    .schedule-table tbody tr:hover { background:rgba(229,115,0,0.02); transform:scale(1.002); }

    .dashboard-sidebar a:hover {
        transform: translateX(2px);
        transition: transform 0.2s;
    }

    button:hover {
        transform: translateY(-1px);
        transition: transform 0.2s;
    }

    /* Deprecated old calendar-day styles overridden above */

    .calendar-day:hover {
        background: #f8f9fa;
    }

    .calendar-day.other-month { color:#ccc; background:transparent; }

    .calendar-day.today { background:#E57300; color:#fff; }

    .calendar-day.selected {
        background: #CA2E55 !important;
        color: #fff !important;
    }

    /* headers handled by .calendar-weekdays */

    /* event chips replaced by dots */

    .calendar-event.available {
        background: #28a745;
    }

    .calendar-event.full {
        background: #dc3545;
    }

    .schedule-row:hover {
        background-color: #FAFAFA;
        transition: background-color 0.2s ease;
    }

    .status-available {
        background: #E8F5E8;
        color: #4CAF50;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .status-full {
        background: #FFEBEE;
        color: #F44336;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .status-unavailable {
        background: #F5F5F5;
        color: #9E9E9E;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    /* Pagination styles – samakan dengan Customer/Testimoni */
    .pagination-wrapper { display:flex; flex-direction:column; align-items:center; gap:16px; margin-top:32px; }
    .pagination-container { display:flex; justify-content:center; align-items:center; gap:8px; }
    .pagination-btn { background:linear-gradient(135deg, #FFE0B2, #FFCC99); color:#6B4F3A; border:1px solid rgba(169,123,93,0.2); border-radius:10px; padding:10px 14px; font-size:0.9rem; font-weight:600; cursor:pointer; transition:all .3s ease; min-width:40px; display:flex; align-items:center; justify-content:center; }
    .pagination-btn:hover { background:linear-gradient(135deg, #F9D9A7, #F0B27A); transform:translateY(-2px); box-shadow:0 4px 12px rgba(229,115,0,0.2); }
    .pagination-btn.active { background:#E57300; color:#fff; border-color:#E57300; box-shadow:0 4px 12px rgba(229,115,0,0.3); }
    .pagination-btn:disabled { opacity:.5; cursor:not-allowed; background:#f0f0f0; color:#999; border-color:#ddd; }
    .page-info { text-align:center; color:#A97B5D; font-size:0.9rem; font-weight:500; }

    @media (max-width: 1200px) {
        .dashboard-root {
            flex-direction: column;
        }

        .dashboard-sidebar {
            width: 100%;
            border-radius: 0;
            border-bottom-left-radius: 24px;
            border-bottom-right-radius: 24px;
        }
    }

    .dashboard-sidebar {
        width: 100%;
        border-radius: 0;
        border-bottom-left-radius: 24px;
        border-bottom-right-radius: 24px;

        .content-header { flex-direction:column; gap:16px; align-items:stretch; }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Data dari backend API
    let scheduleData = [];
    let calendarEvents = {};
    
    let currentDate = new Date();
    let currentMonth = currentDate.getMonth();
    let currentYear = currentDate.getFullYear();

    const monthNames = [
        'January', 'February', 'March', 'April', 'May', 'June',
        'July', 'August', 'September', 'October', 'November', 'December'
    ];

    // Elements
    const currentMonthElement = document.getElementById('currentMonth');
    const calendarGrid = document.getElementById('calendarGrid');
    const scheduleTableBody = document.getElementById('scheduleTableBody');
    const noScheduleMessage = document.getElementById('noScheduleMessage');
    const prevMonthBtn = document.getElementById('prevMonth');
    const nextMonthBtn = document.getElementById('nextMonth');
    const monthFilter = document.getElementById('monthFilter');
    const scheduleModal = document.getElementById('scheduleModal');
    const addScheduleBtn = document.getElementById('addScheduleBtn');
    const addCapacityBtn = document.getElementById('addCapacityBtn');
    const viewMonthBtn = document.getElementById('viewMonth');
    const viewWeekBtn = document.getElementById('viewWeek');
    const closeModal = document.getElementById('closeModal');
    const cancelBtn = document.getElementById('cancelBtn');
    const filterDateInput = document.getElementById('filterDate');

    // Fetch schedule data from backend API
    async function fetchCalendarData() {
        try {
            const response = await fetch(`/admin/schedule/calendar?month=${currentMonth + 1}&year=${currentYear}`);
            const data = await response.json();
            
            if (data.success) {
                calendarEvents = {};
                Object.values(data.schedules).forEach(schedule => {
                    calendarEvents[schedule.date] = [{
                        name: schedule.label,
                        type: schedule.status, // available, moderate, busy
                        booked: schedule.booked,
                        capacity: schedule.capacity
                    }];
                });
                renderCalendar();
            }
        } catch (error) {
            console.error('Error fetching calendar data:', error);
        }
    }

    // Fetch schedule list from backend API
    async function fetchScheduleList(page = 1, filterDate = '') {
        try {
            let url = `/admin/schedule/list?page=${page}&per_page=8`;
            if (filterDate) {
                url += `&date=${filterDate}`;
            }
            
            const response = await fetch(url);
            const data = await response.json();
            
            if (data.success) {
                scheduleData = data.data;
                renderScheduleTable(data.data, data.pagination);
            }
        } catch (error) {
            console.error('Error fetching schedule list:', error);
            noScheduleMessage.style.display = 'block';
            scheduleTableBody.innerHTML = '';
        }
    }

    // Function to render calendar
    function renderCalendar() {
        currentMonthElement.textContent = `${monthNames[currentMonth]} ${currentYear}`;

        const firstDay = new Date(currentYear, currentMonth, 1);
        const lastDay = new Date(currentYear, currentMonth + 1, 0);
        const firstDayOfWeek = firstDay.getDay() === 0 ? 7 : firstDay.getDay(); // Monday = 1
        const daysInMonth = lastDay.getDate();

        let calendarHTML = '';

        // Weekday headers are static above

        // Previous month days
        const prevMonth = new Date(currentYear, currentMonth - 1, 0);
        for (let i = firstDayOfWeek - 1; i > 0; i--) {
            const day = prevMonth.getDate() - i + 1;
            calendarHTML += `
                <div class="calendar-day other-month">
                    <div style="font-size:0.9rem;font-weight:600;margin-bottom:4px;">${day}</div>
                </div>
            `;
        }

        // Current month days
        for (let day = 1; day <= daysInMonth; day++) {
            const dateStr = `${currentYear}-${String(currentMonth + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
            const isToday = dateStr === new Date().toISOString().split('T')[0];
            const events = calendarEvents[dateStr] || [];
            
            // Determine background class and booking count
            let bgClass = '';
            let bookingCount = 0;
            
            if (events.length) {
                const e = events[0];
                bookingCount = e.booked || 0;
                
                // HANYA tampilkan merah jika FULL (busy)
                if (e.type === 'busy') {
                    bgClass = 'bg-busy';
                }
            }

            calendarHTML += `
                <div class="calendar-day ${bgClass}" data-date="${dateStr}">
                    ${isToday ? '<div class="today-label">Today</div>' : ''}
                    <div class="day-number">${day}</div>
                    ${bookingCount > 0 ? `<div class="booking-count">${bookingCount}</div>` : ''}
                </div>
            `;
        }

        // Next month days to fill the grid
        const totalCells = Math.ceil((firstDayOfWeek - 1 + daysInMonth) / 7) * 7;
        const remainingCells = totalCells - (firstDayOfWeek - 1 + daysInMonth);

        for (let day = 1; day <= remainingCells; day++) {
            calendarHTML += `
                <div class="calendar-day other-month">
                    <div style="font-size:0.9rem;font-weight:600;margin-bottom:4px;">${day}</div>
                </div>
            `;
        }

        calendarGrid.innerHTML = calendarHTML;
    }

    // Pagination state
    let currentPage = 1;

    function buildPagination(pagination) {
        const container = document.getElementById('schedulePagination');
        const pageInfo = document.getElementById('pageInfo');
        if (!container || !pageInfo) return;

        const { current_page, last_page, from, to, total } = pagination;
        
        pageInfo.textContent = total > 0 
            ? `Showing ${from}-${to} of ${total} schedules (Page ${current_page} of ${last_page})`
            : 'No schedules found';

        // Build button-based pagination
        const makeBtn = (label, page, disabled = false, active = false) => {
            const btn = document.createElement('button');
            btn.className = 'pagination-btn' + (active ? ' active' : '');
            btn.textContent = label;
            if (disabled) btn.disabled = true;
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                if (!disabled) {
                    currentPage = page;
                    fetchScheduleList(currentPage, filterDateInput.value);
                }
            });
            return btn;
        };

        container.innerHTML = '';
        // Prev
        const prevBtn = makeBtn('', Math.max(1, current_page - 1), current_page === 1);
        prevBtn.innerHTML = '<img src="{{ asset('images/kiri.svg') }}" alt="Previous" style="width: 16px; height: 16px;">';
        container.appendChild(prevBtn);

        // Page buttons
        for (let p = 1; p <= last_page; p++) {
            container.appendChild(makeBtn(String(p), p, false, p === current_page));
        }

        // Next
        const nextBtn = makeBtn('', Math.min(last_page, current_page + 1), current_page === last_page);
        nextBtn.innerHTML = '<img src="{{ asset('images/kanan.svg') }}" alt="Next" style="width: 16px; height: 16px;">';
        container.appendChild(nextBtn);
    }

    function renderScheduleTable(schedules, pagination) {
        if (schedules.length === 0) {
            scheduleTableBody.innerHTML = '';
            noScheduleMessage.style.display = 'block';
            document.getElementById('schedulePagination').innerHTML = '';
            document.getElementById('pageInfo').textContent = '';
        } else {
            noScheduleMessage.style.display = 'none';
            scheduleTableBody.innerHTML = schedules.map((schedule, idx) => {
                const statusClass = schedule.status_class || 'status-available';

                return `
                    <tr class="schedule-row">
                        <td style="text-align:center; font-weight:700; color:#E57300;">${schedule.id}</td>
                        <td style="background:#fff;padding:16px 12px;font-weight:600;color:#8A6552;">
                            ${schedule.date_formatted || schedule.date}
                        </td>
                        <td style="background:#fff;padding:16px 12px;text-align:center;font-weight:600;color:#8A6552;">
                            ${schedule.capacity}
                        </td>
                        <td style="background:#fff;padding:16px 12px;text-align:center;font-weight:600;color:#8A6552;">
                            ${schedule.booked}
                        </td>
                        <td style="background:#fff;padding:16px 12px;text-align:center;font-weight:600;color:#8A6552;">
                            ${schedule.remaining}
                        </td>
                        <td style="background:#fff;padding:16px 12px;text-align:center;">
                            <span class="${statusClass}">${schedule.status}</span>
                        </td>
                        <td style="background:#fff;padding:16px 12px;text-align:center;">
                            <div style="display:flex;gap:8px;justify-content:center;">
                                <button style="background:#F9B17A;color:#fff;border:none;border-radius:8px;padding:8px 12px;font-size:0.85rem;font-weight:600;cursor:pointer;transition:all 0.2s;" onmouseover="this.style.background='#F5A869';this.style.transform='translateY(-1px)'" onmouseout="this.style.background='#F9B17A';this.style.transform='translateY(0)'" onclick="viewScheduleDetails('${schedule.date}')">
                                    <i class="bi bi-eye"></i> View Details
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            }).join('');
            buildPagination(pagination);
        }
    }

    // Function to format schedule date
    function formatScheduleDate(dateString) {
        const date = new Date(dateString);
        const month = date.getMonth() + 1;
        const day = date.getDate();
        const year = date.getFullYear();
        return `${month}/${day}/${year}`;
    }

    // Modal functions
    function showModal() {
        scheduleModal.style.display = 'flex';
        document.body.style.overflow = 'hidden';

        // Set default date to today
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('scheduleDate').value = today;
    }

    function hideModal() {
        scheduleModal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }

    // View schedule details function
    window.viewScheduleDetails = async function(date) {
        try {
            const response = await fetch(`/admin/schedule/date/${date}`);
            const data = await response.json();
            
            if (data.success) {
                const bookingList = data.bookings.map((b, idx) => 
                    `${idx + 1}. ${b.pet_name} (${b.pet_type}) - ${b.customer_name} - ${b.service_type} at ${b.booking_time}`
                ).join('\n');
                
                alert(`Schedule Details for ${date}\n\nCapacity: ${data.capacity}\nBooked: ${data.booked}\nRemaining: ${data.remaining}\n\nBookings:\n${bookingList || 'No bookings'}`);
            }
        } catch (error) {
            console.error('Error fetching schedule details:', error);
            alert('Failed to load schedule details');
        }
    };

    // Event listeners for calendar navigation
    if (prevMonthBtn) {
        prevMonthBtn.addEventListener('click', () => {
            currentMonth--;
            if (currentMonth < 0) {
                currentMonth = 11;
                currentYear--;
            }
            fetchCalendarData();
        });
    }

    if (nextMonthBtn) {
        nextMonthBtn.addEventListener('click', () => {
            currentMonth++;
            if (currentMonth > 11) {
                currentMonth = 0;
                currentYear++;
            }
            fetchCalendarData();
        });
    }

    // Event listener for date filter
    if (filterDateInput) {
        filterDateInput.addEventListener('change', () => {
            currentPage = 1;
            fetchScheduleList(currentPage, filterDateInput.value);
        });
    }

    // Calendar day click handler with enhanced functionality
    document.addEventListener('click', function(e) {
        if (e.target.closest('.calendar-day') && !e.target.closest('.calendar-day').classList.contains('other-month')) {
            const dateElement = e.target.closest('.calendar-day');
            const selectedDate = dateElement.dataset.date;

            // Remove previous selection
            document.querySelectorAll('.calendar-day.selected').forEach(day => {
                day.classList.remove('selected');
            });

            // Add selection to clicked day
            dateElement.classList.add('selected');

            // Set filter date and fetch schedule list
            if (filterDateInput) {
                filterDateInput.value = selectedDate;
                currentPage = 1;
                fetchScheduleList(currentPage, selectedDate);
                
                // Smooth scroll to schedule table
                const scheduleTable = document.querySelector('.content-card');
                if (scheduleTable) {
                    setTimeout(() => {
                        scheduleTable.scrollIntoView({ 
                            behavior: 'smooth', 
                            block: 'start' 
                        });
                    }, 100);
                }
            }

            // Fetch and show booking details
            viewScheduleDetails(selectedDate);
        }
    });

    // Initialize page - fetch data from backend
    fetchCalendarData();
    fetchScheduleList(currentPage);

    // No time input; default values handled server-side if needed
});
    // Notification Functions
    const modal = document.getElementById("notificationModal");
const badge = document.getElementById("notificationBadge");
let notifications = document.querySelectorAll(".notification-item.unread");

function toggleNotificationModal() {
    modal.classList.toggle("show");
    document.body.style.overflow = modal.classList.contains("show") ? "hidden" : "";
    
    // Mark notifications as read when opening the modal
    if (modal.classList.contains("show")) {
        markAllAsRead();
    }
}

function markAllAsRead() {
    const unreadItems = document.querySelectorAll(".notification-item.unread");
    unreadItems.forEach(item => {
        item.classList.remove("unread");
        item.style.opacity = '0.7';
    });
    
    // Update badge count
    updateBadgeCount();
}

function updateBadgeCount() {
    const unreadCount = document.querySelectorAll(".notification-item.unread").length;
    if (unreadCount > 0) {
        badge.textContent = unreadCount;
        badge.style.display = 'flex';
    } else {
        badge.style.display = 'none';
    }
}

// Close modal when clicking outside
window.onclick = function(event) {
    if (event.target === modal) {
        toggleNotificationModal();
    }
}

// Close modal with Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape' && modal.classList.contains('show')) {
        toggleNotificationModal();
    }
});
</script>
@endsection
