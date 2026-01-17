<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * Combines Category and sub-category (supports multi-level hierarchy)
     *
     * @param  int  $business_id
     * @return array
     */
    public static function catAndSubCategories($business_id)
    {
        // Get all categories
        $all_categories = Category::where('business_id', $business_id)
                                ->where('category_type', 'product')
                                ->whereNull('deleted_at')
                                ->orderBy('name', 'asc')
                                ->get()
                                ->toArray();

        if (empty($all_categories)) {
            return [];
        }

        // Build hierarchical structure
        return self::buildCategoryHierarchy($all_categories);
    }

    /**
     * Build hierarchical category structure recursively
     */
    private static function buildCategoryHierarchy($categories, $parent_id = 0)
    {
        $branch = [];

        foreach ($categories as $category) {
            if ($category['parent_id'] == $parent_id) {
                $children = self::buildCategoryHierarchy($categories, $category['id']);
                if ($children) {
                    $category['sub_categories'] = $children;
                } else {
                    $category['sub_categories'] = [];
                }
                $branch[] = $category;
            }
        }

        return $branch;
    }

    /**
     * Get all categories in flat format with level indicators (for dropdowns)
     */
    public static function getAllCategoriesFlat($business_id, $parent_id = 0, $level = 0, $prefix = '')
    {
        $result = [];
        
        $categories = Category::where('business_id', $business_id)
                            ->where('parent_id', $parent_id)
                            ->where('category_type', 'product')
                            ->whereNull('deleted_at')
                            ->orderBy('name', 'asc')
                            ->get();

        foreach ($categories as $category) {
            $indent = str_repeat('├─ ', $level);
            $levelIndicator = " (L" . ($level + 1) . ")";
            $category->display_name = $prefix . $indent . $category->name . $levelIndicator;
            $category->level = $level + 1;
            
            $result[] = $category;
            
            // Recursively get children
            $children = self::getAllCategoriesFlat($business_id, $category->id, $level + 1, $prefix);
            $result = array_merge($result, $children);
        }
        
        return $result;
    }

    /**
     * Category Dropdown
     *
     * @param  int  $business_id
     * @param  string  $type category type
     * @return array
     */
    public static function forDropdown($business_id, $type)
    {
        $categories = Category::where('business_id', $business_id)
                            ->where('parent_id', 0)
                            ->where('category_type', $type)
                            ->select(DB::raw('IF(short_code IS NOT NULL, CONCAT(name, "-", short_code), name) as name'), 'id')
                            ->orderBy('name', 'asc')
                            ->get();

        $dropdown = $categories->pluck('name', 'id');

        return $dropdown;
    }

    public function sub_categories()
    {
        return $this->hasMany(\App\Category::class, 'parent_id');
    }

    /**
     * Get products belonging to this category
     */
    public function products()
    {
        return $this->hasMany(\App\Product::class, 'category_id');
    }

    /**
     * Get subcategories (children) of this category
     */
    public function children()
    {
        return $this->hasMany(\App\Category::class, 'parent_id', 'id')
                    ->with(['children', 'products'])
                    ->withCount('products')
                    ->orderBy('name');
    }

    /**
     * Get parent category
     */
    public function parent()
    {
        return $this->belongsTo(\App\Category::class, 'parent_id');
    }

    /**
     * Get all descendants (children, grandchildren, etc.) recursively
     */
    public function descendants()
    {
        return $this->children()->with('descendants');
    }

    /**
     * Get all ancestors (parent, grandparent, etc.) up to root
     */
    public function ancestors()
    {
        $ancestors = collect();
        $parent = $this->parent;
        
        while ($parent) {
            $ancestors->push($parent);
            $parent = $parent->parent;
        }
        
        return $ancestors;
    }

    /**
     * Get the category path from root to this category
     */
    public function getCategoryPath($separator = ' > ')
    {
        $ancestors = $this->ancestors()->reverse();
        $path = $ancestors->pluck('name')->toArray();
        $path[] = $this->name;
        
        return implode($separator, $path);
    }

    /**
     * Get the depth level of this category (root = 1, child = 2, etc.)
     */
    public function getDepthLevel()
    {
        return $this->ancestors()->count() + 1;
    }

    /**
     * Check if this category is a descendant of the given category
     */
    public function isDescendantOf($category)
    {
        return $this->ancestors()->contains('id', $category->id);
    }

    /**
     * Get all categories at a specific depth level
     */
    public static function getCategoriesByDepth($business_id, $depth)
    {
        $categories = static::where('business_id', $business_id)
            ->where('category_type', 'product')
            ->whereNull('deleted_at')
            ->with(['ancestors'])
            ->get()
            ->filter(function($category) use ($depth) {
                return $category->getDepthLevel() == $depth;
            });
            
        return $categories;
    }

    /**
     * Scope a query to only include main categories.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOnlyParent($query)
    {
        return $query->where('parent_id', 0);
    }
}
