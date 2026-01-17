<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Add enable_level to business table to control level feature
        Schema::table('business', function (Blueprint $table) {
            $table->boolean('enable_level')->after('enable_position')->default(false);
        });

        // Add level field to product_racks table
        Schema::table('product_racks', function (Blueprint $table) {
            $table->integer('level')->after('position')->nullable()->comment('Shelf level number for product location');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('business', function (Blueprint $table) {
            $table->dropColumn('enable_level');
        });

        Schema::table('product_racks', function (Blueprint $table) {
            $table->dropColumn('level');
        });
    }
};
