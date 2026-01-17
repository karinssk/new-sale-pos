<div class="category-item sortable-item" 
     data-id="{{ $category->id }}" 
     data-category-id="{{ $category->id }}" 
     data-parent-id="{{ $category->parent_id ?? 0 }}"
     data-level="{{ $level + 1 }}"
     style="margin-left: {{ $level * 20 }}px;">
    
    <div class="category-node" data-category-id="{{ $category->id }}">
        <!-- Drag Handle -->
        <div class="drag-handle" title="Drag to reorder">
            <i class="fa fa-grip-vertical"></i>
        </div>
        
        <!-- Expand/Collapse Toggle -->
        <div class="category-toggle {{ (isset($category->children) && count($category->children) > 0) ? '' : 'no-children' }}">
            @if(isset($category->children) && count($category->children) > 0)
                <i class="fa fa-chevron-right"></i>
            @else
                <span style="width: 24px; display: inline-block;"></span>
            @endif
        </div>
        
        <!-- Category Icon with Status -->
        <div class="category-icon">
            @if($level == 0)
                <i class="fa fa-folder-open text-blue-600"></i>
            @elseif(isset($category->children) && count($category->children) > 0)
                <i class="fa fa-folder text-yellow-600"></i>
            @else
                <i class="fa fa-file-o text-gray-500"></i>
            @endif
        </div>
        
        <!-- Category Information -->
        <div class="category-info">
            <div class="category-name">{{ $category->name }}</div>
            @if($category->short_code)
                <div class="category-code">{{ $category->short_code }}</div>
            @endif
            <!-- Level Badge -->
            <span class="category-level level-{{ $level + 1 }}">L{{ $level + 1 }}</span>
        </div>
        
        <!-- Category Stats -->
        <div class="category-stats">
            @if($category->products_count > 0)
                <div class="category-count products-count" title="Products in this category">
                    <i class="fa fa-cube"></i>
                    {{ $category->products_count }}
                </div>
            @endif
            
            @if(isset($category->children) && count($category->children) > 0)
                <div class="category-count children-count" title="Subcategories">
                    <i class="fa fa-folder"></i>
                    {{ count($category->children) }}
                </div>
            @endif
        </div>
        
        <!-- Action Buttons -->
        <div class="category-actions">
            <button class="btn btn-xs btn-info" title="View Products" onclick="loadCategoryProducts({{ $category->id }}, '{{ addslashes($category->name) }}')">
                <i class="fa fa-eye"></i>
            </button>
            <button class="btn btn-xs btn-primary" title="Edit Category" data-category-id="{{ $category->id }}">
                <i class="fa fa-edit"></i>
            </button>
            <button class="btn btn-xs btn-success" title="Add Subcategory" data-parent-id="{{ $category->id }}">
                <i class="fa fa-plus"></i>
            </button>
        </div>
    </div>
    
    <!-- Children Container -->
    @if(isset($category->children) && count($category->children) > 0)
        <div class="category-children expanded sortable-container" data-parent="{{ $category->id }}">
            @foreach($category->children as $child)
                @include('category_tree.partials.category_node', ['category' => $child, 'level' => $level + 1])
            @endforeach
        </div>
    @endif
</div>