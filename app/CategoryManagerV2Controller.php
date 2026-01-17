<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryManagerV2Controller extends Controller
{
    /**
     * Display the category manager V2 page
     */
    public function index()
    {
        if (!auth()->user()->can('category.view')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = request()->session()->get('user.business_id');

        return view('category_manager_v2.index');
    }

    /**
     * Get categories in JSON format for tree view
     */
    public function getCategoriesJson()
    {
        try {
            $business_id = request()->session()->get('user.business_id');
            
            $categories = Category::where('business_id', $business_id)
                        ->where('category_type', 'product')
                        ->whereNull('deleted_at')
                        ->select(['id', 'name', 'short_code', 'parent_id', 'description'])
                        ->withCount('products')
                        ->orderBy('parent_id')
                        ->orderBy('name')
                        ->get();

            $formatted_categories = $this->formatCategoriesForTreeV2($categories);

            return response()->json([
                'success' => true,
                'categories' => $formatted_categories
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error loading categories: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Store a new category
     */
    public function store(Request $request)
    {
        if (!auth()->user()->can('category.create')) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $business_id = $request->session()->get('user.business_id');

            $request->validate([
                'name' => 'required|string|max:255',
                'short_code' => 'nullable|string|max:10',
                'parent_id' => 'nullable|integer|exists:categories,id',
                'description' => 'nullable|string|max:500'
            ]);

            $category = new Category();
            $category->name = $request->name;
            $category->short_code = $request->short_code;
            $category->parent_id = $request->parent_id ?: 0;
            $category->business_id = $business_id;
            $category->category_type = 'product';
            $category->description = $request->description;
            $category->created_by = auth()->user()->id;
            $category->save();

            return response()->json([
                'success' => true,
                'message' => 'Category created successfully!',
                'category' => $category
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating category: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Update category
     */
    public function update(Request $request, $id)
    {
        if (!auth()->user()->can('category.update')) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $business_id = $request->session()->get('user.business_id');
            
            $category = Category::where('business_id', $business_id)->findOrFail($id);

            $request->validate([
                'name' => 'required|string|max:255',
                'short_code' => 'nullable|string|max:10',
                'parent_id' => 'nullable|integer|exists:categories,id',
                'description' => 'nullable|string|max:500'
            ]);

            $category->name = $request->name;
            $category->short_code = $request->short_code;
            $category->parent_id = $request->parent_id ?: 0;
            $category->description = $request->description;
            $category->save();

            return response()->json([
                'success' => true,
                'message' => 'Category updated successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating category: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Delete category
     */
    public function destroy($id)
    {
        if (!auth()->user()->can('category.delete')) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $business_id = request()->session()->get('user.business_id');
            
            $category = Category::where('business_id', $business_id)->findOrFail($id);

            // Check if category has products
            if ($category->products()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete category that has products assigned to it.'
                ]);
            }

            // Check if category has children
            if (Category::where('parent_id', $id)->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete category that has sub-categories.'
                ]);
            }

            $category->delete();

            return response()->json([
                'success' => true,
                'message' => 'Category deleted successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting category: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Update category order and hierarchy
     */
    public function updateOrder(Request $request)
    {
        try {
            $business_id = $request->session()->get('user.business_id');
            $categories = $request->categories;
            
            if (empty($categories)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No categories data provided'
                ]);
            }

            $updated_count = 0;
            $details = [];
            
            foreach ($categories as $categoryData) {
                $category = Category::where('business_id', $business_id)
                                  ->where('id', $categoryData['id'])
                                  ->first();
                
                if ($category) {
                    $old_parent_id = $category->parent_id;
                    $new_parent_id = $categoryData['parent_id'] ?? 0;
                    
                    // Update the category
                    $category->parent_id = $new_parent_id;
                    $category->save();
                    
                    $updated_count++;
                    
                    // Track movement details for feedback
                    if ($old_parent_id != $new_parent_id) {
                        $old_parent_name = $old_parent_id > 0 ? 
                            Category::find($old_parent_id)->name ?? 'Unknown' : 'Root';
                        $new_parent_name = $new_parent_id > 0 ? 
                            Category::find($new_parent_id)->name ?? 'Unknown' : 'Root';
                            
                        $details[] = "'{$category->name}' moved from '{$old_parent_name}' to '{$new_parent_name}'";
                    }
                }
            }

            $message = "Updated {$updated_count} category(ies)";
            if (!empty($details)) {
                $message .= ": " . implode(', ', $details);
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'updated_count' => $updated_count,
                'details' => $details
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating order: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Format categories for tree view (V2 style)
     */
    private function formatCategoriesForTreeV2($categories, $parent_id = 0, $level = 1)
    {
        $result = [];
        
        foreach ($categories as $category) {
            if ($category->parent_id == $parent_id) {
                $children = $this->formatCategoriesForTreeV2($categories, $category->id, $level + 1);
                
                $result[] = [
                    'id' => $category->id,
                    'name' => $category->name,
                    'short_code' => $category->short_code,
                    'parent_id' => $category->parent_id,
                    'description' => $category->description,
                    'product_count' => $category->products_count,
                    'level' => $level,
                    'has_children' => count($children) > 0,
                    'children' => $children,
                    'expanded' => false
                ];
            }
        }
        
        return $result;
    }
}
