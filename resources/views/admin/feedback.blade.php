@extends('layouts.app')
@include ('layoutadmin.navbar')
{{-- @section('content') --}}
<div class="dashboard-root">
    <!-- Main Content -->
    <main class="dashboard-main">
        <!-- Header -->
        <x-dashboard-header 
            title="Customer Feedback"
            subtitle="Pet Boarding Customer Reviews & Feedback"
            icon="chat-dots"
        />

        <!-- Stats Summary -->
        <div class="stats-summary">
            <div class="summary-card">
                <div class="summary-icon">
                    <img src="{{ asset('images/komen.svg') }}" alt="Komentar" class="icon-image">
                </div>
                <div class="summary-content">
                    <div class="summary-number" id="totalFeedback">{{ number_format($totalFeedback) }}</div>
                    <div class="summary-label">Total Feedback</div>
                </div>
            </div>
            <div class="summary-card">
                <div class="summary-icon average">
                    <img src="{{ asset('images/chart.svg') }}" alt="Chart" class="icon-image">
                </div>
                <div class="summary-content">
                    <div class="summary-number">{{ $averageRating }}</div>
                    <div class="summary-label">Average Rating</div>
                </div>
            </div>
            <div class="summary-card">
                <div class="summary-icon monthly">
                    <img src="{{ asset('images/kalender.svg') }}" alt="Calendar" class="icon-image">
                </div>
                <div class="summary-content">
                    <div class="summary-number">{{ $thisMonth }}</div>
                    <div class="summary-label">This Month</div>
                </div>
            </div>
            <div class="summary-card">
                <div class="summary-icon positive">
                    <img src="{{ asset('images/star.svg') }}" alt="Star" class="icon-image">
                </div>
                <div class="summary-content">
                    <div class="summary-number">{{ $positivePercentage }}%</div>
                    <div class="summary-label">Positive Reviews</div>
                </div>
            </div>
        </div>

        <!-- Main Content Card -->
        <div class="content-card">
            <!-- Page Header -->
            <div class="page-header">
                <div class="header-content">
                    <h1 class="page-title">Customer Feedback</h1>
                    <p class="page-subtitle">Manage and review customer feedback</p>
                </div>
                <div class="header-controls">
                    <form method="GET" action="{{ route('admin.feedback.index') }}" class="controls-form">
                        <div class="filter-dropdown">
                            <select name="rating" id="ratingFilter" class="rating-filter" onchange="this.form.submit()">
                                <option value="">All Ratings</option>
                                <option value="5" {{ request('rating') == '5' ? 'selected' : '' }}>5 Stars</option>
                                <option value="4" {{ request('rating') == '4' ? 'selected' : '' }}>4 Stars</option>
                                <option value="3" {{ request('rating') == '3' ? 'selected' : '' }}>3 Stars</option>
                                <option value="2" {{ request('rating') == '2' ? 'selected' : '' }}>2 Stars</option>
                                <option value="1" {{ request('rating') == '1' ? 'selected' : '' }}>1 Star</option>
                            </select>
                        </div>
                        <div class="search-box">
                            <i class="bi bi-search search-icon"></i>
                            <input type="text" 
                                   name="search" 
                                   class="search-input" 
                                   placeholder="Search feedback..."
                                   value="{{ request('search') }}">
                        </div>
                        @if(request('search') || request('rating'))
                            <a href="{{ route('admin.feedback.index') }}" class="clear-filters-btn">
                                Clear Filters
                            </a>
                        @endif
                    </form>
                </div>
            </div>

            <!-- Feedback Table -->
            <div class="table-container">
                <table class="feedback-table">
                    <thead>
                        <tr>
                            <th>NO</th>
                            <th>TESTIMONIAL</th>
                            <th>DATE</th>
                            <th>RATING</th>
                            <th>ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody id="feedbackTableBody">
                        @forelse($feedbacks as $index => $feedback)
                            <tr data-rating="{{ $feedback->rating }}" class="testimonial-row {{ $loop->even ? 'even-row' : 'odd-row' }}">
                                <td class="text-center">
                                    <span class="row-number">{{ ($feedbacks->currentPage() - 1) * $feedbacks->perPage() + $loop->iteration }}</span>
                                </td>
                                <td class="testimonial-text">
                                    {{ $feedback->message }}
                                </td>
                                <td class="text-center">
                                    <div class="testimonial-date">{{ $feedback->created_at->format('d/m/Y') }}</div>
                                </td>
                                <td class="text-center">
                                    <div class="rating-section">
                                        <div class="rating-score">{{ $feedback->rating }}/5</div>
                                        <div class="star-display">
                                            @for($i = 1; $i <= 5; $i++)
                                                <span class="testimonial-star {{ $i <= $feedback->rating ? 'filled' : 'empty' }}">★</span>
                                            @endfor
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="action-buttons">
                                        <button type="button" class="edit-btn" onclick="showEditModal({{ $feedback->id }}, {{ $feedback->rating }}, `{{ addslashes($feedback->message) }}`)">
                                            <i class="bi bi-pencil"></i>
                                            Edit
                                        </button>
                                        <button type="button" class="delete-btn" onclick="confirmDelete({{ $feedback->id }})">
                                            <i class="bi bi-trash"></i>
                                            Delete
                                        </button>
                                        <form id="delete-form-{{ $feedback->id }}" action="{{ route('admin.feedback.destroy', $feedback) }}" method="POST" class="d-none">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div class="empty-state">
                                        <div class="empty-icon">
                                            <i class="bi bi-chat-square-text-fill"></i>
                                        </div>
                                        <div class="empty-title">No Feedback Available</div>
                                        <div class="empty-subtitle">Customer feedback will appear here once submitted</div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- No Feedback Message -->
            <div id="noFeedbackMessage" class="no-data-message" style="display:none;">
                <div class="no-data-icon">
                    <i class="bi bi-chat-dots"></i>
                </div>
                <div class="no-data-title">No feedback found</div>
                <div class="no-data-subtitle">No customer feedback available at this time.</div>
            </div>

            <!-- Pagination -->
            @if($feedbacks->hasPages())
                <div class="pagination-wrapper">
                    {{ $feedbacks->appends(request()->query())->links('pagination::bootstrap-4') }}
                    <div class="page-info">
                        Showing {{ $feedbacks->firstItem() }} to {{ $feedbacks->lastItem() }} of {{ $feedbacks->total() }} entries
                    </div>
                </div>
            @endif

        </div>
    </main>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteConfirmationModalLabel">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this feedback? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Feedback Modal -->
