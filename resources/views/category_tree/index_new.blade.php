@extends('layouts.app')
@section('title', 'Category Tree')

@section('content')
<style>
/* Category Tree Styles */
.category-tree-container {
    display: flex;
    height: calc(100vh - 200px);
    gap: 20px;
    background: #f8fafc;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

/* Left Panel - Categories */
.categories-panel {
    width: 30%;
    background: white;
    border-right: 2px solid #e5e7eb;
    display: flex;
    flex-direction: column;
}

.categories-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 20px;
    font-weight: 600;
    font-size: 1.1rem;
    display: flex;
    align-items: center;
    gap: 10px;
}

.categories-search {
    padding: 15px;
    border-bottom: 1px solid #e5e7eb;
}

.categories-search input {
    width: 100%;
    padding: 8px 12px;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    font-size: 14px;
}

.categories-search input:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.categories-tree {
    flex: 1;
    overflow-y: auto;
    padding: 10px;
}

.categories-tree::-webkit-scrollbar {
    width: 6px;
}

.categories-tree::-webkit-scrollbar-track {
    background: #f1f5f9;
}

.categories-tree::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 3px;
}

/* Category Tree Items */
.category-item {
    margin-bottom: 2px;
    position: relative;
    transition: all 0.3s ease;
}

.category-item.dragging {
    opacity: 0.6;
    transform: rotate(2deg);
    z-index: 1000;
    background: rgba(255, 255, 255, 0.9);
    border-radius: 6px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
}

.category-item.drag-over {
    background: rgba(102, 126, 234, 0.1);
    border: 2px dashed #667eea;
    border-radius: 6px;
}

/* Drag Handle */
.drag-handle {
    position: absolute;
    left: 4px;
    top: 50%;
    transform: translateY(-50%);
    width: 16px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: grab;
    color: #9ca3af;
    font-size: 12px;
    opacity: 0;
    transition: all 0.2s;
    z-index: 10;
}

.category-item:hover .drag-handle {
    opacity: 1;
}

.drag-handle:hover {
    color: #667eea;
    transform: translateY(-50%) scale(1.1);
}

.drag-handle:active {
    cursor: grabbing;
}

/* Tree Connection Lines */
.tree-lines {
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    pointer-events: none;
    z-index: 1;
}

.vertical-line,
.horizontal-line,
.continue-line {
    position: absolute;
    background: #d1d5db;
}

