<?php

namespace App\Http\Controllers;

use App\Category;
use App\Utils\ModuleUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryManagerController extends Controller
{
    /**
     * All Utils instance.
     */
    protected $moduleUtil;

    /**
     * Constructor
     */
    public function __construct(ModuleUtil $moduleUtil)
    {
        $this->moduleUtil = $moduleUtil;
    }

    /**
     * Display the category management page
     */
    public function index()
    {
        if (!auth()->user()->can('category.view') && !auth()->user()->can('category.create')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = request()->session()->get('user.business_id');
        
        // Get all categories in tree structure
        $categories = $this->getCategoryTree($business_id);
        
        return view('category_manager.index', compact('categories'));
    }

    /**
     * Get categories in tree structure
     */
    private function getCategoryTree($business_id, $parent_id = 0)
    {
        $categories = Category::where('business_id', $business_id)
            ->where('category_type', 'product')
            ->where('parent_id', $parent_id)
            ->orderBy('name')
            ->get();

        foreach ($categories as $category) {
            $category->children = $this->getCategoryTree($business_id, $category->id);
            $category->product_count = $category->products()->count();
        }

        return $categories;
    }

    /**
     * Get categories as JSON for tree view
     */
    public function getCategoriesJson()
    {
        if (!request()->ajax()) {
            abort(404);
        }

        $business_id = request()->session()->get('user.business_id');
        $categories = $this->getCategoryTree($business_id);
        
        return response()->json($this->formatCategoriesForTree($categories));
    }

    /**
     * Format categories for tree view component
     */
    private function formatCategoriesForTree($categories, $level = 1)
    {
        $formatted = [];
        
        foreach ($categories as $category) {
            $formatted[] = [
                'id' => $category->id,
                'name' => $category->name,
                'short_code' => $category->short_code,
                'description' => $category->description,
                'parent_id' => $category->parent_id,
                'level' => $level,
                'product_count' => $category->product_count,
                'has_children' => $category->children->count() > 0,
                'children' => $this->formatCategoriesForTree($category->children, $level + 1)
            ];
        }
        
        return $formatted;
    }

    /**
     * Create new category
     */
    public function store(Request $request)
    {
        if (!auth()->user()->can('category.create')) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'short_code' => 'required|string|max:10',
            'parent_id' => 'nullable|integer|exists:categories,id'
        ]);

        try {
            $business_id = request()->session()->get('user.business_id');
            
            $category = Category::create([
                'name' => $request->name,
                'short_code' => $request->short_code,
                'description' => $request->description,
                'parent_id' => $request->parent_id ?? 0,
                'business_id' => $business_id,
                'category_type' => 'product',
                'created_by' => auth()->id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Category created successfully',
                'category' => $category
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating category: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update category order (drag and drop)
     */
    public function updateOrder(Request $request)
    {
        if (!auth()->user()->can('category.update')) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $business_id = request()->session()->get('user.business_id');
            $categories = $request->input('categories');

            DB::beginTransaction();

            foreach ($categories as $categoryData) {
                Category::where('id', $categoryData['id'])
                    ->where('business_id', $business_id)
                    ->update([
                        'parent_id' => $categoryData['parent_id'] ?? 0
                    ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Category order updated successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Error updating category order: ' . $e->getMessage()
            ], 500);
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

        $request->validate([
            'name' => 'required|string|max:255',
            'short_code' => 'required|string|max:10',
            'parent_id' => 'nullable|integer|exists:categories,id'
        ]);

        try {
            $business_id = request()->session()->get('user.business_id');
            
            $category = Category::where('id', $id)
                ->where('business_id', $business_id)
                ->firstOrFail();

            $category->update([
                'name' => $request->name,
                'short_code' => $request->short_code,
                'description' => $request->description,
                'parent_id' => $request->parent_id ?? 0
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Category updated successfully',
                'category' => $category
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating category: ' . $e->getMessage()
            ], 500);
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
            
            $category = Category::where('id', $id)
                ->where('business_id', $business_id)
                ->firstOrFail();

            // Check if category has children
            if ($category->children()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete category with subcategories. Please delete subcategories first.'
                ], 400);
            }

            // Check if category has products
            if ($category->products()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete category with products. Please move products to another category first.'
                ], 400);
            }

            $category->delete();

            return response()->json([
                'success' => true,
                'message' => 'Category deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting category: ' . $e->getMessage()
            ], 500);
        }
    }
}
