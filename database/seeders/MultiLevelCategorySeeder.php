<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Category;

class MultiLevelCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $business_id = 1; // Default business ID
        $user_id = 1; // Default user ID

        // Level 1 Categories
        $electronics = Category::create([
            'name' => 'Electronics',
            'business_id' => $business_id,
            'parent_id' => 0,
            'created_by' => $user_id,
            'category_type' => 'product',
            'short_code' => 'ELEC',
            'description' => 'Electronic devices and accessories'
        ]);

        $clothing = Category::create([
            'name' => 'Clothing & Apparel',
            'business_id' => $business_id,
            'parent_id' => 0,
            'created_by' => $user_id,
            'category_type' => 'product',
            'short_code' => 'CLOTH',
            'description' => 'Clothing and fashion items'
        ]);

        $home = Category::create([
            'name' => 'Home & Garden',
            'business_id' => $business_id,
            'parent_id' => 0,
            'created_by' => $user_id,
            'category_type' => 'product',
            'short_code' => 'HOME',
            'description' => 'Home and garden products'
        ]);

        // Level 2 Categories - Electronics
        $computers = Category::create([
            'name' => 'Computers',
            'business_id' => $business_id,
            'parent_id' => $electronics->id,
            'created_by' => $user_id,
            'category_type' => 'product',
            'short_code' => 'COMP',
            'description' => 'Computer hardware and accessories'
        ]);

        $mobile = Category::create([
            'name' => 'Mobile Devices',
            'business_id' => $business_id,
            'parent_id' => $electronics->id,
            'created_by' => $user_id,
            'category_type' => 'product',
            'short_code' => 'MOB',
            'description' => 'Mobile phones and tablets'
        ]);

        $audio = Category::create([
            'name' => 'Audio & Video',
            'business_id' => $business_id,
            'parent_id' => $electronics->id,
            'created_by' => $user_id,
            'category_type' => 'product',
            'short_code' => 'AV',
            'description' => 'Audio and video equipment'
        ]);

        // Level 2 Categories - Clothing
        $mens = Category::create([
            'name' => 'Men\'s Clothing',
            'business_id' => $business_id,
            'parent_id' => $clothing->id,
            'created_by' => $user_id,
            'category_type' => 'product',
            'short_code' => 'MENS',
            'description' => 'Men\'s clothing and accessories'
        ]);

        $womens = Category::create([
            'name' => 'Women\'s Clothing',
            'business_id' => $business_id,
            'parent_id' => $clothing->id,
            'created_by' => $user_id,
            'category_type' => 'product',
            'short_code' => 'WOMENS',
            'description' => 'Women\'s clothing and accessories'
        ]);

        // Level 3 Categories - Computers
        $laptops = Category::create([
            'name' => 'Laptops',
            'business_id' => $business_id,
            'parent_id' => $computers->id,
            'created_by' => $user_id,
            'category_type' => 'product',
            'short_code' => 'LAPTOP',
            'description' => 'Laptop computers'
        ]);

        $desktops = Category::create([
            'name' => 'Desktop Computers',
            'business_id' => $business_id,
            'parent_id' => $computers->id,
            'created_by' => $user_id,
            'category_type' => 'product',
            'short_code' => 'DESKTOP',
            'description' => 'Desktop computers'
        ]);

        $accessories = Category::create([
            'name' => 'Computer Accessories',
            'business_id' => $business_id,
            'parent_id' => $computers->id,
            'created_by' => $user_id,
            'category_type' => 'product',
            'short_code' => 'COMP_ACC',
            'description' => 'Computer accessories and peripherals'
        ]);

        // Level 3 Categories - Men's Clothing
        $shirts = Category::create([
            'name' => 'Shirts',
            'business_id' => $business_id,
            'parent_id' => $mens->id,
            'created_by' => $user_id,
            'category_type' => 'product',
            'short_code' => 'SHIRTS',
            'description' => 'Men\'s shirts'
        ]);

        $pants = Category::create([
            'name' => 'Pants & Trousers',
            'business_id' => $business_id,
            'parent_id' => $mens->id,
            'created_by' => $user_id,
            'category_type' => 'product',
            'short_code' => 'PANTS',
            'description' => 'Men\'s pants and trousers'
        ]);

        // Level 4 Categories - Laptops
        $gaming_laptops = Category::create([
            'name' => 'Gaming Laptops',
            'business_id' => $business_id,
            'parent_id' => $laptops->id,
            'created_by' => $user_id,
            'category_type' => 'product',
            'short_code' => 'GAMING_LAP',
            'description' => 'Gaming laptops'
        ]);

        $business_laptops = Category::create([
            'name' => 'Business Laptops',
            'business_id' => $business_id,
            'parent_id' => $laptops->id,
            'created_by' => $user_id,
            'category_type' => 'product',
            'short_code' => 'BUS_LAP',
            'description' => 'Business laptops'
        ]);

        $ultrabooks = Category::create([
            'name' => 'Ultrabooks',
            'business_id' => $business_id,
            'parent_id' => $laptops->id,
            'created_by' => $user_id,
            'category_type' => 'product',
            'short_code' => 'ULTRA',
            'description' => 'Ultrabook laptops'
        ]);

        // Level 4 Categories - Shirts
        $formal_shirts = Category::create([
            'name' => 'Formal Shirts',
            'business_id' => $business_id,
            'parent_id' => $shirts->id,
            'created_by' => $user_id,
            'category_type' => 'product',
            'short_code' => 'FORMAL',
            'description' => 'Formal shirts'
        ]);

        $casual_shirts = Category::create([
            'name' => 'Casual Shirts',
            'business_id' => $business_id,
            'parent_id' => $shirts->id,
            'created_by' => $user_id,
            'category_type' => 'product',
            'short_code' => 'CASUAL',
            'description' => 'Casual shirts'
        ]);

        // Level 5 Categories - Gaming Laptops
        Category::create([
            'name' => 'Budget Gaming',
            'business_id' => $business_id,
            'parent_id' => $gaming_laptops->id,
            'created_by' => $user_id,
            'category_type' => 'product',
            'short_code' => 'BUDGET_GAM',
            'description' => 'Budget gaming laptops'
        ]);

        Category::create([
            'name' => 'High-End Gaming',
            'business_id' => $business_id,
            'parent_id' => $gaming_laptops->id,
            'created_by' => $user_id,
            'category_type' => 'product',
            'short_code' => 'HIGH_GAM',
            'description' => 'High-end gaming laptops'
        ]);

        Category::create([
            'name' => 'Professional Gaming',
            'business_id' => $business_id,
            'parent_id' => $gaming_laptops->id,
            'created_by' => $user_id,
            'category_type' => 'product',
            'short_code' => 'PRO_GAM',
            'description' => 'Professional gaming laptops'
        ]);

        // Level 5 Categories - Formal Shirts
        Category::create([
            'name' => 'Cotton Formal',
            'business_id' => $business_id,
            'parent_id' => $formal_shirts->id,
            'created_by' => $user_id,
            'category_type' => 'product',
            'short_code' => 'COTTON_FOR',
            'description' => 'Cotton formal shirts'
        ]);

        Category::create([
            'name' => 'Silk Formal',
            'business_id' => $business_id,
            'parent_id' => $formal_shirts->id,
            'created_by' => $user_id,
            'category_type' => 'product',
            'short_code' => 'SILK_FOR',
            'description' => 'Silk formal shirts'
        ]);

        Category::create([
            'name' => 'Linen Formal',
            'business_id' => $business_id,
            'parent_id' => $formal_shirts->id,
            'created_by' => $user_id,
            'category_type' => 'product',
            'short_code' => 'LINEN_FOR',
            'description' => 'Linen formal shirts'
        ]);

        echo "Multi-level categories seeded successfully!\n";
    }
}