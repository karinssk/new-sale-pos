@extends('layouts.app')

@section('title', __('Category Manager V2'))

@section('content')
<div class="content-wrapper">
    <!-- Content Header -->
    <section class="content-header">
        <h1>@lang('Category Manager V2')
            <small>Advanced tree view with folder structure</small>
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            <i class="fa fa-sitemap"></i> Category Tree Structure
                        </h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-sm btn-primary" id="refresh-tree-v2">
                                <i class="fa fa-refresh"></i> Refresh
                            </button>
                            <button type="button" class="btn btn-sm btn-success" id="expand-all-v2">
                                <i class="fa fa-expand"></i> Expand All
                            </button>
                            <button type="button" class="btn btn-sm btn-warning" id="collapse-all-v2">
                                <i class="fa fa-compress"></i> Collapse All
                            </button>
                            <button type="button" class="btn btn-sm btn-info" id="add-root-category">
                                <i class="fa fa-plus"></i> Add Root Category
                            </button>
                            <button type="button" class="btn btn-sm btn-success" id="toggle-drag-mode">
                                <i class="fa fa-arrows"></i> <span id="drag-mode-text">Disable Drag</span>
                            </button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <!-- Tree View Column -->
                            <div class="col-md-8">
                                <div class="drag-instructions">
                                    <i class="fa fa-info-circle"></i>
                                    <strong>Hierarchical Drag & Drop:</strong> Use the grip handles (<i class="fa fa-grip-vertical"></i>) to reorder categories. 
                                    <br>
                                    <small>
                                        📈 <strong>Move Right:</strong> Drag into a category to increase level (L1→L2, L2→L3) |
                                        📉 <strong>Move Left:</strong> Drag to root/parent to decrease level (L3→L2, L2→L1) |
                                        🔄 <strong>Reorder:</strong> Drag within same level to change order
                                    </small>
                                </div>
                                <div id="category-tree-v2" class="category-tree-container">
                                    <div class="loading-spinner">
                                        <i class="fa fa-spinner fa-spin"></i>
                                        <span>Loading categories...</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Form Column -->
                            <div class="col-md-4">
                                <div class="category-form-panel">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4 class="panel-title">
                                                <i class="fa fa-folder-plus"></i>
                                                <span id="form-title">Add New Category</span>
                                            </h4>
                                        </div>
                                        <div class="panel-body">
                                            <form id="category-form-v2">
                                                <input type="hidden" id="category-id" name="category_id">
                                                <input type="hidden" id="parent-id" name="parent_id" value="0">
                                                
                                                <div class="form-group">
                                                    <label for="category-name">Category Name *</label>
                                                    <input type="text" class="form-control" id="category-name" name="name" required>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label for="category-code">Short Code</label>
                                                    <input type="text" class="form-control" id="category-code" name="short_code" maxlength="10">
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label for="parent-display">Parent Category</label>
                                                    <div id="parent-display" class="form-control-static">
                                                        <i class="fa fa-home"></i> Root Level
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label for="category-description">Description</label>
                                                    <textarea class="form-control" id="category-description" name="description" rows="3"></textarea>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-primary btn-block" id="submit-btn-v2">
                                                        <i class="fa fa-save"></i> Save Category
                                                    </button>
                                                    <button type="button" class="btn btn-default btn-block" id="cancel-btn-v2">
                                                        <i class="fa fa-times"></i> Cancel
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    
                                    <!-- Category Statistics -->
                                    <div class="panel panel-info">
                                        <div class="panel-heading">
                                            <h4 class="panel-title">
                                                <i class="fa fa-bar-chart"></i> Statistics
                                            </h4>
                                        </div>
                                        <div class="panel-body">
                                            <div class="stat-item">
                                                <span class="stat-label">Total Categories:</span>
                                                <span class="stat-value" id="total-categories">0</span>
                                            </div>
                                            <div class="stat-item">
                                                <span class="stat-label">Root Categories:</span>
                                                <span class="stat-value" id="root-categories">0</span>
                                            </div>
                                            <div class="stat-item">
                                                <span class="stat-label">Max Depth:</span>
                                                <span class="stat-value" id="max-depth">0</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Keyboard Shortcuts -->
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4 class="panel-title">
                                                <i class="fa fa-keyboard-o"></i> Shortcuts
                                            </h4>
                                        </div>
                                        <div class="panel-body">
                                            <div class="shortcut-item">
                                                <kbd>F2</kbd> Edit selected
                                            </div>
                                            <div class="shortcut-item">
                                                <kbd>Del</kbd> Delete selected
                                            </div>
                                            <div class="shortcut-item">
                                                <kbd>Ctrl+N</kbd> New category
                                            </div>
                                            <div class="shortcut-item">
                                                <kbd>Esc</kbd> Cancel/Close
                                            </div>
                                            <div class="shortcut-item">
                                                <kbd>Right-click</kbd> Context menu
                                            </div>
                                            <div class="shortcut-item">
                                                <kbd>Drag & Drop</kbd> Reorder categories
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Context Menu -->
<div id="context-menu" class="context-menu" style="display: none;">
    <ul class="context-menu-list">
        <li data-action="create-product">
            <i class="fa fa-plus-circle"></i> Create Product
        </li>
        <li data-action="edit">
            <i class="fa fa-edit"></i> Edit Category
        </li>
        <li data-action="add-child">
            <i class="fa fa-plus"></i> Add Child Category
        </li>
        <li class="divider"></li>
        <li data-action="delete" class="danger">
            <i class="fa fa-trash"></i> Delete Category
        </li>
    </ul>
</div>

<style>
/* Category Tree V2 Styles */
.category-tree-container {
    background: #f9f9f9;
    border: 1px solid #ddd;
    border-radius: 6px;
    padding: 15px;
    min-height: 500px;
    max-height: 600px;
    overflow-y: auto;
}

.loading-spinner {
    text-align: center;
    padding: 50px;
    color: #666;
}

