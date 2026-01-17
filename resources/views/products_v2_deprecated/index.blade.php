@extends('layouts.app')
@section('title', 'Products V2 - Advanced Category Filtering')

@section('content')

<!-- Include Tailwind CSS -->
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

<style>
/* Modern ProductsV2 Styles */
.products-v2-container {
    background: #f8fafc;
    min-height: calc(100vh - 200px);
}

.filter-panel {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 16px;
    padding: 24px;
    color: white;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    margin-bottom: 24px;
}

.filter-panel h3 {
    color: white;
    font-weight: 700;
    margin-bottom: 16px;
    font-size: 20px;
}

.filter-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
    margin-bottom: 16px;
}

.filter-group label {
    color: rgba(255, 255, 255, 0.9);
    font-weight: 600;
    margin-bottom: 8px;
    display: block;
    font-size: 14px;
}

.filter-input {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid rgba(255, 255, 255, 0.2);
    border-radius: 10px;
    background: rgba(255, 255, 255, 0.1);
    color: white;
    backdrop-filter: blur(10px);
    transition: all 0.3s ease;
}

.filter-input:focus {
    outline: none;
    border-color: rgba(255, 255, 255, 0.5);
    background: rgba(255, 255, 255, 0.15);
    box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.1);
}

.filter-input option {
    background: #374151;
    color: white;
}

.filter-actions {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}

.btn-filter {
    background: rgba(255, 255, 255, 0.2);
    border: 2px solid rgba(255, 255, 255, 0.3);
    color: white;
    padding: 12px 24px;
    border-radius: 10px;
    font-weight: 600;
    transition: all 0.3s ease;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
}

