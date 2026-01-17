<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MenuSetting;
use Illuminate\Support\Facades\DB;

class MenuSettingsController extends Controller
{
    /**
     * Display menu settings page
     */
    public function index()
    {
        try {
            $menuStructure = $this->getCurrentMenuStructure();
            return view('menu-settings.index', compact('menuStructure'));
        } catch (\Exception $e) {
            return view('menu-settings.index', ['menuStructure' => []]);
        }
    }

    /**
     * Get current menu structure (saved + default)
     */
    private function getCurrentMenuStructure()
    {
        $savedMenuItems = MenuSetting::orderBy('order')->get();
        
        try {
            // If we have saved menu items, format and return them
            if ($savedMenuItems->count() > 0) {
                return $this->formatSavedMenuItems($savedMenuItems);
            }
        } catch (\Exception $e) {
            // Fall back to default if there's an error
        }
        
        return $this->getDefaultMenuStructure();
    }

    /**
     * Get default menu structure representing the actual Laravel application menus
     */
    private function getDefaultMenuStructure()
    {
        // Get actual menu structure from AdminSidebarMenu middleware
        return $this->extractRealMenuStructure();
    }

    private function extractRealMenuStructure()
    {
        // Get actual menu structure from AdminSidebarMenu middleware
        $menuStructure = [];
        $order = 1;

        try {
            // Simulate the AdminSidebarMenu middleware logic to extract real menu
            $user = auth()->user();
            $business_id = session()->get('user.business_id');
            
            if (!$business_id) {
                return $this->getFallbackMenuStructure();
            }
            
            $business = \App\Business::findOrFail($business_id);
            $pos_settings = !empty($business->pos_settings) ? json_decode($business->pos_settings, true) : [];
            $common_settings = !empty($business->common_settings) ? json_decode($business->common_settings, true) : [];
            $enabled_modules = !empty($business->enabled_modules) ? explode(',', $business->enabled_modules) : [];
            $is_admin = $user->hasRole('Admin#' . $business_id);

            // Home menu item
            $menuStructure[] = [
                'id' => 1,
                'name' => trans('home.home'),
                'icon' => 'fa fa-home',
                'url' => action([\App\Http\Controllers\HomeController::class, 'index']),
                'parent_id' => null,
                'order' => $order++,
                'is_visible' => true,
                'is_system' => true,
                'children' => []
            ];

            // User Management dropdown
            if ($is_admin || $user->hasAnyPermission(['user.view', 'user.create', 'roles.view', 'roles.create'])) {
                $userChildren = [];
                $childOrder = 1;

                if ($user->can('user.view') || $user->can('user.create')) {
                    $userChildren[] = [
                        'id' => 21,
                        'name' => trans('user.users'),
                        'icon' => 'fa fa-user',
                        'url' => action([\App\Http\Controllers\ManageUserController::class, 'index']),
                        'parent_id' => 2,
                        'order' => $childOrder++,
                        'is_visible' => true,
                        'is_system' => true
                    ];
                }

                if ($user->can('roles.view') || $user->can('roles.create')) {
                    $userChildren[] = [
                        'id' => 22,
                        'name' => trans('user.roles'),
                        'icon' => 'fa fa-key',
                        'url' => action([\App\Http\Controllers\RoleController::class, 'index']),
                        'parent_id' => 2,
                        'order' => $childOrder++,
                        'is_visible' => true,
                        'is_system' => true
                    ];
                }

                $menuStructure[] = [
                    'id' => 2,
                    'name' => trans('user.user_management'),
                    'icon' => 'fa fa-users',
                    'url' => '#',
                    'parent_id' => null,
                    'order' => $order++,
                    'is_visible' => true,
                    'is_system' => true,
                    'children' => $userChildren
                ];
            }

            // Contacts dropdown
            if (in_array('contacts', $enabled_modules) && ($user->can('supplier.view') || $user->can('supplier.create') || $user->can('customer.view') || $user->can('customer.create'))) {
                $contactChildren = [];
                $childOrder = 1;

                if ($user->can('supplier.view') || $user->can('supplier.create')) {
                    $contactChildren[] = [
                        'id' => 31,
                        'name' => trans('report.supplier'),
                        'icon' => 'fa fa-building',
                        'url' => action([\App\Http\Controllers\ContactController::class, 'index'], ['type' => 'supplier']),
                        'parent_id' => 3,
                        'order' => $childOrder++,
                        'is_visible' => true,
                        'is_system' => true
                    ];
                }

                if ($user->can('customer.view') || $user->can('customer.create')) {
                    $contactChildren[] = [
                        'id' => 32,
                        'name' => trans('report.customer'),
                        'icon' => 'fa fa-user-circle',
                        'url' => action([\App\Http\Controllers\ContactController::class, 'index'], ['type' => 'customer']),
                        'parent_id' => 3,
                        'order' => $childOrder++,
                        'is_visible' => true,
                        'is_system' => true
                    ];
                }

                $menuStructure[] = [
                    'id' => 3,
                    'name' => trans('contact.contacts'),
                    'icon' => 'fa fa-address-book',
                    'url' => '#',
                    'parent_id' => null,
                    'order' => $order++,
                    'is_visible' => true,
                    'is_system' => true,
                    'children' => $contactChildren
                ];
            }

            // Products dropdown
            if (in_array('products', $enabled_modules) && ($user->can('product.view') || $user->can('product.create'))) {
                $productChildren = [];
                $childOrder = 1;

                if ($user->can('product.view')) {
                    $productChildren[] = [
                        'id' => 41,
                        'name' => trans('lang_v1.list_products'),
                        'icon' => 'fa fa-list',
                        'url' => action([\App\Http\Controllers\ProductController::class, 'index']),
                        'parent_id' => 4,
                        'order' => $childOrder++,
                        'is_visible' => true,
                        'is_system' => true
                    ];
                }

                if ($user->can('product.create')) {
                    $productChildren[] = [
                        'id' => 42,
                        'name' => trans('product.add_product'),
                        'icon' => 'fa fa-plus',
                        'url' => action([\App\Http\Controllers\ProductController::class, 'create']),
                        'parent_id' => 4,
                        'order' => $childOrder++,
                        'is_visible' => true,
                        'is_system' => true
                    ];
                }

                if ($user->can('category.view') || $user->can('category.create')) {
                    $productChildren[] = [
                        'id' => 43,
                        'name' => trans('category.categories'),
                        'icon' => 'fa fa-tags',
                        'url' => action([\App\Http\Controllers\TaxonomyController::class, 'index']) . '?type=product',
                        'parent_id' => 4,
                        'order' => $childOrder++,
                        'is_visible' => true,
                        'is_system' => true
                    ];
                }

                $menuStructure[] = [
                    'id' => 4,
                    'name' => trans('product.products'),
                    'icon' => 'fa fa-cubes',
                    'url' => '#',
                    'parent_id' => null,
                    'order' => $order++,
                    'is_visible' => true,
                    'is_system' => true,
                    'children' => $productChildren
                ];
            }

            // Purchases dropdown
            if (in_array('purchases', $enabled_modules) && ($user->can('purchase.view') || $user->can('purchase.create') || $user->can('purchase.update'))) {
                $purchaseChildren = [];
                $childOrder = 1;

                if ($user->can('purchase.view') || $user->can('view_own_purchase')) {
                    $purchaseChildren[] = [
                        'id' => 51,
                        'name' => trans('purchase.list_purchase'),
                        'icon' => 'fa fa-list',
                        'url' => action([\App\Http\Controllers\PurchaseController::class, 'index']),
                        'parent_id' => 5,
                        'order' => $childOrder++,
                        'is_visible' => true,
                        'is_system' => true
                    ];
                }

                if ($user->can('purchase.create')) {
                    $purchaseChildren[] = [
                        'id' => 52,
                        'name' => trans('purchase.add_purchase'),
                        'icon' => 'fa fa-plus',
                        'url' => action([\App\Http\Controllers\PurchaseController::class, 'create']),
                        'parent_id' => 5,
                        'order' => $childOrder++,
                        'is_visible' => true,
                        'is_system' => true
                    ];
                }

                $menuStructure[] = [
                    'id' => 5,
                    'name' => trans('purchase.purchases'),
                    'icon' => 'fa fa-arrow-circle-down',
                    'url' => '#',
                    'parent_id' => null,
                    'order' => $order++,
                    'is_visible' => true,
                    'is_system' => true,
                    'children' => $purchaseChildren
                ];
            }

            // Sales dropdown
            if ($is_admin || $user->hasAnyPermission(['sell.view', 'sell.create', 'direct_sell.access', 'view_own_sell_only'])) {
                $salesChildren = [];
                $childOrder = 1;

                if ($is_admin || $user->hasAnyPermission(['sell.view', 'sell.create', 'direct_sell.access', 'view_own_sell_only'])) {
                    $salesChildren[] = [
                        'id' => 61,
                        'name' => trans('lang_v1.all_sales'),
                        'icon' => 'fa fa-list',
                        'url' => action([\App\Http\Controllers\SellController::class, 'index']),
                        'parent_id' => 6,
                        'order' => $childOrder++,
                        'is_visible' => true,
                        'is_system' => true
                    ];
                }

                if (in_array('add_sale', $enabled_modules) && $user->can('direct_sell.access')) {
                    $salesChildren[] = [
                        'id' => 62,
                        'name' => trans('sale.add_sale'),
                        'icon' => 'fa fa-plus',
                        'url' => action([\App\Http\Controllers\SellController::class, 'create']),
                        'parent_id' => 6,
                        'order' => $childOrder++,
                        'is_visible' => true,
                        'is_system' => true
                    ];
                }

                if ($user->can('sell.create') && in_array('pos_sale', $enabled_modules)) {
                    $salesChildren[] = [
                        'id' => 63,
                        'name' => trans('sale.pos_sale'),
                        'icon' => 'fa fa-th-large',
                        'url' => action([\App\Http\Controllers\SellPosController::class, 'create']),
                        'parent_id' => 6,
                        'order' => $childOrder++,
                        'is_visible' => true,
                        'is_system' => true
                    ];
                }

                $menuStructure[] = [
                    'id' => 6,
                    'name' => trans('sale.sale'),
                    'icon' => 'fa fa-arrow-circle-up',
                    'url' => '#',
                    'parent_id' => null,
                    'order' => $order++,
                    'is_visible' => true,
                    'is_system' => true,
                    'children' => $salesChildren
                ];
            }

            return $menuStructure;
            
        } catch (\Exception $e) {
            // Return fallback menu if there's any error
            return $this->getFallbackMenuStructure();
        }
    }

