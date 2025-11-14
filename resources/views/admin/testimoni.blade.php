@extends('layouts.app')
@include ('layoutadmin.navbar')
@section('content')
<div class="dashboard-root">
    <!-- Main Content -->
    <main class="dashboard-main">
        <!-- Header -->
        <x-dashboard-header 
            title="Customer Testimonials"
            subtitle="Reviews from verified customers with completed bookings"
            icon="star"
        />

        <!-- Stats Summary -->
        <div class="stats-summary">
            <div class="summary-card">
                <div class="summary-icon">
                    <img src="{{ asset('images/komen.svg') }}" alt="Reviews Icon" class="icon-image">
                </div>
                <div class="summary-content">
                    <div class="summary-number" id="totalReviews">{{ number_format($totalTestimonials) }}</div>
                    <div class="summary-label">Total Testimonials</div>
                </div>
            </div>
            <div class="summary-card">
                <div class="summary-icon average">
                    <img src="{{ asset('images/chart.svg') }}" alt="Chart Icon" class="icon-image">
                </div>
                <div class="summary-content">
                    <div class="summary-number">{{ $averageRating }}</div>
                    <div class="summary-label">Average Rating</div>
                </div>
            </div>
            <div class="summary-card">
                <div class="summary-icon monthly">
                    <img src="{{ asset('images/kalender.svg') }}" alt="Calendar Icon" class="icon-image">
                </div>
                <div class="summary-content">
                    <div class="summary-number">{{ $thisMonth }}</div>
                    <div class="summary-label">This Month</div>
                </div>
            </div>
            <div class="summary-card">
                <div class="summary-icon positive">
                    <img src="{{ asset('images/star.svg') }}" alt="Star Icon" class="icon-image">
                </div>
                <div class="summary-content">
                    <div class="summary-number">{{ $positivePercentage }}%</div>
                    <div class="summary-label">Positive Testimonials</div>
                </div>
            </div>
        </div>

        <!-- Main Content Card -->
        <div class="content-card">
            <!-- Header with Search and Filters -->
            <div class="content-header">
                <div class="content-info">
                    <div class="content-title">Customer Testimonials</div>
                    <div class="content-subtitle">Manage and review customer feedback</div>
                </div>
                <div class="content-controls">
                    <form method="GET" action="{{ route('admin.testimoni') }}" class="controls-form" style="display:flex;gap:12px;align-items:center;flex-wrap:wrap;">
                        <select name="rating" id="ratingFilter" class="filter-select" onchange="this.form.submit()">
                            <option value="">All Ratings</option>
                            <option value="5" {{ request('rating') == '5' ? 'selected' : '' }}>★★★★★ 5 Stars</option>
                            <option value="4" {{ request('rating') == '4' ? 'selected' : '' }}>★★★★☆ 4 Stars</option>
                            <option value="3" {{ request('rating') == '3' ? 'selected' : '' }}>★★★☆☆ 3 Stars</option>
                            <option value="2" {{ request('rating') == '2' ? 'selected' : '' }}>★★☆☆☆ 2 Stars</option>
                            <option value="1" {{ request('rating') == '1' ? 'selected' : '' }}>★☆☆☆☆ 1 Star</option>
                        </select>
                        <div class="search-box">
                            <i class="bi bi-search"></i>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search testimonials..." class="search-input">
                        </div>
                        @if(request('search') || request('rating'))
                            <a href="{{ route('admin.testimoni') }}" class="action-btn" style="background:#DC3545;color:#fff;">Clear</a>
                        @endif
                    </form>
                </div>
            </div>

            <!-- Testimonial Table -->
            <div class="table-container">
                <table class="testimonial-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Customer Name</th>
                            <th>Testimonial</th>
                            <th>Date</th>
                            <th>Rating</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="testimonialTableBody">
                        @forelse($testimonials as $testimonial)
                            <tr data-rating="{{ $testimonial->rating }}">
                                <td>
                                    {{ ($testimonials->currentPage() - 1) * $testimonials->perPage() + $loop->iteration }}
                                </td>
                                <td>
                                    <div class="customer-name">{{ $testimonial->member->name ?? 'Member' }}</div>
                                    <div class="customer-email" style="font-size:0.85rem;color:#888;">{{ $testimonial->member->email ?? '' }}</div>
                                </td>
                                <td>
                                    <div class="testimonial-text">{{ $testimonial->message }}</div>
                                    <div style="font-size:0.8rem;color:#999;margin-top:4px;">
                                        Booking: {{ $testimonial->booking->service_type ?? '' }} - {{ $testimonial->booking->pet_name ?? '' }}
                                    </div>
                                </td>
                                <td class="date-display">
                                    {{ $testimonial->created_at?->format('d/m/Y') }}
                                </td>
                                <td>
                                    <div class="rating-display">
                                        <div class="rating-score">{{ $testimonial->rating }}/5</div>
                                        <div class="star-rating">
                                            @for($i=1;$i<=5;$i++)
                                                <span class="star {{ $i <= $testimonial->rating ? 'filled' : 'empty' }}">★</span>
                                            @endfor
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <form id="delete-form-{{ $testimonial->id }}" action="{{ route('admin.testimonials.destroy', $testimonial) }}" method="POST" onsubmit="return confirm('Delete this testimonial?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="action-btn btn-delete">
                                                <i class="bi bi-trash"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">
                                    <div class="no-data-title">No testimonials found</div>
                                    <div class="no-data-subtitle">No customer testimonials available at this time.</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- No Testimonials Message -->
            <div id="noTestimonialsMessage" class="no-data-message" style="display:none;">
                <div class="no-data-icon">
                    <i class="bi bi-chat-dots"></i>
                </div>
                <div class="no-data-title">No testimonials found</div>
                <div class="no-data-subtitle">No customer testimonials available at this time.</div>
            </div>

            <!-- Pagination -->
            @if($testimonials->hasPages())
                <div class="pagination-wrapper">
                    {{ $testimonials->appends(request()->query())->links('pagination::bootstrap-4') }}
                    <div id="pageInfo" class="page-info">
                        Showing {{ $testimonials->firstItem() }} to {{ $testimonials->lastItem() }} of {{ $testimonials->total() }} entries
                    </div>
                </div>
            @endif

           
        </div>
    </main>
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
.dashboard-main {
    flex: 1;
    padding: 40px 32px 32px 32px;
    max-width: 100%;
    margin-left: 250px; /* geser isi karena sidebar */
}
</style>


