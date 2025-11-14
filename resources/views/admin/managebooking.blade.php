<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Booking - Pet Boarding</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
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
            margin-left: 250px;
        }

        /* Header - Same as Customer */
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

        .summary-icon.total {
            background: linear-gradient(135deg, #F5E6D3 0%, #BBDEFB 100%);
            border-color: rgba(139, 115, 85, 0.15);
        }

        .summary-icon.active {
            background: linear-gradient(135deg, #E8F5E8 0%, #F5E6D3 100%);
            border-color: rgba(76, 175, 80, 0.15);
        }

        .summary-icon.pending {
            background: linear-gradient(135deg, #FFF8E1 0%, #FFECB3 100%);
            border-color: rgba(255, 193, 7, 0.15);
        }

        .summary-icon.revenue {
            background: linear-gradient(135deg, #E3F2FD 0%, #FFF8E1 100%);
            border-color: rgba(33, 150, 243, 0.15);
        }

        .summary-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .summary-number {
            font-size: 2rem;
            font-weight: 800;
            color: #6B4F3A;
            margin-bottom: 2px;
            line-height: 1.1;
            letter-spacing: -0.02em;
        }

        .summary-label {
            color: #A97B5D;
            font-size: 0.9rem;
            font-weight: 600;
            letter-spacing: 0.01em;
            line-height: 1.2;
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

        /* Search Box */
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

        /* Add Button */
        .btn-add-customer {
            background: #E57300;
            color: #fff;
            border: none;
            border-radius: 12px;
            padding: 12px 20px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.9rem;
        }

        .btn-add-customer:hover {
            background: #D16500;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(229, 115, 0, 0.3);
        }

        /* Table Styles */
        .table-container {
            overflow-x: auto;
            border-radius: 16px;
            overflow: hidden;
            margin-bottom: 24px;
        }

        .booking-table {
            width: 100%;
            min-width: 1000px;
            background: #fff;
            border-collapse: collapse;
        }

        .booking-table thead tr {
            background: linear-gradient(135deg, #FFE0B2, #F9D9A7);
        }

        .booking-table th {
            color: #6B4F3A;
            padding: 20px 16px;
            font-weight: 700;
            text-align: left;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid rgba(169, 123, 93, 0.1);
        }

        .booking-table th:first-child {
            text-align: center;
            width: 80px;
        }

        .booking-table th:nth-child(4),
        .booking-table th:nth-child(5),
        .booking-table th:nth-child(6) {
            text-align: center;
        }

        .booking-table th:last-child {
            text-align: center;
            width: 180px;
        }

        .booking-table td {
            padding: 20px 16px;
            border-bottom: 1px solid rgba(240, 240, 240, 0.8);
            color: #6B4F3A;
            vertical-align: middle;
            font-weight: 500;
        }

        .booking-table tbody tr {
            transition: all 0.3s ease;
        }

        .booking-table tbody tr:hover {
            background: rgba(229, 115, 0, 0.02);
            transform: scale(1.002);
        }

        .booking-table td:first-child {
            text-align: center;
            font-weight: 600;
            color: #E57300;
        }

        .booking-table td:nth-child(4),
        .booking-table td:nth-child(5),
        .booking-table td:nth-child(6) {
            text-align: center;
        }

        .booking-table td:last-child {
            text-align: center;
        }

        .customer-name {
            font-weight: 600;
            color: #6B4F3A;
            font-size: 0.95rem;
        }

        .pet-name {
            font-weight: 600;
            color: #E57300;
            font-size: 0.95rem;
        }

        .date-display {
            font-weight: 600;
            color: #6B4F3A;
            font-size: 0.9rem;
        }

        .duration-display {
            font-weight: 600;
            color: #6B4F3A;
            font-size: 0.9rem;
        }

        /* Status Badges */
        .status-badge {
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: inline-block;
        }

        .status-pending {
            background: linear-gradient(135deg, #FFF3CD, #FFF8DC);
            color: #856404;
            border: 1px solid #FFEAA7;
        }

        .status-confirmed {
            background: linear-gradient(135deg, #D4EDDA, #C3E6CB);
            color: #155724;
            border: 1px solid #B8DAFF;
        }

        .status-checked-in {
            background: linear-gradient(135deg, #D1ECF1, #BEE5EB);
            color: #0C5460;
            border: 1px solid #BEE5EB;
        }

        .status-completed {
            background: linear-gradient(135deg, #E2E3E5, #F8F9FA);
            color: #383D41;
            border: 1px solid #DEE2E6;
        }

        .status-on-pickup {
            background: linear-gradient(135deg, #FFE0B2, #F9D9A7);
            color: #6B4F3A;
            border: 1px solid #F5CBA7;
        }

        .status-cancelled {
            background: linear-gradient(135deg, #F8D7DA, #F5C6CB);
            color: #721C24;
            border: 1px solid #F5C6CB;
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
            padding: 8px 12px;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            text-transform: capitalize;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .btn-view {
            background: #2196F3;
            color: #fff;
        }

        .btn-view:hover {
            background: #0b7dda;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(33, 150, 243, 0.3);
        }

        .btn-edit {
            background: #FF9800;
            color: #fff;
        }

        .btn-edit:hover {
            background: #e68a00;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(255, 152, 0, 0.3);
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
                margin-left: 0;
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

            .booking-table th,
            .booking-table td {
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
                font-size: 1.6rem;
            }

            .booking-table th,
            .booking-table td {
                padding: 12px 8px;
                font-size: 0.8rem;
            }

            .action-btn {
                padding: 6px 10px;
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

        /* Modal Styles */
        .modal {
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background: #fff;
            border-radius: 16px;
            width: 90%;
            max-width: 600px;
            max-height: 80vh;
            overflow-y: auto;
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
            animation: slideUp 0.3s ease;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .modal-header {
            background: linear-gradient(135deg, #FFE0B2, #F9D9A7);
            padding: 20px 32px;
            border-radius: 16px 16px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-title {
            font-size: 1.4rem;
            font-weight: 700;
            color: #6B4F3A;
            margin: 0;
        }

        .modal-close {
            font-size: 28px;
            color: #6B4F3A;
            cursor: pointer;
            font-weight: bold;
            line-height: 1;
        }

        .modal-close:hover {
            color: #E57300;
        }

        .modal-body {
            padding: 32px;
        }

        /* Form Styles */
        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
        }

        .form-field {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .form-field label {
            font-size: 0.85rem;
            color: #A97B5D;
            font-weight: 600;
        }

        .form-field input,
        .form-field select {
            padding: 10px 12px;
            border-radius: 10px;
            border: 1px solid rgba(169, 123, 93, 0.25);
            background: #F7F5F2;
            color: #6B4F3A;
        }

        .form-field input:focus,
        .form-field select:focus {
            outline: none;
            border-color: #E57300;
            box-shadow: 0 0 0 3px rgba(229, 115, 0, 0.1);
            background: #fff;
        }

        .form-actions {
            grid-column: 1 / -1;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 8px;
        }

        .btn-add {
            background: #E57300;
            color: #fff;
            border: none;
            border-radius: 12px;
            padding: 12px 20px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.9rem;
        }

        .btn-add:hover {
            background: #D16500;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(229, 115, 0, 0.3);
        }

        
    </style>
</head>

<body>
    @include('layoutadmin.navbar')
    @extends ('layouts.app')
    
    <div class="dashboard-root">
        <main class="dashboard-main">
            <!-- Header -->
            <div class="dashboard-header">
                <div class="header-left">
                    <h1 class="header-title">Booking Management</h1>
                    <p class="header-subtitle">Pet Boarding Reservations & Bookings</p>
                </div>
                <div class="header-profile">
                    <div class="notification-icon" onclick="toggleNotificationModal()">
                        <span class="badge" id="notificationBadge">2</span>
                    </div>
                    <div class="profile-info">
                        <div class="profile-details">
                            <span class="profile-name">Admin</span>
                            <span class="profile-role">Administrator</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Summary -->
            <div class="stats-summary">
                <div class="summary-card">
                    {{-- <div class="summary-icon total">
                        <img src="{{ asset('images/total.svg') }}" alt="Total Bookings" class="icon-image">
                    </div> --}}
                    <div class="summary-content">
                        <div class="summary-number" id="totalBookings">0</div>
                        <div class="summary-label">Total Bookings</div>
                    </div>
                </div>
                <div class="summary-card">
                    {{-- <div class="summary-icon active">
                        <img src="{{ asset('images/active.svg') }}" alt="Active Pets" class="icon-image">
                    </div> --}}
                    <div class="summary-content">
                        <div class="summary-number" id="activePets">0</div>
                        <div class="summary-label">Pets Boarded</div>
                    </div>
                </div>
                <div class="summary-card">
                    {{-- <div class="summary-icon pending">
                        <img src="{{ asset('images/pending.svg') }}" alt="Pending Bookings" class="icon-image">
                    </div> --}}
                    <div class="summary-content">
                        <div class="summary-number" id="pendingBookings">0</div>
                        <div class="summary-label">Pending Bookings</div>
                    </div>
                </div>
                <div class="summary-card">
                    {{-- <div class="summary-icon revenue">
                        <img src="{{ asset('images/revenue.svg') }}" alt="Monthly Revenue" class="icon-image">
                    </div> --}}
                    <div class="summary-content">
                        <div class="summary-number" id="monthlyRevenue">Rp 0</div>
                        <div class="summary-label">Monthly Revenue</div>
                    </div>
                </div>
            </div>

            <!-- Main Content Card -->
            <div class="content-card">
                <!-- Header with Search and Filters -->
                <div class="content-header">
                    <div class="content-info">
                        <div class="content-title">Booking Management</div>
                        <div class="content-subtitle">Manage pet boarding reservations and bookings</div>
                    </div>
                <div class="content-controls">
                    <!-- Status Filter -->
                    <select id="statusFilter" class="filter-select">
                        <option value="">Semua Status</option>
                        <option value="pending">Pending</option>
                        <option value="confirmed">Confirmed</option>
                        <option value="checked-in">Checked In</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                        <option value="on-pickup">On Pickup</option>
                        

                    </select>
                    <!-- Search Bar -->
                    <div class="search-box">
                        <i class="bi bi-search"></i>
                        <input type="text" id="searchInput" placeholder="Search bookings..." class="search-input">
                    </div>
                    <!-- Add Booking Button -->
                    <button type="button" class="btn-add-customer" onclick="openAddBookingModal()">
                        <i class="bi bi-plus-lg"></i> Add Booking
                    </button>
                </div>
            </div>

            <!-- Booking Table -->
            <div class="table-container">
                <table class="booking-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Customer</th>
                            <th>Pet Name</th>
                            <th>Check-in Date</th>
                            <th>Duration</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="bookingTableBody">
                        <!-- Booking rows will be populated by JavaScript -->
                    </tbody>
                </table>
            </div>

            <!-- No Bookings Message -->
            <div id="noBookingsMessage" class="no-data-message" style="display:none;">
                <div class="no-data-icon">
                    <i class="bi bi-calendar-x"></i>
                </div>
                <div class="no-data-title">No bookings found</div>
                <div class="no-data-subtitle">No bookings available at this time.</div>
            </div>

            <!-- Pagination -->
            <div class="pagination-wrapper">
                <div id="paginationContainer" class="pagination-container"></div>
                <div id="pageInfo" class="page-info"></div>
            </div>

            <div id="searchStatus" class="search-status"></div>


           
        </div>
    </main>
</div>

<!-- Booking Detail Modal -->
<div id="bookingModal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">Booking Details</h3>
            <span class="modal-close" onclick="closeModal()">&times;</span>
        </div>
        <div class="modal-body" id="modalBody">
            <!-- Booking details will be loaded here -->
        </div>
    </div>
</div>

<!-- Add Booking Modal -->
<div id="addBookingModal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title" id="addBookingModalTitle">Add Booking</h3>
            <span class="modal-close" onclick="closeAddBookingModal()">&times;</span>
        </div>
        <div class="modal-body">
            <form id="addBookingForm" class="form-grid">
                @csrf
                <input type="hidden" id="editingId" value="">
                <div class="form-field">
                    <label>Customer Name</label>
                    <input type="text" name="customer" required />
                </div>
                <div class="form-field">
                    <label>Pet Name</label>
                    <input type="text" name="pet" required />
                </div>
                <div class="form-field">
                    <label>Pet Type</label>
                    <select name="petType" required>
                        <option value="Dog">Dog</option>
                        <option value="Cat">Cat</option>
                    </select>
                </div>
                <div class="form-field">
                    <label>Phone</label>
                    <input type="tel" name="phone" required />
                </div>
                <div class="form-field">
                    <label>Email</label>
                    <input type="email" name="email" />
                </div>
                <div class="form-field">
                    <label>Service</label>
                    <select name="service" required>
                        <option value="Drop Off">Drop Off</option>
                        <option value="Pet Pickup">Pet Pickup</option>
                    </select>
                </div>
                <div class="form-field">
                    <label>Check-in</label>
                    <input type="date" name="checkin" required />
                </div>
                <div class="form-field">
                    <label>Check-out</label>
                    <input type="date" name="checkout" required />
                </div>
                <div class="form-field" id="statusFieldWrapper">
                    <label>Status</label>
                    <select name="status" required>
                        <option value="pending">Pending</option>
                        <option value="confirmed">Confirmed</option>
                        <option value="checked-in">Checked In</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                        <option value="on-pickup">On Pickup</option>
                    </select>
                </div>
                <div class="form-field">
                    <label>Total Price (Rp)</label>
                    <input type="number" name="price" min="0" step="1000" value="0" required />
                </div>
                <div class="form-actions">
                    <button type="button" class="btn-delete" onclick="closeAddBookingModal()">Cancel</button>
                    <button type="submit" id="addBookingSubmitBtn" class="btn-add">Save</button>
                </div>
            </form>
        </div>
    </div>
    
</div>

<!-- Update Status Modal -->
<div id="statusModal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">Update Booking Status</h3>
            <span class="modal-close" onclick="closeStatusModal()">&times;</span>
        </div>
        <div class="modal-body">
            <form id="statusForm" class="form-grid">
                @csrf
                <input type="hidden" id="statusBookingId" value="">
                <div class="form-field">
                    <label>Booking Status</label>
                    <select id="statusSelect" required>
                        <option value="pending">Pending</option>
                        <option value="confirmed">Confirmed</option>
                        <option value="checked-in">Checked In</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                        <option value="on-pickup">On Pickup</option>
                    </select>
                </div>
                <div class="form-actions">
                    <button type="button" class="btn-delete" onclick="closeStatusModal()">Cancel</button>
                    <button type="submit" class="btn-add">Update Status</button>
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
      .dashboard-card:hover {
        transform: translateY(-2px);
    }

    .dashboard-sidebar a:hover {
        transform: translateX(2px);
    }

    @media (max-width: 1200px) {
        .dashboard-stats-row {
            grid-template-columns: repeat(2, 1fr);
        }

      

        .dashboard-sidebar {
            width: 100%;
            border-radius: 0;
            border-bottom-left-radius: 24px;
            border-bottom-right-radius: 24px;
        }
    }

    @media (max-width: 768px) {
        .dashboard-stats-row {
            grid-template-columns: 1fr;
        }

        .dashboard-main {
            padding: 20px 16px;
        }

        .dashboard-header {
            flex-direction: column;
            gap: 16px;
            text-align: center;
        }

        .dashboard-header h1 {
            font-size: 1.5rem;
        }
    }
/* Root Styles - Same as Testimonial */
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
    margin-left: 250px;
    /* prevent overlap with fixed sidebar (see layoutadmin.navbar width:250px) */
}

/* Header - Same as Testimonial */
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
    padding: 13px; /* mengikuti preferensi terbaru */
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

.summary-icon.total {
    background: linear-gradient(135deg, #F5E6D3 0%, #BBDEFB 100%);
    border-color: rgba(139, 115, 85, 0.15);
}

.summary-icon.active {
    background: linear-gradient(135deg, #E8F5E8 0%, #F5E6D3 100%);
    border-color: rgba(76, 175, 80, 0.15);
}

.summary-icon.pending {
    background: linear-gradient(135deg, #FFF8E1 0%, #FFECB3 100%);
    border-color: rgba(255, 193, 7, 0.15);
}

.summary-icon.revenue {
    background: linear-gradient(135deg, #E3F2FD 0%, #FFF8E1 100%);
    border-color: rgba(33, 150, 243, 0.15);
}

.summary-content {
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.summary-number {
    font-size: 2rem; /* samakan dengan halaman feedback */
    font-weight: 800;
    color: #6B4F3A;
    margin-bottom: 2px;
    line-height: 1.1;
    letter-spacing: -0.02em;
}

.summary-label {
    color: #A97B5D;
    font-size: 0.9rem;
    font-weight: 600;
    letter-spacing: 0.01em;
    line-height: 1.2;
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

/* Search Box - Same as Testimonial */
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

/* Table Styles - Same as Testimonial */
.table-container {
    overflow-x: auto;
    border-radius: 16px;
    overflow: hidden;
    margin-bottom: 24px;
}

.booking-table {
    width: 100%;
    min-width: 1000px;
    background: #fff;
    border-collapse: collapse;
}

.booking-table thead tr {
    background: linear-gradient(135deg, #FFE0B2, #F9D9A7);
}

.booking-table th {
    color: #6B4F3A;
    padding: 20px 16px;
    font-weight: 700;
    text-align: left;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-bottom: 2px solid rgba(169, 123, 93, 0.1);
}

.booking-table th:first-child {
    text-align: center;
    width: 80px;
}

.booking-table th:nth-child(4),
.booking-table th:nth-child(5),
.booking-table th:nth-child(6) {
    text-align: center;
}

.booking-table th:last-child {
    text-align: center;
    width: 180px;
}

.booking-table td {
    padding: 20px 16px;
    border-bottom: 1px solid rgba(240, 240, 240, 0.8);
    color: #6B4F3A;
    vertical-align: middle;
    font-weight: 500;
}

.booking-table tbody tr {
    transition: all 0.3s ease;
}

.booking-table tbody tr:hover {
    background: rgba(229, 115, 0, 0.02);
    transform: scale(1.002);
}

/* Table Cell Specific Styles */
.booking-table td:first-child {
    text-align: center;
    font-weight: 600;
    color: #E57300;
}

.booking-table td:nth-child(4),
.booking-table td:nth-child(5),
.booking-table td:nth-child(6) {
    text-align: center;
}

.booking-table td:last-child {
    text-align: center;
}

.customer-name {
    font-weight: 600;
    color: #6B4F3A;
    font-size: 0.95rem;
}

.pet-name {
    font-weight: 600;
    color: #E57300;
    font-size: 0.95rem;
}

.date-display {
    font-weight: 600;
    color: #6B4F3A;
    font-size: 0.9rem;
}

.duration-display {
    font-weight: 600;
    color: #6B4F3A;
    font-size: 0.9rem;
}

/* Status Badges */
.status-badge {
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    display: inline-block;
}

.status-pending {
    background: linear-gradient(135deg, #FFF3CD, #FFF8DC);
    color: #856404;
    border: 1px solid #FFEAA7;
}

.status-confirmed {
    background: linear-gradient(135deg, #D4EDDA, #C3E6CB);
    color: #155724;
    border: 1px solid #B8DAFF;
}

.status-checked-in {
    background: linear-gradient(135deg, #D1ECF1, #BEE5EB);
    color: #0C5460;
    border: 1px solid #BEE5EB;
}

.status-completed {
    background: linear-gradient(135deg, #E2E3E5, #F8F9FA);
    color: #383D41;
    border: 1px solid #DEE2E6;
}

/* On Pickup Status */
.status-on-pickup {
    background: linear-gradient(135deg, #FFE0B2, #F9D9A7);
    color: #6B4F3A;
    border: 1px solid #F5CBA7;
}

/* Cancelled Status (missing earlier) */
.status-cancelled {
    background: linear-gradient(135deg, #F8D7DA, #F5C6CB);
    color: #721C24;
    border: 1px solid #F5C6CB;
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
    padding: 12px 16px;
    font-size: 0.9rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
    text-transform: capitalize;
}

.btn-view {
    background: #17A2B8;
    color: #fff;
}

.btn-view:hover {
    background: #138496;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(23, 162, 184, 0.3);
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

.btn-status {
    background: #4CAF50;
    color: #fff;
}

.btn-status:hover {
    background: #43A047;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(76, 175, 80, 0.3);
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

/* Add Booking Button */
/* Add Booking Button (match Customer page style) */
.btn-add {
    background: #E57300;
    color: #fff;
    border-radius: 12px;
    padding: 12px 20px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-size: 0.9rem;
}



/* Make Add button size visually balanced with filter/select & search input */
.content-controls .btn-add {
    height: 44px;
}

/* Modal Styles */
.modal {
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.5);
    display: flex;
    justify-content: center;
    align-items: center;
}

.modal-content {
    background: #fff;
    border-radius: 16px;
    width: 90%;
    max-width: 600px;
    max-height: 80vh;
    overflow-y: auto;
    box-shadow: 0 20px 40px rgba(0,0,0,0.15);
    animation: slideUp 0.3s ease;
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.modal-header {
    background: linear-gradient(135deg, #FFE0B2, #F9D9A7);
    padding: 20px 32px;
    border-radius: 16px 16px 0 0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-title {
    font-size: 1.4rem;
    font-weight: 700;
    color: #6B4F3A;
    margin: 0;
}

.modal-close {
    font-size: 28px;
    color: #6B4F3A;
    cursor: pointer;
    font-weight: bold;
    line-height: 1;
}

.modal-close:hover {
    color: #E57300;
}

.modal-body {
    padding: 32px;
}

/* Add Booking Form */
.form-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 16px;
}

.form-field {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.form-field label {
    font-size: 0.85rem;
    color: #A97B5D;
    font-weight: 600;
}

.form-field input,
.form-field select {
    padding: 10px 12px;
    border-radius: 10px;
    border: 1px solid rgba(169, 123, 93, 0.25);
    background: #F7F5F2;
    color: #6B4F3A;
}

.form-field input:focus,
.form-field select:focus {
    outline: none;
    border-color: #E57300;
    box-shadow: 0 0 0 3px rgba(229, 115, 0, 0.1);
    background: #fff;
}

.form-actions {
    grid-column: 1 / -1;
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    margin-top: 8px;
}

.booking-detail-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
    margin-bottom: 24px;
}

.detail-item {
    padding: 16px;
    background: #F7F5F2;
    border-radius: 12px;
    border: 1px solid rgba(169, 123, 93, 0.1);
}

.detail-label {
    font-size: 0.85rem;
    color: #A97B5D;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 6px;
}

.detail-value {
    font-size: 1rem;
    color: #6B4F3A;
    font-weight: 600;
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

/* Pagination Wrapper */
.pagination-wrapper {
    display: flex !important;
    flex-direction: row; /* selalu horizontal */
    justify-content: center;
    align-items: center;
    margin: 24px 0;
    width: 100%;
    padding: 8px 0;
}

/* Pagination List */
.pagination {
    display: flex;
    align-items: center;
    gap: 6px;
    margin: 0;
    padding: 0;
    list-style: none;
}

/* Pagination Item */
.page-item {
    margin: 0;
    padding: 0;
    list-style: none;
}

/* Pagination Link (Button) */
.page-link {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    padding: 0;
    background: #fff;
    color: #6B4F3A;
    border: 1px solid #E6A15D;
    border-radius: 4px;
    font-weight: 600;
    font-size: 0.9rem;
    cursor: pointer;
    transition: all 0.2s ease;
    text-decoration: none;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    text-align: center;
}

/* Icon di dalam pagination */
.page-link i {
    font-size: 1rem;
}

/* Hover efek */
.page-link:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 6px rgba(229, 115, 0, 0.15);
}

/* Aktif */
.page-item.active .page-link {
    background: #E57300;
    color: #fff;
    border-color: #E57300;
    box-shadow: 0 2px 6px rgba(229, 115, 0, 0.25);
}

/* Disabled */
.page-item.disabled .page-link {
    opacity: 0.5;
    cursor: not-allowed;
    background: #f9f9f9;
    border-color: #ddd;
    box-shadow: none;
    pointer-events: none;
    transform: none !important;
}

/* Arrow Style */
.pagination-arrow {
    width: 16px;
    height: 16px;
    display: block;
    margin: 0 auto;
}

.page-item.disabled .pagination-arrow {
    filter: grayscale(100%);
    opacity: 0.5;
}

/* Dots separator */
.page-dots {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    color: #6B7280;
    user-select: none;
    font-size: 1.2rem;
    border: none;
    background: transparent;
    box-shadow: none;
    cursor: default;
}

/* Responsive */
@media (max-width: 768px) {
    .pagination {
        gap: 4px;
        flex-wrap: wrap; /* biar rapi kalau kepanjangan */
        justify-content: center;
    }

    .page-link {
        width: 32px;
        height: 32px;
        font-size: 0.8rem;
    }

    .pagination-arrow {
        width: 14px;
        height: 14px;
    }
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
    .dashboard-main { margin-left: 0; }

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

    .booking-table th,
    .booking-table td {
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

    .booking-detail-grid {
        grid-template-columns: 1fr;
    }

    .modal-content {
        width: 95%;
        margin: 20px;
    }

    .modal-body {
        padding: 20px;
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

    .booking-table th,
    .booking-table td {
        padding: 12px 8px;
        font-size: 0.8rem;
    }

    .action-btn {
        padding: 6px 10px;
        font-size: 0.75rem;
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
  document.addEventListener('DOMContentLoaded', function() {
    // Load bookings from backend API
    let bookings = [];

    const statusFilter = document.getElementById('statusFilter');
    const searchInput = document.getElementById('searchInput');
    const bookingTableBody = document.getElementById('bookingTableBody');
    const noBookingsMessage = document.getElementById('noBookingsMessage');
    const paginationContainer = document.getElementById('paginationContainer');
    const pageInfo = document.getElementById('pageInfo');
    const searchStatus = document.getElementById('searchStatus');
    const totalBookingsElement = document.getElementById('totalBookings');
    // Add Booking modal elements
    const addBookingModal = document.getElementById('addBookingModal');
    const addBookingForm = document.getElementById('addBookingForm');
    const addBookingModalTitle = document.getElementById('addBookingModalTitle');
    const addBookingSubmitBtn = document.getElementById('addBookingSubmitBtn');
    const editingIdInput = document.getElementById('editingId');

    // Status update modal elements
    const statusModal = document.getElementById('statusModal');
    const statusForm = document.getElementById('statusForm');
    const statusSelect = document.getElementById('statusSelect');
    const statusBookingIdInput = document.getElementById('statusBookingId');
    const statusFieldWrapper = document.getElementById('statusFieldWrapper');

    let currentPage = 1;
    const itemsPerPage = 10; // Changed from 8 to 10 items per page
    let filteredBookings = [...bookings];

    // Fetch bookings from backend
    fetch("{{ route('admin.bookings.index') }}")
      .then(res => res.json())
      .then(data => {
        // data is Laravel pagination object
        const records = data.data || [];
        bookings = records.map((b) => {
          const durationDays = Number(b.duration_days || 1);
          const checkoutDate = computeCheckoutDate(b.booking_date, durationDays);

          return {
            id: b.id,
            customer: b.member?.name || '—',
            pet: b.pet_name,
            petType: b.pet_type,
            // Map check-in to booking_date
            checkin: b.booking_date,
            // Compute checkout based on booking_date + duration_days
            checkout: checkoutDate,
            // Human readable duration
            duration: `${durationDays} hari`,
            status: b.status,
            phone: b.member?.phone || '—',
            email: b.member?.email || '—',
            // Use service_type as service label
            service: b.service_type,
            price: Number(b.total_price || 0)
          };
        });
        filteredBookings = [...bookings];
        renderBookings();
      })
      .catch(err => {
        console.error('Failed to load bookings', err);
        // Keep UI graceful even if API fails
        renderBookings();
      });

    // Open/Close Add Booking Modal
    window.openAddBookingModal = function() {
        addBookingModalTitle.textContent = 'Add Booking';
        addBookingSubmitBtn.textContent = 'Save';
        editingIdInput.value = '';
        if (addBookingForm) {
            addBookingForm.reset();
            if (addBookingForm.status) {
                addBookingForm.status.value = 'pending';
            }
        }
        if (statusFieldWrapper) {
            statusFieldWrapper.style.display = '';
        }
        addBookingModal.style.display = 'flex';
    };

    window.closeAddBookingModal = function() {
        addBookingModal.style.display = 'none';
    };

    // Handle Add Booking Submit
    if (addBookingForm) {
        addBookingForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(addBookingForm);
            const payload = {
                customer: (formData.get('customer') || '').toString().trim(),
                pet: (formData.get('pet') || '').toString().trim(),
                petType: formData.get('petType'),
                phone: (formData.get('phone') || '').toString().trim(),
                email: (formData.get('email') || '').toString().trim() || null,
                service: formData.get('service') || null,
                checkin: formData.get('checkin'),
                checkout: formData.get('checkout'),
                status: formData.get('status'),
                price: Number(formData.get('price') || 0)
            };

            // CSRF token from meta or hidden input
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') 
                || addBookingForm.querySelector('input[name="_token"]')?.value;

            const isEditing = !!editingIdInput.value;
            const url = isEditing ? `{{ url('/admin/bookings') }}/${editingIdInput.value}` : "{{ route('admin.bookings.store') }}";
            const method = isEditing ? 'PUT' : 'POST';
            fetch(url, {
                method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken || ''
                },
                body: JSON.stringify(payload)
            })
            .then(async (res) => {
                if (!res.ok) {
                    const data = await res.json().catch(() => ({}));
                    const msg = data.message || 'Failed to create booking';
                    throw new Error(msg);
                }
                return res.json();
            })
            .then(({ booking }) => {
                const durationDays = Number(booking.duration_days || 1);
                const checkoutDate = computeCheckoutDate(booking.booking_date, durationDays);

                const mapped = {
                    id: booking.id,
                    customer: booking.member?.name || payload.customer,
                    pet: booking.pet_name,
                    petType: booking.pet_type,
                    checkin: booking.booking_date,
                    checkout: checkoutDate,
                    duration: `${durationDays} hari`,
                    status: booking.status,
                    phone: booking.member?.phone || payload.phone,
                    email: booking.member?.email || payload.email || '—',
                    service: booking.service_type,
                    price: Number(booking.total_price || payload.price)
                };
                if (isEditing) {
                    const idx = bookings.findIndex(b => b.id === mapped.id);
                    if (idx > -1) bookings[idx] = mapped;
                } else {
                    bookings.unshift(mapped);
                }
                filterBookings();
                addBookingForm.reset();
                closeAddBookingModal();
            })
            .catch((err) => {
                alert(err.message || 'Failed to create booking');
            });
        });
    }

    // Handle Status Update submit
    if (statusForm) {
        statusForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const bookingId = statusBookingIdInput ? statusBookingIdInput.value : '';
            const newStatus = statusSelect ? statusSelect.value : '';
            if (!bookingId || !newStatus) {
                return;
            }

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') 
                || statusForm.querySelector('input[name="_token"]')?.value;

            fetch(`{{ url('/admin/booking') }}/${bookingId}/status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken || '',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ status: newStatus })
            })
            .then(async (res) => {
                if (!res.ok) {
                    const data = await res.json().catch(() => ({}));
                    const msg = data.message || 'Failed to update status';
                    throw new Error(msg);
                }
                return res.json().catch(() => ({}));
            })
            .then(() => {
                const booking = bookings.find(b => b.id === Number(bookingId));
                if (booking) {
                    booking.status = newStatus;
                }
                filterBookings();
                window.closeStatusModal && window.closeStatusModal();
            })
            .catch((err) => {
                alert(err.message || 'Failed to update status');
            });
        });
    }

    // Function to change page
    function changePage(page) {
        const totalPages = Math.ceil(filteredBookings.length / itemsPerPage);
        if (page >= 1 && page <= totalPages) {
            currentPage = page;
            renderBookings();
            // Smooth scroll to top of table
            document.querySelector('.content-card')?.scrollIntoView({ 
                behavior: 'smooth', 
                block: 'start' 
            });
        }
    }

    // Function to render pagination
    function renderPagination() {
        const totalPages = Math.ceil(filteredBookings.length / itemsPerPage);
        const paginationContainer = document.getElementById('paginationContainer');
        
        if (totalPages <= 1) {
            paginationContainer.innerHTML = '';
            return;
        }

        let paginationHTML = '';

        // Previous button
        paginationHTML += `
            <button class="pagination-btn" onclick="changePage(${currentPage - 1})" ${currentPage === 1 ? 'disabled' : ''} title="Previous">
                <img class="nav-img" src="{{ asset('images/kiri.svg') }}" alt="Previous" style="width: 16px; height: 16px;">
            </button>
        `;

        // First page
        if (totalPages > 1) {
            paginationHTML += `
                <button class="pagination-btn ${currentPage === 1 ? 'active' : ''}" onclick="changePage(1)">1</button>
            `;
        }

        // Left dots
        if (currentPage > 3) {
            paginationHTML += '<span class="pagination-dots">...</span>';
        }

        // Pages around current
        for (let i = Math.max(2, currentPage - 1); i <= Math.min(totalPages - 1, currentPage + 1); i++) {
            if (i > 1 && i < totalPages) {
                paginationHTML += `
                    <button class="pagination-btn ${i === currentPage ? 'active' : ''}" onclick="changePage(${i})">${i}</button>
                `;
            }
        }

        // Right dots
        if (currentPage < totalPages - 2) {
            paginationHTML += '<span class="pagination-dots">...</span>';
        }

        // Last page
        if (totalPages > 1) {
            paginationHTML += `
                <button class="pagination-btn ${currentPage === totalPages ? 'active' : ''}" onclick="changePage(${totalPages})">${totalPages}</button>
            `;
        }

        // Next button
        paginationHTML += `
            <button class="pagination-btn" onclick="changePage(${currentPage + 1})" ${currentPage === totalPages ? 'disabled' : ''} title="Next">
                <img class="nav-img" src="{{ asset('images/kanan.svg') }}" alt="Next" style="width: 16px; height: 16px;">
            </button>
        `;

        paginationContainer.innerHTML = paginationHTML;

        // Center the current page in the scrollable area
        const currentBtn = paginationContainer.querySelector('.pagination-btn.active');
        if (currentBtn) {
            currentBtn.scrollIntoView({
                behavior: 'smooth',
                block: 'nearest',
                inline: 'center'
            });
        }
    }

    // Function to render bookings
    function renderBookings() {
        const startIndex = (currentPage - 1) * itemsPerPage;
        const endIndex = startIndex + itemsPerPage;
        const currentBookings = filteredBookings.slice(startIndex, endIndex);
        
        // Render pagination
        renderPagination();

        if (currentBookings.length === 0) {
            bookingTableBody.innerHTML = '';
            noBookingsMessage.style.display = 'block';
            document.querySelector('.pagination-wrapper').style.display = 'none';
        } else {
            noBookingsMessage.style.display = 'none';
            document.querySelector('.pagination-wrapper').style.display = 'flex';

            bookingTableBody.innerHTML = currentBookings.map((booking, index) => {
                const globalIndex = startIndex + index + 1;
                const statusBadge = getStatusBadge(booking.status);

                return `
                    <tr data-status="${normalizeStatus(booking.status)}">
                        <td>${globalIndex}</td>
                        <td>
                            <div class="customer-name">${booking.customer}</div>
                        </td>
                        <td>
                            <div class="pet-name">${booking.pet}</div>
                        </td>
                        <td>
                            <div class="date-display">${formatDate(booking.checkin)}</div>
                        </td>
                        <td>
                            <div class="duration-display">${booking.duration}</div>
                        </td>
                        <td>
                            ${statusBadge}
                        </td>
                        <td>
                            <div class="action-buttons">
                                <button class="action-btn btn-view" onclick="viewBooking(${booking.id})">
                                    <i class="bi bi-eye"></i> View
                                </button>
                                <button class="action-btn btn-edit" onclick="editBooking(${booking.id})">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </button>
                                <button class="action-btn btn-status" onclick="openStatusModal(${booking.id})">
                                    <i class="bi bi-arrow-repeat"></i> Update Status
                                </button>
                                <button class="action-btn btn-delete" onclick="deleteBooking(${booking.id})">
                                    <i class="bi bi-trash"></i> Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        // Update total bookings count
        totalBookingsElement.textContent = filteredBookings.length;

        // Render pagination
        renderPagination();

        // Render page info
        renderPageInfo();

        // Render search status
        renderSearchStatus();

        // Update statistics
        updateStatistics();
    }

    // Function to format date for display
    function formatDate(dateString) {
        if (!dateString) return '-';
        const date = new Date(dateString);
        if (Number.isNaN(date.getTime())) return '-';
        return date.toLocaleDateString('id-ID', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric'
        });
    }

    // Compute checkout date (YYYY-MM-DD) from booking_date and duration_days
    function computeCheckoutDate(bookingDate, durationDays) {
        if (!bookingDate) return '';
        const parts = String(bookingDate).split('-');
        if (parts.length !== 3) return '';
        const year = parseInt(parts[0], 10);
        const month = parseInt(parts[1], 10) - 1;
        const day = parseInt(parts[2], 10);
        const baseDate = new Date(year, month, day);
        if (Number.isNaN(baseDate.getTime())) return '';
        const days = Number(durationDays || 1) - 1;
        baseDate.setDate(baseDate.getDate() + (days > 0 ? days : 0));
        const yyyy = baseDate.getFullYear();
        const mm = String(baseDate.getMonth() + 1).padStart(2, '0');
        const dd = String(baseDate.getDate()).padStart(2, '0');
        return `${yyyy}-${mm}-${dd}`;
    }

    // Normalize status to a consistent key
    function normalizeStatus(status) {
        if (!status) return '';
        return String(status)
            .toLowerCase()
            .trim()
            .replace(/_/g, '-')
            .replace(/\s+/g, '-')
            .replace(/onpickup/g, 'on-pickup')
            .replace(/checkedin/g, 'checked-in');
    }

    // Function to get status badge
    function getStatusBadge(status) {
        const key = normalizeStatus(status);
        const statusMap = {
            'pending': { class: 'status-pending', text: 'Pending' },
            'confirmed': { class: 'status-confirmed', text: 'Confirmed' },
            'checked-in': { class: 'status-checked-in', text: 'Checked In' },
            'completed': { class: 'status-completed', text: 'Completed' },
            'cancelled': { class: 'status-cancelled', text: 'Cancelled' },
            'on-pickup': { class: 'status-on-pickup', text: 'On Pickup' },
        };

        const statusInfo = statusMap[key] || { class: 'status-pending', text: 'Unknown' };
        return `<span class="status-badge ${statusInfo.class}">${statusInfo.text}</span>`;
    }

    // Function to render page info
    function renderPageInfo() {
        const totalPages = Math.ceil(filteredBookings.length / itemsPerPage);
        const startIndex = filteredBookings.length > 0 ? (currentPage - 1) * itemsPerPage + 1 : 0;
        const endIndex = Math.min(currentPage * itemsPerPage, filteredBookings.length);
        const pageInfo = document.getElementById('pageInfo');
        
        if (!pageInfo) {
            console.error('Page info element not found');
            return;
        }

        if (filteredBookings.length === 0) {
            pageInfo.innerHTML = 'No bookings to display';
        } else {
            pageInfo.innerHTML = `
                <div style="text-align: center; margin-top: 15px; color: #6B4F3A; font-size: 14px;">
                    <div>Showing ${startIndex}-${endIndex} of ${filteredBookings.length} customers (Page ${currentPage} of ${totalPages})</div>
                    ${filteredBookings.length <= itemsPerPage ? '<div>Showing all customers</div>' : ''}
                </div>
            `;
        }
        
        // Make sure the element is visible
        pageInfo.style.display = 'block';
    }

    // Function to render search status
    function renderSearchStatus() {
        if (!searchStatus) {
            return;
        }

        const searchTerm = searchInput.value.trim();
        const selectedStatus = statusFilter.value;

        const statusLabelMap = {
            'pending': 'Pending',
            'confirmed': 'Confirmed',
            'checked-in': 'Checked In',
            'completed': 'Completed',
            'cancelled': 'Cancelled',
            'on-pickup': 'On Pickup',
        };

        let displayStatus = '';
        if (selectedStatus) {
            const key = normalizeStatus(selectedStatus);
            displayStatus = statusLabelMap[key] || selectedStatus;
        }

        if (searchTerm || selectedStatus) {
            const filters = [];
            if (searchTerm) filters.push(`"${searchTerm}"`);
            if (selectedStatus) filters.push(`${displayStatus} status`);
            searchStatus.innerHTML = `Applied filters: ${filters.join(', ')}`;
            searchStatus.style.display = 'inline-block';
        } else {
            searchStatus.innerHTML = 'Showing all customers';
            searchStatus.style.display = 'inline-block';
        }
    }

    // Function to render pagination
    function renderPagination() {
        const totalPages = Math.ceil(filteredBookings.length / itemsPerPage);

        if (totalPages <= 1) {
            paginationContainer.innerHTML = '';
            return;
        }

        let paginationHTML = '';

        // Previous button
        paginationHTML += `
            <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                <a class="page-link" href="#" onclick="changePage(${currentPage - 1}); return false;">
                    <img src="{{ asset('images/kiri.svg') }}" alt="Previous" class="pagination-arrow">
                </a>
            </li>
        `;

        // First page
        if (totalPages > 1) {
            paginationHTML += `
                <li class="page-item ${currentPage === 1 ? 'active' : ''}">
                    <a class="page-link" href="#" onclick="changePage(1); return false;">1</a>
                </li>
            `;
        }

        // Left dots
        if (currentPage > 3) {
            paginationHTML += '<li class="page-item disabled"><span class="page-link">...</span></li>';
        }

        // Pages around current
        for (let i = Math.max(2, currentPage - 1); i <= Math.min(totalPages - 1, currentPage + 1); i++) {
            if (i > 1 && i < totalPages) {
                paginationHTML += `
                    <li class="page-item ${i === currentPage ? 'active' : ''}">
                        <a class="page-link" href="#" onclick="changePage(${i}); return false;">${i}</a>
                    </li>
                `;
            }
        }

        // Right dots
        if (currentPage < totalPages - 2) {
            paginationHTML += '<li class="page-item disabled"><span class="page-link">...</span></li>';
        }

        // Last page
        if (totalPages > 1) {
            paginationHTML += `
                <li class="page-item ${currentPage === totalPages ? 'active' : ''}">
                    <a class="page-link" href="#" onclick="changePage(${totalPages}); return false;">${totalPages}</a>
                </li>
            `;
        }

        // Next button
        paginationHTML += `
            <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
                <a class="page-link" href="#" onclick="changePage(${currentPage + 1}); return false;">
                    <img src="{{ asset('images/kanan.svg') }}" alt="Next" class="pagination-arrow">
                </a>
            </li>
        `;

        paginationContainer.innerHTML = paginationHTML;
    }

    // Function to change page
    window.changePage = function(page) {
        const totalPages = Math.ceil(filteredBookings.length / itemsPerPage);
        if (page >= 1 && page <= totalPages) {
            currentPage = page;
            renderBookings();
            // Smooth scroll to top of table
            document.querySelector('.content-card').scrollIntoView({ 
                behavior: 'smooth', 
                block: 'start' 
            });
        }
    };

    // Function to filter bookings
    function filterBookings() {
        const searchTerm = searchInput.value.toLowerCase().trim();
        const selectedStatus = statusFilter.value;

        filteredBookings = bookings.filter(booking => {
            const matchesSearch = !searchTerm || 
                booking.customer.toLowerCase().includes(searchTerm) ||
                booking.pet.toLowerCase().includes(searchTerm);
            const matchesStatus = !selectedStatus || normalizeStatus(booking.status) === normalizeStatus(selectedStatus);

            return matchesSearch && matchesStatus;
        });

        currentPage = 1; // Reset to first page when filtering
        renderBookings();
    }

    // Function to update statistics
    function updateStatistics() {
        // Total bookings
        const totalBookings = filteredBookings.length;
        
        // Pets currently boarded (checked-in status)
        const activePets = filteredBookings.filter(b => b.status === 'checked-in').length;
        
        // Pending bookings
        const pendingBookings = filteredBookings.filter(b => b.status === 'pending').length;
        
        // Calculate monthly revenue (all bookings regardless of status for realistic revenue)
        const monthlyRevenue = bookings.reduce((sum, booking) => sum + booking.price, 0);
        const revenueFormatted = (monthlyRevenue / 1000000).toFixed(1) + 'M';

        // Update DOM elements with proper IDs
        document.getElementById('totalBookings').textContent = totalBookings;
        document.getElementById('activePets').textContent = activePets;
        document.getElementById('pendingBookings').textContent = pendingBookings;
        document.getElementById('monthlyRevenue').textContent = `Rp ${revenueFormatted}`;
    }

    // Action functions
    window.viewBooking = function(id) {
        const booking = bookings.find(b => b.id === id);
        if (booking) {
            showBookingModal(booking);
        }
    };

    window.openStatusModal = function(id) {
        const booking = bookings.find(b => b.id === id);
        if (!booking || !statusModal || !statusSelect || !statusBookingIdInput) {
            return;
        }
        statusBookingIdInput.value = String(id);
        statusSelect.value = normalizeStatus(booking.status) || 'pending';
        statusModal.style.display = 'flex';
    };

    window.closeStatusModal = function() {
        if (statusModal) {
            statusModal.style.display = 'none';
        }
    };

    window.editBooking = function(id) {
        // Fetch latest data from backend
        fetch(`{{ url('/admin/bookings') }}/${id}`)
            .then(res => res.json())
            .then(data => {
                // Populate form
                addBookingModalTitle.textContent = 'Edit Booking';
                addBookingSubmitBtn.textContent = 'Update';
                editingIdInput.value = String(id);

                addBookingForm.customer.value = data.member?.name || '';
                addBookingForm.pet.value = data.pet_name || '';
                addBookingForm.petType.value = (data.pet_type || '').charAt(0).toUpperCase() + (data.pet_type || '').slice(1);
                addBookingForm.phone.value = data.member?.phone || '';
                if (addBookingForm.email) {
                    addBookingForm.email.value = data.member?.email || '';
                }

                // Try to recover service from notes (e.g. "Service: Drop Off")
                let serviceFromNotes = '';
                if (data.notes) {
                    const match = String(data.notes).match(/Service:\s*(.+)/i);
                    if (match) {
                        serviceFromNotes = match[1].trim();
                    }
                }
                if (addBookingForm.service) {
                    addBookingForm.service.value = serviceFromNotes || addBookingForm.service.value || 'Drop Off';
                }

                // Set check-in date
                addBookingForm.checkin.value = data.booking_date || '';

                // Hitung dan set check-out date berdasarkan booking_date + duration_days
                const durationDays = Number(data.duration_days || 1);
                const checkoutDate = computeCheckoutDate(data.booking_date, durationDays);
                if (addBookingForm.checkout) {
                    addBookingForm.checkout.value = checkoutDate || data.booking_date || '';
                }
                if (addBookingForm.status) {
                    addBookingForm.status.value = data.status || 'pending';
                }
                addBookingForm.price.value = data.total_price || 0;

                if (statusFieldWrapper) {
                    statusFieldWrapper.style.display = 'none';
                }

                addBookingModal.style.display = 'flex';
            })
            .catch(err => alert('Failed to load booking data'));
    };

    window.deleteBooking = function(id) {
        const booking = bookings.find(b => b.id === id);
        if (!booking) return;
        if (!confirm(`Are you sure you want to delete the booking for ${booking.customer} - ${booking.pet}?`)) return;

        // CSRF token from meta or hidden input
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') 
            || document.querySelector('#addBookingForm input[name="_token"]')?.value;

        fetch(`{{ url('/admin/booking') }}/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken || ''
            }
        })
        .then(async (res) => {
            if (!res.ok) {
                const data = await res.json().catch(() => ({}));
                const msg = data.message || 'Failed to delete booking';
                throw new Error(msg);
            }
            return res.text();
        })
        .then(() => {
            const index = bookings.findIndex(b => b.id === id);
            if (index > -1) {
                bookings.splice(index, 1);
                filterBookings();
            }
        })
        .catch((err) => alert(err.message || 'Failed to delete booking'));
    };

    // Function to show booking modal
    function showBookingModal(booking) {
        const modal = document.getElementById('bookingModal');
        const modalBody = document.getElementById('modalBody');
        
        modalBody.innerHTML = `
            <div class="booking-detail-grid">
                <div class="detail-item">
                    <div class="detail-label">Customer Name</div>
                    <div class="detail-value">${booking.customer}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Pet Name</div>
                    <div class="detail-value">${booking.pet}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Pet Type</div>
                    <div class="detail-value">${booking.petType}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Phone Number</div>
                    <div class="detail-value">${booking.phone}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Email</div>
                    <div class="detail-value">${booking.email}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Service Method</div>
                    <div class="detail-value">${booking.service}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Check-in Date</div>
                    <div class="detail-value">${formatDate(booking.checkin)}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Check-out Date</div>
                    <div class="detail-value">${formatDate(booking.checkout)}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Duration</div>
                    <div class="detail-value">${booking.duration}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Status</div>
                    <div class="detail-value">${getStatusBadge(booking.status)}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Total Price</div>
                    <div class="detail-value">${new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 0
                    }).format(booking.price).replace('IDR', 'Rp')}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Booking ID</div>
                    <div class="detail-value">#BK${String(booking.id).padStart(4, '0')}</div>
                </div>
            </div>
        `;
        
        modal.style.display = 'flex';
    }

    // Function to close modal
    window.closeModal = function() {
        const modal = document.getElementById('bookingModal');
        modal.style.display = 'none';
    };

    // Close booking modal when clicking outside (without overriding other handlers)
    document.addEventListener('click', function(event) {
        const modal = document.getElementById('bookingModal');
        if (modal && event.target === modal) {
            modal.style.display = 'none';
        }
    });

    // Event listeners
    statusFilter.addEventListener('change', filterBookings);
    searchInput.addEventListener('input', debounce(filterBookings, 300));

    // Debounce function for search input
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                func(...args);
                renderBookings();
                renderPageInfo();
                // Smooth scroll to top of table
                document.querySelector('.content-card')?.scrollIntoView({ 
                    behavior: 'smooth', 
                    block: 'start' 
                });
            }, wait);
        };
    }

    // Initialize
    filterBookings();
    renderBookings();
    renderPageInfo();

    // Add some visual feedback for loading
    setTimeout(() => {
        document.querySelectorAll('.summary-card, .content-card').forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            setTimeout(() => {
                card.style.transition = 'all 0.6s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 100);
        });
    }, 100);
});
</script>

</body>
</html>