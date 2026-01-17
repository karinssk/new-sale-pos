<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryTreeController;

Route::get('/test-subcategory/{categoryId}', function($categoryId) {
    try {
        // Mock authentication and session for testing
        if (!session()->has('user.business_id')) {
            session(['user.business_id' => 1]);
        }
        
        $request = request();
        $request->merge([
            'category_id' => $categoryId,
            'include_subcategories' => true
        ]);
        
        $controller = new CategoryTreeController();
        
        // Use reflection to access the protected method
        $reflection = new ReflectionClass($controller);
        $method = $reflection->getMethod('getCategoryProducts');
        $method->setAccessible(true);
        
        // Mock auth user
        auth()->shouldReceive('user')->andReturn((object)[
            'can' => function() { return true; }
        ]);
        
        $response = $method->invoke($controller, $request);
        
        return $response;
        
    } catch (Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
    }
})->name('test-subcategory');
