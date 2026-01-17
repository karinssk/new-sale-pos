<?php

namespace App\Http\Controllers;

use App\Category;
use App\Product;
use App\Brands;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryTreeSimpleController extends Controller
{
    /**
     * Get products for a specific category (AJAX) - Simplified version
     */
    public function getCategoryProductsSimple(Request $request)
    {
        try {
            // Get business ID (you may need to adjust this based on your auth system)
            $business_id = $request->session()->get('user.business_id') ?? 1;
            $category_id = $request->get('category_id');
            
            if (empty($category_id)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Category ID is required'
                ], 400);
            }

            // Get category info
            $category = Category::where('business_id', $business_id)
                ->where('id', $category_id)
                ->first();

            if (!$category) {
                return response()->json([
                    'success' => false,
                    'message' => 'Category not found'
                ], 404);
            }

            // Build category IDs array - start with the requested category
            $categoryIds = [$category_id];
            
            // Add subcategories if requested
            if ($request->get('include_subcategories', true)) {
                $subcategoryIds = $this->getSubcategoryIds($business_id, $category_id);
                $categoryIds = array_merge($categoryIds, $subcategoryIds);
            }

            // Get products from these categories
            $products = Product::where('business_id', $business_id)
                ->where('is_inactive', 0)
                ->whereIn('category_id', $categoryIds)
                ->get(['id', 'name', 'sku', 'category_id']);

            $showingParentProducts = false;
            $message = null;

            // If no products found and this is a subcategory, try parent category
            if ($products->count() == 0 && $category->parent_id > 0) {
                $parentProducts = Product::where('business_id', $business_id)
                    ->where('is_inactive', 0)
                    ->where('category_id', $category->parent_id)
                    ->get(['id', 'name', 'sku', 'category_id']);
                
                if ($parentProducts->count() > 0) {
                    $products = $parentProducts;
                    $showingParentProducts = true;
                    
                    $parentCategory = Category::find($category->parent_id);
                    $message = "Showing products from parent category: " . ($parentCategory ? $parentCategory->name : 'Parent Category');
                }
            }

            // Format products for display
            $formattedProducts = $products->map(function($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'sku' => $product->sku,
                    'price_display' => 'N/A' // You can add price logic here
                ];
            });

            $response = [
                'success' => true,
                'category' => [
                    'id' => $category->id,
                    'name' => $category->name,
                    'parent_id' => $category->parent_id
                ],
                'products' => $formattedProducts,
                'total_products' => $formattedProducts->count(),
                'showing_parent_products' => $showingParentProducts
            ];

            if ($message) {
                $response['message'] = $message;
            }

            return response()->json($response);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error loading products: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get subcategory IDs recursively
     */
    private function getSubcategoryIds($business_id, $parent_id)
    {
        $subcategories = Category::where('business_id', $business_id)
            ->where('parent_id', $parent_id)
            ->where('category_type', 'product')
            ->whereNull('deleted_at')
            ->pluck('id')
            ->toArray();

        $allSubcategories = $subcategories;

        // Recursively get subcategories
        foreach ($subcategories as $subcategoryId) {
            $childSubcategories = $this->getSubcategoryIds($business_id, $subcategoryId);
            $allSubcategories = array_merge($allSubcategories, $childSubcategories);
        }

        return $allSubcategories;
    }
}