<div class="modal fade" id="editFeedbackModal" tabindex="-1" aria-labelledby="editFeedbackModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editFeedbackForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="editFeedbackModalLabel">Edit Feedback</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="editRating" class="form-label">Rating</label>
                        <select class="form-select" id="editRating" name="rating" required>
                            <option value="5">5 Stars</option>
                            <option value="4">4 Stars</option>
                            <option value="3">3 Stars</option>
                            <option value="2">2 Stars</option>
                            <option value="1">1 Star</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="editMessage" class="form-label">Message</label>
                        <textarea class="form-control" id="editMessage" name="message" rows="4" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
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
    margin-left: 250px; /* biar ga ketimpa sidebar */
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
    color: #8B7355;
    font-size: 1.5rem;
    flex-shrink: 0;
    overflow: hidden;
    padding: 8px;
    position: relative;
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
    color: #4CAF50;
}

.summary-icon.monthly {
    background: linear-gradient(135deg, #E3F2FD 0%, #BBDEFB 100%);
    border-color: rgba(33, 150, 243, 0.15);
    color: #2196F3;
}

.summary-icon.positive {
    background: linear-gradient(135deg, #FFF3E0 0%, #FFCC99 100%);
    border-color: rgba(255, 152, 0, 0.15);
    color: #FF9800;
}

.summary-content {
    flex: 1;
}

.summary-number {
    font-size: 2rem;
    font-weight: 800;
    color: #6B4F3A;
    margin-bottom: 4px;
    line-height: 1;
}

.summary-label {
    color: #A97B5D;
    font-size: 0.9rem;
    font-weight: 600;
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

/* Page Header */
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 24px;
    padding: 0 0 20px 0;
}

.header-content {
    flex: 1;
}

.page-title {
    font-size: 2rem;
    font-weight: 700;
    color: #8B5E3C;
    margin: 0 0 8px 0;
    line-height: 1.2;
}

.page-subtitle {
    font-size: 16px;
    color: #8B5E3C;
    margin: 0;
    font-weight: 400;
}

.header-controls {
    display: flex;
    align-items: center;
    gap: 16px;
}

.controls-form {
    display: flex;
    align-items: center;
    gap: 16px;
}

/* Filter Dropdown */
.filter-dropdown {
    position: relative;
}

.rating-filter {
    background: #F4D4A7;
    border: 1px solid #E8D5C4;
    border-radius: 15px;
    padding: 12px 20px;
    font-size: 14px;
    color: #8B5E3C;
    font-weight: 500;
    cursor: pointer;
    min-width: 140px;
    appearance: none;
    background-image: url('data:image/svg+xml;charset=US-ASCII,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 4 5"><path fill="%238B5E3C" d="M2 0L0 2h4zm0 5L0 3h4z"/></svg>');
    background-repeat: no-repeat;
    background-position: right 16px center;
    background-size: 12px;
    padding-right: 40px;
}

.rating-filter:focus {
    outline: none;
    border-color: #D35400;
    box-shadow: 0 0 0 2px rgba(211, 84, 0, 0.2);
}

/* Search Box */
.search-box {
    position: relative;
    display: flex;
    align-items: center;
}

.search-icon {
    position: absolute;
    left: 16px;
    color: #999;
    font-size: 16px;
    z-index: 1;
}

.search-input {
    padding: 12px 20px 12px 45px;
    border: 1px solid #E0E0E0;
    border-radius: 15px;
    font-size: 14px;
    color: #333;
    background: #F8F8F8;
    min-width: 300px;
    transition: all 0.2s ease;
}

.search-input:focus {
    outline: none;
    border-color: #D35400;
    box-shadow: 0 0 0 2px rgba(211, 84, 0, 0.2);
}

.search-input::placeholder {
    color: #B0B0B0;
    font-style: italic;
}

/* Clear Filters Button */
.clear-filters-btn {
    background: #E74C3C;
    color: white;
    padding: 8px 16px;
    border-radius: 6px;
    text-decoration: none;
    font-size: 12px;
    font-weight: 600;
    transition: all 0.2s ease;
}

.clear-filters-btn:hover {
    background: #C0392B;
    color: white;
    text-decoration: none;
    transform: translateY(-1px);
}

/* Table Styles */
.table-container {
    overflow-x: auto;
    border-radius: 16px;
    overflow: hidden;
    margin-bottom: 24px;
}

.feedback-table {
    width: 100%;
    border-collapse: collapse;
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    font-size: 16px;
}

.feedback-table thead tr {
    background: #F4D4A7;
}

.feedback-table th {
    color: #8B5E3C;
    padding: 16px 20px;
    font-weight: 700;
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    text-align: left;
    border: none;
}

.feedback-table th:first-child {
    text-align: center;
    width: 60px;
}

.feedback-table th:nth-child(2) {
    width: auto;
}

.feedback-table th:nth-child(3) {
    text-align: center;
    width: 100px;
}

.feedback-table th:nth-child(4) {
    text-align: center;
    width: 120px;
}

.feedback-table th:last-child {
    text-align: center;
    width: 160px;
}

.feedback-table td {
    padding: 20px;
    border-bottom: 1px solid #F5F5F5;
    vertical-align: top;
    font-size: 16px;
    color: #333;
}

.testimonial-row {
    transition: all 0.2s ease;
}

.testimonial-row:hover {
    background-color: #FEFCF8;
}

.odd-row {
    background-color: white;
}

.even-row {
    background-color: #FEFEFE;
}

/* Row Number Styling */
.row-number {
    color: #E67E22;
    font-weight: 600;
    font-size: 16px;
}

/* Customer Name */
.customer-name {
    font-weight: 600;
    color: #2C3E50;
}

/* Testimonial Text */
.testimonial-text {
    line-height: 1.5;
    color: #555;
    max-width: 400px;
}

/* Date */
.testimonial-date {
    color: #666;
    font-weight: 500;
}

/* Rating Section */
.rating-section {
    text-align: center;
}

.rating-score {
    color: #E67E22;
    font-weight: 600;
    font-size: 14px;
    margin-bottom: 4px;
}

.star-display {
    display: flex;
    justify-content: center;
    gap: 2px;
}

.testimonial-star {
    font-size: 16px;
}

.testimonial-star.filled {
    color: #F39C12;
}

.testimonial-star.empty {
    color: #DDD;
}

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 8px;
    justify-content: center;
}

.edit-btn, .delete-btn {
    padding: 12px 16px;
    border: none;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    gap: 6px;
    min-width: 80px;
    justify-content: center;
}

.edit-btn {
    background-color: #E67E22;
    color: white;
    box-shadow: 0 2px 4px rgba(230, 126, 34, 0.3);
}

.edit-btn:hover {
    background-color: #D35400;
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(230, 126, 34, 0.4);
}

.delete-btn {
    background-color: #E74C3C;
    color: white;
    box-shadow: 0 2px 4px rgba(231, 76, 60, 0.3);
}

.delete-btn:hover {
    background-color: #C0392B;
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(231, 76, 60, 0.4);
}


/* Empty State */
.empty-state {
    padding: 40px 20px;
    text-align: center;
}

.empty-icon {
    font-size: 48px;
    color: #DDD;
    margin-bottom: 16px;
}

.empty-title {
    color: #6B4F3A;
    font-size: 18px;
    font-weight: 600;
    margin-bottom: 8px;
}

.empty-subtitle {
    color: #999;
    font-size: 14px;
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

.btn-reply {
    background: #E57300;
    color: #fff;
}

.btn-reply:hover {
    background: #E57300;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
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

.pagination-wrapper .pagination {
    display: flex !important;
    flex-direction: row !important;
    align-items: center;
    gap: 4px;
    margin: 0;
}

.pagination-wrapper .pagination .page-item {
    margin: 0 2px;
}

.pagination-wrapper .pagination .page-link {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 8px 12px;
    background: linear-gradient(135deg, #FFE0B2, #FFCC99);
    color: #6B4F3A;
    border: 1px solid rgba(169, 123, 93, 0.2);
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.2s ease;
    min-width: 40px;
}

.pagination-wrapper .pagination .page-link:hover {
    background: linear-gradient(135deg, #F9D9A7, #F0B27A);
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(229, 115, 0, 0.2);
    color: #6B4F3A;
}

.pagination-wrapper .pagination .page-item.active .page-link {
    background: #E57300;
    color: white;
    border-color: #E57300;
    box-shadow: 0 2px 8px rgba(229, 115, 0, 0.3);
}

.pagination-wrapper .pagination .page-item.disabled .page-link {
    opacity: 0.5;
    background: #f0f0f0;
    color: #999;
    border-color: #ddd;
    cursor: not-allowed;
}

.pagination-wrapper .pagination .page-item.disabled .page-link:hover {
    transform: none;
    box-shadow: none;
    background: #f0f0f0;
}

.pagination-container {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 8px;
}

.pagination-btn {
    background: linear-gradient(135deg, #FFE0B2, #FFCC99);
    color: #6B4F3A;
    border: 1px solid rgba(169, 123, 93, 0.2);
    border-radius: 10px;
    padding: 10px 14px;
    font-size: 0.9rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    min-width: 40px;
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

    .summary-number {
        font-size: 1.8rem;
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

    .feedback-table th,
    .feedback-table td {
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
        font-size: 1.3rem;
    }

    .summary-number {
        font-size: 1.6rem;
    }

    .feedback-table th,
    .feedback-table td {
        padding: 12px 8px;
        font-size: 0.8rem;
    }

    .feedback-text {
        max-width: 250px;
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
    let feedbackIdToDelete = null;
    let editFeedbackModal = null;
    let editForm = null;

    // Initialize modals when DOM is loaded
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize modals
        editFeedbackModal = new bootstrap.Modal(document.getElementById('editFeedbackModal'));
        editForm = document.getElementById('editFeedbackForm');
        
        // Handle edit form submission
        if (editForm) {
            editForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(editForm);
                const feedbackId = editForm.getAttribute('data-feedback-id');
                
                fetch(`/admin/feedback/${feedbackId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-HTTP-Method-Override': 'PUT',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        '_method': 'PUT',
                        'rating': formData.get('rating'),
                        'message': formData.get('message')
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Reload the page to show updated feedback
                        window.location.reload();
                    } else {
                        alert('Failed to update feedback. Please try again.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while updating feedback.');
                });
            });
        }
    });

    function confirmDelete(id) {
        feedbackIdToDelete = id;
        const modal = new bootstrap.Modal(document.getElementById('deleteConfirmationModal'));
        modal.show();
    }

    function showEditModal(id, rating, message) {
        const form = document.getElementById('editFeedbackForm');
        form.setAttribute('data-feedback-id', id);
        form.action = `/admin/feedback/${id}`;
        document.getElementById('editRating').value = rating;
        document.getElementById('editMessage').value = message;
        editFeedbackModal.show();
    }

    document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
        if (feedbackIdToDelete) {
            document.getElementById('delete-form-' + feedbackIdToDelete).submit();
        }
    });

    // Search and Filter Functionality
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.querySelector('.search-input');
        const ratingFilter = document.querySelector('.rating-filter');
        const tableBody = document.getElementById('feedbackTableBody');
        const allRows = Array.from(tableBody.querySelectorAll('.testimonial-row'));

        function filterTable() {
            const searchTerm = searchInput.value.toLowerCase().trim();
            const selectedRating = ratingFilter.value;

            allRows.forEach(row => {
                const testimonialText = row.querySelector('.testimonial-text').textContent.toLowerCase();
                const rowRating = row.getAttribute('data-rating');
                
                const matchesSearch = searchTerm === '' || testimonialText.includes(searchTerm);
                const matchesRating = selectedRating === '' || rowRating === selectedRating;
                
                if (matchesSearch && matchesRating) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });

            // Update row numbers for visible rows
            updateRowNumbers();
        }

        function updateRowNumbers() {
            const visibleRows = allRows.filter(row => row.style.display !== 'none');
            visibleRows.forEach((row, index) => {
                const rowNumberElement = row.querySelector('.row-number');
                if (rowNumberElement) {
                    rowNumberElement.textContent = index + 1;
                }
            });
        }

        // Event listeners
        if (searchInput) {
            searchInput.addEventListener('input', filterTable);
        }

        if (ratingFilter) {
            ratingFilter.addEventListener('change', filterTable);
        }

        // Clear search functionality
        const clearButton = document.querySelector('.clear-filters-btn');
        if (clearButton) {
            clearButton.addEventListener('click', function(e) {
                e.preventDefault();
                searchInput.value = '';
                ratingFilter.value = '';
                filterTable();
            });
        }
    });

</script>
{{-- @endsection --}}