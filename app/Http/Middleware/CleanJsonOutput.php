<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CleanJsonOutput
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Only apply to AJAX requests that expect JSON
        if ($request->ajax() || $request->expectsJson()) {
            // Start output buffering
            ob_start();
            
            // Temporarily disable error output
            $oldErrorReporting = error_reporting(0);
            
            try {
                $response = $next($request);
                
                // Clean any unwanted output
                $unwantedOutput = ob_get_clean();
                
                // Log any unwanted output for debugging
                if (!empty($unwantedOutput)) {
                    \Log::warning('Unwanted output in AJAX request: ' . $unwantedOutput);
                }
                
                // Restore error reporting
                error_reporting($oldErrorReporting);
                
                return $response;
            } catch (\Exception $e) {
                // Clean output and restore error reporting
                ob_end_clean();
                error_reporting($oldErrorReporting);
                throw $e;
            }
        }
        
        return $next($request);
    }
}