<style>
/* Root Styles - Same as Dashboard */
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
}

/* Header - Same as Dashboard */
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

.header-left {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.header-title {
    font-size: 2.2rem;
    font-weight: 800;
    color: #6B4F3A;
    letter-spacing: -0.02em;
}

.header-subtitle {
    color: #A97B5D;
    font-size: 1rem;
    font-weight: 500;
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

/* Stats Summary */
.stats-summary {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 24px;
    margin-bottom: 32px;
}

.summary-card {
    background: linear-gradient(135deg, #fff 0%, #fefefe 100%);
    border-radius: 18px;
    padding: 24px;
    display: flex;
    align-items: center;
    gap: 20px;
    box-shadow: 0 4px 24px rgba(230, 161, 93, 0.08);
    transition: all 0.3s ease;
    border: 1px solid rgba(255, 255, 255, 0.5);
}

.summary-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 32px rgba(230, 161, 93, 0.15);
}

.summary-icon {
    width: 64px;
    height: 64px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #F5E6D3 0%, #E8D5C4 100%);
    border: 2px solid rgba(139, 115, 85, 0.15);
    flex-shrink: 0;
    position: relative;
    overflow: hidden;
}

.summary-icon::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: radial-gradient(circle at 30% 30%, rgba(255, 255, 255, 0.3) 0%, transparent 60%);
    border-radius: 14px;
}

.icon-image {
    width: 32px;
    height: 32px;
    object-fit: contain;
    opacity: 0.9;
    filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.1));
    z-index: 1;
    position: relative;
    margin: auto;
    display: block;
}

