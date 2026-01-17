<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMultiLevelCategoryToProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            // Add multi-level category fields
            $table->integer('category_l1_id')->unsigned()->nullable()->after('sub_category_id');
            $table->integer('category_l2_id')->unsigned()->nullable()->after('category_l1_id');
            $table->integer('category_l3_id')->unsigned()->nullable()->after('category_l2_id');
            $table->integer('category_l4_id')->unsigned()->nullable()->after('category_l3_id');
            $table->integer('category_l5_id')->unsigned()->nullable()->after('category_l4_id');
            
            // Add indexes for better performance
            $table->index('category_l1_id');
            $table->index('category_l2_id');
            $table->index('category_l3_id');
            $table->index('category_l4_id');
            $table->index('category_l5_id');
            
            // Add foreign key constraints
            $table->foreign('category_l1_id')->references('id')->on('categories')->onDelete('set null');
            $table->foreign('category_l2_id')->references('id')->on('categories')->onDelete('set null');
            $table->foreign('category_l3_id')->references('id')->on('categories')->onDelete('set null');
            $table->foreign('category_l4_id')->references('id')->on('categories')->onDelete('set null');
            $table->foreign('category_l5_id')->references('id')->on('categories')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            // Drop foreign key constraints first
            $table->dropForeign(['category_l1_id']);
            $table->dropForeign(['category_l2_id']);
            $table->dropForeign(['category_l3_id']);
            $table->dropForeign(['category_l4_id']);
            $table->dropForeign(['category_l5_id']);
            
            // Drop indexes
            $table->dropIndex(['category_l1_id']);
            $table->dropIndex(['category_l2_id']);
            $table->dropIndex(['category_l3_id']);
            $table->dropIndex(['category_l4_id']);
            $table->dropIndex(['category_l5_id']);
            
            // Drop columns
            $table->dropColumn([
                'category_l1_id',
                'category_l2_id',
                'category_l3_id',
                'category_l4_id',
                'category_l5_id'
            ]);
        });
    }
}