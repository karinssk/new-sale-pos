<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Fix inconsistent category data where multi-level categories exist but legacy fields are null
        DB::statement("
            UPDATE products 
            SET category_id = category_l1_id 
            WHERE category_l1_id IS NOT NULL 
            AND category_id IS NULL
        ");
        
        // Set sub_category_id to the deepest level category
        DB::statement("
            UPDATE products 
            SET sub_category_id = COALESCE(category_l5_id, category_l4_id, category_l3_id, category_l2_id) 
            WHERE category_l1_id IS NOT NULL 
            AND (category_l2_id IS NOT NULL OR category_l3_id IS NOT NULL OR category_l4_id IS NOT NULL OR category_l5_id IS NOT NULL)
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // This migration fixes data integrity, so we don't reverse it
    }
};
