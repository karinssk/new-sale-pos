<?php

namespace App\Http\Controllers;

use App\Category;
use App\Product;
use App\Utils\BusinessUtil;
use App\Utils\ModuleUtil;
use App\Utils\ProductUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class ProductsV2Controller extends Controller
{
    /**
     * All Utils instance.
     */
    protected $productUtil;
    protected $businessUtil;
    protected $moduleUtil;

    /**
     * Constructor
     *
     * @param ProductUtil $productUtil
     * @return void
     */
    public function __construct(ProductUtil $productUtil, BusinessUtil $businessUtil, ModuleUtil $moduleUtil)
    {
        $this->productUtil = $productUtil;
        $this->businessUtil = $businessUtil;
        $this->moduleUtil = $moduleUtil;
    }

    /**
     * Display the products list with subcategory filtering
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!auth()->user()->can('product.view')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = $request->session()->get('user.business_id');

        // Get categories with hierarchical structure for filtering
        $categories = $this->getCategoriesHierarchy($business_id);

        // Get brands for filtering
        $brands = DB::table('brands')
            ->where('business_id', $business_id)
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        // Get units for filtering
        $units = DB::table('units')
            ->where('business_id', $business_id)
            ->select('id', 'actual_name as name')
            ->orderBy('actual_name')
            ->get();

        // Get product types
        $product_types = [
            'single' => 'Single',
            'variable' => 'Variable',
            'modifier' => 'Modifier',
            'combo' => 'Combo'
        ];

        return view('products_v2.index')
            ->with(compact('categories', 'brands', 'units', 'product_types'));
    }

    /**
     * Get products by subcategory via AJAX
     */
    public function getProductsBySubcategory(Request $request)
    {
        if (!auth()->user()->can('product.view')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $business_id = $request->session()->get('user.business_id');
        $category_id = $request->get('category_id');
        $include_subcategories = $request->get('include_subcategories', true);

        try {
            // Get category info
            $category = Category::where('business_id', $business_id)
                ->where('id', $category_id)
                ->first();

            if (!$category) {
                return response()->json(['error' => 'Category not found'], 404);
            }

            // Build category IDs array
            $categoryIds = [$category_id];
            
            if ($include_subcategories) {
                $subcategoryIds = $this->getSubcategoryIds($business_id, $category_id);
                $categoryIds = array_merge($categoryIds, $subcategoryIds);
            }

            // Debug logging
            Log::info('ProductsV2 Category Filter Debug:', [
                'category_id' => $category_id,
                'category_name' => $category->name,
                'category_level' => $category->parent_id ? 'L2+' : 'L1',
                'parent_id' => $category->parent_id,
                'include_subcategories' => $include_subcategories,
                'category_ids_to_search' => $categoryIds,
                'business_id' => $business_id
            ]);

            // Get products with details
            $productsQuery = Product::with(['brand', 'variations.variation_location_details'])
                ->leftJoin('brands', 'products.brand_id', '=', 'brands.id')
                ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
                ->where('products.business_id', $business_id)
                ->where('products.is_inactive', 0)
                ->whereIn('products.category_id', $categoryIds);

            // Debug the query
            $sql = $productsQuery->toSql();
            $bindings = $productsQuery->getBindings();
            Log::info('ProductsV2 SQL Query:', [
                'sql' => $sql,
                'bindings' => $bindings,
                'category_ids' => $categoryIds
            ]);

            $products = $productsQuery->select([
                'products.id',
                'products.name',
                'products.sku',
                'products.image',
                'products.type',
                'products.product_description',
                'products.category_id',
                'brands.name as brand_name',
                'categories.name as category_name'
            ])
            ->orderBy('products.name')
            ->get();

            Log::info('ProductsV2 Products Found:', [
                'products_count' => $products->count(),
                'first_few_products' => $products->take(3)->pluck('name', 'id')->toArray()
            ]);

            // If no products found and this is not an L1 category, also search parent categories
            if ($products->count() == 0 && $category->parent_id > 0) {
                Log::info('No products in L2+ category, searching parent categories');
                
                // Get parent category and its products
                $parentCategoryIds = [$category->parent_id];
                
                // Also get sibling categories if they have products
                $siblingIds = $this->getSubcategoryIds($business_id, $category->parent_id);
                $allParentIds = array_merge($parentCategoryIds, $siblingIds);
                
                Log::info('Searching parent categories:', [
                    'parent_id' => $category->parent_id,
                    'all_parent_ids' => $allParentIds
                ]);
                
                $parentProducts = Product::with(['brand', 'variations.variation_location_details'])
                    ->leftJoin('brands', 'products.brand_id', '=', 'brands.id')
                    ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
                    ->where('products.business_id', $business_id)
                    ->where('products.is_inactive', 0)
                    ->whereIn('products.category_id', $allParentIds)
                    ->select([
                        'products.id',
                        'products.name',
                        'products.sku',
                        'products.image',
                        'products.type',
                        'products.product_description',
                        'products.category_id',
                        'brands.name as brand_name',
                        'categories.name as category_name'
                    ])
                    ->orderBy('products.name')
                    ->get();
                    
                Log::info('Parent category products found:', [
                    'parent_products_count' => $parentProducts->count(),
                    'parent_products' => $parentProducts->take(3)->pluck('name', 'id')->toArray()
                ]);
                
                if ($parentProducts->count() > 0) {
                    $products = $parentProducts;
                }
            }

            // Format products with stock and pricing info
            $formattedProducts = $products->map(function($product) {
                // Calculate stock
                $stock = 0;
                $price_min = 0;
                $price_max = 0;

                if ($product->variations && $product->variations->count() > 0) {
                    foreach ($product->variations as $variation) {
                        // Sum stock from all locations
                        $variation_stock = $variation->variation_location_details->sum('qty_available');
                        $stock += $variation_stock;

                        // Get price range
                        if ($price_min == 0 || $variation->sell_price_inc_tax < $price_min) {
                            $price_min = $variation->sell_price_inc_tax;
                        }
                        if ($variation->sell_price_inc_tax > $price_max) {
                            $price_max = $variation->sell_price_inc_tax;
                        }
                    }
                }

                // Format price display
                $price_display = '';
                if ($price_min == $price_max) {
                    $price_display = number_format($price_min, 2);
                } else {
                    $price_display = number_format($price_min, 2) . ' - ' . number_format($price_max, 2);
                }

                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'sku' => $product->sku,
                    'image' => $product->image ? asset('uploads/img/' . $product->image) : asset('img/default.png'),
                    'type' => ucfirst($product->type),
                    'description' => $product->product_description,
                    'brand_name' => $product->brand_name ?? 'No Brand',
                    'category_name' => $product->category_name,
                    'category_id' => $product->category_id,
                    'stock' => $stock,
                    'price_display' => $price_display,
                    'stock_status' => $this->getStockStatus($stock)
                ];
            });

            // Group products by subcategory
            $productsByCategory = $formattedProducts->groupBy('category_id');

            // Get category names for the groups
            $categoryNames = Category::whereIn('id', $productsByCategory->keys())
                ->pluck('name', 'id');

            $result = [];
            foreach ($productsByCategory as $cat_id => $categoryProducts) {
                $result[] = [
                    'category_id' => $cat_id,
                    'category_name' => $categoryNames[$cat_id] ?? 'Unknown Category',
                    'product_count' => $categoryProducts->count(),
                    'products' => $categoryProducts->values()
                ];
            }

            return response()->json([
                'success' => true,
                'data' => $result,
                'main_category' => $category->name,
                'total_products' => $formattedProducts->count()
            ]);

        } catch (\Exception $e) {
            Log::error('ProductsV2 getProductsBySubcategory error: ' . $e->getMessage());
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    /**
     * Get all products with advanced filtering
     */
    public function getAllProducts(Request $request)
    {
        if (!auth()->user()->can('product.view')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $business_id = $request->session()->get('user.business_id');

        try {
            $query = Product::with(['brand', 'category', 'variations.variation_location_details'])
                ->leftJoin('brands', 'products.brand_id', '=', 'brands.id')
                ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
                ->where('products.business_id', $business_id)
                ->where('products.is_inactive', 0);

            // Apply filters
            if ($request->has('category_id') && $request->category_id != '') {
                $categoryIds = [$request->category_id];
                if ($request->get('include_subcategories', true)) {
                    $subcategoryIds = $this->getSubcategoryIds($business_id, $request->category_id);
                    $categoryIds = array_merge($categoryIds, $subcategoryIds);
                }
                
                // Debug logging for category filtering
                Log::info('ProductsV2 getAllProducts Category Filter:', [
                    'category_id' => $request->category_id,
                    'include_subcategories' => $request->get('include_subcategories', true),
                    'category_ids_to_search' => $categoryIds,
                    'business_id' => $business_id
                ]);
                
                $query->whereIn('products.category_id', $categoryIds);
            }

            if ($request->has('brand_id') && $request->brand_id != '') {
                $query->where('products.brand_id', $request->brand_id);
            }

            if ($request->has('product_type') && $request->product_type != '') {
                $query->where('products.type', $request->product_type);
            }

            if ($request->has('search') && $request->search != '') {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('products.name', 'like', "%{$search}%")
                      ->orWhere('products.sku', 'like', "%{$search}%");
                });
            }

            $products = $query->select([
                'products.id',
                'products.name',
                'products.sku',
                'products.image',
                'products.type',
                'products.product_description',
                'products.category_id',
                'brands.name as brand_name',
                'categories.name as category_name'
            ])
            ->orderBy('products.name')
            ->get();

            // If filtering by category and no products found, try parent categories for L2+ categories
            if ($request->has('category_id') && $request->category_id != '' && $products->count() == 0) {
                $selectedCategory = Category::where('business_id', $business_id)
                    ->where('id', $request->category_id)
                    ->first();
                    
                if ($selectedCategory && $selectedCategory->parent_id > 0) {
                    Log::info('No products in L2+ category for getAllProducts, searching parent categories');
                    
                    // Search parent category and siblings
                    $parentCategoryIds = [$selectedCategory->parent_id];
                    $siblingIds = $this->getSubcategoryIds($business_id, $selectedCategory->parent_id);
                    $allParentIds = array_merge($parentCategoryIds, $siblingIds);
                    
                    $parentQuery = Product::with(['brand', 'category', 'variations.variation_location_details'])
                        ->leftJoin('brands', 'products.brand_id', '=', 'brands.id')
                        ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
                        ->where('products.business_id', $business_id)
                        ->where('products.is_inactive', 0)
                        ->whereIn('products.category_id', $allParentIds);
                        
                    // Apply other filters if they exist
                    if ($request->has('brand_id') && $request->brand_id != '') {
                        $parentQuery->where('products.brand_id', $request->brand_id);
                    }
                    if ($request->has('product_type') && $request->product_type != '') {
                        $parentQuery->where('products.type', $request->product_type);
                    }
                    if ($request->has('search') && $request->search != '') {
                        $search = $request->search;
                        $parentQuery->where(function($q) use ($search) {
                            $q->where('products.name', 'like', "%{$search}%")
                              ->orWhere('products.sku', 'like', "%{$search}%");
                        });
                    }
                    
                    $parentProducts = $parentQuery->select([
                        'products.id',
                        'products.name',
                        'products.sku',
                        'products.image',
                        'products.type',
                        'products.product_description',
                        'products.category_id',
                        'brands.name as brand_name',
                        'categories.name as category_name'
                    ])
                    ->orderBy('products.name')
                    ->get();
                    
                    if ($parentProducts->count() > 0) {
                        $products = $parentProducts;
                        Log::info('Found products in parent categories for getAllProducts:', [
                            'parent_products_count' => $parentProducts->count()
                        ]);
                    }
                }
            }

            // Format products with additional data
            $formattedProducts = $products->map(function($product) {
                // Calculate stock and pricing
                $stock = 0;
                $price_min = 0;
                $price_max = 0;

                if ($product->variations && $product->variations->count() > 0) {
                    foreach ($product->variations as $variation) {
                        $variation_stock = $variation->variation_location_details->sum('qty_available');
                        $stock += $variation_stock;

                        if ($price_min == 0 || $variation->sell_price_inc_tax < $price_min) {
                            $price_min = $variation->sell_price_inc_tax;
                        }
                        if ($variation->sell_price_inc_tax > $price_max) {
                            $price_max = $variation->sell_price_inc_tax;
                        }
                    }
                }

                $price_display = ($price_min == $price_max) 
                    ? number_format($price_min, 2) 
                    : number_format($price_min, 2) . ' - ' . number_format($price_max, 2);

                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'sku' => $product->sku,
                    'image' => $product->image ? asset('uploads/img/' . $product->image) : asset('img/default.png'),
                    'type' => ucfirst($product->type),
                    'description' => $product->product_description,
                    'brand_name' => $product->brand_name ?? 'No Brand',
                    'category_name' => $product->category_name,
                    'category_id' => $product->category_id,
                    'stock' => $stock,
                    'price_display' => $price_display,
                    'stock_status' => $this->getStockStatus($stock)
                ];
            });

            // Group by category if requested
            if ($request->get('group_by_category', false)) {
                $productsByCategory = $formattedProducts->groupBy('category_id');
                
                $result = [];
                foreach ($productsByCategory as $cat_id => $categoryProducts) {
                    $result[] = [
                        'category_id' => $cat_id,
                        'category_name' => $categoryProducts->first()['category_name'] ?? 'Unknown Category',
                        'product_count' => $categoryProducts->count(),
                        'products' => $categoryProducts->values()
                    ];
                }

                return response()->json([
                    'success' => true,
                    'data' => $result,
                    'total_products' => $formattedProducts->count(),
                    'grouped' => true
                ]);
            }

            return response()->json([
                'success' => true,
                'data' => $formattedProducts,
                'total_products' => $formattedProducts->count(),
                'grouped' => false
            ]);

        } catch (\Exception $e) {
            Log::error('ProductsV2 getAllProducts error: ' . $e->getMessage());
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    /**
     * Get categories hierarchy for filtering
     */
    private function getCategoriesHierarchy($business_id)
    {
        // First try with category_type filter, then fallback without it
        $categories = Category::where('business_id', $business_id)
            ->where('category_type', 'product')
            ->whereNull('deleted_at')
            ->where('parent_id', 0)
            ->with(['children' => function($query) use ($business_id) {
                $query->where('business_id', $business_id)
                      ->whereNull('deleted_at')
                      ->with(['children' => function($subQuery) use ($business_id) {
                          $subQuery->where('business_id', $business_id)
                                   ->whereNull('deleted_at')
                                   ->with(['children' => function($subSubQuery) use ($business_id) {
                                       $subSubQuery->where('business_id', $business_id)
                                                  ->whereNull('deleted_at')
                                                  ->with('children')
                                                  ->orderBy('name');
                                   }])
                                   ->orderBy('name');
                      }])
                      ->orderBy('name');
            }])
            ->orderBy('name')
            ->get();

        // If no categories found with category_type, try without it
        if ($categories->count() == 0) {
            Log::info('No categories found with category_type=product, trying without category_type filter');
            
            $categories = Category::where('business_id', $business_id)
                ->whereNull('deleted_at')
                ->where('parent_id', 0)
                ->with(['children' => function($query) use ($business_id) {
                    $query->where('business_id', $business_id)
                          ->whereNull('deleted_at')
                          ->with(['children' => function($subQuery) use ($business_id) {
                              $subQuery->where('business_id', $business_id)
                                       ->whereNull('deleted_at')
                                       ->with(['children' => function($subSubQuery) use ($business_id) {
                                           $subSubQuery->where('business_id', $business_id)
                                                      ->whereNull('deleted_at')
                                                      ->with('children')
                                                      ->orderBy('name');
                                       }])
                                       ->orderBy('name');
                          }])
                          ->orderBy('name');
                }])
                ->orderBy('name')
                ->get();
        }

        Log::info('ProductsV2 Categories loaded:', [
            'categories_count' => $categories->count(),
            'categories' => $categories->pluck('name', 'id')->toArray()
        ]);

        return $categories;
    }

    /**
     * Get all subcategory IDs recursively
     */
    private function getSubcategoryIds($business_id, $parent_id)
    {
        // First try with category_type filter
        $subcategories = Category::where('business_id', $business_id)
            ->where('parent_id', $parent_id)
            ->where('category_type', 'product')
            ->whereNull('deleted_at')
            ->get();

        // If no subcategories found with category_type, try without it
        if ($subcategories->count() == 0) {
            $subcategories = Category::where('business_id', $business_id)
                ->where('parent_id', $parent_id)
                ->whereNull('deleted_at')
                ->get();
        }

        $ids = [];
        foreach ($subcategories as $subcategory) {
            $ids[] = $subcategory->id;
            $childIds = $this->getSubcategoryIds($business_id, $subcategory->id);
            $ids = array_merge($ids, $childIds);
        }

        // Debug logging
        Log::info('getSubcategoryIds Debug:', [
            'parent_id' => $parent_id,
            'found_subcategories' => $subcategories->pluck('name', 'id')->toArray(),
            'subcategory_ids' => $ids,
            'business_id' => $business_id
        ]);

        return $ids;
    }

    /**
     * Get stock status based on quantity
     */
    private function getStockStatus($stock)
    {
        if ($stock <= 0) {
            return ['status' => 'out', 'class' => 'danger', 'text' => 'Out of Stock'];
        } elseif ($stock < 10) {
            return ['status' => 'low', 'class' => 'warning', 'text' => 'Low Stock'];
        } elseif ($stock < 50) {
            return ['status' => 'medium', 'class' => 'info', 'text' => 'Medium Stock'];
        } else {
            return ['status' => 'high', 'class' => 'success', 'text' => 'In Stock'];
        }
    }
}
