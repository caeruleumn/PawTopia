@extends('layouts.app')
@include('layoutadmin.navbar')
<meta name="csrf-token" content="{{ csrf_token() }}">

<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: #EAE6E1;
        margin: 0;
        color: #6B4F3A;
    }
    .dashboard-root {
        display: flex;
        min-height: 100vh;
        background: #EAE6E1;
    }
    .dashboard-main {
        flex: 1;
        padding: 40px 32px 32px 32px;
        max-width: 100%;
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
        border: 1px solid rgba(255, 255, 255, 0.2);
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


    .btn-add-product {
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
    .btn-add-product:hover {
        background: #D16500;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(229, 115, 0, 0.3);
    }
    .category-card {
        background: linear-gradient(135deg, #fff 0%, #fefefe 100%);
        border-radius: 24px;
        padding: 32px;
        box-shadow: 0 12px 40px rgba(230, 161, 93, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.5);
        margin-bottom: 32px;
        animation: fadeInUp 0.6s ease forwards;
    }
    .category-title {
        font-weight:800;
        font-size:1.3rem;
        color:#6B4F3A;
        margin-bottom:24px;
        letter-spacing:-0.01em;
    }
    /* Table Styles */
    .table-container {
        overflow-x: auto;
        border-radius: 16px;
        overflow: hidden;
        margin-bottom: 24px;
    }
    .product-table {
        width: 100%;
        min-width: 800px;
        background: #fff;
        border-collapse: collapse;
    }
    .product-table thead tr {
        background: linear-gradient(135deg, #FFE0B2, #F9D9A7);
    }
    .product-table th {
        color: #6B4F3A;
        padding: 20px 16px;
        font-weight: 700;
        text-align: left;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid rgba(169, 123, 93, 0.1);
    }
    .product-table td {
        padding: 20px 16px;
        border-bottom: 1px solid rgba(240, 240, 240, 0.8);
        color: #6B4F3A;
        vertical-align: middle;
        font-weight: 500;
    }
    .product-table td img {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        object-fit: cover;
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
    .btn-see-more {
        background: #E57300;
        color:#fff;
        border:none;
        border-radius:12px;
        padding:10px 20px;
        font-weight:600;
        cursor:pointer;
        transition:all 0.3s ease;
        display:inline-block;
        margin-top: 10px;
        margin-left: auto;
        margin-right: 0;
        font-size:0.9rem;
    }
    .btn-see-more:hover {
        background: #D16500;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(229, 115, 0, 0.2);
    }
    /* Actions toolbar (separate from header) */
    .actions-toolbar {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 12px;
        margin: -12px 0 20px; /* slightly tuck closer to header */
    }
    /* Modal Style */
    .modal-overlay {
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(107, 79, 58, 0.7);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
    }
    .modal-overlay.active {
        opacity: 1;
        visibility: visible;
    }
    .modal-container {
        background: linear-gradient(135deg, #fff 0%, #fefefe 100%);
        border-radius: 24px;
        width: 90%;
        max-width: 600px;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 20px 60px rgba(107, 79, 58, 0.3);
        border: 1px solid rgba(255, 255, 255, 0.5);
        transform: translateY(30px);
        opacity: 0;
        transition: all 0.4s ease;
        padding: 50px;
    }
    .modal-overlay.active .modal-container {
        transform: translateY(0);
        opacity: 1;
    }
    .modal-header {
        padding: 24px 32px 16px;
        border-bottom: 1px solid rgba(169, 123, 93, 0.2);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .modal-title {
        font-weight: 800;
        font-size: 1.5rem;
        color: #6B4F3A;
        margin: 0;
    }
    .modal-close {
        background: none;
        border: none;
        font-size: 1.5rem;
        color: #A97B5D;
        cursor: pointer;
        width: 40px;
        height: 40px;
        border-radius: 50%;
    }
    .modal-close:hover {
        background: rgba(169, 123, 93, 0.1);
        color: #6B4F3A;
        transform: rotate(90deg);
    }
    .modal-body {
        padding: 24px 32px;
    }
    .form-group {
        margin-bottom: 20px;
    }
    .form-label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #6B4F3A;
        font-size: 0.9rem;
    }
    .form-input, .form-select {
        width: 100%;
        background: #F7F5F2;
        border: 1px solid rgba(169, 123, 93, 0.2);
        border-radius: 12px;
        padding: 12px 16px;
        font-size: 0.9rem;
        color: #6B4F3A;
        transition: all 0.3s ease;
        font-weight: 500;
    }
    .form-input:focus, .form-select:focus {
        outline: none;
        border-color: #E57300;
        box-shadow: 0 0 0 3px rgba(229, 115, 0, 0.1);
        background: #fff;
    }
    .modal-footer {
        padding: 16px 32px 24px;
        border-top: 1px solid rgba(169, 123, 93, 0.2);
        display: flex;
        justify-content: flex-end;
        gap: 12px;
    }
    .modal-btn {
        border: none;
        border-radius: 12px;
        padding: 12px 24px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 0.9rem;
    }
    .btn-cancel {
        background: #F7F5F2;
        color: #6B4F3A;
    }
    .btn-cancel:hover {
        background: #e8e5e0;
        transform: translateY(-2px);
    }
    .btn-save {
        background: #E57300;
        color: #fff;
    }
    .btn-save:hover {
        background: #D16500;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(229, 115, 0, 0.3);
    }
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(30px);}
        to { opacity: 1; transform: translateY(0);}
    }
    /* Responsive */
    @media (max-width: 1200px) {
        .dashboard-main { padding: 20px 16px; }
        .category-card { padding: 20px; }
        .product-table th, .product-table td { padding: 16px 12px; }
    }
    @media (max-width: 768px) {
        .dashboard-main { padding: 12px 6px;}
        .dashboard-header { flex-direction: column; gap: 16px; text-align: center; padding: 20px;}
        .category-card { padding: 16px 10px; border-radius: 16px;}
        .product-table th, .product-table td { padding: 12px 8px; font-size:0.85rem;}
    }
</style>


<div class="dashboard-root">
    <main class="dashboard-main">
        <!-- Header -->
        <x-dashboard-header 
            title="Product Management"
            subtitle="Manage pet shop products by category"
            icon="box"
        />

        <!-- Toolbar below header -->
        <div class="actions-toolbar">
            <button class="btn-add-product" onclick="openAddProductModal()">
                <i class="bi bi-plus-circle"></i> Add Product
            </button>
        </div>

        <!-- Pet Food Section -->
        <div class="category-card">
            <div class="category-title">Pet Food</div>
            <div class="table-container">
                <table class="product-table">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th style="text-align:center;">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="petFoodTbody">
                        @isset($petFood)
                            @forelse($petFood as $p)
                                <tr data-id="{{ $p->id }}">
                                    <td>
                                        @php($img = $p->image_path ? asset('storage/'.$p->image_path) : asset('images/whiskas.svg'))
                                        <img src="{{ $img }}" alt="{{ $p->name }}">
                                    </td>
                                    <td>{{ $p->name }}</td>
                                    <td>{{ $p->category }}</td>
                                    <td>Rp. {{ number_format((int) $p->price, 0, ',', '.') }}</td>
                                    <td>{{ (int) $p->stock }}</td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="action-btn btn-edit" data-action="edit"><i class="bi bi-pencil"></i> Edit</button>
                                            <button class="action-btn btn-delete" data-action="delete"><i class="bi bi-trash"></i> Delete</button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" style="text-align:center;color:#888;">No Pet Food products yet.</td></tr>
                            @endforelse
                        @endisset
                    </tbody>
                </table>
            </div>
            <a href="{{ route('admin.petfood') }}" class="btn-see-more">See More</a>
        </div>

        <!-- Supplies Section -->
        <div class="category-card">
            <div class="category-title">Pet Supplies</div>
            <div class="table-container">
                <table class="product-table">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th style="text-align:center;">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="suppliesTbody">
                        @isset($supplies)
                            @forelse($supplies as $p)
                                <tr data-id="{{ $p->id }}">
                                    <td>
                                        @php($img = $p->image_path ? asset('storage/'.$p->image_path) : asset('images/whiskas.svg'))
                                        <img src="{{ $img }}" alt="{{ $p->name }}">
                                    </td>
                                    <td>{{ $p->name }}</td>
                                    <td>{{ $p->category }}</td>
                                    <td>Rp. {{ number_format((int) $p->price, 0, ',', '.') }}</td>
                                    <td>{{ (int) $p->stock }}</td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="action-btn btn-edit" data-action="edit"><i class="bi bi-pencil"></i> Edit</button>
                                            <button class="action-btn btn-delete" data-action="delete"><i class="bi bi-trash"></i> Delete</button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" style="text-align:center;color:#888;">No Supplies products yet.</td></tr>
                            @endforelse
                        @endisset
                    </tbody>
                </table>
            </div>
            <a href="{{ route('admin.petsupplies') }}" class="btn-see-more">See More</a>
        </div>

        <!-- Vitamins Section -->
        <div class="category-card">
            <div class="category-title">Pet Vitamins</div>
            <div class="table-container">
                <table class="product-table">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th style="text-align:center;">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="vitaminsTbody">
                        @isset($vitamins)
                            @forelse($vitamins as $p)
                                <tr data-id="{{ $p->id }}">
                                    <td>
                                        @php($img = $p->image_path ? asset('storage/'.$p->image_path) : asset('images/whiskas.svg'))
                                        <img src="{{ $img }}" alt="{{ $p->name }}">
                                    </td>
                                    <td>{{ $p->name }}</td>
                                    <td>{{ $p->category }}</td>
                                    <td>Rp. {{ number_format((int) $p->price, 0, ',', '.') }}</td>
                                    <td>{{ (int) $p->stock }}</td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="action-btn btn-edit" data-action="edit"><i class="bi bi-pencil"></i> Edit</button>
                                            <button class="action-btn btn-delete" data-action="delete"><i class="bi bi-trash"></i> Delete</button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" style="text-align:center;color:#888;">No Vitamin products yet.</td></tr>
                            @endforelse
                        @endisset
                    </tbody>
                </table>
            </div>
            <a href="{{ route('admin.petvitamins') }}" class="btn-see-more">See More</a>
        </div>
    </main>
</div>

<!-- Modal Add Product -->
<div class="modal-overlay" id="addProductModal">
    <div class="modal-container">
        <div class="modal-header">
            <h2 class="modal-title">Add Product</h2>
            <button class="modal-close" onclick="closeAddModal()">
                <i class="bi bi-x"></i>
            </button>
        </div>
        <div class="modal-body">
            <form id="addProductForm">
                <div class="form-group">
                    <label for="addProductName" class="form-label">Product Name</label>
                    <input type="text" id="addProductName" class="form-input" placeholder="Product name" required>
                </div>
                <div class="form-group">
                    <label for="addProductSku" class="form-label">SKU</label>
                    <input type="text" id="addProductSku" class="form-input" placeholder="Unique SKU" required>
                </div>
                <div class="form-group">
                    <label for="addProductCategory" class="form-label">Category</label>
                    <select id="addProductCategory" class="form-select" required>
                        <option value="">Select a category</option>
                        <option value="Cat Food">Cat Food</option>
                        <option value="Dog Food">Dog Food</option>
                        <option value="Supplies">Supplies</option>
                        <option value="Vitamin">Vitamin</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="addProductPrice" class="form-label">Price</label>
                    <input type="text" id="addProductPrice" class="form-input" placeholder="Rp. 0" required>
                </div>
                <div class="form-group">
                    <label for="addProductStock" class="form-label">Stock</label>
                    <input type="number" id="addProductStock" class="form-input" placeholder="Stock" min="0" required>
                </div>
                <div class="form-group">
                    <label for="addProductStatus" class="form-label">Status</label>
                    <select id="addProductStatus" class="form-select" required>
                        <option value="available">Available</option>
                        <option value="coming-soon">Coming Soon</option>
                        <option value="preorder">Pre-order</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="addProductDescription" class="form-label">Description</label>
                    <textarea id="addProductDescription" class="form-input" rows="3" placeholder="Description (optional)"></textarea>
                </div>
                <div class="form-group">
                    <label for="addProductImage" class="form-label">Image</label>
                    <input type="file" id="addProductImage" class="form-input" accept="image/*">
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button class="modal-btn btn-cancel" onclick="closeAddModal()">Cancel</button>
            <button class="modal-btn btn-save" onclick="submitAddProduct()">Add Product</button>
        </div>
    </div>
</div>

<!-- Modal Edit Product -->
<div class="modal-overlay" id="editProductModal">
    <div class="modal-container">
        <div class="modal-header">
            <h2 class="modal-title">Edit Product</h2>
            <button class="modal-close" onclick="closeEditModal()">
                <i class="bi bi-x"></i>
            </button>
        </div>
        <div class="modal-body">
            <form id="editProductForm">
                <input type="hidden" id="editProductId">
                <div class="form-group">
                    <label for="editProductName" class="form-label">Product Name</label>
                    <input type="text" id="editProductName" class="form-input" required>
                </div>
                <div class="form-group">
                    <label for="editProductSku" class="form-label">SKU</label>
                    <input type="text" id="editProductSku" class="form-input" required>
                </div>
                <div class="form-group">
                    <label for="editProductCategory" class="form-label">Category</label>
                    <select id="editProductCategory" class="form-select" required>
                        <option value="Cat Food">Cat Food</option>
                        <option value="Dog Food">Dog Food</option>
                        <option value="Supplies">Supplies</option>
                        <option value="Vitamin">Vitamin</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="editProductPrice" class="form-label">Price</label>
                    <input type="text" id="editProductPrice" class="form-input" required>
                </div>
                <div class="form-group">
                    <label for="editProductStock" class="form-label">Stock</label>
                    <input type="number" id="editProductStock" class="form-input" min="0" required>
                </div>
                <div class="form-group">
                    <label for="editProductStatus" class="form-label">Status</label>
                    <select id="editProductStatus" class="form-select" required>
                        <option value="available">Available</option>
                        <option value="coming-soon">Coming Soon</option>
                        <option value="preorder">Pre-order</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="editProductDescription" class="form-label">Description</label>
                    <textarea id="editProductDescription" class="form-input" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <label for="editProductImage" class="form-label">Image</label>
                    <input type="file" id="editProductImage" class="form-input" accept="image/*">
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button class="modal-btn btn-cancel" onclick="closeEditModal()">Cancel</button>
            <button class="modal-btn btn-save" onclick="submitEditProduct()">Save Changes</button>
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
<script>
    // ===== Helpers =====
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const currencyToInt = (val) => {
        if (typeof val !== 'string') return Number(val) || 0;
        return Number(val.replace(/[^0-9]/g, '')) || 0;
    };
    const intToRupiah = (n) => new Intl.NumberFormat('id-ID').format(n);

    async function apiFetch(url, options = {}) {
        const opts = { credentials: 'same-origin', ...options };
        opts.headers = opts.headers || {};
        if (!(opts.body instanceof FormData)) {
            opts.headers['Content-Type'] = 'application/json';
        }
        // Always ask for JSON so Laravel validation returns JSON (even with FormData)
        opts.headers['Accept'] = 'application/json';
        opts.headers['X-Requested-With'] = 'XMLHttpRequest';
        opts.headers['X-CSRF-TOKEN'] = csrfToken;
        const res = await fetch(url, opts);
        if (!res.ok) {
            const ctErr = res.headers.get('content-type') || '';
            try {
                if (ctErr.includes('application/json')) {
                    const errJson = await res.json();
                    if (errJson?.errors) {
                        // Flatten validation messages
                        const messages = Object.values(errJson.errors).flat().join('\n');
                        throw new Error(messages || errJson.message || `Request failed (${res.status})`);
                    }
                    throw new Error(errJson.message || `Request failed (${res.status})`);
                }
                const text = await res.text();
                throw new Error(text || `Request failed (${res.status})`);
            } catch (e) {
                throw e instanceof Error ? e : new Error(`Request failed (${res.status})`);
            }
        }
        const ct = res.headers.get('content-type') || '';
        if (ct.includes('application/json')) return res.json();
        return res.text();
    }

    // ===== Rendering =====
    function productRowTemplate(p) {
        const imgSrc = p.image_path ? (`/storage/` + p.image_path) : `{{ asset('images/whiskas.svg') }}`;
        return `
            <tr data-id="${p.id}">
                <td><img src="${imgSrc}" alt="${p.name}"></td>
                <td>${p.name}</td>
                <td>${p.category}</td>
                <td>Rp. ${intToRupiah(Number(p.price) || 0)}</td>
                <td>${p.stock}</td>
                <td>
                    <div class="action-buttons">
                        <button class="action-btn btn-edit" data-action="edit"><i class="bi bi-pencil"></i> Edit</button>
                        <button class="action-btn btn-delete" data-action="delete"><i class="bi bi-trash"></i> Delete</button>
                    </div>
                </td>
            </tr>
        `;
    }

    function bindRowActions() {
        document.querySelectorAll('.action-btn').forEach(btn => {
            btn.addEventListener('click', async (e) => {
                const tr = e.currentTarget.closest('tr');
                const id = Number(tr.getAttribute('data-id'));
                const action = e.currentTarget.getAttribute('data-action');
                
                if (action === 'edit') {
                    try {
                        // Fetch full product details from API so fields like SKU/description are filled
                        const p = await apiFetch(`{{ url('/admin/products') }}/${id}`);
                        if (!p || typeof p !== 'object') throw new Error('Product not found');
                        openEditProductModal(p);
                    } catch (err) {
                        console.error('Failed to fetch product details:', err);
                        // Minimal fallback if API fails
                        const fallback = {
                            id,
                            name: tr.cells[1]?.textContent || '',
                            sku: '',
                            category: tr.cells[2]?.textContent || '',
                            price: currencyToInt(tr.cells[3]?.textContent || '0'),
                            stock: parseInt(tr.cells[4]?.textContent || '0') || 0,
                            status: 'available',
                            description: '',
                        };
                        openEditProductModal(fallback);
                    }
                } else if (action === 'delete') {
                    deleteProduct(id);
                }
            });
        });
    }

    function normalizeCategory(cat) {
        const c = String(cat || '').trim().toLowerCase();
        if (c.includes('vitamin')) return 'Vitamin';
        if (c.includes('suppl')) return 'Supplies';
        if (c.includes('food') || c === 'cat food' || c === 'dog food' || c === 'pet food') return (c.includes('dog') ? 'Dog Food' : (c.includes('cat') ? 'Cat Food' : 'Pet Food'));
        return cat || '';
    }

    function renderTables(all) {
        const petFoodTbody = document.getElementById('petFoodTbody');
        const suppliesTbody = document.getElementById('suppliesTbody');
        const vitaminsTbody = document.getElementById('vitaminsTbody');
        petFoodTbody.innerHTML = '';
        suppliesTbody.innerHTML = '';
        vitaminsTbody.innerHTML = '';

        // Sort by created_at desc if available
        const sorted = [...all].sort((a,b)=>{
            const da = new Date(a.created_at || 0).getTime();
            const db = new Date(b.created_at || 0).getTime();
            return db - da;
        });

        const petFood = sorted.filter(p => {
            const cat = normalizeCategory(p.category);
            return cat === 'Cat Food' || cat === 'Dog Food' || cat === 'Pet Food' || (String(p.category||'').toLowerCase().includes('food'));
        }).slice(0,3);
        const supplies = sorted.filter(p => {
            const cat = normalizeCategory(p.category);
            return cat === 'Supplies' || String(p.category||'').toLowerCase().includes('suppl');
        }).slice(0,3);
        const vitamins = sorted.filter(p => {
            const cat = normalizeCategory(p.category);
            return cat === 'Vitamin' || String(p.category||'').toLowerCase().includes('vitamin');
        }).slice(0,3);

        petFood.forEach(p => petFoodTbody.insertAdjacentHTML('beforeend', productRowTemplate(p)));
        supplies.forEach(p => suppliesTbody.insertAdjacentHTML('beforeend', productRowTemplate(p)));
        vitamins.forEach(p => vitaminsTbody.insertAdjacentHTML('beforeend', productRowTemplate(p)));

        if (!petFood.length) petFoodTbody.insertAdjacentHTML('beforeend', '<tr><td colspan="6" style="text-align:center;color:#888;">No Pet Food products yet.</td></tr>');
        if (!supplies.length) suppliesTbody.insertAdjacentHTML('beforeend', '<tr><td colspan="6" style="text-align:center;color:#888;">No Supplies products yet.</td></tr>');
        if (!vitamins.length) vitaminsTbody.insertAdjacentHTML('beforeend', '<tr><td colspan="6" style="text-align:center;color:#888;">No Vitamin products yet.</td></tr>');

        bindRowActions();
    }

    async function loadProducts() {
        try {
            const res = await apiFetch(`{{ url('/admin/products') }}?per_page=1000`, { method: 'GET' });
            const items = Array.isArray(res) ? res : (res.data || []);
            if (!Array.isArray(items)) throw new Error('Invalid response');
            renderTables(items);
        } catch (err) {
            console.error('Failed to load products:', err);
        }
    }

    // ===== Modal controls =====
    function openAddProductModal() {
        document.getElementById('addProductForm').reset();
        document.getElementById('addProductModal').classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeAddModal() {
        document.getElementById('addProductModal').classList.remove('active');
        document.body.style.overflow = '';
    }

    function openEditProductModal(p) {
        document.getElementById('editProductId').value = p.id;
        document.getElementById('editProductName').value = p.name || '';
        document.getElementById('editProductSku').value = p.sku || '';
        document.getElementById('editProductCategory').value = p.category || '';
        document.getElementById('editProductPrice').value = `Rp. ${intToRupiah(Number(p.price) || 0)}`;
        document.getElementById('editProductStock').value = p.stock ?? 0;
        document.getElementById('editProductStatus').value = p.status || 'available';
        document.getElementById('editProductDescription').value = p.description || '';
        document.getElementById('editProductModal').classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeEditModal() {
        document.getElementById('editProductModal').classList.remove('active');
        document.body.style.overflow = '';
    }

    async function submitAddProduct() {
        try {
            const btn = document.querySelector('#addProductModal .btn-save');
            btn.disabled = true; 
            btn.textContent = 'Saving...';
            
            const fd = new FormData();
            fd.append('name', document.getElementById('addProductName').value.trim());
            fd.append('sku', document.getElementById('addProductSku').value.trim());
            fd.append('category', document.getElementById('addProductCategory').value);
            fd.append('price', String(currencyToInt(document.getElementById('addProductPrice').value)));
            fd.append('stock', String(Number(document.getElementById('addProductStock').value || 0)));
            fd.append('status', document.getElementById('addProductStatus').value);
            fd.append('description', document.getElementById('addProductDescription').value || '');
            
            const img = document.getElementById('addProductImage').files[0];
            if (img) fd.append('image', img);
            
            await apiFetch(`{{ url('/admin/products') }}`, { method: 'POST', body: fd });
            closeAddModal();
            
            // After adding a product, reload data
            await loadProducts();
            alert('New product added successfully!');
        } catch (e) {
            console.error(e);
            alert('Failed to add product:\n' + (e.message || e));
        } finally {
            const btn = document.querySelector('#addProductModal .btn-save');
            if (btn) { 
                btn.disabled = false; 
                btn.textContent = 'Add Product'; 
            }
        }
    }

    async function submitEditProduct() {
        try {
            const btn = document.querySelector('#editProductModal .btn-save');
            btn.disabled = true; 
            btn.textContent = 'Saving...';
            
            const id = document.getElementById('editProductId').value;
            const fd = new FormData();
            fd.append('_method', 'PUT');
            fd.append('name', document.getElementById('editProductName').value.trim());
            fd.append('sku', document.getElementById('editProductSku').value.trim());
            fd.append('category', document.getElementById('editProductCategory').value);
            fd.append('price', String(currencyToInt(document.getElementById('editProductPrice').value)));
            fd.append('stock', String(Number(document.getElementById('editProductStock').value || 0)));
            fd.append('status', document.getElementById('editProductStatus').value);
            fd.append('description', document.getElementById('editProductDescription').value || '');
            
            const img = document.getElementById('editProductImage').files[0];
            if (img) fd.append('image', img);
            
            await apiFetch(`{{ url('/admin/products') }}/${id}`, { method: 'POST', body: fd });
            closeEditModal();
            
            // After editing a product, reload data
            await loadProducts();
            alert('Product updated successfully!');
        } catch (e) {
            console.error(e);
            alert('Failed to update product:\n' + (e.message || e));
        } finally {
            const btn = document.querySelector('#editProductModal .btn-save');
            if (btn) { 
                btn.disabled = false; 
                btn.textContent = 'Save Changes'; 
            }
        }
    }

    async function deleteProduct(id) {
        if (!confirm('Delete this product?')) return;
        try {
            await apiFetch(`{{ url('/admin/products') }}/${id}`, { 
                method: 'POST', 
                body: (() => {
                    const f = new FormData(); 
                    f.append('_method','DELETE'); 
                    return f;
                })() 
            });
            
            // After deleting a product, reload data
            await loadProducts();
            alert('Product deleted successfully!');
        } catch (e) {
            console.error(e);
            alert('Failed to delete product:\n' + (e.message || e));
        }
    }

    // Close modal with ESC key
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeAddModal();
            closeEditModal();
        }
    });

    // Close modal when click outside
    document.getElementById('addProductModal').addEventListener('click', function (e) {
        if (e.target === this) closeAddModal();
    });
    document.getElementById('editProductModal').addEventListener('click', function (e) {
        if (e.target === this) closeEditModal();
    });

    // Bind action buttons setelah DOM selesai dimuat
    document.addEventListener('DOMContentLoaded', function() {
        bindRowActions();
        
        // Only call loadProducts if no data is displayed
        const hasData = document.querySelectorAll('#petFoodTbody tr, #suppliesTbody tr, #vitaminsTbody tr').length > 0;
        if (!hasData) {
            loadProducts();
        }
    });
</script>