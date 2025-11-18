@include ('layouts.navbar')
@extends('layouts.app')

@section('content')
<!-- Custom CSS -->
<meta name="csrf-token" content="{{ csrf_token() }}">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<style>
body {
    background: #fff;
    font-family: 'Poppins', Arial, sans-serif;
    margin: 0;
    padding: 0;
}
.shop-hero {
    background: #FFE0B2;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-radius: 32px;
    padding: 60px 80px;
    margin: 150px auto 0 auto; /* Increased top margin */
    max-width: 1400px;
    width: 95%;
    min-height: 250px;
    box-shadow: 0 4px 24px #e6a15d22;
    position: relative;
    overflow: visible;
}
.shop-hero-content {
    max-width: 60%;
}

.shop-hero-content h2 {
    font-weight: 700;
    color: #8D5B2D;
    font-size: 2.2rem;
    margin-bottom: 0.8rem;
    letter-spacing: -1px;
    text-align: left;
}
.shop-hero-content p {
    color: #8D5B2D;
    font-size: 1.1rem;
    font-weight: 400;
    text-align: left;
    line-height: 1.6;
    margin: 0;
}
.shop-hero-img {
    height: 420px;
    position: absolute;
    right: -0px;
    bottom: -1px;
    z-index: 10;
}

/* ===== Search Section ===== */
.search-section {
    max-width: 1400px; /* Match hero's max-width */
    margin: 20px auto 40px auto;
    padding: 0;
    display: flex;
    justify-content: flex-end;
    position: relative;
    z-index: 5;
    width: 95%; /* Match hero's width */
    right: 0;
}

.search-container {
    position: relative;
    width: 400px;
    max-width: 100%;
    background: #fff;
    border-radius: 50px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
    border: 1px solid rgba(0, 0, 0, 0.05);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.search-container:focus-within {
    box-shadow: 0 6px 24px rgba(141, 91, 45, 0.15);
    transform: translateY(-2px);
}

.search-box {
    width: 100%;
    padding: 16px 60px 16px 55px;
    border: 2px solid #F0E6D6;
    border-radius: 50px;
    font-size: 1.05rem;
    font-family: 'Poppins', sans-serif;
    background: #fff;
    color: #4A2C12;
    outline: none;
    transition: all 0.3s ease;
}

.search-box:focus {
    border-color: #E6A15D;
    box-shadow: 0 0 0 4px rgba(230, 161, 93, 0.2);
}

.search-box::placeholder {
    color: #B7B2AA;
    font-weight: 400;
    letter-spacing: 0.3px;
}

.search-icon {
    position: absolute;
    left: 22px;
    top: 50%;
    transform: translateY(-50%);
    color: #E6A15D;
    font-size: 1.1rem;
    pointer-events: none;
}

.search-clear {
    position: absolute;
    right: 18px;
    top: 50%;
    transform: translateY(-50%);
    background: #F5F5F5;
    border: none;
    border-radius: 50%;
    color: #B7B2AA;
    cursor: pointer;
    width: 28px;
    height: 28px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
    opacity: 0;
    visibility: hidden;
}

.search-clear.visible {
    opacity: 1;
    visibility: visible;
}

.search-clear:hover {
    background: #E6A15D;
    color: #fff;
}

.search-clear i {
    font-size: 0.9rem;
}

/* No Results Message */
.no-results {
    text-align: center;
    padding: 60px 20px;
    color: #666;
    font-size: 1.1rem;
    display: none;
}

.no-results i {
    font-size: 4rem;
    color: #E6A15D;
    margin-bottom: 20px;
    display: block;
}

/* Category Sections */
.category-section {
    max-width: 1200px;
    margin: 0 auto 48px auto;
    padding: 0 24px;
}
.category-header {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 32px;
}
.category-title {
    background: #8D5B2D;
    color: #fff;
    border-radius: 32px;
    padding: 12px 32px;
    font-weight: 700;
    font-size: 1.2rem;
    display: flex;
    align-items: center;
    gap: 8px;
    box-shadow: 0 4px 16px rgba(141, 91, 45, 0.3);
}
.subcategory-tabs {
    display: flex;
    gap: 16px;
    margin-bottom: 24px;
}
.subcategory-tab {
    background: #fff;
    border: 2px solid #E6A15D;
    border-radius: 20px;
    padding: 8px 20px;
    font-weight: 600;
    font-size: 1rem;
    color: #8D5B2D;
    cursor: pointer;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    gap: 8px;
}
.subcategory-tab.active, .subcategory-tab:hover {
    background: #8D5B2D;
    color: #fff;
    border-color: #8D5B2D;
}

/* Product Grid */
.products-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 24px;
    margin-bottom: 32px;
}
.product-card {
    background: #fff;
    border-radius: 18px;
    border: 1.5px solid #f3e7d7;
    box-shadow: 0 4px 24px #e6a15d11;
    padding: 20px;
    display: flex;
    flex-direction: column;
    position: relative;
    min-height: 360px;
    transition: box-shadow 0.3s, transform 0.3s;
}
.product-card:hover {
    box-shadow: 0 8px 32px #e6a15d33;
    transform: translateY(-4px);
}

