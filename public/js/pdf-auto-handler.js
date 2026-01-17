/**
 * PDF Auto Handler - Handles PDF route navigation with login-based authentication
 * This script converts PDF route clicks to AJAX requests and then navigates to the PDF
 */
(function() {
    'use strict';
    
    console.log('PDF Auto Handler: Script loaded and initializing...');

    // PDF route patterns that should be handled automatically
    const PDF_ROUTE_PATTERNS = [
        /\/tax-invoice\/(\d+)\/pdf-print-nodejs/,
        /\/billing-receipt\/(\d+)\/pdf-print-nodejs/,
        /\/quotations\/(\d+)\/pdf-print-nodejs/
    ];

    /**
     * Handle PDF route by making AJAX request and navigating to PDF
     */
    async function handlePDFRoute(url) {
        console.log('PDF Auto Handler: Processing URL:', url);
        
        try {
            // Show loading indicator if available
            if (typeof showLoadingIndicator === 'function') {
                showLoadingIndicator();
            }

            // Extract document type and ID from URL
            let documentType, transactionId;
            
            if (url.includes('/tax-invoice/')) {
                const match = url.match(/\/tax-invoice\/(\d+)\/pdf-print-nodejs/);
                if (match) {
                    documentType = 'tax-invoice';
                    transactionId = match[1];
                }
            } else if (url.includes('/billing-receipt/')) {
                const match = url.match(/\/billing-receipt\/(\d+)\/pdf-print-nodejs/);
                if (match) {
                    documentType = 'billing-receipt';
                    transactionId = match[1];
                }
            } else if (url.includes('/quotations/')) {
                const match = url.match(/\/quotations\/(\d+)\/pdf-print-nodejs/);
                if (match) {
                    documentType = 'quotations';
                    transactionId = match[1];
                }
            }

            if (!documentType || !transactionId) {
                console.error('PDF Auto Handler: Failed to extract document type and ID from URL:', url);
                throw new Error('Invalid PDF URL format');
            }

            console.log('PDF Auto Handler: Extracted:', { documentType, transactionId });

            let pdfUrl;

            // Now get the PDF directly - no need for secure tokens since we use login-based auth
            console.log('PDF Auto Handler: Making request to URL:', url);
            const response = await fetch(url, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            console.log('PDF Auto Handler: Response status:', response.status);
            console.log('PDF Auto Handler: Response headers:', Object.fromEntries(response.headers.entries()));

            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }

            const result = await response.json();
            console.log('PDF Auto Handler: Response data:', result);

            if (result.success && result.pdf_url) {
                // Navigate current page to PDF
                window.location.href = result.pdf_url;
            } else {
                throw new Error(result.error || 'Failed to generate PDF');
            }

        } catch (error) {
            console.error('PDF handling error:', error);
            
            // Show error message
            if (typeof toastr !== 'undefined') {
                toastr.error('Error: ' + error.message);
            } else if (typeof alert === 'function') {
                alert('Error: ' + error.message);
            }
        } finally {
            // Hide loading indicator if available
            if (typeof hideLoadingIndicator === 'function') {
                hideLoadingIndicator();
            }
        }
    }

    /**
     * Check if URL matches PDF route pattern
     */
    function isPDFRoute(url) {
        return PDF_ROUTE_PATTERNS.some(pattern => pattern.test(url));
    }

    /**
     * Intercept clicks on links that lead to PDF routes
     */
    function interceptPDFLinks() {
        document.addEventListener('click', function(event) {
            const target = event.target.closest('a');
            if (!target) return;

            const href = target.getAttribute('href');
            console.log('PDF Auto Handler: Link clicked:', href);
            
            if (!href || !isPDFRoute(href)) {
                if (href) console.log('PDF Auto Handler: Not a PDF route:', href);
                return;
            }

            console.log('PDF Auto Handler: Intercepting PDF link:', href);

            // Prevent default navigation
            event.preventDefault();
            event.stopPropagation();

            // Handle PDF route via AJAX
            const fullUrl = href.startsWith('/') ? window.location.origin + href : href;
            handlePDFRoute(fullUrl);
        });
    }

    /**
     * Handle direct navigation to PDF routes (e.g., from address bar or window.location)
     */
    function handleDirectNavigation() {
        const currentPath = window.location.pathname;
        
        if (isPDFRoute(currentPath)) {
            // This is a direct navigation to a PDF route
            // Replace current history entry to prevent back button issues
            history.replaceState(null, '', '/sells');
            
            // Handle the PDF route
            handlePDFRoute(window.location.href);
        }
    }

    /**
     * Initialize PDF auto handler
     */
    function init() {
        console.log('PDF Auto Handler: Initializing...');
        
        // Handle direct navigation on page load
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', handleDirectNavigation);
        } else {
            handleDirectNavigation();
        }

        // Intercept future link clicks
        interceptPDFLinks();
        console.log('PDF Auto Handler: Link interception active');

        // Also handle form submissions that might target PDF routes
        document.addEventListener('submit', function(event) {
            const form = event.target;
            const action = form.getAttribute('action');
            
            if (action && isPDFRoute(action)) {
                event.preventDefault();
                
                // Convert form data to URL parameters
                const formData = new FormData(form);
                const params = new URLSearchParams(formData);
                const fullUrl = action + (action.includes('?') ? '&' : '?') + params.toString();
                
                handlePDFRoute(fullUrl);
            }
        });
    }

    // Global function for manual handling
    window.handlePDFRoute = handlePDFRoute;

    // Initialize when script loads
    init();

})();
