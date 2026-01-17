<?php

// Test routes for multi-level categories (no authentication required)
Route::get('/test-multilevel-categories', function() {
    $business_id = 1; // Default business ID
    
    // Get L1 categories
    $l1_categories = \App\Category::where('business_id', $business_id)
        ->where('parent_id', 0)
        ->where('category_type', 'product')
        ->orderBy('name')
        ->get();
    
    $result = [
        'status' => 'success',
        'message' => 'Multi-level category system is working!',
        'database_columns_added' => [
            'category_l1_id',
            'category_l2_id', 
            'category_l3_id',
            'category_l4_id',
            'category_l5_id'
        ],
        'l1_categories_count' => $l1_categories->count(),
        'l1_categories' => $l1_categories->map(function($cat) {
            return [
                'id' => $cat->id,
                'name' => $cat->name,
                'children_count' => \App\Category::where('parent_id', $cat->id)->count()
            ];
        }),
        'sample_category_hierarchy' => []
    ];
    
    // Get a sample hierarchy
    if ($l1_categories->count() > 0) {
        $electronics = $l1_categories->first();
        $l2_categories = \App\Category::where('parent_id', $electronics->id)->get();
        
        if ($l2_categories->count() > 0) {
            $computers = $l2_categories->first();
            $l3_categories = \App\Category::where('parent_id', $computers->id)->get();
            
            if ($l3_categories->count() > 0) {
                $laptops = $l3_categories->first();
                $l4_categories = \App\Category::where('parent_id', $laptops->id)->get();
                
                if ($l4_categories->count() > 0) {
                    $gaming = $l4_categories->first();
                    $l5_categories = \App\Category::where('parent_id', $gaming->id)->get();
                    
                    $result['sample_category_hierarchy'] = [
                        'L1' => $electronics->name,
                        'L2' => $computers->name,
                        'L3' => $laptops->name,
                        'L4' => $gaming->name,
                        'L5' => $l5_categories->count() > 0 ? $l5_categories->first()->name : 'None',
                        'full_path' => $electronics->name . ' > ' . $computers->name . ' > ' . $laptops->name . ' > ' . $gaming->name . ($l5_categories->count() > 0 ? ' > ' . $l5_categories->first()->name : '')
                    ];
                }
            }
        }
    }
    
    // Check if products table has the new columns
    try {
        $columns = DB::select('DESCRIBE products');
        $multilevel_columns = [];
        foreach($columns as $column) {
            if(strpos($column->Field, 'category_l') !== false) {
                $multilevel_columns[] = $column->Field;
            }
        }
        $result['products_table_columns'] = $multilevel_columns;
    } catch (Exception $e) {
        $result['products_table_error'] = $e->getMessage();
    }
    
    return response()->json($result, 200, [], JSON_PRETTY_PRINT);
});

Route::get('/test-category-api/{parent_id?}', function($parent_id = 0) {
    $business_id = 1;
    
    $categories = \App\Category::where('business_id', $business_id)
        ->where('parent_id', $parent_id)
        ->where('category_type', 'product')
        ->select('id', 'name', 'parent_id')
        ->orderBy('name')
        ->get();

    return response()->json([
        'success' => true,
        'parent_id' => $parent_id,
        'categories' => $categories,
        'count' => $categories->count()
    ], 200, [], JSON_PRETTY_PRINT);
});

// Test the getProductsBySubcategory method directly
Route::get('/test-products-by-subcategory', function() {
    try {
        $business_id = 1; // Default business ID
        $category_id = 13;
        
        // Test the logic directly without session dependency
        $query = \App\Product::with([
            'categoryL1', 'categoryL2', 'categoryL3', 'categoryL4', 'categoryL5',
            'brand', 'unit', 'variations'
        ])
        ->where('business_id', $business_id)
        ->where('is_inactive', 0);

        // Apply category filter
        $query->where(function($q) use ($category_id) {
            $q->where('category_l1_id', $category_id)
              ->orWhere('category_l2_id', $category_id)
              ->orWhere('category_l3_id', $category_id)
              ->orWhere('category_l4_id', $category_id)
              ->orWhere('category_l5_id', $category_id)
              ->orWhere('category_id', $category_id); // Legacy category field
        });

        $products = $query->orderBy('created_at', 'desc')->get();

        // Group by category
        $grouped = $products->groupBy(function($product) {
            return $product->getCategoryPath() ?: 'Uncategorized';
        });

        return response()->json([
            'success' => true,
            'message' => 'Products fetched by subcategory successfully',
            'category_id' => $category_id,
            'business_id' => $business_id,
            'grouped_products' => $grouped,
            'total_count' => $products->count()
        ], 200, [], JSON_PRETTY_PRINT);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error testing getProductsBySubcategory: ' . $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500, [], JSON_PRETTY_PRINT);
    }
});