.product-card.hidden {
    display: none;
}

/* Product images */
.product-images {
    width: 100%;
    height: 140px;
    background: #fafafa;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 16px;
    position: relative;
    overflow: hidden;
}
.product-images img {
    max-width: 100px;
    max-height: 120px;
    object-fit: contain;
    border-radius: 8px;
}

/* Product Information */
.product-info {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.product-name {
    font-weight: 700;
    font-size: 1rem;
    color: #222;
    line-height: 1.3;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

.product-description {
    font-size: 0.85rem;
    color: #666;
    line-height: 1.5;
    flex: 1;
}

.product-sku {
    font-family: 'Courier New', monospace;
    font-size: 0.75rem;
    color: #999;
    font-weight: 600;
}

.product-status {
    display: inline-block;
    font-size: 0.75rem;
    font-weight: 600;
    padding: 4px 8px;
    border-radius: 10px;
    text-transform: uppercase;
    letter-spacing: 0.3px;
    align-self: flex-start;
}

.status-available {
    background: #d4edda;
    color: #155724;
}

.status-coming-soon {
    background: #fff3cd;
    color: #856404;
}

.status-preorder {
    background: #d1ecf1;
    color: #0c5460;
}

.product-price {
    margin-top: auto;
    padding-top: 12px;
    border-top: 1px solid #f0f0f0;
    display: flex;
    align-items: center;
    gap: 8px;
}
.price-sale {
    color: #E57300;
    font-weight: 700;
    font-size: 1rem;
}
.price-original {
    text-decoration: line-through;
    color: #aaa;
    font-size: 0.85rem;
}

@media (max-width: 1200px) {
    .products-grid { 
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }
    .shop-hero, 
    .category-section, 
    .search-section { 
        max-width: 100%; 
        padding: 0 24px;
    }
}

@media (max-width: 768px) {
    .search-section {
        margin-top: 0;
        padding: 0 16px;
    }
    
    .search-container {
        width: 100%;
    }
    
    .search-box {
        padding: 14px 55px 14px 50px;
    }
}

@media (max-width: 576px) {
    .shop-hero, 
    .category-section, 
    .search-section { 
        padding: 0 16px;
    }
    
    .shop-hero { 
        flex-direction: column; 
        text-align: left;
        padding: 40px 24px;
        margin-top: 100px;
    }
    
    .shop-hero-content {
        max-width: 100%;
    }
    
    .shop-hero-img { 
        position: relative; 
        right: 0;
        bottom: 0;
        margin: 20px auto 0;
        height: 280px;
        width: auto;
    }
    
    .search-section {
        margin: 10px auto 30px;
    }
    
    .search-box {
        font-size: 1rem;
        padding: 14px 50px 14px 45px;
    }
    
    .search-icon {
        left: 18px;
    }
    
    .search-clear {
        right: 15px;
    }
}
</style>

<div class="shop-hero">
    <div class="shop-hero-content">
        <h2>Pet Supplies Catalog</h2>
        <p>Find a variety of pet products at our shop, ready for you to purchase in person.</p>
    </div>
    <img src="{{ asset('images/anjingkucing.png') }}" class="shop-hero-img" alt="Dog and Cat">
</div>

<!-- Search Section -->
<div class="search-section">
    <div class="search-container">
        <i class="fas fa-search search-icon"></i>
        <input type="text" class="search-box" id="productSearch" placeholder="Search products, brands, or SKU...">
        <button class="search-clear" id="clearSearch">
            <i class="fas fa-times"></i>
        </button>
    </div>
</div>

<!-- No Results Message -->
<div class="no-results" id="noResults">
    <i class="fas fa-search"></i>
    <h3>No products found</h3>
    <p>Try adjusting your search terms or browse our categories below.</p>
</div>

<!-- PET FOOD Section -->
<div class="category-section" data-category="food">
    <div class="category-header">
        <div class="category-title">
            <span>🐾</span>
            PET FOOD
        </div>
    </div>
    <div class="subcategory-tabs">
        <div class="subcategory-tab active" data-target="cat-food">
            <span>🐱</span>
            Cat
        </div>
        <div class="subcategory-tab" data-target="dog-food">
            <span>🐕</span>
            Dog
        </div>
    </div>

    <!-- Cat Food Products -->
    <div class="products-grid" id="cat-food">
        @foreach($catFood as $p)
        @php
            $statusClass = $p->status === 'active' ? 'available' : $p->status;
            $statusText = $statusClass === 'available' ? 'Available' : ($statusClass === 'coming-soon' ? 'Coming Soon' : 'Pre-order');
            $img = $p->image_path ? asset($p->image_path) : asset('images/1.svg');
        @endphp
        <div class="product-card"
             data-name="{{ strtolower($p->name) }}"
             data-description="{{ strtolower((string)$p->description) }}"
             data-sku="{{ strtolower((string)$p->sku) }}">
            <div class="product-images">
                <img src="{{ $img }}" alt="{{ $p->name }}">
            </div>
            <div class="product-info">
                <h3 class="product-name">{{ $p->name }}</h3>
                <p class="product-description">{{ $p->description }}</p>
                <div class="product-sku">SKU: {{ $p->sku }}</div>
                <div class="product-status status-{{ $statusClass }}">{{ $statusText }}</div>
                <div class="product-price">
                    <span class="price-sale">Rp{{ number_format($p->price,0,',','.') }}</span>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Dog Food Products -->
    <div class="products-grid" id="dog-food" style="display: none;">
        @foreach($dogFood as $p)
        @php
            $statusClass = $p->status === 'active' ? 'available' : $p->status;
            $statusText = $statusClass === 'available' ? 'Available' : ($statusClass === 'coming-soon' ? 'Coming Soon' : 'Pre-order');
            $img = $p->image_path ? asset($p->image_path) : asset('images/1.svg');
        @endphp
        <div class="product-card"
             data-name="{{ strtolower($p->name) }}"
             data-description="{{ strtolower((string)$p->description) }}"
             data-sku="{{ strtolower((string)$p->sku) }}">
            <div class="product-images">
                <img src="{{ $img }}" alt="{{ $p->name }}">
            </div>
            <div class="product-info">
                <h3 class="product-name">{{ $p->name }}</h3>
                <p class="product-description">{{ $p->description }}</p>
                <div class="product-sku">SKU: {{ $p->sku }}</div>
                <div class="product-status status-{{ $statusClass }}">{{ $statusText }}</div>
                <div class="product-price">
                    <span class="price-sale">Rp{{ number_format($p->price,0,',','.') }}</span>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- PET SUPPLIES Section -->
<div class="category-section" data-category="supplies">
    <div class="category-header">
        <div class="category-title">
            <span>🐾</span>
            PET SUPPLIES
        </div>
    </div>

    <div class="subcategory-tabs">
        <div class="subcategory-tab active" data-target="cat-supplies">
            <span>🐱</span>
            Cat
        </div>
        <div class="subcategory-tab" data-target="dog-supplies">
            <span>🐕</span>
            Dog
        </div>
    </div>

    <!-- Cat Supplies Products -->
    <div class="products-grid" id="cat-supplies">
        @foreach($catSupplies as $p)
        @php
            $statusClass = $p->status === 'active' ? 'available' : $p->status;
            $statusText = $statusClass === 'available' ? 'Available' : ($statusClass === 'coming-soon' ? 'Coming Soon' : 'Pre-order');
            $img = $p->image_path ? asset($p->image_path) : asset('images/1.svg');
        @endphp
        <div class="product-card"
             data-name="{{ strtolower($p->name) }}"
             data-description="{{ strtolower((string)$p->description) }}"
             data-sku="{{ strtolower((string)$p->sku) }}">
            <div class="product-images">
                <img src="{{ $img }}" alt="{{ $p->name }}">
            </div>
            <div class="product-info">
                <h3 class="product-name">{{ $p->name }}</h3>
                <p class="product-description">{{ $p->description }}</p>
                <div class="product-sku">SKU: {{ $p->sku }}</div>
                <div class="product-status status-{{ $statusClass }}">{{ $statusText }}</div>
                <div class="product-price">
                    <span class="price-sale">Rp{{ number_format($p->price,0,',','.') }}</span>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Dog Supplies Products -->
    <div class="products-grid" id="dog-supplies" style="display: none;">
        @foreach($dogSupplies as $p)
        @php
            $statusClass = $p->status === 'active' ? 'available' : $p->status;
            $statusText = $statusClass === 'available' ? 'Available' : ($statusClass === 'coming-soon' ? 'Coming Soon' : 'Pre-order');
            $img = $p->image_path ? asset($p->image_path) : asset('images/1.svg');
        @endphp
        <div class="product-card"
             data-name="{{ strtolower($p->name) }}"
             data-description="{{ strtolower((string)$p->description) }}"
             data-sku="{{ strtolower((string)$p->sku) }}">
            <div class="product-images">
                <img src="{{ $img }}" alt="{{ $p->name }}">
            </div>
            <div class="product-info">
                <h3 class="product-name">{{ $p->name }}</h3>
                <p class="product-description">{{ $p->description }}</p>
                <div class="product-sku">SKU: {{ $p->sku }}</div>
                <div class="product-status status-{{ $statusClass }}">{{ $statusText }}</div>
                <div class="product-price">
                    <span class="price-sale">Rp{{ number_format($p->price,0,',','.') }}</span>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- PET VITAMINS Section -->
<div class="category-section" data-category="vitamins">
    <div class="category-header">
        <div class="category-title">
            <span>🐾</span>
            PET VITAMINS
        </div>
    </div>

    <div class="subcategory-tabs">
        <div class="subcategory-tab active" data-target="cat-vitamins">
            <span>🐱</span>
            Cat
        </div>
        <div class="subcategory-tab" data-target="dog-vitamins">
            <span>🐕</span>
            Dog
        </div>
    </div>

    <!-- Cat Vitamins Products -->
    <div class="products-grid" id="cat-vitamins">
        @foreach($catVitamins as $p)
        @php
            $statusClass = $p->status === 'active' ? 'available' : $p->status;
            $statusText = $statusClass === 'available' ? 'Available' : ($statusClass === 'coming-soon' ? 'Coming Soon' : 'Pre-order');
            $img = $p->image_path ? asset($p->image_path) : asset('images/1.svg');
        @endphp
        <div class="product-card"
             data-name="{{ strtolower($p->name) }}"
             data-description="{{ strtolower((string)$p->description) }}"
             data-sku="{{ strtolower((string)$p->sku) }}">
            <div class="product-images">
                <img src="{{ $img }}" alt="{{ $p->name }}">
            </div>
            <div class="product-info">
                <h3 class="product-name">{{ $p->name }}</h3>
                <p class="product-description">{{ $p->description }}</p>
                <div class="product-sku">SKU: {{ $p->sku }}</div>
                <div class="product-status status-{{ $statusClass }}">{{ $statusText }}</div>
                <div class="product-price">
                    <span class="price-sale">Rp{{ number_format($p->price,0,',','.') }}</span>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Dog Vitamins Products -->
    <div class="products-grid" id="dog-vitamins" style="display: none;">
        @foreach($dogVitamins as $p)
        @php
            $statusClass = $p->status === 'active' ? 'available' : $p->status;
            $statusText = $statusClass === 'available' ? 'Available' : ($statusClass === 'coming-soon' ? 'Coming Soon' : 'Pre-order');
            $img = $p->image_path ? asset($p->image_path) : asset('images/1.svg');
        @endphp
        <div class="product-card"
             data-name="{{ strtolower($p->name) }}"
             data-description="{{ strtolower((string)$p->description) }}"
             data-sku="{{ strtolower((string)$p->sku) }}">
            <div class="product-images">
                <img src="{{ $img }}" alt="{{ $p->name }}">
            </div>
            <div class="product-info">
                <h3 class="product-name">{{ $p->name }}</h3>
                <p class="product-description">{{ $p->description }}</p>
                <div class="product-sku">SKU: {{ $p->sku }}</div>
                <div class="product-status status-{{ $statusClass }}">{{ $statusText }}</div>
                <div class="product-price">
                    <span class="price-sale">Rp{{ number_format($p->price,0,',','.') }}</span>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@include('layouts.footer')

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchBox = document.getElementById('productSearch');
    const clearButton = document.getElementById('clearSearch');
    const noResults = document.getElementById('noResults');
    const categorySection = document.querySelectorAll('.category-section');

    // Tab functionality
    const tabs = document.querySelectorAll('.subcategory-tab');
    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const parentSection = this.closest('.category-section');
            const allTabs = parentSection.querySelectorAll('.subcategory-tab');
            allTabs.forEach(t => t.classList.remove('active'));

            this.classList.add('active');

            const allGrids = parentSection.querySelectorAll('.products-grid');
            allGrids.forEach(grid => grid.style.display = 'none');

            const targetId = this.getAttribute('data-target');
            const targetGrid = parentSection.querySelector('#' + targetId);
            if (targetGrid) {
                targetGrid.style.display = 'grid';
            }
        });
    });

    // Search functionality
    searchBox.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase().trim();

        if (searchTerm.length > 0) {
            clearButton.classList.add('show');
            performSearch(searchTerm);
        } else {
            clearButton.classList.remove('show');
            clearSearch();
        }
    });

    clearButton.addEventListener('click', function() {
        searchBox.value = '';
        clearButton.classList.remove('show');
        clearSearch();
        searchBox.focus();
    });

    function performSearch(searchTerm) {
        let foundResults = false;

        // Hide all category sections first
        categorySection.forEach(section => {
            section.style.display = 'none';
        });

        // Search through all product cards
        const allProductCards = document.querySelectorAll('.product-card');
        allProductCards.forEach(card => {
            const name = card.getAttribute('data-name') || '';

            // Pencarian hanya berdasarkan nama produk
            if (name.includes(searchTerm)) {

                // Show the product card
                card.classList.remove('hidden');

                // Show the parent category section
                const parentSection = card.closest('.category-section');
                if (parentSection) {
                    parentSection.style.display = 'block';

                    // Show ALL products grids in this category section (both cat and dog)
                    const allGridsInSection = parentSection.querySelectorAll('.products-grid');
                    allGridsInSection.forEach(grid => {
                        // Check if this grid contains any matching products
                        const matchingCards = grid.querySelectorAll('.product-card:not(.hidden)');
                        if (matchingCards.length > 0) {
                            grid.style.display = 'grid';
                        }
                    });

                    // Hide tab functionality during search - show both cat and dog results
                    const tabs = parentSection.querySelectorAll('.subcategory-tab');
                    tabs.forEach(tab => {
                        tab.style.pointerEvents = 'none';
                        tab.style.opacity = '0.6';
                    });
                }

                foundResults = true;
            } else {
                card.classList.add('hidden');
            }
        });

        // After hiding non-matching cards, show all grids that have visible cards
        categorySection.forEach(section => {
            if (section.style.display === 'block') {
                const allGrids = section.querySelectorAll('.products-grid');
                allGrids.forEach(grid => {
                    const visibleCards = grid.querySelectorAll('.product-card:not(.hidden)');
                    if (visibleCards.length > 0) {
                        grid.style.display = 'grid';
                    } else {
                        grid.style.display = 'none';
                    }
                });
            }
        });

        // Show/hide no results message
        if (!foundResults) {
            noResults.style.display = 'block';
        } else {
            noResults.style.display = 'none';
        }
    }

    function clearSearch() {
        // Show all category sections
        categorySection.forEach(section => {
            section.style.display = 'block';
        });

        // Show all product cards
        const allProductCards = document.querySelectorAll('.product-card');
        allProductCards.forEach(card => {
            card.classList.remove('hidden');
        });

        // Hide no results message
        noResults.style.display = 'none';

        // Re-enable tab functionality
        const allTabs = document.querySelectorAll('.subcategory-tab');
        allTabs.forEach(tab => {
            tab.style.pointerEvents = 'auto';
            tab.style.opacity = '1';
        });

        // Reset tab functionality - show active tabs only
        const allSections = document.querySelectorAll('.category-section');
        allSections.forEach(section => {
            const activeTab = section.querySelector('.subcategory-tab.active');
            const allGrids = section.querySelectorAll('.products-grid');

            allGrids.forEach(grid => {
                grid.style.display = 'none';
            });

            if (activeTab) {
                const targetId = activeTab.getAttribute('data-target');
                const targetGrid = section.querySelector('#' + targetId);
                if (targetGrid) {
                    targetGrid.style.display = 'grid';
                }
            }
        });
    }

    // Handle Enter key
    searchBox.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            this.blur();
        }
    });
});
</script>

@endsection

            