    private function getFallbackMenuStructure()
    {
        return [
            [
                'id' => 1,
                'name' => 'Home',
                'icon' => 'fa fa-home',
                'url' => '/home',
                'parent_id' => null,
                'order' => 1,
                'is_visible' => true,
                'is_system' => true,
                'children' => []
            ],
            [
                'id' => 2,
                'name' => 'Users',
                'icon' => 'fa fa-users',
                'url' => '/users',
                'parent_id' => null,
                'order' => 2,
                'is_visible' => true,
                'is_system' => true,
                'children' => []
            ],
            [
                'id' => 3,
                'name' => 'Products',
                'icon' => 'fa fa-cubes',
                'url' => '/products',
                'parent_id' => null,
                'order' => 3,
                'is_visible' => true,
                'is_system' => true,
                'children' => []
            ]
        ];
    }

    /**
     * Format saved menu items
     */
    private function formatSavedMenuItems($savedItems)
    {
        $formatted = [];
        foreach ($savedItems as $item) {
            $formatted[] = [
                'id' => $item->id,
                'name' => $item->name,
                'icon' => $item->icon,
                'url' => $item->url,
                'parent_id' => $item->parent_id,
                'order' => $item->order,
                'is_visible' => $item->is_visible,
                'is_system' => $item->is_system,
                'children' => $item->children->map(function($child) {
                    return [
                        'id' => $child->id,
                        'name' => $child->name,
                        'icon' => $child->icon,
                        'url' => $child->url,
                        'parent_id' => $child->parent_id,
                        'order' => $child->order,
                        'is_visible' => $child->is_visible,
                        'is_system' => $child->is_system
                    ];
                })->toArray()
            ];
        }
        return $formatted;
    }

