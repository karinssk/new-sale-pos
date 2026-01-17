<?php

namespace App\Http\Controllers;

use App\Category;
use App\Product;
use App\Brands;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class CategoryTreeController extends Controller
{
    /**
     * Display the category tree page
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            if (!auth()->user()->can('product.view')) {
                abort(403, 'Unauthorized action.');
            }

            $business_id = request()->session()->get('user.business_id');
            
            if (empty($business_id)) {
                return redirect()->back()->with('error', 'Business ID not found in session.');
            }
            
            // Get all categories with their hierarchy
            $categories = $this->getCategoryTree($business_id);
            
            return view('category_tree.index')->with(compact('categories'));
        } catch (\Exception $e) {
            Log::emergency('File:'.$e->getFile().'Line:'.$e->getLine().'Message:'.$e->getMessage());
            
            return redirect()->back()->with('error', 'Error loading category tree: ' . $e->getMessage());
        }
    }

    /**
     * Get category tree structure
     *
     * @param int $business_id
     * @return array
     */
    private function getCategoryTree($business_id)
    {
        try {
            // Load categories with their children relationship using Eloquent
            $categories = Category::where('business_id', $business_id)
                ->where('category_type', 'product')
                ->whereNull('deleted_at')
                ->where('parent_id', 0) // Only get root categories
                ->with(['children' => function($query) use ($business_id) {
                    $query->where('business_id', $business_id)
                          ->whereNull('deleted_at')
                          ->with(['children' => function($subQuery) use ($business_id) {
                              $subQuery->where('business_id', $business_id)
                                       ->whereNull('deleted_at')
                                       ->with(['children' => function($subSubQuery) use ($business_id) {
                                           $subSubQuery->where('business_id', $business_id)
                                                      ->whereNull('deleted_at')
                                                      ->with(['children' => function($subSubSubQuery) use ($business_id) {
                                                          $subSubSubQuery->where('business_id', $business_id)
                                                                        ->whereNull('deleted_at')
                                                                        ->with('children')
                                                                        ->withCount(['products' => function($pQuery) {
                                                                            $pQuery->where('is_inactive', 0);
                                                                        }])
                                                                        ->orderBy('name');
                                                      }])
                                                      ->withCount(['products' => function($pQuery) {
                                                          $pQuery->where('is_inactive', 0);
                                                      }])
                                                      ->orderBy('name');
                                       }])
                                       ->withCount(['products' => function($pQuery) {
                                           $pQuery->where('is_inactive', 0);
                                       }])
                                       ->orderBy('name');
                          }])
                          ->withCount(['products' => function($pQuery) {
                              $pQuery->where('is_inactive', 0);
                          }])
                          ->orderBy('name');
                }])
                ->withCount(['products' => function($query) {
                    $query->where('is_inactive', 0);
                }])
                ->orderBy('name')
                ->get();

            // If no results, try without category_type filter (fallback for older data)
            if ($categories->count() == 0) {
                Log::info('No product categories found, trying without category_type filter');
                $categories = Category::where('business_id', $business_id)
                    ->whereNull('deleted_at')
                    ->where('parent_id', 0) // Only get root categories
                    ->with(['children' => function($query) use ($business_id) {
                        $query->where('business_id', $business_id)
                              ->whereNull('deleted_at')
                              ->with(['children' => function($subQuery) use ($business_id) {
                                  $subQuery->where('business_id', $business_id)
                                           ->whereNull('deleted_at')
                                           ->with(['children' => function($subSubQuery) use ($business_id) {
                                               $subSubQuery->where('business_id', $business_id)
                                                          ->whereNull('deleted_at')
                                                          ->with(['children' => function($subSubSubQuery) use ($business_id) {
                                                              $subSubSubQuery->where('business_id', $business_id)
                                                                            ->whereNull('deleted_at')
                                                                            ->with('children')
                                                                            ->withCount(['products' => function($pQuery) {
                                                                                $pQuery->where('is_inactive', 0);
                                                                            }])
                                                                            ->orderBy('name');
                                                          }])
                                                          ->withCount(['products' => function($pQuery) {
                                                              $pQuery->where('is_inactive', 0);
                                                          }])
                                                          ->orderBy('name');
                                           }])
                                           ->withCount(['products' => function($pQuery) {
                                               $pQuery->where('is_inactive', 0);
                                           }])
                                           ->orderBy('name');
                              }])
                              ->withCount(['products' => function($pQuery) {
                                  $pQuery->where('is_inactive', 0);
                              }])
                              ->orderBy('name');
                    }])
                    ->withCount(['products' => function($query) {
                        $query->where('is_inactive', 0);
                    }])
                    ->orderBy('name')
                    ->get();
            }

            Log::info('Found root categories count: ' . $categories->count() . ' for business_id: ' . $business_id);
            
            return $categories;

        } catch (\Exception $e) {
            Log::emergency('Error in getCategoryTree: ' . $e->getMessage());
            return collect([]);
        }
    }

    /**
     * Get products for a specific category (AJAX)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCategoryProducts(Request $request)
    {
        // Disable error output for AJAX requests
        $oldErrorReporting = error_reporting(0);
        
        // Start output buffering to catch any unwanted output
        ob_start();
        
        try {
            if (!auth()->user()->can('product.view')) {
                ob_end_clean(); // Clear any output
                error_reporting($oldErrorReporting); // Restore error reporting
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized action.'
                ], 403);
            }

            $business_id = request()->session()->get('user.business_id');
            $category_id = $request->get('category_id');
            $include_subcategories = $request->get('include_subcategories', true);

            if (empty($category_id)) {
                ob_end_clean(); // Clear any output
                error_reporting($oldErrorReporting); // Restore error reporting
                return response()->json([
                    'success' => false,
                    'message' => 'Category ID is required'
                ], 400);
            }

            if (empty($business_id)) {
                ob_end_clean(); // Clear any output
                error_reporting($oldErrorReporting); // Restore error reporting
                return response()->json([
                    'success' => false,
                    'message' => 'Business ID not found in session'
                ], 400);
            }

            // Get category info
            $category = Category::where('business_id', $business_id)
                ->where('id', $category_id)
                ->first();

            if (!$category) {
                ob_end_clean(); // Clear any output
                error_reporting($oldErrorReporting); // Restore error reporting
                return response()->json([
                    'success' => false,
                    'message' => 'Category not found'
                ], 404);
            }

            // Build category IDs array
            $categoryIds = [$category_id];
            
            if ($include_subcategories) {
                $subcategoryIds = $this->getSubcategoryIds($business_id, $category_id);
                $categoryIds = array_merge($categoryIds, $subcategoryIds);
            }

            // If this is a subcategory (has parent), also include parent category products
            // This handles cases where products are assigned to parent categories instead of subcategories
            $parentCategoryIds = [];
            if ($category->parent_id > 0) {
                $parentCategoryIds[] = $category->parent_id;
                
                // Also get sibling subcategories if they have products
                $siblingIds = $this->getSubcategoryIds($business_id, $category->parent_id);
                $parentCategoryIds = array_merge($parentCategoryIds, $siblingIds);
            }

            // Debug logging
            Log::info('Category Products Debug:', [
                'category_id' => $category_id,
                'category_name' => $category->name,
                'is_subcategory' => $category->parent_id > 0,
                'parent_id' => $category->parent_id,
                'include_subcategories' => $include_subcategories,
                'subcategory_ids' => $subcategoryIds ?? [],
                'parent_category_ids' => $parentCategoryIds,
                'all_category_ids' => $categoryIds,
                'business_id' => $business_id
            ]);

            // Get products
            $products = Product::with(['brand'])
                ->leftJoin('brands', 'products.brand_id', '=', 'brands.id')
                ->leftJoin('variations as v', 'v.product_id', '=', 'products.id')
                ->leftJoin('variation_location_details as vld', 'vld.variation_id', '=', 'v.id')
                ->where('products.business_id', $business_id)
                ->where('products.is_inactive', 0)
                ->whereIn('products.category_id', $categoryIds)
                ->whereNull('v.deleted_at')
                ->select([
                    'products.id',
                    'products.name',
                    'products.sku',
                    'products.image',
                    'products.type',
                    'products.product_description',
                    'brands.name as brand_name',
                    DB::raw('SUM(COALESCE(vld.qty_available, 0)) as current_stock'),
                    DB::raw('MIN(v.sell_price_inc_tax) as min_price'),
                    DB::raw('MAX(v.sell_price_inc_tax) as max_price')
                ])
                ->groupBy([
                    'products.id',
                    'products.name', 
                    'products.sku',
                    'products.image',
                    'products.type',
                    'products.product_description',
                    'brands.name'
                ])
                ->orderBy('products.name')
                ->get();

            // Debug the products query
            Log::info('Products Query Result:', [
                'products_found' => $products->count(),
                'category_ids_searched' => $categoryIds,
                'first_few_products' => $products->take(3)->pluck('name', 'id')->toArray()
            ]);

            // If no products found and this is a subcategory, try to get parent category products
            if ($products->count() == 0 && !empty($parentCategoryIds)) {
                Log::info('No products in subcategory, searching parent categories:', $parentCategoryIds);
                
                $products = Product::with(['brand'])
                    ->leftJoin('brands', 'products.brand_id', '=', 'brands.id')
                    ->leftJoin('variations as v', 'v.product_id', '=', 'products.id')
                    ->leftJoin('variation_location_details as vld', 'vld.variation_id', '=', 'v.id')
                    ->where('products.business_id', $business_id)
                    ->where('products.is_inactive', 0)
                    ->whereIn('products.category_id', $parentCategoryIds)
                    ->whereNull('v.deleted_at')
                    ->select([
                        'products.id',
                        'products.name',
                        'products.sku',
                        'products.image',
                        'products.type',
                        'products.product_description',
                        'brands.name as brand_name',
                        DB::raw('SUM(COALESCE(vld.qty_available, 0)) as current_stock'),
                        DB::raw('MIN(v.sell_price_inc_tax) as min_price'),
                        DB::raw('MAX(v.sell_price_inc_tax) as max_price')
                    ])
                    ->groupBy([
                        'products.id',
                        'products.name', 
                        'products.sku',
                        'products.image',
                        'products.type',
                        'products.product_description',
                        'brands.name'
                    ])
                    ->orderBy('products.name')
                    ->get();
                    
                Log::info('Parent category products found:', [
                    'parent_products_found' => $products->count(),
                    'parent_category_ids_searched' => $parentCategoryIds
                ]);
            }

            // Format products for display
            $formattedProducts = $products->map(function($product) {
                // Get image URL - check if product has media or use default image handling
                $imageUrl = null;
                if (!empty($product->image)) {
                    $imageUrl = asset('uploads/img/' . $product->image);
                } else {
                    $imageUrl = asset('img/default.png'); // Default image
                }

                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'sku' => $product->sku,
                    'image' => $imageUrl,
                    'type' => $product->type,
                    'description' => $product->product_description,
                    'brand_name' => $product->brand_name,
                    'current_stock' => (float)($product->current_stock ?? 0),
                    'min_price' => (float)($product->min_price ?? 0),
                    'max_price' => (float)($product->max_price ?? 0),
                    'price_display' => $this->formatPrice($product->min_price, $product->max_price, $product->type)
                ];
            });

            // Check if we ended up using parent category products
            $showingParentProducts = ($products->count() > 0 && !empty($parentCategoryIds) && 
                                    $categoryIds != $parentCategoryIds);

            // Clear any output before sending JSON
            ob_end_clean();
            error_reporting($oldErrorReporting); // Restore error reporting

            $response = [
                'success' => true,
                'category' => [
                    'id' => $category->id,
                    'name' => $category->name,
                    'description' => $category->description,
                    'parent_id' => $category->parent_id
                ],
                'products' => $formattedProducts,
                'total_products' => $formattedProducts->count(),
                'showing_parent_products' => $showingParentProducts
            ];

            // Add message if showing parent products
            if ($showingParentProducts) {
                $parentCategory = Category::find($category->parent_id);
                $response['message'] = "Showing products from parent category: " . ($parentCategory ? $parentCategory->name : 'Parent Category');
            }

            return response()->json($response);

        } catch (\Exception $e) {
            ob_end_clean(); // Clear any output
            error_reporting($oldErrorReporting); // Restore error reporting
            Log::emergency('CategoryTreeController getCategoryProducts Error - File:'.$e->getFile().' Line:'.$e->getLine().' Message:'.$e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while loading products. Please try again.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Get all subcategory IDs recursively
     *
     * @param int $business_id
     * @param int $parent_id
     * @return array
     */
    private function getSubcategoryIds($business_id, $parent_id)
    {
        $subcategories = Category::where('business_id', $business_id)
            ->where('parent_id', $parent_id)
            ->where('category_type', 'product') // Filter by product category type
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

    /**
     * Format price display
     *
     * @param float $min_price
     * @param float $max_price
     * @param string $type
     * @return string
     */
    private function formatPrice($min_price, $max_price, $type)
    {
        if (empty($min_price) && empty($max_price)) {
            return 'Price on request';
        }

        if ($type === 'variable' && $min_price != $max_price) {
            return '฿' . number_format($min_price, 2) . ' - ฿' . number_format($max_price, 2);
        }

        return '฿' . number_format($min_price, 2);
    }

    /**
     * Update category order (for drag and drop - optional)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateCategoryOrder(Request $request)
    {
        // Start output buffering to catch any unwanted output
        ob_start();
        
        try {
            if (!auth()->user()->can('category.update')) {
                ob_end_clean(); // Clear any output
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized action.'
                ], 403);
            }

            $business_id = request()->session()->get('user.business_id');
            $categories = $request->get('categories', []);

            if (empty($business_id)) {
                ob_end_clean(); // Clear any output
                return response()->json([
                    'success' => false,
                    'message' => 'Business ID not found in session'
                ], 400);
            }

            DB::beginTransaction();

            foreach ($categories as $index => $categoryData) {
                Category::where('business_id', $business_id)
                    ->where('id', $categoryData['id'])
                    ->where('category_type', 'product') // Ensure we only update product categories
                    ->update([
                        'parent_id' => $categoryData['parent_id'] ?? 0,
                        'sort_order' => $index + 1
                    ]);
            }

            DB::commit();

            // Clear any output before sending JSON
            ob_end_clean();

            return response()->json([
                'success' => true,
                'message' => 'Category order updated successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            ob_end_clean(); // Clear any output
            Log::emergency('CategoryTreeController updateCategoryOrder Error - File:'.$e->getFile().' Line:'.$e->getLine().' Message:'.$e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating category order. Please try again.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Search categories (AJAX)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchCategories(Request $request)
    {
        // Start output buffering to catch any unwanted output
        ob_start();
        
        try {
            if (!auth()->user()->can('product.view')) {
                ob_end_clean(); // Clear any output
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized action.'
                ], 403);
            }

            $business_id = request()->session()->get('user.business_id');
            $search = $request->get('search', '');

            if (empty($business_id)) {
                ob_end_clean(); // Clear any output
                return response()->json([
                    'success' => false,
                    'message' => 'Business ID not found in session'
                ], 400);
            }

            $categories = Category::where('business_id', $business_id)
                ->where('category_type', 'product') // Filter by product category type
                ->whereNull('deleted_at')
                ->where('name', 'like', "%{$search}%")
                ->withCount(['products' => function($query) {
                    $query->where('is_inactive', 0);
                }])
                ->orderBy('name')
                ->get();

            // Clear any output before sending JSON
            ob_end_clean();

            return response()->json([
                'success' => true,
                'categories' => $categories
            ]);

        } catch (\Exception $e) {
            ob_end_clean(); // Clear any output
            Log::emergency('CategoryTreeController searchCategories Error - File:'.$e->getFile().' Line:'.$e->getLine().' Message:'.$e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while searching categories. Please try again.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Test method to check basic functionality (temporary for debugging)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function testConnection(Request $request)
    {
        // Start output buffering to catch any unwanted output
        ob_start();
        
        try {
            $business_id = request()->session()->get('user.business_id');
            $user = auth()->user();
            
            // Clear any output before sending JSON
            ob_end_clean();
            
            return response()->json([
                'success' => true,
                'message' => 'Connection successful',
                'data' => [
                    'business_id' => $business_id,
                    'user_id' => $user ? $user->id : null,
                    'can_view_product' => $user ? $user->can('product.view') : false,
                    'csrf_token' => csrf_token(),
                    'categories_count' => Category::where('business_id', $business_id)->count()
                ]
            ]);
        } catch (\Exception $e) {
            ob_end_clean(); // Clear any output
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
        }
    }
}