.summary-icon.average {
    background: linear-gradient(135deg, #E8F5E8 0%, #D4EDDA 100%);
    border-color: rgba(76, 175, 80, 0.15);
}

.summary-icon.monthly {
    background: linear-gradient(135deg, #E3F2FD 0%, #BBDEFB 100%);
    border-color: rgba(33, 150, 243, 0.15);
}

.summary-icon.positive {
    background: linear-gradient(135deg, #FFF3E0 0%, #FFCC99 100%);
    border-color: rgba(255, 152, 0, 0.15);
}

.summary-content {
    flex: 1;
}

.summary-number {
    font-size: 2.2rem;
    font-weight: 800;
    color: #6B4F3A;
    margin-bottom: 4px;
    line-height: 1;
    letter-spacing: -0.02em;
}

.summary-label {
    color: #A97B5D;
    font-size: 0.9rem;
    font-weight: 600;
    letter-spacing: 0.01em;
}

/* Content Card */
.content-card {
    background: linear-gradient(135deg, #fff 0%, #fefefe 100%);
    border-radius: 24px;
    padding: 32px;
    box-shadow: 0 12px 40px rgba(230, 161, 93, 0.08);
    border: 1px solid rgba(255, 255, 255, 0.5);
    margin-bottom: 32px;
}

/* Content Header */
.content-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 32px;
    gap: 24px;
}

.content-info {
    flex: 1;
}

.content-title {
    font-weight: 800;
    font-size: 1.6rem;
    color: #6B4F3A;
    margin-bottom: 6px;
}

.content-subtitle {
    color: #A97B5D;
    font-size: 1rem;
    font-weight: 500;
}

.content-controls {
    display: flex;
    align-items: center;
    gap: 16px;
    flex-wrap: wrap;
}

/* Filter Select */
.filter-select {
    background: linear-gradient(135deg, #FFE0B2, #FFCC99);
    border: 1px solid rgba(169, 123, 93, 0.2);
    border-radius: 12px;
    padding: 12px 16px;
    color: #6B4F3A;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    min-width: 160px;
    font-size: 0.9rem;
}

.filter-select:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(229, 115, 0, 0.2);
}

.filter-select:focus {
    outline: none;
    border-color: #E57300;
    box-shadow: 0 0 0 3px rgba(229, 115, 0, 0.1);
}

/* Search Box - Same as Dashboard */
.search-box {
    position: relative;
    display: flex;
    align-items: center;
}

.search-box i {
    position: absolute;
    left: 12px;
    color: #A97B5D;
    z-index: 1;
    font-size: 1rem;
}

.search-input {
    background: #F7F5F2;
    border: 1px solid rgba(169, 123, 93, 0.2);
    border-radius: 12px;
    padding: 12px 16px 12px 40px;
    font-size: 0.9rem;
    color: #6B4F3A;
    width: 280px;
    transition: all 0.3s ease;
    font-weight: 500;
}

.search-input:focus {
    outline: none;
    border-color: #E57300;
    box-shadow: 0 0 0 3px rgba(229, 115, 0, 0.1);
    background: #fff;
}

.search-input::placeholder {
    color: #A97B5D;
    opacity: 0.7;
}

/* Table Styles - Same as Dashboard */
.table-container {
    overflow-x: auto;
    border-radius: 16px;
    overflow: hidden;
    margin-bottom: 24px;
}

.testimonial-table {
    width: 100%;
    min-width: 900px;
    background: #fff;
    border-collapse: collapse;
}

.testimonial-table thead tr {
    background: linear-gradient(135deg, #FFE0B2, #F9D9A7);
}

.testimonial-table th {
    color: #6B4F3A;
    padding: 20px 16px;
    font-weight: 700;
    text-align: left;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-bottom: 2px solid rgba(169, 123, 93, 0.1);
}

.testimonial-table th:first-child {
    text-align: center;
    width: 80px;
}

.testimonial-table th:nth-child(4),
.testimonial-table th:nth-child(5) {
    text-align: center;
}

.testimonial-table th:last-child {
    text-align: center;
    width: 160px;
}

.testimonial-table td {
    padding: 20px 16px;
    border-bottom: 1px solid rgba(240, 240, 240, 0.8);
    color: #6B4F3A;
    vertical-align: middle;
    font-weight: 500;
}

.testimonial-table tbody tr {
    transition: all 0.3s ease;
}

.testimonial-table tbody tr:hover {
    background: rgba(229, 115, 0, 0.02);
    transform: scale(1.002);
}

/* Table Cell Specific Styles */
.testimonial-table td:first-child {
    text-align: center;
    font-weight: 600;
    color: #E57300;
}

.testimonial-table td:nth-child(4),
.testimonial-table td:nth-child(5) {
    text-align: center;
}

.testimonial-table td:last-child {
    text-align: center;
}

.customer-name {
    font-weight: 600;
    color: #6B4F3A;
    font-size: 0.95rem;
}

.testimonial-text {
    max-width: 300px;
    line-height: 1.4;
    color: #6B4F3A;
}

.date-display {
    font-weight: 600;
    color: #6B4F3A;
    font-size: 0.9rem;
}

.rating-display {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 6px;
}

.rating-score {
    font-weight: 600;
    color: #6B4F3A;
    font-size: 0.9rem;
}

.star-rating {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 2px;
}

.star {
    color: #E57300;
    font-size: 1.1rem;
    line-height: 1;
    transition: all 0.2s ease;
}

.star.filled {
    opacity: 1;
}

.star.empty {
    opacity: 0.3;
}

.star:hover {
    transform: scale(1.1);
}

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 8px;
    justify-content: center;
}

.action-btn {
    border: none;
    border-radius: 8px;
    padding: 8px 16px;
    font-size: 0.85rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
    text-transform: capitalize;
}

.btn-edit {
    background: #E57300;
    color: #fff;
}

.btn-edit:hover {
    background: #CC6600;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(229, 115, 0, 0.3);
}

.btn-delete {
    background: #DC3545;
    color: #fff;
}

.btn-delete:hover {
    background: #C82333;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
}

/* No Data Message */
.no-data-message {
    text-align: center;
    padding: 60px 20px;
    color: #A97B5D;
}

.no-data-icon {
    font-size: 4rem;
    margin-bottom: 20px;
    opacity: 0.5;
    color: #E57300;
}

.no-data-title {
    font-size: 1.3rem;
    font-weight: 700;
    color: #6B4F3A;
    margin-bottom: 8px;
}

.no-data-subtitle {
    font-size: 1rem;
    color: #A97B5D;
}

/* Pagination */
.pagination-wrapper {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 16px;
    margin-top: 32px;
}

.pagination-container {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 8px;
}

.pagination-btn {
    min-width: 40px;
    height: 40px;
    border-radius: 10px;
    border: 1px solid rgba(0,0,0,0.06);
    background: linear-gradient(135deg, #FFE0B2, #F9D9A7);
    color: #6B4F3A;
    font-weight: 700;
    cursor: pointer;
    box-shadow: 0 6px 18px rgba(230,161,93,0.14);
    transition: transform .15s ease, box-shadow .15s ease, background .15s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}

.pagination-btn:hover {
    background: linear-gradient(135deg, #F9D9A7, #F0B27A);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(229, 115, 0, 0.2);
}

.pagination-btn.active {
    background: #E57300;
    color: #fff;
    border-color: #E57300;
    box-shadow: 0 4px 12px rgba(229, 115, 0, 0.3);
}

.pagination-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
    background: #f0f0f0;
    color: #999;
    border-color: #ddd;
}

.pagination-btn:disabled:hover {
    transform: none;
    box-shadow: none;
    background: #f0f0f0;
}

.pagination-dots {
    padding: 10px 14px;
    color: #A97B5D;
    font-weight: 600;
}

.page-info {
    text-align: center;
    color: #A97B5D;
    font-size: 0.9rem;
    font-weight: 500;
}

.search-status {
    text-align: center;
    color: #6B4F3A;
    font-size: 0.9rem;
    font-weight: 600;
    margin-top: 12px;
    padding: 8px 16px;
    background: linear-gradient(135deg, #F7F5F2, #FFEEE5);
    border-radius: 20px;
    border: 1px solid rgba(169, 123, 93, 0.1);
    display: inline-block;
}

/* Responsive Design */
@media (max-width: 1200px) {
    .stats-summary {
        grid-template-columns: repeat(2, 1fr);
    }

    .content-controls {
        flex-direction: column;
        align-items: stretch;
        gap: 12px;
    }

    .filter-select,
    .search-input {
        width: 100%;
    }
}

@media (max-width: 768px) {
    .dashboard-main {
        padding: 20px 16px;
    }

    .dashboard-header {
        flex-direction: column;
        gap: 16px;
        text-align: center;
        padding: 20px;
    }

    .header-title {
        font-size: 1.8rem;
    }

    .stats-summary {
        grid-template-columns: 1fr;
        gap: 16px;
    }

    .summary-card {
        padding: 20px;
    }

    .summary-icon {
        width: 56px;
        height: 56px;
    }

    .icon-image {
        width: 28px;
        height: 28px;
    }

    .summary-number {
        font-size: 2rem;
    }

    .content-card {
        padding: 24px 20px;
        border-radius: 16px;
    }

    .content-header {
        flex-direction: column;
        gap: 20px;
        align-items: stretch;
    }

    .content-controls {
        gap: 12px;
    }

    .testimonial-table th,
    .testimonial-table td {
        padding: 16px 12px;
        font-size: 0.85rem;
    }

    .action-buttons {
        flex-direction: column;
        gap: 6px;
    }

    .pagination-btn {
        padding: 8px 12px;
        font-size: 0.85rem;
    }
}

@media (max-width: 480px) {
    .header-title {
        font-size: 1.6rem;
    }

    .content-title {
        font-size: 1.4rem;
    }

    .summary-icon {
        width: 50px;
        height: 50px;
    }

    .icon-image {
        width: 24px;
        height: 24px;
    }

    .summary-number {
        font-size: 1.8rem;
    }

    .testimonial-table th,
    .testimonial-table td {
        padding: 12px 8px;
        font-size: 0.8rem;
    }

    .testimonial-text {
        max-width: 200px;
    }

    .action-btn {
        padding: 6px 12px;
        font-size: 0.8rem;
    }
}

/* Animations */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.summary-card,
.content-card {
    animation: fadeInUp 0.6s ease forwards;
}

.summary-card:nth-child(1) { animation-delay: 0.1s; }
.summary-card:nth-child(2) { animation-delay: 0.2s; }
.summary-card:nth-child(3) { animation-delay: 0.3s; }
.summary-card:nth-child(4) { animation-delay: 0.4s; }
.content-card { animation-delay: 0.5s; }
</style>

<script>
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

// No need for JS-driven table rendering; the table is rendered via Blade and pagination links
</script>
@endsection