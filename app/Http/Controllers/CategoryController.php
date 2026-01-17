<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Get categories by parent ID
     */
    public function getCategoriesByParent(Request $request)
    {
        $parent_id = $request->get('parent_id', 0);
        $type = $request->get('type', 'product');
        $business_id = $request->session()->get('user.business_id');

        $categories = Category::where('business_id', $business_id)
            ->where('parent_id', $parent_id)
            ->where('category_type', $type)
            ->select('id', 'name', 'parent_id')
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'categories' => $categories
        ]);
    }

    /**
     * Get categories by level
     */
    public function getCategoriesByLevel(Request $request)
    {
        $level = $request->get('level', 1);
        $parent_id = $request->get('parent_id', 0);
        $type = $request->get('type', 'product');
        $business_id = $request->session()->get('user.business_id');

        // For level 1, parent_id should be 0
        if ($level == 1) {
            $parent_id = 0;
        }

        $categories = Category::where('business_id', $business_id)
            ->where('parent_id', $parent_id)
            ->where('category_type', $type)
            ->select('id', 'name', 'parent_id')
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'categories' => $categories
        ]);
    }

    /**
     * Get category path
     */
    public function getCategoryPath(Request $request)
    {
        $category_id = $request->get('category_id');
        $business_id = $request->session()->get('user.business_id');

        if (!$category_id) {
            return response()->json([
                'success' => false,
                'message' => 'Category ID is required'
            ]);
        }

        $path = [];
        $current_category = Category::where('business_id', $business_id)
            ->where('id', $category_id)
            ->first();

        if (!$current_category) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found'
            ]);
        }

        // Build path from current category to root
        while ($current_category) {
            array_unshift($path, [
                'id' => $current_category->id,
                'name' => $current_category->name,
                'level' => $this->getCategoryLevel($current_category->id, $business_id)
            ]);

            if ($current_category->parent_id == 0) {
                break;
            }

            $current_category = Category::where('business_id', $business_id)
                ->where('id', $current_category->parent_id)
                ->first();
        }

        return response()->json([
            'success' => true,
            'path' => $path
        ]);
    }

    /**
     * Get category level (depth from root)
     */
    private function getCategoryLevel($category_id, $business_id)
    {
        $level = 1;
        $current_category = Category::where('business_id', $business_id)
            ->where('id', $category_id)
            ->first();

        while ($current_category && $current_category->parent_id != 0) {
            $level++;
            $current_category = Category::where('business_id', $business_id)
                ->where('id', $current_category->parent_id)
                ->first();
        }

        return $level;
    }

    /**
     * Get all categories in tree format
     */
    public function getCategoryTree(Request $request)
    {
        $type = $request->get('type', 'product');
        $business_id = $request->session()->get('user.business_id');

        $categories = Category::where('business_id', $business_id)
            ->where('category_type', $type)
            ->select('id', 'name', 'parent_id')
            ->orderBy('name')
            ->get();

        $tree = $this->buildCategoryTree($categories, 0);

        return response()->json([
            'success' => true,
            'tree' => $tree
        ]);
    }

    /**
     * Build category tree recursively
     */
    private function buildCategoryTree($categories, $parent_id = 0)
    {
        $tree = [];

        foreach ($categories as $category) {
            if ($category->parent_id == $parent_id) {
                $children = $this->buildCategoryTree($categories, $category->id);
                
                $node = [
                    'id' => $category->id,
                    'name' => $category->name,
                    'parent_id' => $category->parent_id
                ];

                if (!empty($children)) {
                    $node['children'] = $children;
                }

                $tree[] = $node;
            }
        }

        return $tree;
    }
}