.category-item-v2 {
    display: flex;
    align-items: center;
    padding: 8px 12px;
    margin: 2px 0;
    border-radius: 4px;
    cursor: pointer;
    transition: all 0.2s ease;
    background: white;
    border: 1px solid #e9ecef;
    position: relative;
}

.category-item-v2:hover {
    background: #f8f9fa;
    border-color: #007bff;
    box-shadow: 0 2px 4px rgba(0,123,255,0.1);
}

.category-item-v2.selected {
    background: #e3f2fd;
    border-color: #2196f3;
    box-shadow: 0 2px 6px rgba(33,150,243,0.2);
}

.category-item-v2.dragging {
    opacity: 0.6;
    transform: rotate(2deg);
    box-shadow: 0 4px 8px rgba(0,0,0,0.3);
    z-index: 1000;
}

.category-item-v2.drag-over {
    border: 2px dashed #007bff;
    background: #f0f8ff;
}

.sortable-ghost {
    opacity: 0.4;
    background: #c8ebfb;
}

.sortable-chosen {
    opacity: 0.8;
}

.drag-handle {
    cursor: grab;
    padding: 4px;
    margin-right: 8px;
    color: #999;
    border-radius: 3px;
    transition: all 0.2s ease;
    display: none;
}

.drag-handle:hover {
    background: #f0f0f0;
    color: #666;
}

.drag-handle:active {
    cursor: grabbing;
}

.drag-mode-enabled .drag-handle {
    display: inline-block;
}

.drag-mode-enabled .category-item-v2 {
    border-left: 3px solid #28a745;
}

.drag-instructions {
    background: #e3f2fd;
    border: 1px solid #90caf9;
    border-radius: 4px;
    padding: 8px 12px;
    margin-bottom: 10px;
    font-size: 12px;
    color: #1976d2;
    display: none;
}

.drag-mode-enabled .drag-instructions {
    display: block;
}

.drop-zone {
    min-height: 30px;
    border: 2px dashed #ccc;
    border-radius: 4px;
    background: #f9f9f9;
    margin: 2px 0;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #666;
    font-size: 12px;
    transition: all 0.2s ease;
}

.drop-zone.drag-over {
    border-color: #007bff;
    background: #f0f8ff;
    color: #007bff;
}

.temp-drop-zone {
    min-height: 35px;
    border: 2px dashed #28a745;
    border-radius: 6px;
    background: #f8fff9;
    margin: 3px 0 3px 25px;
    display: none;
    align-items: center;
    justify-content: center;
    color: #28a745;
    font-size: 11px;
    transition: all 0.3s ease;
    position: relative;
}

.drag-mode-enabled .temp-drop-zone {
    display: flex;
}

.temp-drop-zone:hover,
.temp-drop-zone.drag-over {
    border-color: #155724;
    background: #d4edda;
    color: #155724;
    box-shadow: 0 2px 4px rgba(40, 167, 69, 0.2);
}

.drop-placeholder {
    font-style: italic;
    opacity: 0.8;
}

.temp-drop-zone::before {
    content: '📁';
    margin-right: 6px;
    font-size: 14px;
}

.category-indent {
    display: inline-block;
}

.category-toggle {
    background: none;
    border: none;
    padding: 4px 8px;
    margin-right: 8px;
    cursor: pointer;
    color: #666;
    border-radius: 3px;
    transition: all 0.2s ease;
}

.category-toggle:hover {
    background: #e9ecef;
    color: #333;
}

.category-toggle.expanded {
    transform: rotate(90deg);
}

.category-folder-icon {
    margin-right: 8px;
    font-size: 16px;
    color: #ffa726;
}

.category-folder-icon.open {
    color: #ff9800;
}

.category-folder-icon.has-products {
    color: #4caf50;
}

