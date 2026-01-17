<?php

// Multi-Level Category Routes
Route::middleware(['setData', 'auth', 'SetSessionData', 'language', 'timezone', 'AdminSidebarMenu', 'CheckUserLogin'])->group(function () {
    
    // Category API Routes for Multi-Level System
    Route::get('/categories/by-parent', [App\Http\Controllers\CategoryController::class, 'getCategoriesByParent'])->name('categories.by-parent');
    Route::get('/categories/by-level', [App\Http\Controllers\CategoryController::class, 'getCategoriesByLevel'])->name('categories.by-level');
    Route::get('/categories/path', [App\Http\Controllers\CategoryController::class, 'getCategoryPath'])->name('categories.path');
    Route::get('/categories/tree', [App\Http\Controllers\CategoryController::class, 'getCategoryTree'])->name('categories.tree');
    
    // Products V2 Routes - Multi-Level Categories
    Route::get('/products-v2/all', [App\Http\Controllers\ProductV2Controller::class, 'getAllProducts'])->name('products-v2.all');
    Route::get('/products-v2/by-subcategory', [App\Http\Controllers\ProductV2Controller::class, 'getProductsBySubcategory'])->name('products-v2.by-subcategory');
    Route::get('/products-v2/view/{id}', [App\Http\Controllers\ProductV2Controller::class, 'view'])->name('products-v2.view');
    
    Route::resource('products-v2', App\Http\Controllers\ProductV2Controller::class)->names([
        'index' => 'products-v2.index',
        'create' => 'products-v2.create',
        'store' => 'products-v2.store',
        'show' => 'products-v2.show',
        'edit' => 'products-v2.edit',
        'update' => 'products-v2.update',
        'destroy' => 'products-v2.destroy',
    ]);
    
});