.vertical-line {
    left: 10px;
    top: -12px;
    bottom: 50%;
    width: 1px;
    background: linear-gradient(to bottom, #d1d5db 0%, #d1d5db 100%);
}

.horizontal-line {
    left: 10px;
    top: 50%;
    width: 18px;
    height: 1px;
    background: linear-gradient(to right, #d1d5db 0%, #d1d5db 80%, transparent 100%);
}

.continue-line {
    left: 10px;
    top: 50%;
    bottom: -12px;
    width: 1px;
    background: linear-gradient(to bottom, #d1d5db 0%, #d1d5db 100%);
}

/* Enhanced tree styling for better visual hierarchy */
.category-item[data-level="0"] {
    border-left: 3px solid transparent;
}

.category-item[data-level="1"] {
    border-left: 3px solid #e5e7eb;
    margin-left: 10px;
}

.category-item[data-level="2"] {
    border-left: 3px solid #d1d5db;
    margin-left: 20px;
}

.category-item[data-level="3"] {
    border-left: 3px solid #9ca3af;
    margin-left: 30px;
}

.category-item[data-level="4"] {
    border-left: 3px solid #6b7280;
    margin-left: 40px;
}

.category-node {
    display: flex;
    align-items: center;
    padding: 10px 40px 10px 25px;
    cursor: pointer;
    border-radius: 6px;
    transition: all 0.2s;
    user-select: none;
    position: relative;
    background: white;
}

.category-node:hover {
    background: #f8fafc;
    transform: translateX(2px);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.category-node.active {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    font-weight: 500;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.category-node.active .category-count {
    background: rgba(255, 255, 255, 0.2);
    color: white;
}

.category-node.active .category-actions .action-btn {
    background: rgba(255, 255, 255, 0.2);
    color: white;
}

/* Category Toggle */
.category-toggle {
    width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 8px;
    font-size: 10px;
    color: #6b7280;
    transition: all 0.2s;
    cursor: pointer;
}

.category-toggle.expanded {
    transform: rotate(90deg);
    color: #667eea;
}

.category-toggle:hover {
    color: #667eea;
    transform: scale(1.2);
}

.category-toggle.no-children {
    cursor: default;
}

.category-toggle.no-children:hover {
    transform: none;
    color: #6b7280;
}

.tree-connector {
    font-family: monospace;
    font-size: 12px;
    color: #d1d5db;
}

/* Category Icons */
.category-icon {
    margin-right: 10px;
    font-size: 16px;
    width: 20px;
    text-align: center;
}

.root-folder {
    color: #f59e0b;
}

.parent-folder {
    color: #3b82f6;
}

.leaf-category {
    color: #10b981;
}

/* Category Information */
.category-info {
    flex: 1;
    min-width: 0;
}

.category-name {
    font-size: 14px;
    line-height: 1.4;
    font-weight: 500;
    color: inherit;
    word-break: break-word;
}

.category-code {
    font-size: 11px;
    color: #6b7280;
    font-family: monospace;
    margin-top: 2px;
}

.category-node.active .category-code {
    color: rgba(255, 255, 255, 0.8);
}

/* Category Stats */
.category-stats {
    display: flex;
    gap: 6px;
    margin-left: 8px;
}

.category-count {
    background: #e5e7eb;
    color: #6b7280;
    padding: 3px 8px;
    border-radius: 12px;
    font-size: 10px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 3px;
    min-width: 0;
}

.products-count {
    background: #dcfce7;
    color: #166534;
}

.children-count {
    background: #dbeafe;
    color: #1e40af;
}

/* Category Actions */
.category-actions {
    display: flex;
    gap: 4px;
    opacity: 0;
    transition: opacity 0.2s;
}

.category-item:hover .category-actions {
    opacity: 1;
}

.action-btn {
    width: 24px;
    height: 24px;
    border: none;
    border-radius: 4px;
    background: #f3f4f6;
    color: #6b7280;
    font-size: 10px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
}

.action-btn:hover {
    background: #667eea;
    color: white;
    transform: scale(1.1);
}

.edit-btn:hover {
    background: #f59e0b;
}

.add-btn:hover {
    background: #10b981;
}

.category-children {
    display: none; /* Start collapsed */
    position: relative;
    border-left: 2px dotted #e5e7eb;
    margin-left: 15px;
    padding-left: 10px;
}

.category-children.expanded {
    display: block;
}

.category-children.collapsed {
    display: none;
}

/* Debug styles */
.debug-info {
    font-size: 10px;
    color: #666;
    background: #f0f0f0;
    padding: 2px 4px;
    border-radius: 3px;
    margin-top: 2px;
}

.sortable-container {
    min-height: 20px;
}

/* Sortable placeholders */
.sortable-placeholder {
    background: rgba(102, 126, 234, 0.1);
    border: 2px dashed #667eea;
    border-radius: 6px;
    margin: 2px 0;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #667eea;
    font-size: 12px;
    font-weight: 500;
}

.sortable-placeholder:before {
    content: "Drop here to reorder";
}

/* Right Panel - Products */
.products-panel {
    width: 70%;
    background: white;
    display: flex;
    flex-direction: column;
}

.products-header {
    background: #f9fafb;
    border-bottom: 1px solid #e5e7eb;
    padding: 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.products-title {
    font-size: 1.2rem;
    font-weight: 600;
    color: #1f2937;
    display: flex;
    align-items: center;
    gap: 10px;
}

.products-stats {
    color: #6b7280;
    font-size: 14px;
}

.products-content {
    flex: 1;
    overflow-y: auto;
    padding: 20px;
}

.products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 20px;
}

.product-card {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    overflow: hidden;
    transition: all 0.2s;
    cursor: pointer;
}

.product-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    border-color: #667eea;
}

.product-image {
    height: 160px;
    background: #f9fafb;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    overflow: hidden;
}

.product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.product-image .no-image {
    color: #9ca3af;
    font-size: 48px;
}

.product-type-badge {
    position: absolute;
    top: 8px;
    right: 8px;
    background: rgba(102, 126, 234, 0.9);
    color: white;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 11px;
    font-weight: 500;
    text-transform: capitalize;
}

.product-info {
    padding: 15px;
}

.product-name {
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 5px;
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.product-sku {
    color: #6b7280;
    font-size: 12px;
    margin-bottom: 5px;
}

.product-brand {
    color: #667eea;
    font-size: 12px;
    font-weight: 500;
    margin-bottom: 8px;
}

.product-price {
    font-size: 1.1rem;
    font-weight: 700;
    color: #059669;
    margin-bottom: 8px;
}

.product-stock {
    font-size: 12px;
    padding: 4px 8px;
    border-radius: 4px;
    font-weight: 500;
}

.product-stock.in-stock {
    background: #d1fae5;
    color: #065f46;
}

.product-stock.out-of-stock {
    background: #fee2e2;
    color: #991b1b;
}

/* Empty States */
.empty-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100%;
    color: #6b7280;
    text-align: center;
}

.empty-state-icon {
    font-size: 64px;
    margin-bottom: 16px;
    opacity: 0.5;
}

.empty-state h3 {
    font-size: 1.2rem;
    margin-bottom: 8px;
    color: #374151;
}

.empty-state p {
    font-size: 14px;
    max-width: 300px;
}

/* Loading State */
.loading-state {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 200px;
    flex-direction: column;
    gap: 16px;
}

.spinner {
    width: 40px;
    height: 40px;
    border: 4px solid #f3f4f6;
    border-top: 4px solid #667eea;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Responsive Design */
@media (max-width: 768px) {
    .category-tree-container {
        flex-direction: column;
        height: auto;
    }
    
    .categories-panel {
        width: 100%;
        max-height: 300px;
    }
    
    .products-panel {
        width: 100%;
    }
    
    .products-grid {
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 15px;
    }
}

@media (max-width: 480px) {
    .products-grid {
        grid-template-columns: 1fr;
    }
    
    .category-tree-container {
        margin: 10px;
    }
    
    .categories-header,
    .products-header {
        padding: 15px;
    }
}

/* Success flash animation */
.success-flash {
    animation: successFlash 1s ease-in-out;
}

@keyframes successFlash {
    0%, 100% { background: inherit; }
    50% { background: #10b981 !important; color: white !important; }
}

/* Hover effects for drag handles */
.category-item:hover .drag-handle {
    opacity: 1;
}

/* Better visual feedback during drag operations */
.sortable-drag {
    opacity: 0.6;
}

.sortable-ghost {
    opacity: 0.4;
}

/* Improved tree lines */
.tree-lines .vertical-line {
    background: linear-gradient(to bottom, #d1d5db 0%, #d1d5db 100%);
}

.tree-lines .horizontal-line {
    background: linear-gradient(to right, #d1d5db 0%, transparent 100%);
}
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">
                        <i class="fas fa-sitemap text-primary"></i> 
                        Category Tree Management
                    </h3>
                    <div class="box-tools">
                        <button type="button" class="btn btn-sm btn-info" onclick="expandAll()">
                            <i class="fas fa-expand-arrows-alt"></i> Expand All
                        </button>
                        <button type="button" class="btn btn-sm btn-warning" onclick="collapseAll()">
                            <i class="fas fa-compress-arrows-alt"></i> Collapse All
                        </button>
                        <button type="button" class="btn btn-sm btn-success" onclick="testCategoryProducts()">
                            <i class="fas fa-vial"></i> Test Products
                        </button>
                    </div>
                </div>
                <div class="box-body">
                    <div class="category-tree-container">
                        <!-- Left Panel - Categories -->
                        <div class="categories-panel">
                            <div class="categories-header">
                                <i class="fas fa-sitemap"></i>
                                Category Tree
                            </div>
                            <div class="categories-search">
                                <input type="text" id="categorySearch" placeholder="Search categories..." onkeyup="filterCategories()">
                            </div>
                            <div class="categories-tree" id="categoryTree">
                                <!-- Tree will be loaded here -->
                            </div>
                        </div>

                        <!-- Right Panel - Products -->
                        <div class="products-panel">
                            <div class="products-header">
                                <div class="products-title">
                                    <i class="fas fa-boxes"></i>
                                    <span id="selectedCategoryName">Select a category to view products</span>
                                </div>
                                <div class="products-stats">
                                    <span id="productsCount">0 products</span>
                                </div>
                            </div>
                            <div class="products-content" id="productsContent">
                                <div class="empty-state">
                                    <div class="empty-state-icon">
                                        <i class="fas fa-mouse-pointer"></i>
                                    </div>
                                    <h3>No Category Selected</h3>
                                    <p>Click on a category from the tree to view its products and subcategories.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- SortableJS Library -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

<script>
let selectedCategoryId = null;
let sortableInstances = {};

// CSRF Token for AJAX requests
const csrfToken = $('meta[name="csrf-token"]').attr('content');

// Document ready
$(document).ready(function() {
    console.log('Category Tree - Document Ready');
    loadCategoryTree();
});

// Load the category tree
function loadCategoryTree() {
    console.log('Loading category tree...');
    $.ajax({
        url: '/category-tree/data',
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': csrfToken
        },
        success: function(response) {
            console.log('Tree loaded successfully:', response);
            renderCategoryTree(response);
            initializeSortable();
        },
        error: function(xhr, status, error) {
            console.error('Error loading tree:', error);
            console.error('Response:', xhr.responseText);
            $('#categoryTree').html('<div class="alert alert-danger">Error loading category tree: ' + error + '</div>');
        }
    });
}

// Render the category tree
function renderCategoryTree(categories, level = 0) {
    let html = '';
    
    if (Array.isArray(categories)) {
        categories.forEach(category => {
            html += renderCategoryNode(category, level);
        });
    } else {
        console.error('Categories is not an array:', categories);
    }
    
    if (level === 0) {
        $('#categoryTree').html(html);
    }
    
    return html;
}

// Render a single category node
function renderCategoryNode(category, level = 0) {
    const hasChildren = category.children && category.children.length > 0;
    const productsCount = category.products_count || 0;
    const childrenCount = hasChildren ? category.children.length : 0;
    
    let html = `
        <div class="category-item sortable-item" data-category-id="${category.id}" data-level="${level}">
            <div class="drag-handle" title="Drag to reorder">
                <i class="fas fa-grip-vertical"></i>
            </div>
            
            <div class="category-node" onclick="selectCategory(${category.id}, '${category.name}', this)">
                <div class="category-toggle ${hasChildren ? 'has-children' : 'no-children'}" 
                     onclick="event.stopPropagation(); toggleCategory(this, ${category.id})">
                    ${hasChildren ? '<i class="fas fa-caret-right"></i>' : '<span class="tree-connector">•</span>'}
                </div>
                
                <i class="category-icon fas ${hasChildren ? 'fa-folder parent-folder' : (level === 0 ? 'fa-folder-open root-folder' : 'fa-file-alt leaf-category')}"></i>
                
                <div class="category-info">
                    <div class="category-name">${category.name}</div>
                    ${category.short_code ? `<div class="category-code">${category.short_code}</div>` : ''}
                </div>
                
                <div class="category-stats">
                    ${productsCount > 0 ? `<span class="category-count products-count" title="Products">
                        <i class="fas fa-cube"></i> ${productsCount}
                    </span>` : ''}
                    ${childrenCount > 0 ? `<span class="category-count children-count" title="Subcategories">
                        <i class="fas fa-folder"></i> ${childrenCount}
                    </span>` : ''}
                </div>
                
                <div class="category-actions">
                    <button class="action-btn edit-btn" title="Edit Category" onclick="event.stopPropagation(); editCategory(${category.id})">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="action-btn add-btn" title="Add Subcategory" onclick="event.stopPropagation(); addSubcategory(${category.id})">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
            </div>
    `;
    
    if (hasChildren) {
        html += `<div class="category-children sortable-container" id="children-${category.id}">`;
        category.children.forEach(child => {
            html += renderCategoryNode(child, level + 1);
        });
        html += `</div>`;
    }
    
    html += `</div>`;
    
    return html;
}

// Initialize SortableJS for drag and drop
function initializeSortable() {
    console.log('Initializing sortable...');
    
    // Main tree container
    const mainTree = document.getElementById('categoryTree');
    if (mainTree) {
        sortableInstances['main'] = new Sortable(mainTree, {
            group: 'categories',
            animation: 150,
            handle: '.drag-handle',
            ghostClass: 'sortable-ghost',
            dragClass: 'sortable-drag',
            onStart: function(evt) {
                evt.item.classList.add('dragging');
            },
            onEnd: function(evt) {
                evt.item.classList.remove('dragging');
                updateCategoryOrder(evt);
            }
        });
    }
    
    // Initialize sortable for existing children containers
    document.querySelectorAll('.category-children').forEach(container => {
        const categoryId = container.id.replace('children-', '');
        sortableInstances[categoryId] = new Sortable(container, {
            group: 'categories',
            animation: 150,
            handle: '.drag-handle',
            ghostClass: 'sortable-ghost',
            dragClass: 'sortable-drag',
            onStart: function(evt) {
                evt.item.classList.add('dragging');
            },
            onEnd: function(evt) {
                evt.item.classList.remove('dragging');
                updateCategoryOrder(evt);
            }
        });
    });
}

// Update category order after drag and drop
function updateCategoryOrder(evt) {
    const categoryId = evt.item.dataset.categoryId;
    const newParentContainer = evt.to;
    const newIndex = evt.newIndex;
    
    let newParentId = null;
    if (newParentContainer.id.startsWith('children-')) {
        newParentId = newParentContainer.id.replace('children-', '');
    }
    
    console.log('Updating order:', {
        categoryId,
        newParentId,
        newIndex
    });
    
    // You can implement the AJAX call to update the order in the backend here
    // For now, just show a success flash
    evt.item.classList.add('success-flash');
    setTimeout(() => {
        evt.item.classList.remove('success-flash');
    }, 1000);
}

// Toggle category expansion
function toggleCategory(toggleElement, categoryId) {
    const childrenContainer = document.getElementById(`children-${categoryId}`);
    
    if (childrenContainer) {
        const isExpanded = childrenContainer.classList.contains('expanded');
        
        if (isExpanded) {
            childrenContainer.classList.remove('expanded');
            childrenContainer.classList.add('collapsed');
            toggleElement.classList.remove('expanded');
        } else {
            childrenContainer.classList.remove('collapsed');
            childrenContainer.classList.add('expanded');
            toggleElement.classList.add('expanded');
            
            // Initialize sortable for newly expanded container if not already done
            if (!sortableInstances[categoryId]) {
                sortableInstances[categoryId] = new Sortable(childrenContainer, {
                    group: 'categories',
                    animation: 150,
                    handle: '.drag-handle',
                    ghostClass: 'sortable-ghost',
                    dragClass: 'sortable-drag',
                    onStart: function(evt) {
                        evt.item.classList.add('dragging');
                    },
                    onEnd: function(evt) {
                        evt.item.classList.remove('dragging');
                        updateCategoryOrder(evt);
                    }
                });
            }
        }
    }
}

// Select a category and load its products
function selectCategory(categoryId, categoryName, element) {
    // Update selected category
    selectedCategoryId = categoryId;
    
    // Update UI - remove active class from all nodes
    document.querySelectorAll('.category-node').forEach(node => {
        node.classList.remove('active');
    });
    
    // Add active class to selected node
    element.classList.add('active');
    
    // Update header
    document.getElementById('selectedCategoryName').textContent = categoryName;
    
    // Load products
    loadCategoryProducts(categoryId);
}

// Load products for a category
function loadCategoryProducts(categoryId) {
    console.log('Loading products for category:', categoryId);
    
    // Show loading state
    const productsContent = document.getElementById('productsContent');
    productsContent.innerHTML = `
        <div class="loading-state">
            <div class="spinner"></div>
            <span>Loading products...</span>
        </div>
    `;
    
    $.ajax({
        url: `/category-tree/products/${categoryId}`,
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': csrfToken
        },
        success: function(response) {
            console.log('Products response:', response);
            renderProducts(response);
        },
        error: function(xhr, status, error) {
            console.error('Error loading products:', error);
            console.error('Response:', xhr.responseText);
            productsContent.innerHTML = `
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="fas fa-exclamation-triangle text-danger"></i>
                    </div>
                    <h3>Error Loading Products</h3>
                    <p>There was an error loading products: ${error}</p>
                </div>
            `;
        }
    });
}

// Render products in the products panel
function renderProducts(response) {
    const products = response.products || response || [];
    const productsCount = products.length;
    
    // Update count
    document.getElementById('productsCount').textContent = `${productsCount} product${productsCount !== 1 ? 's' : ''}`;
    
    const productsContent = document.getElementById('productsContent');
    
    if (productsCount === 0) {
        productsContent.innerHTML = `
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="fas fa-box-open"></i>
                </div>
                <h3>No Products Found</h3>
                <p>This category doesn't contain any products yet.</p>
            </div>
        `;
        return;
    }
    
    let html = '<div class="products-grid">';
    
    products.forEach(product => {
        const price = product.variations && product.variations[0] ? 
                     (product.variations[0].default_sell_price || '0.00') : '0.00';
        const stockQuantity = product.variations && product.variations[0] && product.variations[0].variation_location_details ?
                             product.variations[0].variation_location_details[0]?.qty_available || 0 : 0;
        
        html += `
            <div class="product-card">
                <div class="product-image">
                    ${product.image ? 
                        `<img src="/uploads/img/${product.image}" alt="${product.name}">` :
                        `<i class="no-image fas fa-image"></i>`
                    }
                    <div class="product-type-badge">${product.type || 'single'}</div>
                </div>
                <div class="product-info">
                    <div class="product-name">${product.name}</div>
                    <div class="product-sku">SKU: ${product.sku || 'N/A'}</div>
                    ${product.brand ? `<div class="product-brand">${product.brand.name}</div>` : ''}
                    <div class="product-price">$${parseFloat(price).toFixed(2)}</div>
                    <div class="product-stock ${stockQuantity > 0 ? 'in-stock' : 'out-of-stock'}">
                        Stock: ${stockQuantity}
                    </div>
                </div>
            </div>
        `;
    });
    
    html += '</div>';
    productsContent.innerHTML = html;
}

// Utility functions
function expandAll() {
    document.querySelectorAll('.category-children').forEach(container => {
        container.classList.remove('collapsed');
        container.classList.add('expanded');
    });
    
    document.querySelectorAll('.category-toggle.has-children').forEach(toggle => {
        toggle.classList.add('expanded');
    });
}

function collapseAll() {
    document.querySelectorAll('.category-children').forEach(container => {
        container.classList.remove('expanded');
        container.classList.add('collapsed');
    });
    
    document.querySelectorAll('.category-toggle.has-children').forEach(toggle => {
        toggle.classList.remove('expanded');
    });
}

function filterCategories() {
    const searchTerm = document.getElementById('categorySearch').value.toLowerCase();
    const categoryItems = document.querySelectorAll('.category-item');
    
    categoryItems.forEach(item => {
        const categoryName = item.querySelector('.category-name').textContent.toLowerCase();
        const matches = categoryName.includes(searchTerm);
        
        item.style.display = matches ? 'block' : 'none';
        
        // Show parent categories if child matches
        if (matches) {
            let parent = item.parentElement;
            while (parent && parent.classList.contains('category-children')) {
                parent.style.display = 'block';
                parent.classList.add('expanded');
                parent = parent.parentElement.parentElement;
            }
        }
    });
}

function editCategory(categoryId) {
    console.log('Edit category:', categoryId);
    // Implement edit functionality
}

function addSubcategory(parentId) {
    console.log('Add subcategory to:', parentId);
    // Implement add subcategory functionality
}

function testCategoryProducts() {
    console.log('Testing category products...');
    // Test with a known category ID
    loadCategoryProducts(18); // Airless Sprayer category
}
</script>
@endsection