.btn-filter:hover {
    background: rgba(255, 255, 255, 0.3);
    border-color: rgba(255, 255, 255, 0.5);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.btn-clear {
    background: rgba(239, 68, 68, 0.8);
    border-color: rgba(239, 68, 68, 0.5);
}

.btn-clear:hover {
    background: rgba(239, 68, 68, 1);
    border-color: rgba(239, 68, 68, 0.7);
}

/* View Toggle */
.view-toggle {
    background: white;
    border-radius: 12px;
    padding: 24px;
    margin-bottom: 24px;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.view-toggle h4 {
    font-size: 18px;
    font-weight: 700;
    color: #1f2937;
    margin: 0;
}

.view-buttons {
    display: flex;
    gap: 8px;
}

.view-btn {
    padding: 10px 20px;
    border: 2px solid #e5e7eb;
    background: white;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 6px;
    font-weight: 500;
    color: #6b7280;
}

.view-btn.active {
    background: #667eea;
    border-color: #667eea;
    color: white;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.view-btn:hover:not(.active) {
    border-color: #667eea;
    background: #f8fafc;
    color: #667eea;
}

/* Products Content */
.products-content {
    background: white;
    border-radius: 16px;
    padding: 24px;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    min-height: 400px;
}

/* Category Section */
.category-section {
    margin-bottom: 32px;
    padding-bottom: 24px;
    border-bottom: 2px solid #f3f4f6;
}

.category-section:last-child {
    border-bottom: none;
    margin-bottom: 0;
}

.category-header {
    background: linear-gradient(135deg, #f8fafc 0%, #e5e7eb 100%);
    padding: 16px 24px;
    border-radius: 12px;
    margin-bottom: 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-left: 4px solid #667eea;
}

.category-title {
    font-size: 18px;
    font-weight: 700;
    color: #1f2937;
    display: flex;
    align-items: center;
    gap: 10px;
}

.category-stats {
    background: #667eea;
    color: white;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 14px;
    font-weight: 600;
}

/* Products Grid */
.products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 20px;
}

.product-card {
    background: white;
    border: 2px solid #f3f4f6;
    border-radius: 16px;
    overflow: hidden;
    transition: all 0.3s ease;
    cursor: pointer;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.product-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    border-color: #667eea;
}

.product-image {
    width: 100%;
    height: 200px;
    object-fit: cover;
    background: #f8fafc;
}

.product-info {
    padding: 20px;
}

.product-name {
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 8px;
    font-size: 16px;
    line-height: 1.4;
}

.product-sku {
    font-size: 12px;
    color: #6b7280;
    background: #f3f4f6;
    padding: 4px 8px;
    border-radius: 6px;
    display: inline-block;
    margin-bottom: 8px;
}

.product-price {
    font-size: 18px;
    font-weight: 700;
    color: #059669;
    margin-bottom: 12px;
}

.product-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 14px;
}

.product-brand {
    color: #6b7280;
    font-weight: 500;
}

.stock-badge {
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
}

.stock-high { background: #d1fae5; color: #065f46; }
.stock-medium { background: #dbeafe; color: #1e3a8a; }
.stock-low { background: #fef3c7; color: #92400e; }
.stock-out { background: #fee2e2; color: #991b1b; }

/* Products List */
.products-list {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.products-list-header {
    background: #f8fafc;
    border-bottom: 2px solid #e5e7eb;
    padding: 16px 24px;
    display: grid;
    grid-template-columns: 80px 2fr 1fr 1fr 1fr 1fr 100px;
    gap: 16px;
    font-weight: 700;
    color: #374151;
    font-size: 14px;
}

.product-list-item {
    padding: 16px 24px;
    border-bottom: 1px solid #f3f4f6;
    display: grid;
    grid-template-columns: 80px 2fr 1fr 1fr 1fr 1fr 100px;
    gap: 16px;
    align-items: center;
    transition: all 0.2s ease;
    cursor: pointer;
}

.product-list-item:hover {
    background: #f8fafc;
}

.product-list-item:last-child {
    border-bottom: none;
}

.product-list-image {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 8px;
    background: #f8fafc;
    border: 2px solid #e5e7eb;
}

.product-list-name {
    font-weight: 600;
    color: #1f2937;
    font-size: 14px;
}

.product-list-sku {
    font-size: 12px;
    color: #6b7280;
    background: #f3f4f6;
    padding: 2px 6px;
    border-radius: 4px;
    display: inline-block;
    margin-top: 4px;
}

/* Loading and Empty States */
.loading-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 60px;
    color: #6b7280;
}

.loading-spinner {
    width: 48px;
    height: 48px;
    border: 4px solid #f3f4f6;
    border-top: 4px solid #667eea;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin-bottom: 16px;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.empty-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 60px;
    color: #6b7280;
    text-align: center;
}

.empty-state-icon {
    font-size: 64px;
    margin-bottom: 16px;
    opacity: 0.5;
}

.stats-summary {
    background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 24px;
    border-left: 4px solid #0ea5e9;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 16px;
}

.stat-item {
    text-align: center;
}

.stat-number {
    font-size: 24px;
    font-weight: 700;
    color: #0369a1;
}

.stat-label {
    font-size: 14px;
    color: #0369a1;
    font-weight: 500;
}
</style>

<!-- Content Header -->
<section class="content-header mb-8">
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-2xl p-8 text-white shadow-2xl">
        <h1 class="text-3xl md:text-4xl font-bold mb-2 flex items-center gap-3">
            <i class="fa fa-cubes text-4xl"></i>
            Products V2 - Advanced Filtering
            <span class="ml-4 bg-white/20 px-4 py-2 rounded-full text-xl" id="db-total-count">
                <i class="fa fa-database"></i> Total in DB: {{ \App\Product::where('business_id', 1)->count() }}
            </span>
        </h1>
        <p class="text-blue-100 text-lg font-medium">Filter and browse products by subcategories with modern interface</p>
    </div>
</section>

<!-- Main Content -->
<section class="content products-v2-container">
    
    <!-- Filter Panel -->
    <div class="filter-panel">
        <h3><i class="fa fa-filter mr-2"></i> Advanced Product Filters</h3>
        
        <div class="filter-grid">
            <div class="filter-group">
                <label for="category-filter">Category</label>
                <select id="category-filter" class="filter-input">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @if(isset($category->children) && count($category->children) > 0)
                            @foreach($category->children as $subcategory)
                                <option value="{{ $subcategory->id }}">└─ {{ $subcategory->name }}</option>
                                @if(isset($subcategory->children) && count($subcategory->children) > 0)
                                    @foreach($subcategory->children as $subsubcategory)
                                        <option value="{{ $subsubcategory->id }}">　　└─ {{ $subsubcategory->name }}</option>
                                        @if(isset($subsubcategory->children) && count($subsubcategory->children) > 0)
                                            @foreach($subsubcategory->children as $level4)
                                                <option value="{{ $level4->id }}">　　　　└─ {{ $level4->name }}</option>
                                            @endforeach
                                        @endif
                                    @endforeach
                                @endif
                            @endforeach
                        @endif
                    @endforeach
                </select>
            </div>
            
            <div class="filter-group">
                <label for="brand-filter">Brand</label>
                <select id="brand-filter" class="filter-input">
                    <option value="">All Brands</option>
                    @foreach($brands as $brand)
                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="filter-group">
                <label for="type-filter">Product Type</label>
                <select id="type-filter" class="filter-input">
                    <option value="">All Types</option>
                    @foreach($product_types as $key => $type)
                        <option value="{{ $key }}">{{ $type }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="filter-group">
                <label for="search-filter">Search</label>
                <input type="text" id="search-filter" class="filter-input" placeholder="Search by name or SKU...">
            </div>
        </div>
        
        <div class="filter-actions">
            <button id="apply-filters" class="btn-filter">
                <i class="fa fa-search"></i>
                Apply Filters
            </button>
            <button id="clear-filters" class="btn-filter btn-clear">
                <i class="fa fa-times"></i>
                Clear All
            </button>
            <button id="debug-category" class="btn-filter" style="background: rgba(255, 193, 7, 0.8);">
                <i class="fa fa-bug"></i>
                Debug Category
            </button>
            <label class="btn-filter" style="cursor: pointer;">
                <input type="checkbox" id="group-by-category" style="margin-right: 8px;">
                <i class="fa fa-sitemap"></i>
                Group by Category
            </label>
            <label class="btn-filter" style="cursor: pointer;">
                <input type="checkbox" id="include-subcategories" checked style="margin-right: 8px;">
                <i class="fa fa-folder-open"></i>
                Include Subcategories
            </label>
        </div>
    </div>
    
    <!-- View Toggle -->
    <div class="view-toggle">
        <h4>Product Display</h4>
        <div class="view-buttons">
            <button class="view-btn active" id="grid-view" data-view="grid">
                <i class="fa fa-th"></i>
                Grid View
            </button>
            <button class="view-btn" id="list-view" data-view="list">
                <i class="fa fa-list"></i>
                List View
            </button>
        </div>
    </div>
    
    <!-- Statistics Summary -->
    <div class="stats-summary" id="stats-summary">
        <div class="stats-grid">
            <div class="stat-item">
                <div class="stat-number" id="total-products">0</div>
                <div class="stat-label">Total Products</div>
            </div>
            <div class="stat-item">
                <div class="stat-number" id="total-categories">0</div>
                <div class="stat-label">Categories</div>
            </div>
            <div class="stat-item">
                <div class="stat-number" id="in-stock-products">0</div>
                <div class="stat-label">In Stock</div>
            </div>
            <div class="stat-item">
                <div class="stat-number" id="low-stock-products">0</div>
                <div class="stat-label">Low Stock</div>
            </div>
        </div>
    </div>
    
    <!-- Products Content -->
    <div class="products-content" id="products-content">
        <div class="empty-state">
            <div class="empty-state-icon">🛍️</div>
            <h3>Welcome to Products V2</h3>
            <p>Use the filters above to browse products by category and subcategory</p>
        </div>
    </div>
    
</section>

@endsection

@section('javascript')
<script>
// Define base URL for proper routing in subdirectory
var baseUrl = '{{ url("/") }}';

$(document).ready(function() {
    console.log('ProductsV2 JavaScript loaded');
    console.log('Base URL:', baseUrl);
    
    let currentView = 'grid';
    let currentData = null;
    
    // Setup CSRF token
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
    // View toggle handlers
    $('.view-btn').on('click', function() {
        const view = $(this).data('view');
        currentView = view;
        
        $('.view-btn').removeClass('active');
        $(this).addClass('active');
        
        if (currentData) {
            displayProducts(currentData);
        }
    });
    
    // Filter handlers
    $('#apply-filters').on('click', function() {
        applyFilters();
    });
    
    $('#clear-filters').on('click', function() {
        clearFilters();
    });
    
    // Debug category handler
    $('#debug-category').on('click', function() {
        const categoryId = $('#category-filter').val();
        if (!categoryId) {
            alert('Please select a category first');
            return;
        }
        
        console.log('Debugging category:', categoryId);
        
        // Open debug URL in new tab
        window.open(baseUrl + '/products-v2/debug-category/' + categoryId, '_blank');
    });
    
    // Auto-search on input
    $('#search-filter').on('input', debounce(function() {
        applyFilters();
    }, 500));
    
    // Auto-apply on select change
    $('#category-filter, #brand-filter, #type-filter').on('change', function() {
        applyFilters();
    });
    
    // Group by category toggle
    $('#group-by-category').on('change', function() {
        if (currentData) {
            applyFilters();
        }
    });
    
    // Apply filters function
    function applyFilters() {
        const filters = {
            category_id: $('#category-filter').val(),
            brand_id: $('#brand-filter').val(),
            product_type: $('#type-filter').val(),
            search: $('#search-filter').val(),
            group_by_category: $('#group-by-category').is(':checked'),
            include_subcategories: $('#include-subcategories').is(':checked')
        };
        
        console.log('Applying filters:', filters);
        
        showLoading();
        
        // Choose endpoint based on grouping
        const endpoint = filters.group_by_category && filters.category_id 
            ? baseUrl + '/products-v2/by-subcategory' 
            : baseUrl + '/products-v2/all';
            
        console.log('Using endpoint:', endpoint);
        
        $.ajax({
            url: endpoint,
            method: 'GET',
            data: filters,
            success: function(response) {
                console.log('Products loaded successfully:', response);
                
                if (response.success) {
                    currentData = response;
                    displayProducts(response);
                    updateStats(response);
                } else {
                    console.error('Response indicates failure:', response);
                    showError('Failed to load products: ' + (response.message || 'Unknown error'));
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Filter error:', {
                    status: xhr.status,
                    statusText: xhr.statusText,
                    responseText: xhr.responseText,
                    error: error
                });
                showError('Failed to load products: ' + error + ' (Status: ' + xhr.status + ')');
            }
        });
    }
    
    // Display products function
    function displayProducts(response) {
        const $content = $('#products-content');
        
        console.log('displayProducts called with response:', response);
        
        if (!response.data || response.data.length === 0) {
            $content.html(`
                <div class="empty-state">
                    <div class="empty-state-icon">📦</div>
                    <h3>No Products Found</h3>
                    <p>Try adjusting your filters to see more products</p>
                </div>
            `);
            return;
        }
        
        let html = '';
        
        if (response.grouped && Array.isArray(response.data)) {
            console.log('Rendering grouped data with', response.data.length, 'categories');
            // Grouped by category display
            response.data.forEach(function(categoryGroup) {
                console.log('Category group:', categoryGroup.category_name, 'with', categoryGroup.products.length, 'products');
                console.log('First product in group:', categoryGroup.products[0]);
                
                html += `
                    <div class="category-section">
                        <div class="category-header">
                            <div class="category-title">
                                <i class="fa fa-folder"></i>
                                ${categoryGroup.category_name}
                            </div>
                            <div class="category-stats">
                                ${categoryGroup.product_count} products
                            </div>
                        </div>
                        ${renderProductsView(categoryGroup.products)}
                    </div>
                `;
            });
        } else {
            console.log('Rendering regular data with', response.data.length, 'products');
            console.log('First product:', response.data[0]);
            // Regular display
            const products = Array.isArray(response.data) ? response.data : response.data.products || [];
            html = renderProductsView(products);
        }
        
        $content.html(html);
    }
    
    // Render products in current view
    function renderProductsView(products) {
        if (currentView === 'grid') {
            return renderGridView(products);
        } else {
            return renderListView(products);
        }
    }
    
    // Render grid view
    function renderGridView(products) {
        let html = '<div class="products-grid">';
        
        products.forEach(function(product) {
            // Defensive check for stock_status
            const stockStatus = product.stock_status || { status: 'unknown', text: 'Unknown', class: 'secondary' };
            
            html += `
                <div class="product-card" data-product-id="${product.id}">
                    <img src="${product.image || '/img/default.png'}" alt="${product.name || 'Product'}" class="product-image" onerror="this.src='/img/default.png'">
                    <div class="product-info">
                        <div class="product-name">${product.name || 'Unnamed Product'}</div>
                        <div class="product-sku">SKU: ${product.sku || 'N/A'}</div>
                        <div class="product-price">$${product.price_display || '0.00'}</div>
                        <div class="product-meta">
                            <div class="product-brand">${product.brand_name || 'No Brand'}</div>
                            <div class="stock-badge stock-${stockStatus.status}">
                                ${stockStatus.text}
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });
        
        html += '</div>';
        return html;
    }
    
    // Render list view
    function renderListView(products) {
        let html = `
            <div class="products-list">
                <div class="products-list-header">
                    <div>Image</div>
                    <div>Product Name</div>
                    <div>Price</div>
                    <div>Stock</div>
                    <div>Brand</div>
                    <div>Category</div>
                    <div>Actions</div>
                </div>
        `;
        
        products.forEach(function(product) {
            // Defensive check for stock_status
            const stockStatus = product.stock_status || { status: 'unknown', text: 'Unknown', class: 'secondary' };
            
            html += `
                <div class="product-list-item" data-product-id="${product.id}">
                    <div>
                        <img src="${product.image || '/img/default.png'}" alt="${product.name || 'Product'}" class="product-list-image" onerror="this.src='/img/default.png'">
                    </div>
                    <div>
                        <div class="product-list-name">${product.name || 'Unnamed Product'}</div>
                        <div class="product-list-sku">SKU: ${product.sku || 'N/A'}</div>
                    </div>
                    <div class="text-green-600 font-semibold">$${product.price_display || '0.00'}</div>
                    <div>
                        <span class="stock-badge stock-${stockStatus.status}">
                            ${stockStatus.text}
                        </span>
                        <div class="text-sm text-gray-500 mt-1">${product.stock || 0} units</div>
                    </div>
                    <div class="text-gray-600">${product.brand_name || 'No Brand'}</div>
                    <div class="text-gray-600">${product.category_name || 'N/A'}</div>
                    <div>
                        <button class="bg-blue-500 text-white px-3 py-1 rounded text-sm hover:bg-blue-600 transition-colors" onclick="viewProduct(${product.id})">
                            <i class="fa fa-eye"></i>
                        </button>
                    </div>
                </div>
            `;
        });
        
        html += '</div>';
        return html;
    }
    
    // Update statistics
    function updateStats(response) {
        const $stats = $('#stats-summary');
        
        if (response.data && response.data.length > 0) {
            let totalProducts = 0;
            let totalCategories = 0;
            let inStockProducts = 0;
            let lowStockProducts = 0;
            
            if (response.grouped && Array.isArray(response.data)) {
                totalCategories = response.data.length;
                response.data.forEach(function(categoryGroup) {
                    totalProducts += categoryGroup.product_count;
                    categoryGroup.products.forEach(function(product) {
                        const stockStatus = product.stock_status || { status: 'unknown' };
                        if (stockStatus.status === 'high' || stockStatus.status === 'medium') {
                            inStockProducts++;
                        }
                        if (stockStatus.status === 'low') {
                            lowStockProducts++;
                        }
                    });
                });
            } else {
                const products = Array.isArray(response.data) ? response.data : [];
                totalProducts = products.length;
                totalCategories = new Set(products.map(p => p.category_id)).size;
                
                products.forEach(function(product) {
                    const stockStatus = product.stock_status || { status: 'unknown' };
                    if (stockStatus.status === 'high' || stockStatus.status === 'medium') {
                        inStockProducts++;
                    }
                    if (stockStatus.status === 'low') {
                        lowStockProducts++;
                    }
                });
            }
            
            $('#total-products').text(totalProducts);
            $('#total-categories').text(totalCategories);
            $('#in-stock-products').text(inStockProducts);
            $('#low-stock-products').text(lowStockProducts);
            
            $stats.fadeIn();
        } else {
            $stats.fadeOut();
        }
    }
    
    // Clear filters
    function clearFilters() {
        $('#category-filter').val('');
        $('#brand-filter').val('');
        $('#type-filter').val('');
        $('#search-filter').val('');
        $('#group-by-category').prop('checked', false);
        $('#include-subcategories').prop('checked', true);
        
        currentData = null;
        
        $('#products-content').html(`
            <div class="empty-state">
                <div class="empty-state-icon">🛍️</div>
                <h3>Welcome to Products V2</h3>
                <p>Use the filters above to browse products by category and subcategory</p>
            </div>
        `);
        
        $('#stats-summary').fadeOut();
    }
    
    // Show loading state
    function showLoading() {
        $('#products-content').html(`
            <div class="loading-state">
                <div class="loading-spinner"></div>
                <p>Loading products...</p>
            </div>
        `);
    }
    
    // Show error state
    function showError(message) {
        $('#products-content').html(`
            <div class="empty-state">
                <div class="empty-state-icon">❌</div>
                <h3>Error</h3>
                <p>${message}</p>
            </div>
        `);
    }
    
    // Debounce function
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
    
    // Product view function
    function viewProduct(productId) {
        console.log('View product:', productId);
        // Redirect to product edit page
        window.open(baseUrl + '/products/' + productId + '/edit', '_blank');
    }
    
    // Auto-load all products on page load
    setTimeout(function() {
        $('#group-by-category').prop('checked', true);
        applyFilters();
    }, 500);
});

// Global function for product view
function viewProduct(productId) {
    console.log('View product:', productId);
    window.open(baseUrl + '/products/' + productId + '/edit', '_blank');
}
</script>
@endsection