.category-content {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.category-info {
    flex: 1;
}

.category-name {
    font-weight: 500;
    color: #333;
    margin-right: 8px;
}

.category-code {
    font-size: 11px;
    color: #666;
    background: #f1f3f4;
    padding: 2px 6px;
    border-radius: 10px;
    margin-right: 8px;
}

.category-level {
    font-size: 10px;
    color: white;
    padding: 2px 6px;
    border-radius: 3px;
    margin-right: 8px;
}

.level-1 { background: #2196f3; }
.level-2 { background: #4caf50; }
.level-3 { background: #ff9800; }
.level-4 { background: #f44336; }
.level-5 { background: #9c27b0; }

.category-count {
    font-size: 11px;
    color: #666;
    background: #e8f5e8;
    padding: 2px 6px;
    border-radius: 10px;
    margin-right: 8px;
}

.category-actions-v2 {
    display: flex;
    gap: 4px;
    opacity: 0;
    transition: opacity 0.2s ease;
}

.category-item-v2:hover .category-actions-v2 {
    opacity: 1;
}

.category-children {
    margin-left: 25px;
    border-left: 2px solid #e9ecef;
    padding-left: 15px;
    margin-top: 5px;
}

.category-description {
    font-size: 11px;
    color: #6c757d;
    font-style: italic;
    margin-top: 2px;
}

/* Form Styles */
.category-form-panel .panel {
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    border: none;
}

.category-form-panel .panel-heading {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
}

.stat-item {
    display: flex;
    justify-content: space-between;
    padding: 8px 0;
    border-bottom: 1px solid #eee;
}

.stat-item:last-child {
    border-bottom: none;
}

.stat-label {
    color: #666;
    font-weight: 500;
}

.stat-value {
    color: #333;
    font-weight: bold;
}

.shortcut-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 4px 0;
    font-size: 12px;
}

.shortcut-item kbd {
    background-color: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 3px;
    padding: 2px 6px;
    font-size: 11px;
    font-weight: normal;
}

/* Responsive */
@media (max-width: 768px) {
    .category-tree-container {
        max-height: 400px;
    }
    
    .category-item-v2 {
        padding: 6px 8px;
    }
    
    .category-actions-v2 {
        opacity: 1;
    }
}

/* Animation for expand/collapse */
.category-children {
    overflow: hidden;
    transition: max-height 0.3s ease;
}

.category-children.collapsed {
    max-height: 0;
    margin: 0;
    padding: 0;
}

.category-children.expanded {
    max-height: 1000px;
}

/* Context Menu */
.context-menu {
    position: fixed;
    background: white;
    border: 1px solid #ddd;
    border-radius: 6px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    z-index: 9999;
    min-width: 180px;
}

.context-menu-list {
    list-style: none;
    margin: 0;
    padding: 8px 0;
}

.context-menu-list li {
    padding: 8px 16px;
    cursor: pointer;
    transition: background-color 0.2s ease;
    font-size: 13px;
}

.context-menu-list li:hover {
    background-color: #f8f9fa;
}

.context-menu-list li.danger:hover {
    background-color: #fee;
    color: #dc3545;
}

.context-menu-list li.divider {
    height: 1px;
    background-color: #eee;
    margin: 4px 0;
    padding: 0;
}

.context-menu-list li.divider:hover {
    background-color: #eee;
}

.context-menu-list li i {
    margin-right: 8px;
    width: 14px;
}
</style>

@endsection

@section('javascript')
<!-- Include SortableJS for drag and drop -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

<script>
$(function() {
    let categories = [];
    let selectedCategory = null;
    let isEditMode = false;
    let isDragModeEnabled = true; // Default to enabled
    let sortableInstances = [];

    // Initialize
    loadCategoriesV2();

    // Set initial drag mode state
    $('#category-tree-v2').addClass('drag-mode-enabled');
    
    // Event handlers
    $('#refresh-tree-v2').click(function() {
        loadCategoriesV2();
    });

    $('#expand-all-v2').click(function() {
        expandAllCategories();
    });

    $('#collapse-all-v2').click(function() {
        collapseAllCategories();
    });

    $('#add-root-category').click(function() {
        resetForm();
        selectedCategory = null;
        updateParentDisplay();
    });

    $('#cancel-btn-v2').click(function() {
        resetForm();
    });

    $('#toggle-drag-mode').click(function() {
        toggleDragMode();
    });

    $('#category-form-v2').submit(function(e) {
        e.preventDefault();
        saveCategory();
    });

    // Form validation - enable/disable submit button
    $('#category-name').on('input', function() {
        validateForm();
    });

    function validateForm() {
        const categoryName = $('#category-name').val().trim();
        const submitBtn = $('#submit-btn-v2');
        
        if (categoryName.length > 0) {
            submitBtn.prop('disabled', false).removeClass('disabled');
        } else {
            submitBtn.prop('disabled', true).addClass('disabled');
        }
    }

    // Keyboard shortcuts
    $(document).keydown(function(e) {
        // F2 - Edit selected category
        if (e.keyCode === 113 && selectedCategory) {
            e.preventDefault();
            editCategory(selectedCategory);
        }
        // Delete key - Delete selected category
        else if (e.keyCode === 46 && selectedCategory) {
            e.preventDefault();
            deleteCategory(selectedCategory);
        }
        // Ctrl+N - Add new root category
        else if (e.ctrlKey && e.keyCode === 78) {
            e.preventDefault();
            resetForm();
            selectedCategory = null;
            updateParentDisplay();
            $('#category-name').focus();
        }
        // Escape - Cancel form/hide context menu
        else if (e.keyCode === 27) {
            hideContextMenu();
            if (isEditMode) {
                resetForm();
            }
        }
    });

    // Load categories
    function loadCategoriesV2() {
        return $.ajax({
            url: '{{ route("category-manager-v2.categories") }}',
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    categories = response.categories;
                    renderCategoryTreeV2();
                    updateStatistics();
                } else {
                    showAlert('Error loading categories: ' + response.message, 'error');
                }
            },
            error: function(xhr) {
                showAlert('Error loading categories', 'error');
            }
        });
    }

    // Render category tree
    function renderCategoryTreeV2() {
        const treeHtml = renderCategoryLevel(categories);
        $('#category-tree-v2').html(treeHtml);
        bindCategoryEvents();
        if (isDragModeEnabled) {
            initializeDragAndDrop();
        }
    }

    // Toggle drag mode
    function toggleDragMode() {
        isDragModeEnabled = !isDragModeEnabled;
        
        if (isDragModeEnabled) {
            $('#drag-mode-text').text('Disable Drag');
            $('#toggle-drag-mode').removeClass('btn-warning').addClass('btn-success');
            $('#category-tree-v2').addClass('drag-mode-enabled');
            initializeDragAndDrop();
            showAlert('Drag & Drop mode enabled. You can now reorder categories by dragging them.', 'success');
        } else {
            $('#drag-mode-text').text('Enable Drag');
            $('#toggle-drag-mode').removeClass('btn-success').addClass('btn-warning');
            $('#category-tree-v2').removeClass('drag-mode-enabled');
            destroyDragAndDrop();
            showAlert('Drag & Drop mode disabled.', 'info');
        }
    }

    // Render category level
    function renderCategoryLevel(cats, level = 1) {
        let html = '';
        
        cats.forEach(category => {
            const hasChildren = category.children && category.children.length > 0;
            const indent = (level - 1) * 25;
            const productCountText = category.product_count > 0 ? `(${category.product_count})` : '';
            
            html += `
                <div class="category-item-v2 sortable-item" data-id="${category.id}" data-level="${level}" data-parent-id="${category.parent_id || 0}" style="margin-left: ${indent}px;">
                    <div class="drag-handle" title="Drag to reorder">
                        <i class="fa fa-grip-vertical"></i>
                    </div>
                    
                    ${hasChildren ? `<button class="category-toggle" data-action="toggle">
                        <i class="fa fa-chevron-right"></i>
                    </button>` : '<span style="width: 24px; display: inline-block;"></span>'}
                    
                    <i class="fa ${hasChildren ? 'fa-folder' : 'fa-file-text-o'} category-folder-icon ${category.product_count > 0 ? 'has-products' : ''}"></i>
                    
                    <div class="category-content">
                        <div class="category-info">
                            <span class="category-name">${category.name}</span>
                            ${category.short_code ? `<span class="category-code">${category.short_code}</span>` : ''}
                            <span class="category-level level-${level}">L${level}</span>
                            ${category.product_count > 0 ? `<span class="category-count">${productCountText}</span>` : ''}
                            ${category.description ? `<div class="category-description">${category.description}</div>` : ''}
                        </div>
                        
                        <div class="category-actions-v2">
                            <button class="btn btn-xs btn-info" data-action="create-product" title="Create Product in this Category">
                                <i class="fa fa-plus-circle"></i>
                            </button>
                            <button class="btn btn-xs btn-primary" data-action="edit" title="Edit Category">
                                <i class="fa fa-edit"></i>
                            </button>
                            <button class="btn btn-xs btn-success" data-action="add-child" title="Add Child Category">
                                <i class="fa fa-plus"></i>
                            </button>
                            <button class="btn btn-xs btn-danger" data-action="delete" title="Delete Category">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
                ${hasChildren ? `<div class="category-children sortable-container collapsed" data-parent="${category.id}">
                    ${renderCategoryLevel(category.children, level + 1)}
                </div>` : ''}
            `;
        });
        
        return html;
    }

    // Bind category events
    function bindCategoryEvents() {
        // Toggle expand/collapse
        $(document).off('click', '.category-toggle').on('click', '.category-toggle', function(e) {
            e.stopPropagation();
            const $toggle = $(this);
            const $item = $toggle.closest('.category-item-v2');
            const categoryId = $item.data('id');
            const $children = $(`.category-children[data-parent="${categoryId}"]`);
            
            if ($children.hasClass('collapsed')) {
                $children.removeClass('collapsed').addClass('expanded');
                $toggle.addClass('expanded');
                $toggle.find('i').removeClass('fa-chevron-right').addClass('fa-chevron-down');
                $item.find('.category-folder-icon').removeClass('fa-folder').addClass('fa-folder-open');
            } else {
                $children.removeClass('expanded').addClass('collapsed');
                $toggle.removeClass('expanded');
                $toggle.find('i').removeClass('fa-chevron-down').addClass('fa-chevron-right');
                $item.find('.category-folder-icon').removeClass('fa-folder-open').addClass('fa-folder');
            }
        });

        // Category selection
        $(document).off('click', '.category-item-v2').on('click', '.category-item-v2', function(e) {
            if ($(e.target).closest('.category-actions-v2, .category-toggle').length) return;
            
            $('.category-item-v2').removeClass('selected');
            $(this).addClass('selected');
            
            const categoryId = $(this).data('id');
            selectedCategory = findCategoryById(categoryId);
            updateParentDisplay();
        });

        // Right-click context menu
        $(document).off('contextmenu', '.category-item-v2').on('contextmenu', '.category-item-v2', function(e) {
            e.preventDefault();
            
            $('.category-item-v2').removeClass('selected');
            $(this).addClass('selected');
            
            const categoryId = $(this).data('id');
            selectedCategory = findCategoryById(categoryId);
            
            showContextMenu(e.pageX, e.pageY);
        });

        // Hide context menu on click outside
        $(document).click(function() {
            hideContextMenu();
        });

        // Context menu item clicks
        $(document).off('click', '.context-menu-list li').on('click', '.context-menu-list li', function(e) {
            e.preventDefault();
            const action = $(this).data('action');
            
            if (selectedCategory && action) {
                switch(action) {
                    case 'create-product':
                        createProductInCategory(selectedCategory);
                        break;
                    case 'edit':
                        editCategory(selectedCategory);
                        break;
                    case 'add-child':
                        addChildCategory(selectedCategory);
                        break;
                    case 'delete':
                        deleteCategory(selectedCategory);
                        break;
                }
            }
            
            hideContextMenu();
        });

        // Action buttons
        $(document).off('click', '[data-action="edit"]').on('click', '[data-action="edit"]', function(e) {
            e.stopPropagation();
            const categoryId = $(this).closest('.category-item-v2').data('id');
            editCategory(findCategoryById(categoryId));
        });

        $(document).off('click', '[data-action="add-child"]').on('click', '[data-action="add-child"]', function(e) {
            e.stopPropagation();
            const categoryId = $(this).closest('.category-item-v2').data('id');
            addChildCategory(findCategoryById(categoryId));
        });

        $(document).off('click', '[data-action="delete"]').on('click', '[data-action="delete"]', function(e) {
            e.stopPropagation();
            const categoryId = $(this).closest('.category-item-v2').data('id');
            deleteCategory(findCategoryById(categoryId));
        });

        $(document).off('click', '[data-action="create-product"]').on('click', '[data-action="create-product"]', function(e) {
            e.stopPropagation();
            const categoryId = $(this).closest('.category-item-v2').data('id');
            const category = findCategoryById(categoryId);
            createProductInCategory(category);
        });
    }

    // Initialize drag and drop functionality
    function initializeDragAndDrop() {
        destroyDragAndDrop(); // Clean up existing instances
        
        // Initialize sortable for root level
        const rootContainer = document.getElementById('category-tree-v2');
        if (rootContainer) {
            const rootSortable = Sortable.create(rootContainer, {
                group: {
                    name: 'categories',
                    pull: true,
                    put: function(to, from, dragEl) {
                        // Allow dropping to root level (moves to L1)
                        return true;
                    }
                },
                animation: 150,
                ghostClass: 'sortable-ghost',
                chosenClass: 'sortable-chosen',
                dragClass: 'sortable-drag',
                handle: '.drag-handle',
                draggable: '.category-item-v2',
                fallbackOnBody: true,
                swapThreshold: 0.65,
                onStart: function(evt) {
                    evt.item.classList.add('dragging');
                    // Create drop zones for categories without children
                    createDropZonesForChildless();
                    console.log('Drag started:', evt.item.dataset.id);
                },
                onEnd: function(evt) {
                    evt.item.classList.remove('dragging');
                    // Remove temporary drop zones
                    removeTemporaryDropZones();
                    handleHierarchicalDragEnd(evt);
                },
                onMove: function(evt) {
                    return checkValidHierarchicalMove(evt);
                }
            });
            sortableInstances.push(rootSortable);
        }
        
        // Initialize sortable for each category children container
        document.querySelectorAll('.category-children').forEach(container => {
            const parentId = container.dataset.parent;
            const parentLevel = getParentLevel(parentId);
            
            const sortable = Sortable.create(container, {
                group: {
                    name: 'categories',
                    pull: true,
                    put: function(to, from, dragEl) {
                        // Check if the move would create a valid hierarchy
                        const draggedId = parseInt(dragEl.dataset.id);
                        const targetParentId = parseInt(parentId);
                        
                        // Prevent circular reference
                        if (isDescendantOf(targetParentId, draggedId)) {
                            return false;
                        }
                        
                        const newLevel = parentLevel + 1;
                        
                        // Allow moving to deeper levels (increase level)
                        // Prevent moving more than 5 levels deep
                        return newLevel <= 5;
                    }
                },
                animation: 150,
                ghostClass: 'sortable-ghost',
                chosenClass: 'sortable-chosen',
                dragClass: 'sortable-drag',
                handle: '.drag-handle',
                draggable: '.category-item-v2',
                fallbackOnBody: true,
                swapThreshold: 0.65,
                onStart: function(evt) {
                    evt.item.classList.add('dragging');
                    createDropZonesForChildless();
                },
                onEnd: function(evt) {
                    evt.item.classList.remove('dragging');
                    removeTemporaryDropZones();
                    handleHierarchicalDragEnd(evt);
                },
                onMove: function(evt) {
                    return checkValidHierarchicalMove(evt);
                }
            });
            sortableInstances.push(sortable);
        });
        
        // Initialize sortable for temporary drop zones
        initializeDropZones();
    }
    
    // Create drop zones for categories without children so they can accept drops
    function createDropZonesForChildless() {
        document.querySelectorAll('.category-item-v2').forEach(categoryElement => {
            const categoryId = categoryElement.dataset.id;
            const hasChildrenContainer = document.querySelector(`.category-children[data-parent="${categoryId}"]`);
            
            // If category doesn't have a children container, create a temporary drop zone
            if (!hasChildrenContainer) {
                const dropZone = document.createElement('div');
                dropZone.className = 'category-children sortable-container temp-drop-zone';
                dropZone.dataset.parent = categoryId;
                dropZone.innerHTML = '<div class="drop-placeholder">Drop here to make child category</div>';
                
                // Insert after the category item
                categoryElement.parentNode.insertBefore(dropZone, categoryElement.nextSibling);
                
                // Make it sortable
                const dropZoneSortable = Sortable.create(dropZone, {
                    group: {
                        name: 'categories',
                        pull: false,
                        put: function(to, from, dragEl) {
                            const draggedId = parseInt(dragEl.dataset.id);
                            const targetParentId = parseInt(categoryId);
                            
                            // Prevent circular reference
                            if (isDescendantOf(targetParentId, draggedId)) {
                                return false;
                            }
                            
                            const parentLevel = getParentLevel(targetParentId);
                            const newLevel = parentLevel + 1;
                            
                            return newLevel <= 5;
                        }
                    },
                    animation: 150,
                    ghostClass: 'sortable-ghost',
                    chosenClass: 'sortable-chosen',
                    handle: '.drag-handle',
                    draggable: '.category-item-v2',
                    onEnd: function(evt) {
                        evt.item.classList.remove('dragging');
                        removeTemporaryDropZones();
                        handleHierarchicalDragEnd(evt);
                    }
                });
                sortableInstances.push(dropZoneSortable);
            }
        });
    }
    
    // Remove temporary drop zones
    function removeTemporaryDropZones() {
        document.querySelectorAll('.temp-drop-zone').forEach(zone => {
            zone.remove();
        });
    }
    
    // Initialize drop zones for existing empty containers
    function initializeDropZones() {
        // This function can be expanded if needed for persistent drop zones
    }

    // Destroy all sortable instances
    function destroyDragAndDrop() {
        sortableInstances.forEach(instance => {
            if (instance && typeof instance.destroy === 'function') {
                instance.destroy();
            }
        });
        sortableInstances = [];
    }

    // Handle hierarchical drag end event
    function handleHierarchicalDragEnd(evt) {
        const draggedId = parseInt(evt.item.dataset.id);
        const draggedElement = evt.item;
        const oldLevel = parseInt(draggedElement.dataset.level);
        const oldParentId = parseInt(draggedElement.dataset.parentId) || 0;
        
        let newParentId = 0;
        let newLevel = 1;
        let movementDirection = 'none';
        
        // Analyze the drop target to determine new hierarchy
        const dropAnalysis = analyzeDropTarget(evt, oldLevel, oldParentId);
        newParentId = dropAnalysis.newParentId;
        newLevel = dropAnalysis.newLevel;
        movementDirection = dropAnalysis.direction;
        
        // Calculate new sort order based on position in the new container
        const newIndex = evt.newIndex;
        
        // Get category details for better feedback
        const draggedCategory = findCategoryById(draggedId);
        const categoryName = draggedCategory ? draggedCategory.name : 'Category';
        
        // Determine movement type for user feedback
        let movementType = 'reorder';
        if (oldParentId !== newParentId || oldLevel !== newLevel) {
            if (newLevel > oldLevel) {
                movementType = 'moved_right'; // Increased level (L2 to L3)
            } else if (newLevel < oldLevel) {
                movementType = 'moved_left'; // Decreased level (L2 to L1)
            } else {
                movementType = 'moved_same_level'; // Same level, different parent
            }
        }
        
        console.log('Enhanced Hierarchical Drag Analysis:', {
            draggedId: draggedId,
            categoryName: categoryName,
            oldLevel: oldLevel,
            newLevel: newLevel,
            oldParentId: oldParentId,
            newParentId: newParentId,
            newIndex: newIndex,
            movementType: movementType,
            movementDirection: movementDirection,
            fromContainer: evt.from.id || evt.from.dataset.parent,
            toContainer: evt.to.id || evt.to.dataset.parent,
            dropAnalysis: dropAnalysis
        });
        
        // Update the element's data attributes immediately for UI consistency
        draggedElement.dataset.level = newLevel;
        draggedElement.dataset.parentId = newParentId;
        
        // Update visual indicators
        updateCategoryLevelVisuals(draggedElement, newLevel);
        
        // Show enhanced user feedback
        showEnhancedMovementFeedback(movementType, oldLevel, newLevel, categoryName, movementDirection);
        
        // Update category hierarchy via AJAX
        updateCategoryHierarchy(draggedId, newParentId, newIndex, newLevel);
    }
    
    // Analyze drop target to determine hierarchy changes
    function analyzeDropTarget(evt, oldLevel, oldParentId) {
        const toContainer = evt.to;
        const fromContainer = evt.from;
        let newParentId = 0;
        let newLevel = 1;
        let direction = 'none';
        
        // Case 1: Dropped into root level (moving LEFT - decrease level)
        if (toContainer.id === 'category-tree-v2') {
            newParentId = 0;
            newLevel = 1;
            if (oldLevel > 1) {
                direction = 'left'; // Moving from deeper level to root
            }
        }
        // Case 2: Dropped into a category's children container (moving RIGHT - increase level)
        else if (toContainer.classList.contains('category-children')) {
            newParentId = parseInt(toContainer.dataset.parent) || 0;
            newLevel = getParentLevel(newParentId) + 1;
            
            // Determine if this is a left or right movement
            if (newLevel > oldLevel) {
                direction = 'right'; // Moving deeper into hierarchy
            } else if (newLevel < oldLevel) {
                direction = 'left'; // Moving up in hierarchy
            } else {
                direction = 'lateral'; // Same level, different parent
            }
        }
        
        // Additional analysis for better UX
        const analysis = {
            newParentId: newParentId,
            newLevel: newLevel,
            direction: direction,
            isLevelChange: newLevel !== oldLevel,
            isParentChange: newParentId !== oldParentId,
            levelDifference: newLevel - oldLevel
        };
        
        return analysis;
    }
    
    // Get parent level for a given parent ID
    function getParentLevel(parentId) {
        if (!parentId || parentId === 0) return 0;
        
        const parentElement = document.querySelector(`[data-id="${parentId}"]`);
        if (parentElement) {
            return parseInt(parentElement.dataset.level) || 0;
        }
        
        // Fallback: find in categories data
        const parent = findCategoryById(parentId);
        return parent ? (parent.level || 1) : 0;
    }
    
    // Check if hierarchical move is valid
    function checkValidHierarchicalMove(evt) {
        const draggedElement = evt.dragged;
        const targetContainer = evt.to;
        const draggedId = parseInt(draggedElement.dataset.id);
        
        // Prevent dropping a category into its own children (would create circular reference)
        if (targetContainer.classList.contains('category-children')) {
            const targetParentId = parseInt(targetContainer.dataset.parent);
            if (isDescendantOf(targetParentId, draggedId)) {
                console.log('Prevented circular reference move');
                return false;
            }
        }
        
        // Check maximum depth limit
        let newLevel = 1;
        if (targetContainer.classList.contains('category-children')) {
            const targetParentId = parseInt(targetContainer.dataset.parent);
            newLevel = getParentLevel(targetParentId) + 1;
        }
        
        if (newLevel > 5) {
            console.log('Prevented move: maximum depth exceeded');
            return false;
        }
        
        return true;
    }
    
    // Check if targetId is a descendant of ancestorId
    function isDescendantOf(targetId, ancestorId) {
        const target = findCategoryById(targetId);
        if (!target) return false;
        
        let current = target;
        while (current && current.parent_id) {
            if (current.parent_id === ancestorId) {
                return true;
            }
            current = findCategoryById(current.parent_id);
        }
        return false;
    }
    
    // Update visual indicators for category level
    function updateCategoryLevelVisuals(element, newLevel) {
        // Update level badge
        const levelBadge = element.querySelector('.category-level');
        if (levelBadge) {
            levelBadge.textContent = `L${newLevel}`;
            levelBadge.className = `category-level level-${newLevel}`;
        }
        
        // Update indentation if needed (this will be handled by re-rendering)
        // The visual update will happen when the tree is refreshed
    }
    
    // Show enhanced movement feedback to user with specific use case examples
    function showEnhancedMovementFeedback(movementType, oldLevel, newLevel, categoryName = '', movementDirection = '') {
        let message = '';
        let alertType = 'info';
        let useCaseExample = '';
        
        switch(movementType) {
            case 'moved_right':
                message = `📈 Category moved deeper: L${oldLevel} → L${newLevel} (moved right)`;
                useCaseExample = categoryName ? 
                    `Example: "${categoryName}" changed from L${oldLevel} to L${newLevel}` : 
                    `Use case: airlesssub2 L${oldLevel} → airlesssub2 L${newLevel}`;
                alertType = 'success';
                break;
            case 'moved_left':
                message = `📉 Category moved up: L${oldLevel} → L${newLevel} (moved left)`;
                useCaseExample = categoryName ? 
                    `Example: "${categoryName}" changed from L${oldLevel} to L${newLevel}` : 
                    `Use case: airlesssub1 L${oldLevel} → airlesssub1 L${newLevel}`;
                alertType = 'info';
                break;
            case 'moved_same_level':
                message = `↔️ Category moved to different parent (same level: L${newLevel})`;
                useCaseExample = `Category reorganized within level ${newLevel}`;
                alertType = 'info';
                break;
            case 'reorder':
                message = `🔄 Category reordered within same level (L${newLevel})`;
                useCaseExample = `Position changed within level ${newLevel}`;
                alertType = 'success';
                break;
        }
        
        if (message) {
            const directionInfo = movementDirection ? ` (Direction: ${movementDirection})` : '';
            const fullMessage = useCaseExample ? `${message}${directionInfo}\n${useCaseExample}` : `${message}${directionInfo}`;
            showAlert(fullMessage, alertType);
        }
    }

    // Update category hierarchy on server
    function updateCategoryHierarchy(categoryId, newParentId, sortOrder, newLevel) {
        const updateData = {
            categories: [{
                id: categoryId,
                parent_id: newParentId,
                sort_order: sortOrder,
                level: newLevel
            }],
            _token: '{{ csrf_token() }}'
        };
        
        console.log('Updating hierarchy:', updateData);
        
        $.ajax({
            url: '{{ route("category-manager-v2.update-order") }}',
            method: 'POST',
            data: updateData,
            success: function(response) {
                if (response.success) {
                    const levelInfo = newLevel ? ` (Level ${newLevel})` : '';
                    showAlert(`Category moved successfully!${levelInfo} ${response.message}`, 'success');
                    // Update local data without full reload to preserve expanded state
                    updateLocalCategoryData(categoryId, newParentId, newLevel);
                    updateStatistics();
                    
                    // Refresh the tree to show correct hierarchy and indentation
                    setTimeout(() => {
                        const expandedCategories = getExpandedCategories();
                        loadCategoriesV2().then(() => {
                            restoreExpandedCategories(expandedCategories);
                        });
                    }, 500);
                } else {
                    showAlert('Error updating category order: ' + response.message, 'error');
                    // Reload to restore original state only on error
                    loadCategoriesV2();
                }
            },
            error: function(xhr) {
                console.error('AJAX Error:', xhr);
                showAlert('Error updating category order. Changes may not be saved.', 'error');
                // Reload to restore original state only on error
                loadCategoriesV2();
            }
        });
    }

    // Update local category data without full reload
    function updateLocalCategoryData(categoryId, newParentId, newLevel) {
        // Find and update the category in the local data structure
        function updateCategoryInTree(cats) {
            for (let i = 0; i < cats.length; i++) {
                if (cats[i].id == categoryId) {
                    cats[i].parent_id = newParentId;
                    if (newLevel) {
                        cats[i].level = newLevel;
                    }
                    return true;
                }
                if (cats[i].children && updateCategoryInTree(cats[i].children)) {
                    return true;
                }
            }
            return false;
        }
        
        updateCategoryInTree(categories);
        console.log('Local category data updated for ID:', categoryId, 'New level:', newLevel);
    }

    // Get currently expanded categories
    function getExpandedCategories() {
        const expanded = [];
        $('.category-children.expanded').each(function() {
            const parentId = $(this).data('parent');
            if (parentId) {
                expanded.push(parentId);
            }
        });
        return expanded;
    }

    // Restore expanded state for categories
    function restoreExpandedCategories(expandedIds) {
        expandedIds.forEach(parentId => {
            const $children = $(`.category-children[data-parent="${parentId}"]`);
            const $item = $(`.category-item-v2[data-id="${parentId}"]`);
            const $toggle = $item.find('.category-toggle');
            
            if ($children.length && $toggle.length) {
                $children.removeClass('collapsed').addClass('expanded');
                $toggle.addClass('expanded');
                $toggle.find('i').removeClass('fa-chevron-right').addClass('fa-chevron-down');
                $item.find('.category-folder-icon').removeClass('fa-folder').addClass('fa-folder-open');
            }
        });
    }

    // Find category by ID
    function findCategoryById(id, cats = categories) {
        for (let category of cats) {
            if (category.id == id) return category;
            if (category.children) {
                const found = findCategoryById(id, category.children);
                if (found) return found;
            }
        }
        return null;
    }

    // Edit category
    function editCategory(category) {
        if (!category) return;
        
        isEditMode = true;
        $('#form-title').text('Edit Category');
        $('#category-id').val(category.id);
        $('#category-name').val(category.name);
        $('#category-code').val(category.short_code || '');
        $('#category-description').val(category.description || '');
        $('#parent-id').val(category.parent_id || 0);
        $('#submit-btn-v2').html('<i class="fa fa-save"></i> Update Category');
        
        updateParentDisplay();
        // Validate form after loading data
        validateForm();
    }

    // Add child category
    function addChildCategory(parentCategory) {
        if (!parentCategory) return;
        
        resetForm();
        selectedCategory = parentCategory;
        $('#parent-id').val(parentCategory.id);
        $('#form-title').text('Add Child Category');
        updateParentDisplay();
        // Focus on name field for immediate typing
        $('#category-name').focus();
    }

    // Delete category
    function deleteCategory(category) {
        if (!category) return;
        
        if (!confirm(`Are you sure you want to delete "${category.name}"?`)) return;
        
        $.ajax({
            url: '{{ route("category-manager-v2.destroy", ":id") }}'.replace(':id', category.id),
            method: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function(response) {
                showAlert(response.message, response.success ? 'success' : 'error');
                if (response.success) {
                    // Store current expanded state before reload
                    const expandedCategories = getExpandedCategories();
                    loadCategoriesV2();
                    // Restore expanded state after a brief delay
                    setTimeout(() => {
                        restoreExpandedCategories(expandedCategories);
                    }, 300);
                    resetForm();
                }
            },
            error: function(xhr) {
                showAlert('Error deleting category', 'error');
            }
        });
    }

    // Create product in category
    function createProductInCategory(category) {
        const categoryLevel = category.level || 'Unknown';
        const levelText = `L${categoryLevel}`;
        
        const confirmMessage = `Create a new product in:\n\n📁 ${category.name} (${levelText})\n\nThis will open the product creation page with this category pre-selected.`;
        
        if (!confirm(confirmMessage)) return;
        
        const createProductUrl = '{{ url("/products/create") }}' + '?category_id=' + category.id;
        showAlert(` Opening product creation for "${category.name}" (${levelText})...`, 'success');
        window.open(createProductUrl, '_blank');
    }

    // Save category
    function saveCategory() {
        const formData = {
            name: $('#category-name').val(),
            short_code: $('#category-code').val(),
            parent_id: $('#parent-id').val(),
            description: $('#category-description').val(),
            _token: '{{ csrf_token() }}'
        };

        const url = isEditMode 
            ? '{{ route("category-manager-v2.update", ":id") }}'.replace(':id', $('#category-id').val())
            : '{{ route("category-manager-v2.store") }}';
        
        const method = isEditMode ? 'PUT' : 'POST';

        $.ajax({
            url: url,
            method: method,
            data: formData,
            success: function(response) {
                showAlert(response.message, response.success ? 'success' : 'error');
                if (response.success) {
                    // Store current expanded state before reload
                    const expandedCategories = getExpandedCategories();
                    loadCategoriesV2();
                    // Restore expanded state after a brief delay
                    setTimeout(() => {
                        restoreExpandedCategories(expandedCategories);
                    }, 300);
                    resetForm();
                }
            },
            error: function(xhr) {
                showAlert('Error saving category', 'error');
            }
        });
    }

    // Reset form
    function resetForm() {
        isEditMode = false;
        $('#form-title').text('Add New Category');
        $('#category-form-v2')[0].reset();
        $('#category-id').val('');
        $('#parent-id').val(selectedCategory ? selectedCategory.id : 0);
        $('#submit-btn-v2').html('<i class="fa fa-save"></i> Save Category');
        $('.category-item-v2').removeClass('selected');
        
        // Re-validate form after reset
        validateForm();
    }

    // Update parent display
    function updateParentDisplay() {
        if (selectedCategory) {
            const path = getCategoryPath(selectedCategory);
            $('#parent-display').html(`<i class="fa fa-folder text-primary"></i> ${path}`);
        } else {
            $('#parent-display').html('<i class="fa fa-home text-muted"></i> Root Level');
        }
    }

    // Get category path
    function getCategoryPath(category) {
        const path = [category.name];
        let current = category;
        
        while (current.parent_id > 0) {
            const parent = findCategoryById(current.parent_id);
            if (parent) {
                path.unshift(parent.name);
                current = parent;
            } else break;
        }
        
        return path.join(' → ');
    }

    // Expand all categories
    function expandAllCategories() {
        $('.category-children').removeClass('collapsed').addClass('expanded');
        $('.category-toggle').addClass('expanded');
        $('.category-toggle i').removeClass('fa-chevron-right').addClass('fa-chevron-down');
        $('.category-folder-icon').removeClass('fa-folder').addClass('fa-folder-open');
    }

    // Collapse all categories
    function collapseAllCategories() {
        $('.category-children').removeClass('expanded').addClass('collapsed');
        $('.category-toggle').removeClass('expanded');
        $('.category-toggle i').removeClass('fa-chevron-down').addClass('fa-chevron-right');
        $('.category-folder-icon').removeClass('fa-folder-open').addClass('fa-folder');
    }

    // Update statistics
    function updateStatistics() {
        const totalCategories = countAllCategories(categories);
        const rootCategories = categories.length;
        const maxDepth = findMaxDepth(categories);

        $('#total-categories').text(totalCategories);
        $('#root-categories').text(rootCategories);
        $('#max-depth').text(maxDepth);
    }

    // Count all categories
    function countAllCategories(cats) {
        let count = cats.length;
        cats.forEach(cat => {
            if (cat.children) {
                count += countAllCategories(cat.children);
            }
        });
        return count;
    }

    // Find max depth
    function findMaxDepth(cats, currentDepth = 1) {
        let maxDepth = currentDepth;
        cats.forEach(cat => {
            if (cat.children && cat.children.length > 0) {
                maxDepth = Math.max(maxDepth, findMaxDepth(cat.children, currentDepth + 1));
            }
        });
        return maxDepth;
    }

    // Show context menu
    function showContextMenu(x, y) {
        const $menu = $('#context-menu');
        $menu.css({
            left: x + 'px',
            top: y + 'px',
            display: 'block'
        });
        
        // Adjust position if menu goes off screen
        const menuWidth = $menu.outerWidth();
        const menuHeight = $menu.outerHeight();
        const windowWidth = $(window).width();
        const windowHeight = $(window).height();
        
        if (x + menuWidth > windowWidth) {
            $menu.css('left', (x - menuWidth) + 'px');
        }
        
        if (y + menuHeight > windowHeight) {
            $menu.css('top', (y - menuHeight) + 'px');
        }
    }

    // Hide context menu
    function hideContextMenu() {
        $('#context-menu').hide();
    }

    // Show alert
    function showAlert(message, type) {
        let alertClass = 'alert-info'; // default
        let iconClass = 'fa-info-circle';
        
        switch(type) {
            case 'success':
                alertClass = 'alert-success';
                iconClass = 'fa-check-circle';
                break;
            case 'error':
            case 'danger':
                alertClass = 'alert-danger';
                iconClass = 'fa-exclamation-circle';
                break;
            case 'warning':
                alertClass = 'alert-warning';
                iconClass = 'fa-warning';
                break;
            case 'info':
                alertClass = 'alert-info';
                iconClass = 'fa-info-circle';
                break;
        }
        
        const alertHtml = `
            <div class="alert ${alertClass} alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <i class="fa ${iconClass}"></i> ${message}
            </div>
        `;
        $('.content').prepend(alertHtml);
        setTimeout(() => {
            $('.alert').fadeOut();
        }, 4000); // Increased timeout for hierarchical messages
    }
});
</script>
@endsection
