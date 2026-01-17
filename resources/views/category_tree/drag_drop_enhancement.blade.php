{{-- Add this to the end of your category_tree/index.blade.php file before @endsection --}}

{{-- Drag and Drop Enhancement --}}
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

<script>
// Drag and Drop Enhancement
$(document).ready(function() {
    // Initialize drag and drop for category tree
    initializeDragAndDrop();
    
    function initializeDragAndDrop() {
        // Make the main categories tree sortable
        const categoriesTree = document.getElementById('categories-tree');
        
        if (categoriesTree) {
            new Sortable(categoriesTree, {
                group: 'categories',
                animation: 150,
                fallbackOnBody: true,
                swapThreshold: 0.65,
                ghostClass: 'sortable-ghost',
                chosenClass: 'sortable-chosen',
                dragClass: 'sortable-drag',
                onEnd: function(evt) {
                    updateCategoryOrder();
                }
            });
        }
        
        // Make subcategory containers sortable
        $('.category-children').each(function() {
            new Sortable(this, {
                group: 'categories',
                animation: 150,
                fallbackOnBody: true,
                swapThreshold: 0.65,
                ghostClass: 'sortable-ghost',
                chosenClass: 'sortable-chosen',
                dragClass: 'sortable-drag',
                onEnd: function(evt) {
                    updateCategoryOrder();
                }
            });
        });
    }
    
    function updateCategoryOrder() {
        const categories = [];
        
        // Collect category order data
        function collectCategoryData(container, parentId = 0) {
            $(container).children('.category-item').each(function(index) {
                const categoryId = $(this).data('category-id');
                categories.push({
                    id: categoryId,
                    parent_id: parentId,
                    sort_order: index
                });
                
                // Recursively collect children
                const childrenContainer = $(this).find('> .category-children')[0];
                if (childrenContainer) {
                    collectCategoryData(childrenContainer, categoryId);
                }
            });
        }
        
        collectCategoryData('#categories-tree');
        
        // Send update to server
        $.ajax({
            url: '{{ route("category-tree.update-order") }}',
            method: 'POST',
            data: {
                categories: categories,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    toastr.success('Category order updated successfully');
                } else {
                    toastr.error(response.message || 'Failed to update category order');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error updating category order:', error);
                toastr.error('Failed to update category order');
            }
        });
    }
});
</script>

<style>
/* Drag and Drop Styles */
.sortable-ghost {
    opacity: 0.4;
    background: #f3f4f6;
}

.sortable-chosen {
    background: #e0e7ff;
}

.sortable-drag {
    background: white;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    transform: rotate(2deg);
}

.category-node {
    position: relative;
}

.category-node::before {
    content: '⋮⋮';
    position: absolute;
    left: -15px;
    top: 50%;
    transform: translateY(-50%);
    color: #d1d5db;
    font-size: 12px;
    opacity: 0;
    transition: opacity 0.2s;
    cursor: grab;
}

.category-node:hover::before {
    opacity: 1;
}

.category-node:active::before {
    cursor: grabbing;
}

/* Drag handle for better UX */
.drag-handle {
    position: absolute;
    left: -20px;
    top: 50%;
    transform: translateY(-50%);
    color: #9ca3af;
    cursor: grab;
    padding: 5px;
    opacity: 0;
    transition: opacity 0.2s;
}

.category-item:hover .drag-handle {
    opacity: 1;
}

.drag-handle:active {
    cursor: grabbing;
}
</style>