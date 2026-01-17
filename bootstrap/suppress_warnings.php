<?php

// Comprehensive deprecation warning suppression for PHP 8.4 + Laravel 9

// Set error reporting to exclude all deprecation warnings
error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);

// Set custom error handler to catch any remaining deprecation warnings
set_error_handler(function ($severity, $message, $file, $line) {
    // Completely ignore deprecation warnings
    if ($severity === E_DEPRECATED || $severity === E_USER_DEPRECATED) {
        return true;
    }
    
    // Also suppress specific Laravel/PHP compatibility warnings
    if (stripos($message, 'deprecated') !== false || 
        stripos($message, 'implicitly marking parameter') !== false) {
        return true;
    }
    
    // For all other errors, use the default handler
    return false;
});

// Also set ini setting to suppress deprecation warnings
ini_set('log_errors_max_len', 0);
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