    /**
     * Save menu configuration
     */
    public function save(Request $request)
    {
        try {
            DB::beginTransaction();
            
            $menuData = $request->json()->all();
            
            // Clear existing menu items
            MenuSetting::truncate();
            
            // Save new menu structure
            foreach ($menuData as $index => $item) {
                $menuItem = new MenuSetting();
                $menuItem->name = $item['name'] ?? '';
                $menuItem->icon = $item['icon'] ?? '';
                $menuItem->url = $item['url'] ?? '';
                $menuItem->parent_id = $item['parent_id'] ?? null;
                $menuItem->order = $index;
                $menuItem->is_visible = $item['is_visible'] ?? true;
                $menuItem->is_system = $item['is_system'] ?? false;
                $menuItem->save();
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Menu configuration saved successfully!'
            ]);
            
        } catch (\Exception $e) {
            DB::rollback();
            
            return response()->json([
                'success' => false,
                'message' => 'Error saving menu configuration: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete menu item
     */
    public function delete(Request $request)
    {
        try {
            $itemId = $request->input('id');
            
            if (!$itemId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Menu item ID is required'
                ], 400);
            }
            
            $menuItem = MenuSetting::find($itemId);
            
            if (!$menuItem) {
                return response()->json([
                    'success' => false,
                    'message' => 'Menu item not found'
                ], 404);
            }
            
            $menuItem->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Menu item deleted successfully!'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting menu item: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update menu order (AJAX endpoint for saving menu configuration)
     */
    public function updateOrder(Request $request)
    {
        try {
            $menuData = $request->all();
            
            if (empty($menuData)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No menu data provided'
                ], 400);
            }
            
            $business_id = request()->session()->get('user.business_id');
            
            foreach ($menuData as $item) {
                MenuSetting::updateOrCreate(
                    [
                        'business_id' => $business_id,
                        'menu_id' => $item['id']
                    ],
                    [
                        'name' => $item['name'],
                        'icon' => $item['icon'],
                        'url' => $item['url'],
                        'parent_id' => $item['parent_id'],
                        'order' => $item['order'],
                        'is_visible' => $item['is_visible'],
                        'is_system' => $item['is_system'] ?? true
                    ]
                );
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Menu configuration saved successfully!'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error updating menu order: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error saving menu configuration: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle menu item visibility
     */
    public function toggleVisibility(Request $request)
    {
        try {
            $itemId = $request->input('id');
            $isVisible = $request->input('is_visible');
            
            if (!$itemId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Menu item ID is required'
                ], 400);
            }
            
            $business_id = request()->session()->get('user.business_id');
            
            MenuSetting::updateOrCreate(
                [
                    'business_id' => $business_id,
                    'menu_id' => $itemId
                ],
                [
                    'is_visible' => $isVisible
                ]
            );
            
            return response()->json([
                'success' => true,
                'message' => 'Menu visibility updated successfully!'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating menu visibility: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update menu item details
     */
    public function updateMenuItem(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required',
                'name' => 'required|string|max:255',
                'url' => 'required|string|max:255',
                'icon' => 'nullable|string'
            ]);
            
            $business_id = request()->session()->get('user.business_id');
            
            MenuSetting::updateOrCreate(
                [
                    'business_id' => $business_id,
                    'menu_id' => $request->input('id')
                ],
                [
                    'name' => $request->input('name'),
                    'icon' => $request->input('icon'),
                    'url' => $request->input('url')
                ]
            );
            
            return response()->json([
                'success' => true,
                'message' => 'Menu item updated successfully!'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating menu item: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Add new menu item
     */
    public function addMenuItem(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'url' => 'required|string|max:255',
                'icon' => 'nullable|string',
                'parent_id' => 'nullable|integer'
            ]);
            
            $business_id = request()->session()->get('user.business_id');
            
            // Get next order number
            $nextOrder = MenuSetting::where('business_id', $business_id)
                ->where('parent_id', $request->input('parent_id'))
                ->max('order') + 1;
            
            // Generate unique menu_id
            $menuId = MenuSetting::where('business_id', $business_id)->max('menu_id') + 1;
            
            $menuItem = MenuSetting::create([
                'business_id' => $business_id,
                'menu_id' => $menuId,
                'name' => $request->input('name'),
                'icon' => $request->input('icon'),
                'url' => $request->input('url'),
                'parent_id' => $request->input('parent_id'),
                'order' => $nextOrder,
                'is_visible' => true,
                'is_system' => false
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Menu item added successfully!',
                'item' => $menuItem
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error adding menu item: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete menu item
     */
    public function deleteMenuItem(Request $request)
    {
        try {
            $itemId = $request->input('id');
            
            if (!$itemId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Menu item ID is required'
                ], 400);
            }
            
            $business_id = request()->session()->get('user.business_id');
            
            $menuItem = MenuSetting::where('business_id', $business_id)
                ->where('menu_id', $itemId)
                ->first();
            
            if (!$menuItem) {
                return response()->json([
                    'success' => false,
                    'message' => 'Menu item not found'
                ], 404);
            }
            
            // Check if it's a system menu item
            if ($menuItem->is_system) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete system menu items'
                ], 403);
            }
            
            // Delete children first
            MenuSetting::where('business_id', $business_id)
                ->where('parent_id', $itemId)
                ->delete();
            
            // Delete the item
            $menuItem->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Menu item deleted successfully!'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting menu item: ' . $e->getMessage()
            ], 500);
        }
    }
}
