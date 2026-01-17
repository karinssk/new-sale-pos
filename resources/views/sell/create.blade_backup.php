@extends('layouts.app')

@php
	if (!empty($status) && $status == 'quotation') {
		$title = __('lang_v1.add_quotation');
	} else if (!empty($status) && $status == 'draft') {
		$title = __('lang_v1.add_draft');
	} else {
		$title = __('sale.add_sale');
	}

	if($sale_type == 'sales_order') {
		$title = __('lang_v1.sales_order');
	}
@endphp

@section('title', $title)

@section('content')

<style>
:root {
    --primary: #374151;
    --primary-dark: #1f2937;
    --primary-light: #f3f4f6;
    --secondary: #6b7280;
    --accent: #9ca3af;
    --success: #10b981;
    --warning: #f59e0b;
    --danger: #ef4444;
    --surface: #ffffff;
    --surface-alt: #f9fafb;
    --background: linear-gradient(135deg, #f8fafc 0%, #e5e7eb 100%);
    --text: #111827;
    --text-muted: #6b7280;
    --text-light: #9ca3af;
    --border: #d1d5db;
    --border-light: #e5e7eb;
    --radius: 12px;
    --radius-lg: 16px;
    --shadow-neumorphic: 20px 20px 60px #d1d5db, -20px -20px 60px #ffffff;
    --shadow-card: 0 4px 25px 0 rgba(17, 24, 39, 0.1);
    --shadow-hover: 0 8px 40px 0 rgba(17, 24, 39, 0.15);
    --backdrop-blur: blur(40px);
}

* {
    box-sizing: border-box;
}

body {
    font-family: 'Inter', 'Segoe UI', system-ui, -apple-system, sans-serif;
    background: #f0f2f5;
    line-height: 1.6;
}

.modern-sale-page {
    background: #f8fafc;
    min-height: 100vh;
    padding: 1rem 0;
    position: relative;
}

.modern-container {
    max-width: 95%;
    margin: 0 auto;
    padding: 0 1rem;
    position: relative;
    z-index: 1;
}

.modern-page-header {
    margin-bottom: 2rem;
    text-align: center;
}

.modern-page-title {
    font-size: 2.25rem;
    font-weight: 700;
    color: var(--text);
    margin: 0;
    text-shadow: none;
}

.modern-page-subtitle {
    color: var(--text-muted);
    font-size: 1rem;
    margin-top: 0.5rem;
    font-weight: 400;
}

.modern-glass-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: var(--backdrop-blur);
    -webkit-backdrop-filter: var(--backdrop-blur);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-card);
    padding: 2rem;
    margin-bottom: 1.5rem;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: visible;
    height: auto;
}

.modern-glass-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 1px;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.8), transparent);
}

.modern-glass-card:hover {
    box-shadow: var(--shadow-hover);
    transform: translateY(-2px);
}

.modern-card-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid var(--border-light);
}

.modern-card-icon {
    width: 3rem;
    height: 3rem;
    border-radius: var(--radius);
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.25rem;
    box-shadow: 0 4px 12px rgba(17, 24, 39, 0.15);
}

.modern-card-title {
    font-size: 1.375rem;
    font-weight: 700;
    color: var(--text);
    margin: 0;
    flex: 1;
}

.modern-form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 1.5rem;
}

.modern-form-group {
    position: relative;
}

.modern-input-group {
    position: relative;
    display: flex;
    flex-direction: column;
}

.modern-label {
    display: block;
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--text);
    margin-bottom: 0.75rem;
    transition: color 0.2s ease;
}

.modern-input-wrapper {
    position: relative;
    display: flex;
    align-items: center;
}

.modern-input {
    width: 100%;
    padding: 1rem 1.25rem;
    font-size: 1rem;
    line-height: 1.5;
    color: var(--text);
    background: var(--surface);
    border: 2px solid var(--border);
    border-radius: var(--radius);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: inset 0 2px 4px rgba(0,0,0,0.06);
}

.modern-input:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 4px rgba(17, 24, 39, 0.1), inset 0 2px 4px rgba(0,0,0,0.06);
    transform: translateY(-1px);
}

.modern-input:focus + .modern-label {
    color: var(--primary);
}

.modern-input-icon {
    position: absolute;
    left: 1rem;
    color: var(--text-muted);
    pointer-events: none;
    z-index: 2;
}

.modern-input.has-icon {
    padding-left: 3rem;
}

.modern-select {
    appearance: none;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/path%3e%3c/svg%3e");
    background-position: right 0.75rem center;
    background-repeat: no-repeat;
    background-size: 1.25rem;
    padding-right: 3rem;
}

.modern-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 0.875rem 2rem;
    font-size: 1rem;
    font-weight: 600;
    line-height: 1.5;
    border-radius: var(--radius);
    border: none;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    text-transform: uppercase;
    letter-spacing: 0.025em;
    position: relative;
    overflow: hidden;
}

.modern-btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: left 0.5s ease;
}

.modern-btn:hover::before {
    left: 100%;
}

.modern-btn-primary {
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    color: white;
    box-shadow: 0 4px 15px rgba(17, 24, 39, 0.2);
}

.modern-btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(17, 24, 39, 0.3);
}

.modern-btn-success {
    background: linear-gradient(135deg, var(--success), #059669);
    color: white;
    box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
}

.modern-btn-success:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(16, 185, 129, 0.4);
}

.modern-btn-icon {
    background: var(--surface);
    color: var(--primary);
    border: 2px solid var(--border);
    padding: 0.75rem;
    width: auto;
    height: auto;
    min-width: 3rem;
}

.modern-btn-icon:hover {
    background: var(--primary-light);
    border-color: var(--primary);
    transform: translateY(-1px);
}

/* Enhanced POS Table Styling */
.modern-pos-table-container {
    background: var(--surface);
    border-radius: 12px;
    overflow: visible !important;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    border: 1px solid var(--border);
    height: auto !important;
    max-height: none !important;
    min-height: 500px !important; /* Set minimum height for better display */
}

/* Force minimum height on the pos_product_div container */
.pos_product_div {
    min-height: 550px !important;
    height: auto !important;
}

/* Additional specific targeting to ensure height is applied */
div.pos_product_div .modern-pos-table-container {
    min-height: 500px !important;
}

div.pos_product_div .pos-table-wrapper {
    min-height: 450px !important;
}

/* Ensure the container takes up space even when empty */
.modern-pos-table-container .pos-table-wrapper,
.modern-pos-table-container #pos_table_empty_state {
    min-height: 400px !important;
}

.modern-pos-table {
    width: 100%;
    border-collapse: collapse;
    min-height: 300px; /* Ensure table takes adequate vertical space */
}

/* Ensure table body has proper spacing for multiple products */
.modern-pos-table tbody {
    min-height: 250px;
    display: table-row-group;
}

/* Individual product rows styling for better readability */
.modern-pos-table tbody tr {
    transition: background-color 0.2s ease;
}

.modern-pos-table tbody tr:hover {
    background-color: rgba(var(--primary-rgb), 0.05) !important;
}

/* When products are present, ensure optimal display */
.modern-pos-table-container.has-products {
    min-height: 450px !important;
}

.modern-pos-table-container.has-products .pos-table-wrapper {
    min-height: 400px !important;
}

/* Smooth height transitions */
.modern-pos-table-container {
    transition: min-height 0.3s ease;
}

.pos-table-wrapper {
    transition: min-height 0.3s ease;
}

/* Modern Contact Modal Styling */
.contact_modal .modal-dialog {
    max-width: 900px !important;
    margin: 2rem auto !important;
}

.contact_modal .modal-content {
    border: none !important;
    border-radius: 16px !important;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1) !important;
    background: linear-gradient(135deg, #ffffff, #f8fafc) !important;
    overflow: hidden !important;
}

.contact_modal .modal-header {
    background: linear-gradient(135deg, var(--primary), var(--primary-dark)) !important;
    border-bottom: none !important;
    padding: 1.5rem 2rem !important;
    color: white !important;
}

.contact_modal .modal-title {
    font-size: 1.25rem !important;
    font-weight: 700 !important;
    color: white !important;
    display: flex !important;
    align-items: center !important;
    gap: 0.75rem !important;
}

.contact_modal .modal-title::before {
    content: '👤';
    font-size: 1.5rem;
    display: inline-block;
}

.contact_modal .modal-header .close,
.contact_modal .modal-header .btn-close {
    color: white !important;
    opacity: 0.8 !important;
    font-size: 1.5rem !important;
    background: none !important;
    border: none !important;
    padding: 0.5rem !important;
    border-radius: 50% !important;
    width: 2.5rem !important;
    height: 2.5rem !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    transition: all 0.3s ease !important;
}

.contact_modal .modal-header .close:hover,
.contact_modal .modal-header .btn-close:hover {
    opacity: 1 !important;
    background: rgba(255, 255, 255, 0.1) !important;
    transform: rotate(90deg) !important;
}

.contact_modal .modal-body {
    padding: 2rem !important;
    background: transparent !important;
}

/* Contact Type Toggle Styling */
.contact_modal .contact-type-toggle {
    display: flex !important;
    background: var(--surface-alt) !important;
    border-radius: 12px !important;
    padding: 0.25rem !important;
    margin-bottom: 2rem !important;
    border: 1px solid var(--border) !important;
}

.contact_modal .contact-type-toggle label {
    flex: 1 !important;
    text-align: center !important;
    padding: 0.75rem 1rem !important;
    margin: 0 !important;
    border-radius: 8px !important;
    cursor: pointer !important;
    transition: all 0.3s ease !important;
    font-weight: 600 !important;
    font-size: 0.875rem !important;
    color: var(--text-muted) !important;
}

.contact_modal .contact-type-toggle input[type="radio"] {
    display: none !important;
}

.contact_modal .contact-type-toggle input[type="radio"]:checked + label {
    background: var(--primary) !important;
    color: white !important;
    box-shadow: 0 2px 8px rgba(var(--primary-rgb), 0.3) !important;
}

/* Modern Form Groups */
.contact_modal .form-group {
    margin-bottom: 1.5rem !important;
}

.contact_modal .form-group label {
    font-weight: 600 !important;
    color: var(--text) !important;
    font-size: 0.875rem !important;
    margin-bottom: 0.5rem !important;
    display: block !important;
}

.contact_modal .form-group label.required::after {
    content: ' *';
    color: var(--danger);
}

/* Modern Input Styling */
.contact_modal .form-control,
.contact_modal input,
.contact_modal textarea,
.contact_modal select {
    border: 2px solid var(--border) !important;
    border-radius: 10px !important;
    padding: 0.75rem 1rem !important;
    font-size: 0.875rem !important;
    background: white !important;
    transition: all 0.3s ease !important;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05) !important;
}

.contact_modal .form-control:focus,
.contact_modal input:focus,
.contact_modal textarea:focus,
.contact_modal select:focus {
    border-color: var(--primary) !important;
    box-shadow: 0 0 0 3px rgba(var(--primary-rgb), 0.1) !important;
    outline: none !important;
}

/* Input with Icons */
.contact_modal .input-group {
    position: relative !important;
}

.contact_modal .input-group-addon {
    position: absolute !important;
    left: 1rem !important;
    top: 50% !important;
    transform: translateY(-50%) !important;
    z-index: 3 !important;
    background: none !important;
    border: none !important;
    color: var(--text-muted) !important;
    font-size: 0.875rem !important;
}

.contact_modal .input-group .form-control {
    padding-left: 2.5rem !important;
}

/* Modern Form Layout */
.contact_modal .row {
    margin-left: -0.75rem !important;
    margin-right: -0.75rem !important;
}

.contact_modal .row > [class*="col-"] {
    padding-left: 0.75rem !important;
    padding-right: 0.75rem !important;
}

/* Modern Buttons */
.contact_modal .modal-footer {
    border-top: 1px solid var(--border) !important;
    background: var(--surface-alt) !important;
    padding: 1.5rem 2rem !important;
    display: flex !important;
    gap: 1rem !important;
    justify-content: flex-end !important;
}

.contact_modal .btn {
    border-radius: 8px !important;
    padding: 0.75rem 1.5rem !important;
    font-weight: 600 !important;
    font-size: 0.875rem !important;
    border: none !important;
    transition: all 0.3s ease !important;
    display: inline-flex !important;
    align-items: center !important;
    gap: 0.5rem !important;
}

.contact_modal .btn-primary {
    background: linear-gradient(135deg, var(--primary), var(--primary-dark)) !important;
    color: white !important;
    box-shadow: 0 4px 12px rgba(var(--primary-rgb), 0.3) !important;
}

.contact_modal .btn-primary:hover {
    transform: translateY(-2px) !important;
    box-shadow: 0 6px 16px rgba(var(--primary-rgb), 0.4) !important;
}

.contact_modal .btn-secondary,
.contact_modal .btn-default {
    background: var(--surface) !important;
    color: var(--text) !important;
    border: 2px solid var(--border) !important;
}

.contact_modal .btn-secondary:hover,
.contact_modal .btn-default:hover {
    background: var(--surface-alt) !important;
    border-color: var(--text-muted) !important;
}

/* Modern Select2 Integration */
.contact_modal .select2-container {
    width: 100% !important;
}

.contact_modal .select2-selection {
    border: 2px solid var(--border) !important;
    border-radius: 10px !important;
    min-height: 48px !important;
    background: white !important;
}

.contact_modal .select2-selection:focus,
.contact_modal .select2-container--open .select2-selection {
    border-color: var(--primary) !important;
    box-shadow: 0 0 0 3px rgba(var(--primary-rgb), 0.1) !important;
}

/* Select2 Multiple Selection (Tags) - Fix for Assigned To field */
.contact_modal .select2-selection--multiple {
    padding: 8px 12px !important;
}

.contact_modal .select2-selection--multiple .select2-selection__choice {
    background: linear-gradient(135deg, var(--primary), #4f46e5) !important;
    border: none !important;
    border-radius: 20px !important;
    color: white !important;
    padding: 4px 12px !important;
    margin: 2px 4px 2px 0 !important;
    font-size: 13px !important;
    font-weight: 500 !important;
    display: inline-flex !important;
    align-items: center !important;
    gap: 6px !important;
}

.contact_modal .select2-selection--multiple .select2-selection__choice__remove {
    color: rgba(255, 255, 255, 0.8) !important;
    background: none !important;
    border: none !important;
    font-size: 16px !important;
    font-weight: bold !important;
    cursor: pointer !important;
    padding: 0 !important;
    margin-left: 6px !important;
    width: 16px !important;
    height: 16px !important;
    border-radius: 50% !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    transition: all 0.2s ease !important;
}

.contact_modal .select2-selection--multiple .select2-selection__choice__remove:hover {
    color: white !important;
    background: rgba(255, 255, 255, 0.2) !important;
    transform: scale(1.1) !important;
}

.contact_modal .select2-selection--multiple .select2-selection__choice__remove:active {
    transform: scale(0.95) !important;
}

/* Search field in multiple selection */
.contact_modal .select2-selection--multiple .select2-search--inline .select2-search__field {
    border: none !important;
    outline: none !important;
    background: transparent !important;
    margin: 0 !important;
    padding: 4px 0 !important;
    font-size: 14px !important;
    min-width: 150px !important;
}

/* Dropdown for multiple selection */
.contact_modal .select2-dropdown {
    border: 2px solid var(--border) !important;
    border-radius: 10px !important;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1) !important;
}

.contact_modal .select2-results__option {
    padding: 12px 16px !important;
    border-bottom: 1px solid #f1f5f9 !important;
    transition: all 0.2s ease !important;
}

.contact_modal .select2-results__option:hover,
.contact_modal .select2-results__option--highlighted {
    background: linear-gradient(135deg, #f8fafc, #e2e8f0) !important;
    color: var(--text) !important;
}

.contact_modal .select2-results__option[aria-selected="true"] {
    background: linear-gradient(135deg, var(--primary), #4f46e5) !important;
    color: white !important;
}

/* Additional fixes for Assigned To field issues */
.contact_modal .select2-selection--multiple .select2-selection__rendered {
    display: flex !important;
    flex-wrap: wrap !important;
    align-items: center !important;
    gap: 4px !important;
    padding: 0 !important;
}

/* Fix the remove button (×) to be functional and visible */
.contact_modal .select2-selection__choice__remove {
    color: rgba(255, 255, 255, 0.8) !important;
    background: none !important;
    border: none !important;
    font-size: 16px !important;
    font-weight: bold !important;
    cursor: pointer !important;
    padding: 0 4px !important;
    margin-left: 6px !important;
    width: auto !important;
    height: auto !important;
    border-radius: 0 !important;
    display: inline-block !important;
    line-height: 1 !important;
    text-align: center !important;
    transition: all 0.2s ease !important;
    /* Don't override the default behavior */
    pointer-events: auto !important;
    position: relative !important;
}

.contact_modal .select2-selection__choice__remove:hover {
    color: white !important;
    background: rgba(255, 255, 255, 0.2) !important;
    border-radius: 3px !important;
}

.contact_modal .select2-selection__choice__remove:active {
    transform: scale(0.95) !important;
}

/* Fix for when Select2 container is too small */
.contact_modal .select2-container--default .select2-selection--multiple {
    min-height: 48px !important;
    display: flex !important;
    align-items: flex-start !important;
}

/* Ensure proper z-index for dropdown */
.contact_modal .select2-dropdown {
    z-index: 9999 !important;
}

/* Fix for modal positioning issues */
.contact_modal.modal {
    z-index: 1050 !important;
}

.select2-container--open {
    z-index: 9999 !important;
}

/* Critical fixes for the x button functionality */
.contact_modal .select2-selection--multiple .select2-selection__choice {
    position: relative !important;
    display: inline-flex !important;
    align-items: center !important;
}

/* Ensure the remove button is not blocked by other elements */
.contact_modal .select2-selection__choice__remove {
    z-index: 10 !important;
    /* Reset any overriding styles that might block clicking */
    opacity: 0.8 !important;
    text-decoration: none !important;
    outline: none !important;
}

/* Remove any CSS that might interfere with Select2's default behavior */
.contact_modal .select2-selection__choice__remove::before {
    content: none !important;
}

/* Ensure proper mouse events */
.contact_modal .select2-selection__choice,
.contact_modal .select2-selection__choice__remove {
    user-select: none !important;
    -webkit-user-select: none !important;
    -moz-user-select: none !important;
    -ms-user-select: none !important;
}

/* Section Dividers */
.contact_modal .section-divider {
    border-top: 1px solid var(--border) !important;
    margin: 2rem 0 1.5rem 0 !important;
    padding-top: 1.5rem !important;
    position: relative !important;
}

.contact_modal .section-divider::before {
    content: attr(data-title);
    position: absolute !important;
    top: -0.5rem !important;
    left: 1rem !important;
    background: linear-gradient(135deg, #ffffff, #f8fafc) !important;
    padding: 0 1rem !important;
    font-size: 0.75rem !important;
    font-weight: 700 !important;
    color: var(--text-muted) !important;
    text-transform: uppercase !important;
    letter-spacing: 0.05em !important;
}

/* Responsive Design */
@media (max-width: 768px) {
    .contact_modal .modal-dialog {
        max-width: 95% !important;
        margin: 1rem auto !important;
    }
    
    .contact_modal .modal-header,
    .contact_modal .modal-body,
    .contact_modal .modal-footer {
        padding: 1rem !important;
    }
    
    .contact_modal .row > [class*="col-"] {
        margin-bottom: 1rem !important;
    }
}

/* Animation */
.contact_modal.fade .modal-dialog {
    transform: translateY(-50px) scale(0.95) !important;
    transition: all 0.3s ease !important;
}

.contact_modal.show .modal-dialog {
    transform: translateY(0) scale(1) !important;
}

.modern-pos-table thead th {
    background: linear-gradient(135deg, #ffffff, #f8fafc);
    padding: 1rem 1.25rem;
    text-align: left;
    position: sticky;
    top: 0;
    z-index: 10;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    font-size: 0.75rem;
    font-weight: 700;
    color: var(--text);
    text-transform: uppercase;
    letter-spacing: 0.05em;
    border-bottom: 2px solid var(--border);
    white-space: nowrap;
}

.modern-pos-table tbody td {
    padding: 1rem 1.25rem;
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    vertical-align: middle;
    font-size: 0.875rem;
    min-height: 240px;
    height: auto;
}

.modern-pos-table tbody td:first-child {
    text-align: center;
    padding: 1rem 0.5rem;
    min-height: 240px;
    vertical-align: middle;
}

.modern-pos-table tbody td:nth-child(2) .product-name {
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 0.25rem;
    font-size: 0.95rem;
}

.modern-pos-table tbody td:nth-child(2) .product-sku {
    font-size: 0.8rem;
    color: var(--text-muted);
    font-weight: 400;
}

.modern-pos-table tbody tr {
    transition: all 0.2s ease;
    border-left: 3px solid transparent;
}

.modern-pos-table tbody tr:hover {
    background: linear-gradient(135deg, #f8fafc, #f1f5f9);
    border-left-color: var(--primary);
    transform: translateX(2px);
}

.modern-pos-table tbody tr:last-child td {
    border-bottom: none;
}

/* Product row enhancements */
.product_row td:nth-child(2) {
    font-weight: 600;
    color: var(--text);
}

.product_row .product-name {
    display: block;
    font-weight: 600;
    font-size: 0.875rem;
    color: var(--text);
    margin-bottom: 0.25rem;
}

.product_row .product-sku {
    display: block;
    font-size: 0.75rem;
    color: var(--text-muted);
    opacity: 0.8;
}

/* Product image styling in table */
.product-table-image {
    width: 200px;
    height: 200px;
    border-radius: 8px;
    object-fit: cover;
    border: 2px solid var(--border);
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.product-table-image:hover {
    transform: scale(1.1);
    border-color: var(--primary);
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
}

.product-image-container {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 220px;
    height: 220px;
    border-radius: 10px;
    background: var(--surface-alt);
    border: 1px solid var(--border);
    position: relative;
    overflow: hidden;
    margin: 0 auto;
    cursor: pointer;
    transition: all 0.3s ease;
}

.product-image-container:hover {
    border-color: var(--primary);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.15);
    transform: translateY(-2px);
}

.product-image-placeholder {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #f8fafc, #e2e8f0);
    color: var(--text-muted);
    font-size: 1.25rem;
}

.product-image-loading {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.9);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.75rem;
    color: var(--text-muted);
}

/* Table input styling */
.modern-pos-table input[type="text"],
.modern-pos-table input[type="number"],
.modern-pos-table select {
    border: 1px solid var(--border);
    border-radius: 6px;
    padding: 0.5rem 0.75rem;
    font-size: 0.875rem;
    background: var(--surface);
    color: var(--text);
    transition: all 0.2s ease;
    width: 100%;
}

.modern-pos-table input[type="text"]:focus,
.modern-pos-table input[type="number"]:focus,
.modern-pos-table select:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    outline: none;
}

/* Remove button styling */
.remove_product_row {
    background: linear-gradient(135deg, var(--danger), #dc2626) !important;
    color: white !important;
    border: none !important;
    border-radius: 6px !important;
    padding: 0.5rem !important;
    width: 2rem !important;
    height: 2rem !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    transition: all 0.2s ease !important;
    font-size: 0.75rem !important;
}

.remove_product_row:hover {
    transform: scale(1.1) !important;
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3) !important;
}

/* Products count badge animation */
.products-count-badge {
    transition: all 0.3s ease !important;
}

.products-count-badge.updated {
    transform: scale(1.2);
    animation: countPulse 0.3s ease;
}

@keyframes countPulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.2); }
    100% { transform: scale(1); }
}

/* Empty state styling */
#pos_table_empty_state {
    background: linear-gradient(135deg, #f8fafc, #f1f5f9);
    border-top: 1px solid var(--border);
    min-height: 300px;
    display: flex !important;
    align-items: center;
    justify-content: center;
}

/* Hide empty state when products are present */
.modern-pos-table-container.has-products #pos_table_empty_state,
#pos_table_empty_state.hidden {
    display: none !important;
    visibility: hidden !important;
    height: 0 !important;
    min-height: 0 !important;
    padding: 0 !important;
    margin: 0 !important;
    opacity: 0 !important;
}

/* Table wrapper - full height display with expanded space for multiple products */
.pos-table-wrapper {
    width: 100%;
    height: auto !important;
    min-height: 450px !important; /* Ensure minimum height for product display */
    max-height: 700px !important; /* Set reasonable maximum height */
    overflow-x: auto;
    overflow-y: auto; /* Allow vertical scrolling when content exceeds max height */
    padding: 1rem 0;
    position: relative;
}

/* Add scroll indicator */
.pos-table-wrapper::-webkit-scrollbar {
    width: 8px;
    height: 8px;
}

.pos-table-wrapper::-webkit-scrollbar-track {
    background: var(--surface-alt);
    border-radius: 4px;
}

.pos-table-wrapper::-webkit-scrollbar-thumb {
    background: var(--primary);
    border-radius: 4px;
    transition: background-color 0.3s ease;
}

.pos-table-wrapper::-webkit-scrollbar-thumb:hover {
    background: var(--primary-dark);
}

/* Scroll shadow indicators */
.pos-table-wrapper::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 20px;
    background: linear-gradient(to bottom, rgba(0,0,0,0.1), transparent);
    opacity: 0;
    transition: opacity 0.3s ease;
    pointer-events: none;
    z-index: 1;
}

.pos-table-wrapper.scrolled::before {
    opacity: 1;
}

/* Legacy table container for compatibility */
.modern-table-container {
    background: var(--surface);
    border-radius: var(--radius);
    overflow: hidden;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    border: 1px solid var(--border);
}

.modern-table {
    width: 100%;
    border-collapse: collapse;
}

.modern-table thead th {
    background: linear-gradient(135deg, #f8fafc, #e2e8f0);
    padding: 1.25rem 1rem;
    text-align: left;
    font-size: 0.8125rem;
    font-weight: 700;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 0.1em;
    border-bottom: 2px solid var(--border);
}

.modern-table tbody td {
    padding: 1.25rem 1rem;
    border-bottom: 1px solid var(--border-light);
    vertical-align: middle;
}

.modern-table tbody tr:last-child td {
    border-bottom: none;
}

.modern-table tbody tr:hover {
    background: var(--surface-alt);
}

.modern-summary-card {
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    color: white;
    border-radius: var(--radius-lg);
    padding: 2rem;
    position: relative;
    overflow: hidden;
    box-shadow: 0 10px 40px rgba(17, 24, 39, 0.2);
}

.modern-summary-card::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 100%;
    height: 100%;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
    pointer-events: none;
}

.modern-summary-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 0;
    border-bottom: 1px solid rgba(255, 255, 255, 0.2);
}

.modern-summary-row:last-child {
    border-bottom: none;
    padding-top: 1.5rem;
    font-size: 1.5rem;
    font-weight: 800;
}

.modern-summary-label {
    font-weight: 500;
    opacity: 0.9;
}

.modern-summary-value {
    font-weight: 700;
}

.modern-actions-bar {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
    padding-top: 2rem;
    flex-wrap: wrap;
    margin-right: 60px;
        margin-bottom: 60px;
}

.modern-grid-2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
}

.modern-grid-3 {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.5rem;
}

.modern-grid-4 {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1.5rem;
}

@media (max-width: 1024px) {
    .modern-grid-4 {
        grid-template-columns: repeat(2, 1fr);
    }
    .modern-grid-3 {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .modern-container {
        padding: 0 1rem;
    }
    
    .modern-glass-card {
        padding: 1.5rem;
    }
    
    .modern-page-title {
        font-size: 2rem;
    }
    
    .modern-form-grid,
    .modern-grid-2,
    .modern-grid-3,
    .modern-grid-4 {
        grid-template-columns: 1fr;
    }
    
    .modern-actions-bar {
        flex-direction: column;
    }
    
    .modern-btn {
        width: 100%;
    }
    
    .modern-card-header {
        flex-direction: column;
        text-align: center;
    }
}

/* Animation classes */
.fade-in {
    animation: fadeIn 0.6s ease-out;
}

.slide-up {
    animation: slideUp 0.6s ease-out;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slideUp {
    from { 
        opacity: 0;
        transform: translateY(30px);
    }
    to { 
        opacity: 1;
        transform: translateY(0);
    }
}

/* Status indicators */
.status-indicator {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: 50px;
    font-size: 0.875rem;
    font-weight: 600;
}

.status-draft {
    background: #fef3c7;
    color: #92400e;
}

.status-final {
    background: #d1fae5;
    color: #065f46;
}

.status-quotation {
    background: #dbeafe;
    color: #1e40af;
}

/* Loading states */
.loading {
    opacity: 0.6;
    pointer-events: none;
}

.loading::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 20px;
    height: 20px;
    margin: -10px;
    border: 2px solid var(--primary);
    border-radius: 50%;
    border-top-color: transparent;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

/* Select2 Custom Styling for Customer Dropdown - Clean White & Black Theme */
.select2-container--default .select2-selection--single {
    background: white !important;
    border: 2px solid #e5e7eb !important;
    border-radius: 12px !important;
    height: auto !important;
    padding: 1rem 4rem 1rem 3rem !important;
    font-size: 1rem !important;
    line-height: 1.5 !important;
    color: #1f2937 !important;
    min-height: 3.5rem !important;
    transition: all 0.3s ease !important;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05) !important;
}

.select2-container--default .select2-selection--single .select2-selection__rendered {
    color: #1f2937 !important;
    padding: 0 !important;
    line-height: 1.5 !important;
    font-weight: 500 !important;
}

.select2-container--default .select2-selection--single .select2-selection__placeholder {
    color: #6b7280 !important;
    opacity: 0.7 !important;
}

.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 100% !important;
    right: 3rem !important;
    top: 0 !important;
}

.select2-container--default .select2-selection--single .select2-selection__arrow b {
    border-color: #4b5563 transparent transparent transparent !important;
    border-width: 6px 6px 0 6px !important;
}

.select2-container--default.select2-container--focus .select2-selection--single,
.select2-container--default .select2-selection--single:hover {
    border-color: #374151 !important;
    box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.1) !important;
}

.select2-dropdown {
    border: 2px solid #e5e7eb !important;
    border-radius: 12px !important;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08), 0 4px 10px rgba(0, 0, 0, 0.05) !important;
    background: white !important;
    margin-top: 4px !important;
    overflow: hidden !important;
    overflow-x: hidden !important;
    width: auto !important;
    min-width: 0 !important;
    box-sizing: border-box !important;
}

.customer-source-dropdown {
    width: 100% !important;
    min-width: 100% !important;
    box-sizing: border-box !important;
    top: 100% !important;
    bottom: auto !important;
    z-index: 3000 !important;
}

.customer-source-dropdown.select2-dropdown--above {
    top: 100% !important;
    bottom: auto !important;
}

.select2-opened-elevated {
    position: relative;
    z-index: 2999;
}

.customer-source-section {
    padding-bottom: 12rem;
}

.inline-address-card {
    position: relative;
}

.inline-address-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 0.75rem;
    margin-bottom: 0.75rem;
}

.inline-address-title {
    margin: 0;
    font-size: 0.875rem;
    font-weight: 700;
    color: var(--text);
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.inline-address-edit-btn {
    border: 1px solid var(--border);
    background: white;
    color: var(--text);
    border-radius: 6px;
    padding: 0.35rem 0.65rem;
    font-size: 0.75rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    transition: all 0.2s ease;
}

.inline-address-edit-btn:hover {
    background: var(--surface-alt);
    border-color: var(--primary);
    color: var(--primary);
}

.inline-address-phone {
    margin-top: 0.75rem;
    font-size: 0.8125rem;
    color: var(--text-muted);
    display: flex;
    gap: 0.35rem;
    align-items: center;
    flex-wrap: wrap;
}

.inline-address-phone-label {
    font-weight: 600;
    color: var(--text);
}

.inline-address-edit {
    margin-top: 0.5rem;
}

.inline-address-label {
    display: block;
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--text);
    margin: 0.5rem 0 0.25rem 0;
}

.inline-address-input {
    width: 100%;
    border: 1px solid var(--border);
    border-radius: 8px;
    padding: 0.65rem 0.75rem;
    font-size: 0.875rem;
    background: white;
}

.inline-address-actions {
    display: flex;
    justify-content: flex-end;
    gap: 0.5rem;
    margin-top: 0.75rem;
}

.inline-address-save-btn,
.inline-address-cancel-btn {
    border-radius: 6px;
    border: 1px solid transparent;
    padding: 0.4rem 0.85rem;
    font-size: 0.75rem;
    font-weight: 600;
    transition: all 0.2s ease;
}

.inline-address-save-btn {
    background: var(--success);
    color: white;
}

.inline-address-save-btn:hover {
    filter: brightness(0.95);
}

.inline-address-cancel-btn {
    background: white;
    border-color: var(--border);
    color: var(--text);
}

.inline-address-cancel-btn:hover {
    border-color: var(--primary);
    color: var(--primary);
}

.modern-form-group .select2-container {
    width: 100% !important;
}

.responsible-salesperson-dropdown {
    width: 100% !important;
    min-width: 100% !important;
    box-sizing: border-box !important;
}

.customer-select2-container {
    z-index: 2000 !important;
}

.customer-select2-dropdown {
    z-index: 2001 !important;
}

.select2-results__options {
    overflow-x: hidden !important;
}

.select2-container--default .select2-results__option {
    padding: 1rem 1.5rem !important;
    color: #1f2937 !important;
    background: white !important;
    border-bottom: 1px solid rgba(229, 231, 235, 0.5) !important;
    transition: all 0.2s ease !important;
    white-space: nowrap !important;
    overflow: hidden !important;
    text-overflow: ellipsis !important;
}

.select2-container--default .select2-results__option:last-child {
    border-bottom: none !important;
}

.select2-container--default .select2-results__option--highlighted[data-selected] {
    background: #f3f4f6 !important;
    color: #1f2937 !important;
    font-weight: 600 !important;
}

.select2-container--default .select2-results__option:hover {
    background: #f9fafb !important;
    color: #1f2937 !important;
    transform: translateX(4px) !important;
}

.select2-container--default .select2-results__option[data-selected] {
    background: #374151 !important;
    color: white !important;
    font-weight: 600 !important;
}

.select2-search--dropdown .select2-search__field {
    border: 2px solid #e5e7eb !important;
    border-radius: 8px !important;
    padding: 0.75rem 1rem !important;
    font-size: 0.95rem !important;
    margin: 0.5rem !important;
    width: calc(100% - 1rem) !important;
    background: #f9fafb !important;
    color: #1f2937 !important;
}

.select2-search--dropdown .select2-search__field:focus {
    border-color: #374151 !important;
    box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.1) !important;
    outline: none !important;
    background: white !important;
}

/* Compact customer section hover effects */
.modern-form-group:has(.modern-customer-select):hover {
    box-shadow: 0 4px 12px rgba(251, 191, 36, 0.15) !important;
    transform: translateY(-1px) !important;
}

.modern-form-group:has(.modern-customer-select) {
    transition: all 0.3s ease !important;
}

.modern-form-group.no-hover-card:has(.modern-customer-select):hover {
    box-shadow: 0 2px 8px rgba(251, 191, 36, 0.1) !important;
    transform: none !important;
}

.inline-address-card.no-hover-card:hover {
    box-shadow: none !important;
    transform: none !important;
}

/* Add customer button hover effects */
.modern-add-customer-btn:hover {
    transform: translateY(-50%) scale(1.05) !important;
    box-shadow: 0 4px 12px rgba(34, 197, 94, 0.4) !important;
}

.no-hover-card .modern-add-customer-btn:hover {
    transform: translateY(-50%) !important;
    box-shadow: 0 2px 6px rgba(34, 197, 94, 0.3) !important;
}

.modern-add-customer-btn:active {
    transform: translateY(-50%) scale(0.95) !important;
}
}

.customer-source-option {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    max-width: 100%;
}

.customer-source-logo {
    width: 18px;
    height: 18px;
    object-fit: contain;
    border-radius: 3px;
    flex: 0 0 auto;
}

.customer-source-text {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    color: inherit;
}

.select2-container--default .select2-results__option--highlighted[aria-selected] {
    background: var(--primary-light) !important;
    color: var(--primary) !important;
}

.select2-container--default .select2-search--dropdown .select2-search__field {
    border: 2px solid var(--border) !important;
    border-radius: var(--radius) !important;
    padding: 0.75rem 1rem !important;
    color: var(--text) !important;
    background: var(--surface) !important;
}

/* Responsive Design */
@media (max-width: 1024px) {
    .modern-grid-4 {
        grid-template-columns: repeat(2, 1fr);
    }
    .modern-grid-3 {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .modern-container {
        padding: 0 0.5rem;
        max-width: 98%;
    }
    
    .modern-glass-card {
        padding: 1.25rem;
        margin-bottom: 1rem;
    }
    
    .modern-page-title {
        font-size: 1.75rem;
    }
    
    .modern-form-grid,
    .modern-grid-2,
    .modern-grid-3,
    .modern-grid-4 {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .modern-actions-bar {
        flex-direction: column;
    }
    
    .modern-btn {
        width: 100%;
    }
    
    .modern-card-header {
        flex-direction: column;
        text-align: center;
    }
    
    /* Mobile adjustments for product table */
    .modern-pos-table-container {
        min-height: 350px !important;
    }
    
    .pos-table-wrapper {
        min-height: 300px !important;
        max-height: 500px !important;
    }
    
    #pos_table_empty_state {
        min-height: 250px;
    }
}

/* Date Picker Styling */
.transaction-date-picker {
    cursor: pointer;
    transition: all 0.3s ease;
}

.transaction-date-picker:hover {
    border-color: var(--primary);
    box-shadow: 0 2px 8px rgba(55, 65, 81, 0.1);
}

.transaction-date-picker:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(55, 65, 81, 0.1);
}

/* Make calendar icon clickable */
.modern-input-icon.fa-calendar {
    cursor: pointer;
    transition: color 0.2s ease;
}

.modern-input-icon.fa-calendar:hover {
    color: var(--primary-dark);
}

/* Bootstrap DateTimePicker Customization */
.bootstrap-datetimepicker-widget.dropdown-menu {
    background: var(--surface);
    border: 2px solid var(--border);
    border-radius: var(--radius);
    box-shadow: var(--shadow-card);
    padding: 0;
    margin-top: 8px;
}

.bootstrap-datetimepicker-widget .datepicker table td,
.bootstrap-datetimepicker-widget .timepicker table td {
    border-radius: 6px;
    transition: all 0.2s ease;
}

.bootstrap-datetimepicker-widget .datepicker table td.active,
.bootstrap-datetimepicker-widget .datepicker table td.active:hover,
.bootstrap-datetimepicker-widget .timepicker table td.active,
.bootstrap-datetimepicker-widget .timepicker table td.active:hover {
    background: var(--primary) !important;
    color: white !important;
}

.bootstrap-datetimepicker-widget .datepicker table td.today,
.bootstrap-datetimepicker-widget .datepicker table td.today:hover {
    background: var(--warning) !important;
    color: white !important;
}

.bootstrap-datetimepicker-widget .datepicker table td:hover,
.bootstrap-datetimepicker-widget .timepicker table td:hover {
    background: var(--primary-light) !important;
    color: var(--primary) !important;
}

.bootstrap-datetimepicker-widget .datepicker-months th,
.bootstrap-datetimepicker-widget .datepicker-years th,
.bootstrap-datetimepicker-widget .datepicker-decades th {
    color: var(--primary);
    font-weight: 600;
}

.bootstrap-datetimepicker-widget .btn {
    border-radius: 6px;
    font-size: 0.875rem;
    padding: 0.5rem 1rem;
    transition: all 0.2s ease;
}

.bootstrap-datetimepicker-widget .btn-primary {
    background: var(--primary);
    border-color: var(--primary);
}

.bootstrap-datetimepicker-widget .btn-primary:hover {
    background: var(--primary-dark);
    border-color: var(--primary-dark);
}
</style>

<div class="modern-sale-page fade-in">
    <div class="modern-container">
        <div class="modern-page-header slide-up">
            <h1 class="modern-page-title">{{$title}}</h1>
          
        </div>

        <!-- Hidden inputs for settings -->
        <input type="hidden" id="amount_rounding_method" value="{{$pos_settings['amount_rounding_method'] ?? ''}}">
        @if(!empty($pos_settings['allow_overselling']))
            <input type="hidden" id="is_overselling_allowed">
        @endif
        @if(session('business.enable_rp') == 1)
            <input type="hidden" id="reward_point_enabled">
        @endif

        @if(count($business_locations) > 0)
        <div class="modern-glass-card slide-up" style="padding: 1rem; margin-bottom: 1rem;">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <div style="display: flex; align-items: center; gap: 0.5rem; min-width: 140px;">
                    <div style="width: 2rem; height: 2rem; border-radius: 8px; background: linear-gradient(135deg, var(--primary), var(--primary-dark)); display: flex; align-items: center; justify-content: center; color: white; font-size: 0.875rem;">
                        <i class="fa fa-map-marker"></i>
                    </div>
                    <span style="font-weight: 600; color: var(--text); font-size: 0.875rem;">{{ __('custom.business_location') }}</span>
                </div>
                <div style="flex: 1;">
                    <div class="modern-input-wrapper">
                        <i class="fa fa-map-marker modern-input-icon"></i>
                        {!! Form::select('select_location_id', $business_locations, $default_location->id ?? null, ['class' => 'modern-input modern-select has-icon', 'id' => 'select_location_id', 'required', 'autofocus', 'style' => 'padding: 0.75rem 1rem; padding-left: 2.5rem; font-size: 0.875rem;'], $bl_attributes); !!}
                    </div>
                </div>
            </div>
        </div>
        @endif

        @php
            $custom_labels = json_decode(session('business.custom_labels'), true);
            $common_settings = session()->get('business.common_settings');
        @endphp
        
        <input type="hidden" id="item_addition_method" value="{{$business_details->item_addition_method}}">

        {!! Form::open(['url' => action([\App\Http\Controllers\SellPosController::class, 'store']), 'method' => 'post', 'id' => 'add_sell_form', 'files' => true ]) !!}
        
        @if(!empty($sale_type))
            <input type="hidden" id="sale_type" name="type" value="{{$sale_type}}">
        @endif

        <!-- Sale Information Card -->
        <div class="modern-glass-card slide-up">
            <div class="modern-card-header">
                <div class="modern-card-icon">
                    <i class="fa fa-file-invoice"></i>
                </div>
                <h2 class="modern-card-title">{{ __('custom.sale_information') }}</h2>
                
                <!-- Status, Invoice Scheme, and Invoice No in header -->
                <div style="margin-left: auto; display: flex; gap: 1rem; align-items: center;">
                    <!-- Sale Status -->
                    @if(!empty($status))
                        <input type="hidden" name="status" id="status" value="{{$status}}">
                        @if(in_array($status, ['draft', 'quotation']))
                            <input type="hidden" id="disable_qty_alert">
                        @endif
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <label style="font-size: 0.75rem; color: var(--text-muted); font-weight: 600; margin: 0;">{{ __('custom.status') }}:</label>
                            <div class="status-indicator status-{{$status}}" style="padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.75rem;">
                                <i class="fa fa-circle" style="font-size: 0.5rem;"></i>
                                {{ ucfirst($status) }}
                            </div>
                        </div>
                    @else
                        <div style="min-width: 150px;">
                            <label style="font-size: 0.75rem; color: var(--text-muted); font-weight: 600; margin: 0 0 0.25rem 0; display: block;">{{ __('custom.status') }} *</label>
                            <div class="modern-input-wrapper">
                                <i class="fa fa-tags modern-input-icon" style="font-size: 0.75rem;"></i>
                                @php
                                    // Default to 'proforma' when no status is provided (matching TAX-INVOICE default)
                                    $default_status = 'proforma';
                                @endphp
                                {!! Form::select('status', $statuses, $default_status, ['class' => 'modern-input modern-select has-icon', 'id' => 'status', 'placeholder' => __('messages.please_select'), 'required', 'style' => 'padding: 0.5rem 2rem 0.5rem 1.75rem; font-size: 0.75rem; min-height: 2rem;']); !!}
                            </div>
                             <small style="font-size: 0.6875rem; color: var(--text-muted);">{{ __('custom.type_of_bill') }}</small>
                        </div>
                    @endif

                    <!-- Invoice Scheme -->
                    @if($sale_type != 'sales_order')
                        <div style="min-width: 150px;">
                            <label style="font-size: 0.75rem; color: var(--text-muted); font-weight: 600; margin: 0 0 0.25rem 0; display: block;">{{ __('custom.invoice_scheme') }}</label>
                            <div class="modern-input-wrapper">
                                <i class="fa fa-file-invoice modern-input-icon" style="font-size: 0.75rem;"></i>
                                @php
                                    // Default to TAX-INVOICE (id=4) when no status is provided
                                    $default_invoice_scheme_id = 4;
                                    
                                    if (!empty($status) && $status == 'quotation') {
                                        $default_invoice_scheme_id = 1;
                                    } elseif (!empty($status) && $status == 'proforma') {
                                        $default_invoice_scheme_id = 4;
                                    } elseif (!empty($status) && $status == 'final') {
                                        $default_invoice_scheme_id = 5;
                                    }
                                @endphp
                                {!! Form::select('invoice_scheme_id', $invoice_schemes, $default_invoice_scheme_id, ['class' => 'modern-input modern-select has-icon', 'id' => 'invoice_scheme_id', 'placeholder' => __('messages.please_select'), 'style' => 'padding: 0.5rem 2rem 0.5rem 1.75rem; font-size: 0.75rem; min-height: 2rem;']); !!}
                            </div>
                             <small style="font-size: 0.6875rem; color: var(--text-muted);">{{ __('custom.invoice_scheme') }}</small>
                        </div>
                    @endif

                    <!-- Invoice No -->
                    @can('edit_invoice_number')
                        <div style="min-width: 150px;">
                            <label style="font-size: 0.75rem; color: var(--text-muted); font-weight: 600; margin: 0 0 0.25rem 0; display: block;">{{ $sale_type == 'sales_order' ? __('restaurant.order_no') : __('sale.invoice_no') }}</label>
                            <div class="modern-input-wrapper">
                                <i class="fa fa-hashtag modern-input-icon" style="font-size: 0.75rem;"></i>
                                {!! Form::text('invoice_no', null, ['class' => 'modern-input has-icon', 'placeholder' => $sale_type == 'sales_order' ? __('restaurant.order_no') : __('sale.invoice_no'), 'style' => 'padding: 0.5rem 1rem 0.5rem 1.75rem; font-size: 0.75rem; min-height: 2rem;']); !!}
                            </div>
                            <small style="font-size: 0.6875rem; color: var(--text-muted);">@lang('lang_v1.keep_blank_to_autogenerate')</small>
                        </div>
                    @endcan
                </div>
            </div>
            {!! Form::hidden('location_id', !empty($default_location) ? $default_location->id : null , ['id' => 'location_id', 'data-receipt_printer_type' => !empty($default_location->receipt_printer_type) ? $default_location->receipt_printer_type : 'browser', 'data-default_payment_accounts' => !empty($default_location) ? $default_location->default_payment_accounts : '']); !!}



            <div class="modern-form-grid">

                @if(in_array('types_of_service', $enabled_modules) && !empty($types_of_service))
                    <div class="modern-form-group">
                        <label class="modern-label" for="types_of_service_id">{{ __('custom.service_type') }}</label>
                        <div class="modern-input-wrapper">
                            <i class="fa fa-concierge-bell modern-input-icon"></i>
                            {!! Form::select('types_of_service_id', $types_of_service, null, ['class' => 'modern-input modern-select has-icon', 'id' => 'types_of_service_id', 'placeholder' => __('lang_v1.select_types_of_service')]); !!}
                        </div>
                        {!! Form::hidden('types_of_service_price_group', null, ['id' => 'types_of_service_price_group']) !!}
                        <small><p class="help-block hide" id="price_group_text" style="margin-top: 0.5rem; color: var(--text-muted);">@lang('lang_v1.price_group'): <span></span></p></small>
                    </div>
                    <div class="modal fade types_of_service_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel"></div>
                @endif
                
                @if(in_array('subscription', $enabled_modules))
                    <div class="modern-form-group">
                        <div style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: var(--surface-alt); border-radius: var(--radius); border: 1px solid var(--border);">
                            {!! Form::checkbox('is_recurring', 1, false, ['class' => 'modern-checkbox', 'id' => 'is_recurring']); !!}
                            <label for="is_recurring" class="modern-label" style="margin: 0; cursor: pointer;">@lang('lang_v1.subscribe')?</label>
                            <button type="button" data-toggle="modal" data-target="#recurringInvoiceModal" class="modern-btn-icon" style="margin-left: auto;">
                                <i class="fa fa-external-link"></i>
                            </button>
                        </div>
                    </div>
                @endif
            </div>
            
            <!-- Customer and Sale Date Section - Same Line -->
            <div class="modern-form-grid" style="grid-template-columns: 2fr 1fr; gap: 2rem;">
                <!-- Compact Customer Section -->
                <div class="modern-form-group no-hover-card" style="background: linear-gradient(135deg, #fffbeb, #fef3c7); padding: 1.5rem; border-radius: var(--radius); border: 2px solid #fbbf24; box-shadow: 0 2px 8px rgba(251, 191, 36, 0.1);">
                    <label class="modern-label" for="customer_id" style="color: #92400e; font-weight: 700; margin-bottom: 0.75rem;">{{ __('custom.customer_label') }} *</label>

                    <input type="hidden" id="default_customer_id" value="{{ $walk_in_customer['id']}}" >
                    <input type="hidden" id="default_customer_name" value="{{ $walk_in_customer['name']}}" >
                    <input type="hidden" id="default_customer_balance" value="{{ $walk_in_customer['balance'] ?? ''}}" >
                    <input type="hidden" id="default_customer_address" value="{{ $walk_in_customer['shipping_address'] ?? ''}}" >
                    @if(!empty($walk_in_customer['price_calculation_type']) && $walk_in_customer['price_calculation_type'] == 'selling_price_group')
                        <input type="hidden" id="default_selling_price_group" value="{{ $walk_in_customer['selling_price_group_id'] ?? ''}}" >
                    @endif

                    <div class="modern-input-wrapper" style="position: relative;">
                        <i class="fa fa-user modern-input-icon" style="color: #f59e0b; z-index: 2;"></i>
                        {!! Form::select('contact_id', [], null, ['class' => 'modern-customer-select mousetrap', 'id' => 'customer_id', 'required', 'style' => 'padding: 0.75rem 3rem 0.75rem 2.5rem; font-size: 1rem; border: 2px solid #fbbf24; border-radius: 8px; background: white; transition: all 0.3s ease; box-shadow: 0 2px 8px rgba(251, 191, 36, 0.1); width: 100%; min-height: 2.75rem;']); !!}

                        <button type="button" class="add_new_customer modern-add-customer-btn" data-name="" title="{{ __('custom.add_new_customer') }}" style="position: absolute; right: 6px; top: 50%; transform: translateY(-50%); z-index: 3; background: var(--success); color: white; border: none; width: 2rem; height: 2rem; border-radius: 6px; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 6px rgba(34, 197, 94, 0.3); transition: all 0.3s ease;">
                            <i class="fa fa-plus" style="font-size: 0.75rem;"></i>
                        </button>
                    </div>

                    <small class="text-danger hide contact_due_text" style="margin-top: 0.5rem; font-weight: 600;"><strong>@lang('account.customer_due'):</strong> <span></span></small>

                    <!-- Tax ID lookup status messages -->
                    <div id="tax_lookup_status" style="margin-top: 8px; display: none;">
                        <small id="tax_lookup_loading" class="status-indicator" style="background: #e0f2fe; color: #01579b; display: none;">
                            <i class="fa fa-spinner fa-spin"></i> {{ __('custom.tax_lookup_loading') }}
                        </small>
                        <small id="tax_lookup_success" class="status-indicator status-final" style="display: none;">
                            <i class="fa fa-check"></i> <span id="company_found_name"></span>
                        </small>
                        <small id="tax_lookup_error" class="status-indicator" style="background: #ffebee; color: #c62828; display: none;">
                            <i class="fa fa-times"></i> <span id="tax_lookup_error_msg"></span>
                        </small>
                    </div>
                </div>

                <div class="modern-form-group">
                    <label class="modern-label" for="transaction_date">{{ __('custom.sale_date') }} *</label>
                    <div class="modern-input-wrapper" style="position: relative;">
                        <i class="fa fa-calendar modern-input-icon" style="color: var(--primary);"></i>
                        {!! Form::text('transaction_date', $default_datetime, ['class' => 'modern-input has-icon transaction-date-picker', 'required', 'autocomplete' => 'off', 'placeholder' => __('custom.select_date_placeholder'), 'id' => 'transaction_date']); !!}
                    </div>
                    <small class="text-muted" style="margin-top: 0.25rem; font-size: 0.75rem;">
                        {{ __('custom.sale_date_helper') }}
                    </small>
                </div>
            </div>

            <!-- Customer Address Information -->
            <div class="modern-grid-2" style="margin-top: 1.5rem;">
                @php
                    $billing_address_value = $walk_in_customer['address_line_1'] ?? $walk_in_customer['contact_address'] ?? '';
                    $shipping_address_value = $walk_in_customer['shipping_address'] ?? '';
                    $customer_phone_value = $walk_in_customer['mobile'] ?? '';
                    $customer_company_name = $walk_in_customer['supplier_business_name'] ?? $walk_in_customer['name'] ?? '';
                    $customer_is_business = !empty($walk_in_customer['supplier_business_name']);
                    $customer_tax_number = $walk_in_customer['tax_number'] ?? '';
                @endphp
                <div class="inline-address-card no-hover-card" data-address-type="billing" data-address="{{ e($billing_address_value) }}" data-phone="{{ e($customer_phone_value) }}" data-company-name="{{ e($customer_company_name) }}" data-tax-number="{{ e($customer_tax_number) }}" data-contact-type="{{ $customer_is_business ? 'business' : 'individual' }}" style="background: var(--surface-alt); padding: 1rem; border-radius: var(--radius); border: 1px solid var(--border);">
                    <div class="inline-address-header">
                        <h4 class="inline-address-title">@lang('lang_v1.billing_address')</h4>
                        <button type="button" class="inline-address-edit-btn" data-address="billing">
                            <i class="fa fa-edit"></i> {{ __('custom.edit_inline') }}
                        </button>
                    </div>
                    <div class="inline-address-view" id="billing_address_view">
                        <div id="billing_address_div" style="color: var(--text-muted); line-height: 1.5;">
                            {!! $walk_in_customer['contact_address'] ?? __('custom.no_billing_address') !!}
                        </div>
                        <div class="inline-address-phone" id="billing_phone_text" data-phone="{{ e($customer_phone_value) }}">
                            <span class="inline-address-phone-label">{{ __('custom.phone') }}:</span>
                            <span class="inline-address-phone-value">{{ $customer_phone_value ?: __('custom.no_phone') }}</span>
                        </div>
                    </div>
                    <div class="inline-address-edit" id="billing_address_edit" style="display: none;">
                        <label class="inline-address-label" for="billing_company_name_input">{{ __('custom.company_name') }}</label>
                        <input type="text" id="billing_company_name_input" class="inline-address-input no-auto-select" value="{{ e($customer_company_name) }}" placeholder="{{ __('custom.company_name_placeholder') }}">
                        <label class="inline-address-label" for="billing_address_input">{{ __('custom.billing_address_label') }}</label>
                        <textarea id="billing_address_input" class="inline-address-input" rows="3" placeholder="{{ __('custom.billing_address_placeholder') }}">{{ e($billing_address_value) }}</textarea>
                        <label class="inline-address-label" for="billing_tax_number_input">{{ __('custom.tax_id') }}</label>
                        <input type="text" id="billing_tax_number_input" class="inline-address-input no-auto-select" value="{{ e($customer_tax_number) }}" placeholder="{{ __('custom.tax_id_placeholder') }}">
                        <label class="inline-address-label" for="billing_phone_input">{{ __('custom.phone') }}</label>
                        <input type="text" id="billing_phone_input" class="inline-address-input no-auto-select" value="{{ e($customer_phone_value) }}" placeholder="{{ __('custom.phone_placeholder') }}">
                        <div class="inline-address-actions">
                            <button type="button" class="inline-address-save-btn" data-address="billing">{{ __('custom.save') }}</button>
                            <button type="button" class="inline-address-cancel-btn" data-address="billing">{{ __('custom.cancel') }}</button>
                        </div>
                    </div>
                </div>
                <div class="inline-address-card no-hover-card" data-address-type="shipping" data-address="{{ e($shipping_address_value) }}" data-phone="{{ e($customer_phone_value) }}" style="background: var(--surface-alt); padding: 1rem; border-radius: var(--radius); border: 1px solid var(--border);">
                    <div class="inline-address-header">
                        <h4 class="inline-address-title">@lang('lang_v1.shipping_address')</h4>
                        <button type="button" class="inline-address-edit-btn" data-address="shipping">
                            <i class="fa fa-edit"></i> {{ __('custom.edit_inline') }}
                        </button>
                    </div>
                    <div class="inline-address-view" id="shipping_address_view">
                        <div id="shipping_address_div" style="color: var(--text-muted); line-height: 1.5;">
                            {{$walk_in_customer['supplier_business_name'] ?? ''}}<br>
                            {{$walk_in_customer['name'] ?? ''}}<br>
                            {{$walk_in_customer['shipping_address'] ?? __('custom.no_shipping_address')}}
                        </div>
                        <div class="inline-address-phone" id="shipping_phone_text" data-phone="{{ e($customer_phone_value) }}">
                            <span class="inline-address-phone-label">{{ __('custom.phone') }}:</span>
                            <span class="inline-address-phone-value">{{ $customer_phone_value ?: __('custom.no_phone') }}</span>
                        </div>
                    </div>
                    <div class="inline-address-edit" id="shipping_address_edit" style="display: none;">
                        <label class="inline-address-label" for="shipping_address_input">{{ __('custom.shipping_address_label') }}</label>
                        <textarea id="shipping_address_input" class="inline-address-input" rows="3" placeholder="{{ __('custom.shipping_address_placeholder') }}">{{ e($shipping_address_value) }}</textarea>
                        <label class="inline-address-label" for="shipping_phone_input">{{ __('custom.phone') }}</label>
                        <input type="text" id="shipping_phone_input" class="inline-address-input no-auto-select" value="{{ e($customer_phone_value) }}" placeholder="{{ __('custom.phone_placeholder') }}">
                        <div class="inline-address-actions">
                            <button type="button" class="inline-address-save-btn" data-address="shipping">{{ __('custom.save') }}</button>
                            <button type="button" class="inline-address-cancel-btn" data-address="shipping">{{ __('custom.cancel') }}</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pricing, Payment Terms & Documents Section - Collapsible -->
            <div style="margin-top: 2rem;">
                <div style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: var(--surface-alt); border-radius: var(--radius); border: 1px solid var(--border); cursor: pointer;" onclick="togglePricingDocumentSection()">
                    <div style="width: 2rem; height: 2rem; border-radius: 8px; background: linear-gradient(135deg, var(--primary), var(--primary-dark)); display: flex; align-items: center; justify-content: center; color: white; font-size: 0.875rem;">
                        <i class="fa fa-money-bill-alt"></i>
                    </div>
                    <span style="font-weight: 600; color: var(--text); font-size: 0.875rem;">{{ __('custom.pricing_payment_documents') }}</span>
                    <button type="button" class="modern-btn-icon" style="margin-left: auto; background: var(--surface-alt); border: 1px solid var(--border);">
                        <i class="fa fa-chevron-down" id="pricing-document-toggle-icon"></i>
                    </button>
                </div>
                
                <div id="pricing-document-content" style="display: none; transition: all 0.3s ease; margin-top: 1rem;">
                    <!-- Pricing & Payment Terms Content -->
                    <div class="modern-form-grid">
                        @if(!empty($price_groups))
                            @if(count($price_groups) > 1)
                                <div class="modern-form-group">
                                    <label class="modern-label" for="price_group">@lang('lang_v1.price_group')</label>
                                    <div class="modern-input-wrapper">
                                        <i class="fas fa-money-bill-alt modern-input-icon"></i>
                                        @php
                                            reset($price_groups);
                                            $selected_price_group = !empty($default_price_group_id) && array_key_exists($default_price_group_id, $price_groups) ? $default_price_group_id : null;
                                            $first_price_group_key = !empty($price_groups) ? key($price_groups) : '';
                                        @endphp
                                        {!! Form::hidden('hidden_price_group', $first_price_group_key, ['id' => 'hidden_price_group']) !!}
                                        {!! Form::select('price_group', $price_groups, $selected_price_group, ['class' => 'modern-input modern-select has-icon', 'id' => 'price_group']); !!}
                                    </div>
                                </div>
                            @else
                                @php
                                    reset($price_groups);
                                    $price_group_key = !empty($price_groups) ? key($price_groups) : '';
                                @endphp
                                {!! Form::hidden('price_group', $price_group_key, ['id' => 'price_group']) !!}
                            @endif
                        @endif

                        {!! Form::hidden('default_price_group', null, ['id' => 'default_price_group']) !!}

                        <div class="modern-form-group">
                            @php
                                $is_pay_term_required = !empty($pos_settings['is_pay_term_required']);
                            @endphp
                            <label class="modern-label">{{ __('custom.payment_terms') }}</label>
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.5rem;">
                                {!! Form::number('pay_term_number', $walk_in_customer['pay_term_number'], ['class' => 'modern-input', 'placeholder' => __('custom.number_placeholder'), 'required' => $is_pay_term_required]); !!}
                                {!! Form::select('pay_term_type', 
                                    ['months' => __('lang_v1.months'), 'days' => __('lang_v1.days')], 
                                    $walk_in_customer['pay_term_type'], 
                                    ['class' => 'modern-input modern-select', 'placeholder' => __('messages.please_select'), 'required' => $is_pay_term_required]); !!}
                            </div>
                        </div>

                        <!-- Document Upload Section -->
                        <div class="modern-form-group">
                            <label class="modern-label" for="upload_document_main">{{ __('purchase.attach_document') }}</label>
                            <div class="modern-input-wrapper">
                                <i class="fa fa-paperclip modern-input-icon"></i>
                                {!! Form::file('sell_document', ['id' => 'upload_document_main', 'class' => 'modern-input has-icon', 'accept' => implode(',', array_keys(config('constants.document_upload_mimes_types')))]); !!}
                            </div>
                            <small style="margin-top: 0.5rem; color: var(--text-muted); font-size: 0.8125rem;">
                                @lang('purchase.max_file_size', ['size' => (config('constants.document_size_limit') / 1000000)])
                                @includeIf('components.document_help_text')
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customer Source and Responsible Salesperson -->
            <div class="modern-form-grid customer-source-section" style="grid-template-columns: 1fr 1fr; gap: 2rem; margin-top: 2rem;">
                <!-- Customer Source -->
                <div class="modern-form-group">
                    <label class="modern-label" for="customer_source_id">{{ __('custom.customer_source') }}</label>
                    <div class="modern-input-wrapper" style="position: relative;">
                        <i class="fa fa-share-alt modern-input-icon" style="color: var(--primary);"></i>
                        <select name="customer_source_id" id="customer_source_id" class="form-control modern-input has-icon customer-source-select" style="padding-left: 2.5rem;">
                            <option value="">-- {{ __('custom.select_source') }} --</option>
                            @foreach($customer_sources as $source)
                                <option value="{{ $source->id }}" data-logo="{{ $source->logo_url }}">
                                    {{ $source->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <small class="text-muted" style="margin-top: 0.25rem; font-size: 0.75rem;">
                        {{ __('custom.customer_source_help') }}
                    </small>
                </div>

                <!-- Responsible Salesperson -->
                <div class="modern-form-group">
                    <label class="modern-label" for="responsible_salesperson_id">{{ __('custom.responsible_salesperson') }}</label>
                    <div class="modern-input-wrapper" style="position: relative;">
                        <i class="fa fa-user-tie modern-input-icon" style="color: var(--primary);"></i>
                        {!! Form::select('responsible_salesperson_id', $all_users, auth()->user()->id, ['class' => 'form-control modern-input has-icon select2', 'id' => 'responsible_salesperson_id', 'style' => 'padding-left: 2.5rem;']); !!}
                    </div>
                    <small class="text-muted" style="margin-top: 0.25rem; font-size: 0.75rem;">
                        {{ __('custom.responsible_salesperson_help') }}
                    </small>
                </div>
            </div>

            <div class="modern-form-grid" style="margin-top: 2rem;">
                @if(!empty($commission_agent))
                    @php
                        $is_commission_agent_required = !empty($pos_settings['is_commission_agent_required']);
                    @endphp
                    <div class="modern-form-group">
                        <label class="modern-label" for="commission_agent">@lang('lang_v1.commission_agent')</label>
                        <div class="modern-input-wrapper">
                            <i class="fa fa-user-tie modern-input-icon"></i>
                            {!! Form::select('commission_agent', $commission_agent, null, ['class' => 'modern-input modern-select has-icon', 'id' => 'commission_agent', 'required' => $is_commission_agent_required, 'placeholder' => __('custom.select_agent')]); !!}
                        </div>
                    </div>
                @endif
            </div>


				
				@php
			        $custom_field_1_label = !empty($custom_labels['sell']['custom_field_1']) ? $custom_labels['sell']['custom_field_1'] : '';

			        $is_custom_field_1_required = !empty($custom_labels['sell']['is_custom_field_1_required']) && $custom_labels['sell']['is_custom_field_1_required'] == 1 ? true : false;

			        $custom_field_2_label = !empty($custom_labels['sell']['custom_field_2']) ? $custom_labels['sell']['custom_field_2'] : '';

			        $is_custom_field_2_required = !empty($custom_labels['sell']['is_custom_field_2_required']) && $custom_labels['sell']['is_custom_field_2_required'] == 1 ? true : false;

			        $custom_field_3_label = !empty($custom_labels['sell']['custom_field_3']) ? $custom_labels['sell']['custom_field_3'] : '';

			        $is_custom_field_3_required = !empty($custom_labels['sell']['is_custom_field_3_required']) && $custom_labels['sell']['is_custom_field_3_required'] == 1 ? true : false;

			        $custom_field_4_label = !empty($custom_labels['sell']['custom_field_4']) ? $custom_labels['sell']['custom_field_4'] : '';

			        $is_custom_field_4_required = !empty($custom_labels['sell']['is_custom_field_4_required']) && $custom_labels['sell']['is_custom_field_4_required'] == 1 ? true : false;
		        @endphp
		        @if(!empty($custom_field_1_label))
		        	@php
		        		$label_1 = $custom_field_1_label . ':';
		        		if($is_custom_field_1_required) {
		        			$label_1 .= '*';
		        		}
		        	@endphp

		        	<div class="col-md-4">
				        <div class="form-group">
				            {!! Form::label('custom_field_1', $label_1 ) !!}
				            {!! Form::text('custom_field_1', null, ['class' => 'form-control','placeholder' => $custom_field_1_label, 'required' => $is_custom_field_1_required]); !!}
				        </div>
				    </div>
		        @endif
		        @if(!empty($custom_field_2_label))
		        	@php
		        		$label_2 = $custom_field_2_label . ':';
		        		if($is_custom_field_2_required) {
		        			$label_2 .= '*';
		        		}
		        	@endphp

		        	<div class="col-md-4">
				        <div class="form-group">
				            {!! Form::label('custom_field_2', $label_2 ) !!}
				            {!! Form::text('custom_field_2', null, ['class' => 'form-control','placeholder' => $custom_field_2_label, 'required' => $is_custom_field_2_required]); !!}
				        </div>
				    </div>
		        @endif
		        @if(!empty($custom_field_3_label))
		        	@php
		        		$label_3 = $custom_field_3_label . ':';
		        		if($is_custom_field_3_required) {
		        			$label_3 .= '*';
		        		}
		        	@endphp

		        	<div class="col-md-4">
				        <div class="form-group">
				            {!! Form::label('custom_field_3', $label_3 ) !!}
				            {!! Form::text('custom_field_3', null, ['class' => 'form-control','placeholder' => $custom_field_3_label, 'required' => $is_custom_field_3_required]); !!}
				        </div>
				    </div>
		        @endif
		        @if(!empty($custom_field_4_label))
		        	@php
		        		$label_4 = $custom_field_4_label . ':';
		        		if($is_custom_field_4_required) {
		        			$label_4 .= '*';
		        		}
		        	@endphp

		        	<div class="col-md-4">
				        <div class="form-group">
				            {!! Form::label('custom_field_4', $label_4 ) !!}
				            {!! Form::text('custom_field_4', null, ['class' => 'form-control','placeholder' => $custom_field_4_label, 'required' => $is_custom_field_4_required]); !!}
				        </div>
				    </div>
		        @endif
		        <div class="col-sm-3" style="display: none;">
	                <div class="form-group">
	                    {!! Form::label('upload_document', __('purchase.attach_document') . ':') !!}
	                    {!! Form::file('sell_document', ['id' => 'upload_document', 'accept' => implode(',', array_keys(config('constants.document_upload_mimes_types')))]); !!}
	                    <p class="help-block">
	                    	@lang('purchase.max_file_size', ['size' => (config('constants.document_size_limit') / 1000000)])
	                    	@includeIf('components.document_help_text')
	                    </p>
	                </div>
	            </div>
		        <div class="clearfix"></div>

            <!-- Custom Fields and Additional Options -->
            @if(!empty($custom_field_1_label) || !empty($custom_field_2_label) || !empty($custom_field_3_label) || !empty($custom_field_4_label))
                <div class="modern-form-grid" style="margin-top: 2rem;">
                    @if(!empty($custom_field_1_label))
                        <div class="modern-form-group">
                            <label class="modern-label" for="custom_field_1">{{ $custom_field_1_label }}{{ $is_custom_field_1_required ? ' *' : '' }}</label>
                            <div class="modern-input-wrapper">
                                <i class="fa fa-tag modern-input-icon"></i>
                                {!! Form::text('custom_field_1', null, ['class' => 'modern-input has-icon', 'placeholder' => $custom_field_1_label, 'required' => $is_custom_field_1_required]); !!}
                            </div>
                        </div>
                    @endif
                    @if(!empty($custom_field_2_label))
                        <div class="modern-form-group">
                            <label class="modern-label" for="custom_field_2">{{ $custom_field_2_label }}{{ $is_custom_field_2_required ? ' *' : '' }}</label>
                            <div class="modern-input-wrapper">
                                <i class="fa fa-tag modern-input-icon"></i>
                                {!! Form::text('custom_field_2', null, ['class' => 'modern-input has-icon', 'placeholder' => $custom_field_2_label, 'required' => $is_custom_field_2_required]); !!}
                            </div>
                        </div>
                    @endif
                    @if(!empty($custom_field_3_label))
                        <div class="modern-form-group">
                            <label class="modern-label" for="custom_field_3">{{ $custom_field_3_label }}{{ $is_custom_field_3_required ? ' *' : '' }}</label>
                            <div class="modern-input-wrapper">
                                <i class="fa fa-tag modern-input-icon"></i>
                                {!! Form::text('custom_field_3', null, ['class' => 'modern-input has-icon', 'placeholder' => $custom_field_3_label, 'required' => $is_custom_field_3_required]); !!}
                            </div>
                        </div>
                    @endif
                    @if(!empty($custom_field_4_label))
                        <div class="modern-form-group">
                            <label class="modern-label" for="custom_field_4">{{ $custom_field_4_label }}{{ $is_custom_field_4_required ? ' *' : '' }}</label>
                            <div class="modern-input-wrapper">
                                <i class="fa fa-tag modern-input-icon"></i>
                                {!! Form::text('custom_field_4', null, ['class' => 'modern-input has-icon', 'placeholder' => $custom_field_4_label, 'required' => $is_custom_field_4_required]); !!}
                            </div>
                        </div>
                    @endif
                </div>
            @endif



            <div class="modern-form-grid" style="margin-top: 2rem;">
                @if((!empty($pos_settings['enable_sales_order']) && $sale_type != 'sales_order') || !empty($is_order_request_enabled))
                    <div class="modern-form-group">
                        <label class="modern-label" for="sales_order_ids">{{ __('custom.sales_orders') }}</label>
                        <div class="modern-input-wrapper">
                            <i class="fa fa-shopping-cart modern-input-icon"></i>
                            {!! Form::select('sales_order_ids[]', [], null, ['class' => 'modern-input modern-select has-icon', 'multiple', 'id' => 'sales_order_ids', 'placeholder' => __('custom.select_sales_orders')]); !!}
                        </div>
                    </div>
                @endif
            </div>

            <!-- Call restaurant module if defined -->
            @if(!empty($enabled_modules) && (in_array('tables', $enabled_modules) || in_array('service_staff', $enabled_modules)))
                <span id="restaurant_module_span"></span>
            @endif
        </div>

        <!-- Product Search Card -->
        <div class="modern-glass-card slide-up">
            <div class="modern-card-header">
                <div class="modern-card-icon">
                    <i class="fa fa-search"></i>
                </div>
                <h2 class="modern-card-title">{{ __('custom.product_search_selection') }}</h2>
                <div style="margin-left: auto; display: flex; gap: 0.5rem;">
                    <button type="button" class="modern-btn-icon" data-toggle="modal" data-target="#configure_search_modal" title="{{__('lang_v1.configure_product_search')}}" style="background: var(--surface-alt); border: 1px solid var(--border);">
                        <i class="fas fa-cog"></i>
                    </button>
                </div>
            </div>

            <!-- Enhanced Search Section -->
            <div class="product-search-container" style="background: linear-gradient(135deg, #f8fafc, #e2e8f0); padding: 2rem; border-radius: var(--radius); border: 1px solid var(--border); margin-bottom: 2rem;">
                <div class="search-header" style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem;">
                    <div style="width: 3rem; height: 3rem; border-radius: 12px; background: linear-gradient(135deg, var(--primary), var(--primary-dark)); display: flex; align-items: center; justify-content: center; color: white; font-size: 1.125rem;">
                        <i class="fa fa-search"></i>
                    </div>
                    <div>
                        <h3 style="margin: 0; font-size: 1.125rem; font-weight: 700; color: var(--text);">{{ __('custom.find_products') }}</h3>
                        <p style="margin: 0; font-size: 0.875rem; color: var(--text-muted);">{{ __('custom.search_by_name_sku_barcode') }}</p>
                    </div>
                </div>

                <div class="modern-search-wrapper" style="position: relative;">
                    <div class="modern-input-wrapper" style="position: relative;">
                        <i class="fa fa-search modern-input-icon" style="color: var(--primary); z-index: 2;"></i>
                        {!! Form::text('search_product', null, [
                            'class' => 'modern-search-input mousetrap', 
                            'id' => 'search_product_new', 
                            'placeholder' => __('custom.search_products_placeholder'), 
                            'disabled' => is_null($default_location) ? true : false, 
                            'autofocus' => is_null($default_location) ? false : true,
                            'autocomplete' => 'off',
                            'style' => 'padding: 1rem 4rem 1rem 3rem; font-size: 1rem; border: 2px solid var(--border); border-radius: 12px; background: white; transition: all 0.3s ease; box-shadow: 0 2px 8px rgba(0,0,0,0.08);'
                        ]); !!}
                        
                        <button type="button" class="pos_add_quick_product modern-add-product-btn" data-href="{{action([\App\Http\Controllers\ProductController::class, 'quickAdd'])}}" data-container=".quick_add_product_modal" title="{{ __('custom.add_new_product') }}" style="position: absolute; right: 8px; top: 50%; transform: translateY(-50%); z-index: 3; background: var(--success); color: white; border: none; width: 2.5rem; height: 2.5rem; border-radius: 8px; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 8px rgba(34, 197, 94, 0.3); transition: all 0.3s ease;">
                            <i class="fa fa-plus" style="font-size: 0.875rem;"></i>
                        </button>
                    </div>

                    <!-- Search Results Dropdown -->
                    <div id="search_results_dropdown" style="position: absolute; top: 100%; left: 0; right: 0; background: white; border-radius: 12px; box-shadow: 0 8px 32px rgba(0,0,0,0.12); border: 1px solid var(--border); max-height: 400px; overflow-y: auto; z-index: 1000; display: none; margin-top: 0.5rem;">
                        <div class="search-results-header" style="padding: 1rem; border-bottom: 1px solid var(--border); background: var(--surface-alt);">
                            <div style="display: flex; align-items: center; justify-content: between;">
                                <span style="font-weight: 600; color: var(--text); font-size: 0.875rem;">{{ __('custom.search_results') }}</span>
                                <span class="results-count" style="color: var(--text-muted); font-size: 0.75rem;"></span>
                            </div>
                        </div>
                        <div class="search-results-content" style="max-height: 320px; overflow-y: auto;">
                            <!-- Results will be populated here -->
                        </div>
                        <div class="search-results-footer" style="padding: 0.75rem; background: var(--surface-alt); border-top: 1px solid var(--border); text-align: center;">
                            <small style="color: var(--text-muted); font-size: 0.75rem;">
                                <i class="fa fa-info-circle"></i> {{ __('custom.search_results_hint') }}
                            </small>
                        </div>
                    </div>
                </div>

             
            </div>

            <div class="pos_product_div" style="min-height: 500px; height: auto;">

                <input type="hidden" name="sell_price_tax" id="sell_price_tax" value="{{$business_details->sell_price_tax}}">
                <input type="hidden" id="product_row_count" value="0">
                
                @php
                    $hide_tax = '';
                    if( session()->get('business.enable_inline_tax') == 0){
                        $hide_tax = 'hide';
                    }
                @endphp

                <div class="modern-pos-table-container" style="min-height: 500px !important; height: auto !important;">
                    <div class="pos-table-header" style="background: linear-gradient(135deg, #f8fafc, #e2e8f0); padding: 1rem 1.5rem; border-bottom: 2px solid var(--border); display: flex; align-items: center; gap: 1rem;">
                        <div style="width: 2.5rem; height: 2.5rem; border-radius: 10px; background: linear-gradient(135deg, var(--primary), var(--primary-dark)); display: flex; align-items: center; justify-content: center; color: white; font-size: 1rem;">
                            <i class="fa fa-shopping-cart"></i>
                        </div>
                        <div>
                            <h3 style="margin: 0; font-size: 1rem; font-weight: 700; color: var(--text);">{{ __('custom.selected_products') }}</h3>
                            <p style="margin: 0; font-size: 0.75rem; color: var(--text-muted);">{{ __('custom.items_added_to_sale') }}</p>
                        </div>
                        <div style="margin-left: auto; display: flex; align-items: center; gap: 0.5rem;">
                            <span class="products-count-badge" style="background: var(--primary); color: white; padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.75rem; font-weight: 600;">{{ __('custom.items_count', ['count' => 0]) }}</span>
                        </div>
                    </div>
                    
                    <div class="pos-table-wrapper" style="min-height: 450px !important; height: auto !important;">
                        <table class="modern-pos-table" id="pos_table">
                            <thead style="position: sticky; top: 0; z-index: 10;">
                                <tr>
                                    <th style="width: 15%;">
                                        <div style="display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                                            <i class="fa fa-image" style="color: var(--primary);"></i>
                                            <span>{{ __('custom.image') }}</span>
                                        </div>
                                    </th>
                                    <th style="width: 25%;">
                                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                                            <i class="fa fa-box" style="color: var(--primary);"></i>
                                            <span>@lang('sale.product')</span>
                                        </div>
                                    </th>
                                    <th style="width: 15%;">
                                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                                            <i class="fa fa-align-left" style="color: var(--primary);"></i>
                                            <span>@lang('lang_v1.description')</span>
                                        </div>
                                    </th>
                                    <th style="width: 10%;">
                                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                                            <i class="fa fa-sort-numeric-up" style="color: var(--primary);"></i>
                                            <span>@lang('sale.qty')</span>
                                        </div>
                                    </th>
                                    @if(!empty($pos_settings['inline_service_staff']))
                                        <th style="width: 8%;">
                                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                                <i class="fa fa-user-tie" style="color: var(--primary);"></i>
                                                <span>@lang('restaurant.service_staff')</span>
                                            </div>
                                        </th>
                                    @endif
                                    <th class="@if(!auth()->user()->can('edit_product_price_from_sale_screen')) hide @endif" style="width: 10%;">
                                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                                            <i class="fa fa-dollar-sign" style="color: var(--primary);"></i>
                                            <span>@lang('sale.unit_price')</span>
                                        </div>
                                    </th>
                                    <th class="@if(!auth()->user()->can('edit_product_discount_from_sale_screen')) hide @endif" style="width: 8%;">
                                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                                            <i class="fa fa-percentage" style="color: var(--primary);"></i>
                                            <span>@lang('receipt.discount')</span>
                                        </div>
                                    </th>
                                    <th class="{{$hide_tax}}" style="width: 6%;">
                                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                                            <i class="fa fa-calculator" style="color: var(--primary);"></i>
                                            <span>@lang('sale.tax')</span>
                                        </div>
                                    </th>
                                    <th class="{{$hide_tax}}" style="width: 10%;">
                                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                                            <i class="fa fa-tag" style="color: var(--primary);"></i>
                                            <span>@lang('sale.price_inc_tax')</span>
                                        </div>
                                    </th>
                                    @if(!empty($common_settings['enable_product_warranty']))
                                        <th style="width: 8%;">
                                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                                <i class="fa fa-shield-alt" style="color: var(--primary);"></i>
                                                <span>@lang('lang_v1.warranty')</span>
                                            </div>
                                        </th>
                                    @endif
                                    <th style="width: 10%;">
                                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                                            <i class="fa fa-receipt" style="color: var(--primary);"></i>
                                            <span>@lang('sale.subtotal')</span>
                                        </div>
                                    </th>
                                    <th style="width: 5%;">
                                        <div style="display: flex; align-items: center; justify-content: center;">
                                            <i class="fas fa-trash-alt" style="color: var(--danger);"></i>
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="pos_table_body"></tbody>
                        </table>
                    </div>
                    
                    <!-- Empty State -->
                    <div id="pos_table_empty_state" style="padding: 3rem 2rem; text-align: center; display: block; min-height: 400px !important; display: flex !important; align-items: center; justify-content: center;">
                        <div style="color: var(--text-muted); max-width: 400px; margin: 0 auto;">
                            <div style="width: 4rem; height: 4rem; border-radius: 50%; background: var(--surface-alt); display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
                                <i class="fa fa-shopping-cart" style="font-size: 1.5rem; color: var(--text-muted);"></i>
                            </div>
                            <h4 style="margin: 0 0 0.5rem 0; font-size: 1.125rem; font-weight: 600; color: var(--text);">{{ __('custom.no_products_selected') }}</h4>
                            <p style="margin: 0; font-size: 0.875rem; line-height: 1.5;">{{ __('custom.no_products_selected_help') }}</p>
                        </div>
                    </div>
                </div>

                <div class="modern-summary-card" style="margin-top: 1.5rem;">
                    <div class="modern-summary-row">
                        <span class="modern-summary-label">@lang('sale.item'):</span>
                        <span class="modern-summary-value total_quantity">0</span>
                    </div>
                    <div class="modern-summary-row">
                        <span class="modern-summary-label">@lang('sale.total'):</span>
                        <span class="modern-summary-value price_total">0</span>
                    </div>
                </div>
            </div>
            
            <!-- Sale Note Section -->
            <div class="modern-form-group" style="margin-top: 1.5rem;">
                <label class="modern-label" for="sale_note">{{ __('custom.sale_note_label') }}</label>
                <div class="modern-input-wrapper">
                    <i class="fa fa-sticky-note modern-input-icon" style="top: 1.25rem;"></i>
                    {!! Form::textarea('sale_note', null, ['class' => 'modern-input has-icon', 'rows' => 3, 'placeholder' => __('custom.sale_note_placeholder'), 'style' => 'padding-top: 1rem; resize: vertical;']); !!}
                </div>
            </div>
        </div>

        <!-- Discount and Tax Card -->
        <div class="modern-glass-card slide-up">
            <div class="modern-card-header">
                <div class="modern-card-icon">
                    <i class="fa fa-percentage"></i>
                </div>
                <h2 class="modern-card-title">{{ __('custom.discount_tax') }}</h2>
                <button type="button" class="modern-btn-icon" onclick="toggleDiscountSection()" style="margin-left: auto; background: var(--surface-alt); border: 1px solid var(--border);">
                    <i class="fa fa-chevron-down" id="discount-toggle-icon"></i>
                </button>
            </div>
            @php
                $max_discount = !is_null(auth()->user()->max_sales_discount_percent) ? auth()->user()->max_sales_discount_percent : '';
                $sales_discount = $business_details->default_sales_discount;
                if($max_discount != '' && $sales_discount > $max_discount) $sales_discount = $max_discount;
                $default_sales_tax = $business_details->default_sales_tax;
                
                if($sale_type == 'sales_order') {
                    $sales_discount = 0;
                    $default_sales_tax = null;
                }
            @endphp



            <!-- Always Visible Tax Fields -->
            <div class="modern-form-grid">
                <div class="modern-form-group @if($sale_type == 'sales_order') hide @endif">
                    <label class="modern-label" for="tax_rate_id">{{ __('custom.order_tax') }} *</label>
                    <div class="modern-input-wrapper">
                        <i class="fa fa-calculator modern-input-icon"></i>
                        @php
                            $selected_tax = !empty($taxes['tax_rates'][1]) ? 1 : $default_sales_tax;
                        @endphp
                        {!! Form::select('tax_rate_id', $taxes['tax_rates'], $selected_tax, ['placeholder' => __('messages.please_select'), 'class' => 'modern-input modern-select has-icon', 'data-default'=> $selected_tax], $taxes['attributes']); !!}
                    </div>
                    <input type="hidden" name="tax_calculation_amount" id="tax_calculation_amount" value="@if(empty($edit)) {{@num_format($business_details->tax_calculation_amount)}} @else {{@num_format($transaction->tax?->amount)}} @endif" data-default="{{$business_details->tax_calculation_amount}}">
                </div>

                <div class="modern-form-group @if($sale_type == 'sales_order') hide @endif">
                    <label class="modern-label">{{ __('custom.order_tax_total') }}</label>
                    <div style="background: var(--surface-alt); padding: 1rem; border-radius: var(--radius); border: 1px solid var(--border); display: flex; align-items: center; gap: 0.5rem;">
                        <i class="fa fa-plus-circle" style="color: var(--success);"></i>
                        <span style="font-weight: 600; color: var(--success);">(+) <span class="display_currency" id="order_tax">0</span></span>
                    </div>
                </div>
            </div>

            <!-- Collapsible Discount Section -->
            <div id="discount-content" style="display: none; transition: all 0.3s ease;">
                <div class="modern-form-grid @if($sale_type == 'sales_order') hide @endif" style="margin-top: 1.5rem;">
                    <div class="modern-form-group">
                        <label class="modern-label" for="discount_type">{{ __('custom.discount_type') }} *</label>
                        <div class="modern-input-wrapper">
                            <i class="fa fa-percentage modern-input-icon"></i>
                            {!! Form::select('discount_type', ['fixed' => __('lang_v1.fixed'), 'percentage' => __('lang_v1.percentage')], 'percentage', ['class' => 'modern-input modern-select has-icon', 'placeholder' => __('messages.please_select'), 'required', 'data-default' => 'percentage']); !!}
                        </div>
                    </div>

                    <div class="modern-form-group">
                        <label class="modern-label" for="discount_amount">{{ __('custom.discount_amount') }} *</label>
                        <div class="modern-input-wrapper">
                            <i class="fa fa-minus-circle modern-input-icon"></i>
                            {!! Form::text('discount_amount', @num_format($sales_discount), ['class' => 'modern-input has-icon input_number', 'data-default' => $sales_discount, 'data-max-discount' => $max_discount, 'data-max-discount-error_msg' => __('lang_v1.max_discount_error_msg', ['discount' => $max_discount != '' ? @num_format($max_discount) : ''])]); !!}
                        </div>
                    </div>

                    <div class="modern-form-group">
                        <label class="modern-label">{{ __('custom.total_discount') }}</label>
                        <div style="background: var(--surface-alt); padding: 1rem; border-radius: var(--radius); border: 1px solid var(--border); display: flex; align-items: center; gap: 0.5rem;">
                            <i class="fa fa-minus-circle" style="color: var(--danger);"></i>
                            <span style="font-weight: 600; color: var(--danger);">(-) <span class="display_currency" id="total_discount">0</span></span>
                        </div>
                    </div>
                </div>

                <!-- Reward Points Section -->
                @if(session('business.enable_rp') == 1 && $sale_type != 'sales_order')
                    <div class="modern-glass-card" style="background: linear-gradient(135deg, #fbbf24, #f59e0b); color: white; margin-top: 1.5rem;">
                        <input type="hidden" name="rp_redeemed" id="rp_redeemed" value="0">
                        <input type="hidden" name="rp_redeemed_amount" id="rp_redeemed_amount" value="0">
                        <input type="hidden" id="rp_name" value="{{session('business.rp_name')}}">
                        
                        <div class="modern-card-header" style="border-color: rgba(255,255,255,0.2);">
                            <div class="modern-card-icon" style="background: rgba(255,255,255,0.2);">
                                <i class="fa fa-gift"></i>
                            </div>
                            <h3 class="modern-card-title" style="color: white;">{{session('business.rp_name')}}</h3>
                        </div>

                        <div class="modern-grid-3">
                            <div class="modern-form-group">
                                <label class="modern-label" style="color: rgba(255,255,255,0.9);" for="rp_redeemed_modal">{{ __('custom.redeemed_points') }}</label>
                                <div class="modern-input-wrapper">
                                    <i class="fa fa-gift modern-input-icon"></i>
                                    {!! Form::number('rp_redeemed_modal', 0, ['class' => 'modern-input has-icon direct_sell_rp_input', 'data-amount_per_unit_point' => session('business.redeem_amount_per_unit_rp'), 'min' => 0, 'data-max_points' => 0, 'data-min_order_total' => session('business.min_order_total_for_redeem')]); !!}
                                </div>
                            </div>
                            <div>
                                <p style="margin: 0; opacity: 0.9;"><strong>{{ __('custom.available') }}:</strong> <span id="available_rp">0</span></p>
                            </div>
                            <div>
                                <p style="margin: 0; opacity: 0.9;"><strong>{{ __('custom.redeemed_amount') }}:</strong> (-)<span id="rp_redeemed_amount_text">0</span></p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <input type="hidden" name="is_direct_sale" value="1">
        </div>

        <!-- Shipping & Payment Section - Combined Collapsible -->
        <div class="modern-glass-card slide-up">
            <div class="modern-card-header" style="cursor: pointer;" onclick="toggleShippingPaymentSection()">
                <div class="modern-card-icon">
                    <i class="fa fa-shipping-fast"></i>
                </div>
                <h2 class="modern-card-title">{{ __('custom.shipping_payment_information') }}</h2>
                <button type="button" class="modern-btn-icon" style="margin-left: auto; background: var(--surface-alt); border: 1px solid var(--border);">
                    <i class="fa fa-chevron-down" id="shipping-payment-toggle-icon"></i>
                </button>
            </div>
            <div id="shipping-payment-content" style="display: none; transition: all 0.3s ease;">
                
                <!-- Shipping Information Section -->
                <div style="margin-bottom: 2rem;">
                    <h3 style="margin: 0 0 1rem 0; font-size: 1rem; font-weight: 700; color: var(--text); display: flex; align-items: center; gap: 0.5rem;">
                        <i class="fa fa-truck" style="color: var(--primary);"></i>
                        {{ __('custom.shipping_details') }}
                    </h3>
                    
                    <div class="modern-grid-3">
                        <div class="modern-form-group">
                            <label class="modern-label" for="shipping_details">{{ __('custom.shipping_details') }}</label>
                            <div class="modern-input-wrapper">
                                <i class="fa fa-clipboard-list modern-input-icon" style="top: 1.25rem;"></i>
                                {!! Form::textarea('shipping_details', null, ['class' => 'modern-input has-icon', 'placeholder' => __('sale.shipping_details'), 'rows' => '3', 'style' => 'resize: vertical;']); !!}
                            </div>
                        </div>

                        <div class="modern-form-group">
                            <label class="modern-label" for="shipping_address">{{ __('custom.shipping_address') }}</label>
                            <div class="modern-input-wrapper">
                                <i class="fa fa-map-marker-alt modern-input-icon" style="top: 1.25rem;"></i>
                                {!! Form::textarea('shipping_address', null, ['class' => 'modern-input has-icon', 'placeholder' => __('lang_v1.shipping_address'), 'rows' => '3', 'style' => 'resize: vertical;']); !!}
                            </div>
                        </div>

                        <div class="modern-form-group">
                            <label class="modern-label" for="shipping_charges">{{ __('custom.shipping_charges') }}</label>
                            <div class="modern-input-wrapper">
                                <i class="fa fa-dollar-sign modern-input-icon"></i>
                                {!! Form::text('shipping_charges', @num_format(0.00), ['class' => 'modern-input has-icon input_number', 'placeholder' => __('sale.shipping_charges')]); !!}
                            </div>
                        </div>
                    </div>

                    <div class="modern-grid-3" style="margin-top: 1.5rem;">
                        <div class="modern-form-group">
                            <label class="modern-label" for="shipping_status">{{ __('custom.shipping_status') }}</label>
                            <div class="modern-input-wrapper">
                                <i class="fa fa-truck modern-input-icon"></i>
                                {!! Form::select('shipping_status', $shipping_statuses, null, ['class' => 'modern-input modern-select has-icon', 'placeholder' => __('messages.please_select')]); !!}
                            </div>
                        </div>

                        <div class="modern-form-group">
                            <label class="modern-label" for="delivered_to">{{ __('custom.delivered_to') }}</label>
                            <div class="modern-input-wrapper">
                                <i class="fa fa-user-check modern-input-icon"></i>
                                {!! Form::text('delivered_to', null, ['class' => 'modern-input has-icon', 'placeholder' => __('lang_v1.delivered_to')]); !!}
                            </div>
                        </div>

                        <div class="modern-form-group">
                            <label class="modern-label" for="delivery_person">{{ __('custom.delivery_person') }}</label>
                            <div class="modern-input-wrapper">
                                <i class="fa fa-user-cog modern-input-icon"></i>
                                {!! Form::select('delivery_person', $users, null, ['class' => 'modern-input modern-select has-icon', 'placeholder' => __('messages.please_select')]); !!}
                            </div>
                        </div>
                    </div>

                    <!-- Shipping Custom Fields -->
                    @php
                        $shipping_custom_label_1 = !empty($custom_labels['shipping']['custom_field_1']) ? $custom_labels['shipping']['custom_field_1'] : '';
                        $is_shipping_custom_field_1_required = !empty($custom_labels['shipping']['is_custom_field_1_required']) && $custom_labels['shipping']['is_custom_field_1_required'] == 1 ? true : false;

                        $shipping_custom_label_2 = !empty($custom_labels['shipping']['custom_field_2']) ? $custom_labels['shipping']['custom_field_2'] : '';
                        $is_shipping_custom_field_2_required = !empty($custom_labels['shipping']['is_custom_field_2_required']) && $custom_labels['shipping']['is_custom_field_2_required'] == 1 ? true : false;

                        $shipping_custom_label_3 = !empty($custom_labels['shipping']['custom_field_3']) ? $custom_labels['shipping']['custom_field_3'] : '';
                        $is_shipping_custom_field_3_required = !empty($custom_labels['shipping']['is_custom_field_3_required']) && $custom_labels['shipping']['is_custom_field_3_required'] == 1 ? true : false;

                        $shipping_custom_label_4 = !empty($custom_labels['shipping']['custom_field_4']) ? $custom_labels['shipping']['custom_field_4'] : '';
                        $is_shipping_custom_field_4_required = !empty($custom_labels['shipping']['is_custom_field_4_required']) && $custom_labels['shipping']['is_custom_field_4_required'] == 1 ? true : false;

                        $shipping_custom_label_5 = !empty($custom_labels['shipping']['custom_field_5']) ? $custom_labels['shipping']['custom_field_5'] : '';
                        $is_shipping_custom_field_5_required = !empty($custom_labels['shipping']['is_custom_field_5_required']) && $custom_labels['shipping']['is_custom_field_5_required'] == 1 ? true : false;
                    @endphp
                    
                    @if(!empty($shipping_custom_label_1) || !empty($shipping_custom_label_2) || !empty($shipping_custom_label_3) || !empty($shipping_custom_label_4) || !empty($shipping_custom_label_5))
                        <div class="modern-form-grid" style="margin-top: 2rem;">
                            @if(!empty($shipping_custom_label_1))
                                <div class="modern-form-group">
                                    <label class="modern-label" for="shipping_custom_field_1">{{ $shipping_custom_label_1 }}{{ $is_shipping_custom_field_1_required ? ' *' : '' }}</label>
                                    <div class="modern-input-wrapper">
                                        <i class="fa fa-tag modern-input-icon"></i>
                                        {!! Form::text('shipping_custom_field_1', !empty($walk_in_customer['shipping_custom_field_details']['shipping_custom_field_1']) ? $walk_in_customer['shipping_custom_field_details']['shipping_custom_field_1'] : null, ['class' => 'modern-input has-icon', 'placeholder' => $shipping_custom_label_1, 'required' => $is_shipping_custom_field_1_required]); !!}
                                    </div>
                                </div>
                            @endif
                            @if(!empty($shipping_custom_label_2))
                                <div class="modern-form-group">
                                    <label class="modern-label" for="shipping_custom_field_2">{{ $shipping_custom_label_2 }}{{ $is_shipping_custom_field_2_required ? ' *' : '' }}</label>
                                    <div class="modern-input-wrapper">
                                        <i class="fa fa-tag modern-input-icon"></i>
                                        {!! Form::text('shipping_custom_field_2', !empty($walk_in_customer['shipping_custom_field_details']['shipping_custom_field_2']) ? $walk_in_customer['shipping_custom_field_details']['shipping_custom_field_2'] : null, ['class' => 'modern-input has-icon', 'placeholder' => $shipping_custom_label_2, 'required' => $is_shipping_custom_field_2_required]); !!}
                                    </div>
                                </div>
                            @endif
                            @if(!empty($shipping_custom_label_3))
                                <div class="modern-form-group">
                                    <label class="modern-label" for="shipping_custom_field_3">{{ $shipping_custom_label_3 }}{{ $is_shipping_custom_field_3_required ? ' *' : '' }}</label>
                                    <div class="modern-input-wrapper">
                                        <i class="fa fa-tag modern-input-icon"></i>
                                        {!! Form::text('shipping_custom_field_3', !empty($walk_in_customer['shipping_custom_field_details']['shipping_custom_field_3']) ? $walk_in_customer['shipping_custom_field_details']['shipping_custom_field_3'] : null, ['class' => 'modern-input has-icon', 'placeholder' => $shipping_custom_label_3, 'required' => $is_shipping_custom_field_3_required]); !!}
                                    </div>
                                </div>
                            @endif
                            @if(!empty($shipping_custom_label_4))
                                <div class="modern-form-group">
                                    <label class="modern-label" for="shipping_custom_field_4">{{ $shipping_custom_label_4 }}{{ $is_shipping_custom_field_4_required ? ' *' : '' }}</label>
                                    <div class="modern-input-wrapper">
                                        <i class="fa fa-tag modern-input-icon"></i>
                                        {!! Form::text('shipping_custom_field_4', !empty($walk_in_customer['shipping_custom_field_details']['shipping_custom_field_4']) ? $walk_in_customer['shipping_custom_field_details']['shipping_custom_field_4'] : null, ['class' => 'modern-input has-icon', 'placeholder' => $shipping_custom_label_4, 'required' => $is_shipping_custom_field_4_required]); !!}
                                    </div>
                                </div>
                            @endif
                            @if(!empty($shipping_custom_label_5))
                                <div class="modern-form-group">
                                    <label class="modern-label" for="shipping_custom_field_5">{{ $shipping_custom_label_5 }}{{ $is_shipping_custom_field_5_required ? ' *' : '' }}</label>
                                    <div class="modern-input-wrapper">
                                        <i class="fa fa-tag modern-input-icon"></i>
                                        {!! Form::text('shipping_custom_field_5', !empty($walk_in_customer['shipping_custom_field_details']['shipping_custom_field_5']) ? $walk_in_customer['shipping_custom_field_details']['shipping_custom_field_5'] : null, ['class' => 'modern-input has-icon', 'placeholder' => $shipping_custom_label_5, 'required' => $is_shipping_custom_field_5_required]); !!}
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif

                    <div class="modern-form-grid" style="margin-top: 2rem;">
                        <div class="modern-form-group">
                            <label class="modern-label" for="shipping_documents">{{ __('custom.shipping_documents') }}</label>
                            <div class="modern-input-wrapper">
                                <i class="fa fa-file-upload modern-input-icon"></i>
                                {!! Form::file('shipping_documents[]', ['id' => 'shipping_documents', 'class' => 'modern-input has-icon', 'multiple', 'accept' => implode(',', array_keys(config('constants.document_upload_mimes_types')))]); !!}
                            </div>
                            <small style="margin-top: 0.5rem; color: var(--text-muted); font-size: 0.8125rem;">
                                @lang('purchase.max_file_size', ['size' => (config('constants.document_size_limit') / 1000000)])
                                @includeIf('components.document_help_text')
                            </small>
                        </div>
                    </div>

                    <!-- Additional Expenses -->
                    <div style="text-align: center; margin-top: 2rem;">
                        <button type="button" class="modern-btn modern-btn-primary" id="toggle_additional_expense">
                            <i class="fas fa-plus"></i> @lang('lang_v1.add_additional_expenses') <i class="fas fa-chevron-down"></i>
                        </button>
                    </div>

                    <div id="additional_expenses_div" style="display: none; margin-top: 1.5rem;">
                        <div class="modern-table-container">
                            <table class="modern-table">
                                <thead>
                                    <tr>
                                        <th>@lang('lang_v1.additional_expense_name')</th>
                                        <th>@lang('sale.amount')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{!! Form::text('additional_expense_key_1', null, ['class' => 'modern-input', 'id' => 'additional_expense_key_1', 'placeholder' => __('custom.expense_name')]); !!}</td>
                                        <td>{!! Form::text('additional_expense_value_1', 0, ['class' => 'modern-input input_number', 'id' => 'additional_expense_value_1', 'placeholder' => '0.00']); !!}</td>
                                    </tr>
                                    <tr>
                                        <td>{!! Form::text('additional_expense_key_2', null, ['class' => 'modern-input', 'id' => 'additional_expense_key_2', 'placeholder' => __('custom.expense_name')]); !!}</td>
                                        <td>{!! Form::text('additional_expense_value_2', 0, ['class' => 'modern-input input_number', 'id' => 'additional_expense_value_2', 'placeholder' => '0.00']); !!}</td>
                                    </tr>
                                    <tr>
                                        <td>{!! Form::text('additional_expense_key_3', null, ['class' => 'modern-input', 'id' => 'additional_expense_key_3', 'placeholder' => __('custom.expense_name')]); !!}</td>
                                        <td>{!! Form::text('additional_expense_value_3', 0, ['class' => 'modern-input input_number', 'id' => 'additional_expense_value_3', 'placeholder' => '0.00']); !!}</td>
                                    </tr>
                                    <tr>
                                        <td>{!! Form::text('additional_expense_key_4', null, ['class' => 'modern-input', 'id' => 'additional_expense_key_4', 'placeholder' => __('custom.expense_name')]); !!}</td>
                                        <td>{!! Form::text('additional_expense_value_4', 0, ['class' => 'modern-input input_number', 'id' => 'additional_expense_value_4', 'placeholder' => '0.00']); !!}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Payment Information Section -->
                @php
                    $is_enabled_download_pdf = config('constants.enable_download_pdf');
                    $payment_body_id = 'payment_rows_div';
                    if ($is_enabled_download_pdf) {
                        $payment_body_id = '';
                    }
                @endphp
                @if((empty($status) || (!in_array($status, ['quotation', 'draft'])) || $is_enabled_download_pdf) && $sale_type != 'sales_order')
                    @can('sell.payments')
                        <div style="border-top: 2px solid var(--border); padding-top: 2rem; margin-top: 2rem;">
                            <h3 style="margin: 0 0 1rem 0; font-size: 1rem; font-weight: 700; color: var(--text); display: flex; align-items: center; gap: 0.5rem;">
                                <i class="fa fa-credit-card" style="color: var(--primary);"></i>
                                {{ __('custom.payment_information') }}
                            </h3>
                            
                            @if($is_enabled_download_pdf)
                                <div class="modern-form-grid" style="margin-bottom: 2rem;">
                                    <div class="modern-form-group">
                                        <label class="modern-label" for="prefer_payment_method">@lang('lang_v1.prefer_payment_method')</label>
                                        <div class="modern-input-wrapper">
                                            <i class="fa fa-money-bill-alt modern-input-icon"></i>
                                            {!! Form::select("prefer_payment_method", $payment_types, 'cash', ['class' => 'modern-input modern-select has-icon']); !!}
                                        </div>
                                        <small style="margin-top: 0.25rem; color: var(--text-muted); font-size: 0.75rem;">@lang('lang_v1.this_will_be_shown_in_pdf')</small>
                                    </div>
                                    <div class="modern-form-group">
                                        <label class="modern-label" for="prefer_payment_account">@lang('lang_v1.prefer_payment_account')</label>
                                        <div class="modern-input-wrapper">
                                            <i class="fa fa-money-bill-alt modern-input-icon"></i>
                                            {!! Form::select("prefer_payment_account", $accounts, null, ['class' => 'modern-input modern-select has-icon']); !!}
                                        </div>
                                        <small style="margin-top: 0.25rem; color: var(--text-muted); font-size: 0.75rem;">@lang('lang_v1.this_will_be_shown_in_pdf')</small>
                                    </div>
                                </div>
                            @endif
                            
                            @if(empty($status) || !in_array($status, ['quotation', 'draft']))
                                <div class="payment_row" @if($is_enabled_download_pdf) id="payment_rows_div" @endif>
                                    <div class="modern-form-group" style="margin-bottom: 1rem;">
                                        <div style="background: var(--surface-alt); padding: 1rem; border-radius: var(--radius); border: 1px solid var(--border);">
                                            <strong>@lang('lang_v1.advance_balance'):</strong> <span id="advance_balance_text">0.00</span>
                                            {!! Form::hidden('advance_balance', null, ['id' => 'advance_balance', 'data-error-msg' => __('lang_v1.required_advance_balance_not_available')]); !!}
                                        </div>
                                    </div>
                                    @include('sale_pos.partials.payment_row_form', ['row_index' => 0, 'show_date' => true, 'show_denomination' => true])
                                </div>
                                
                                <div class="payment_row" style="margin-top: 2rem;">
                                    <div style="background: var(--surface-alt); padding: 1.5rem; border-radius: var(--radius); border: 1px solid var(--border);">
                                        <div style="border-bottom: 1px solid var(--border); padding-bottom: 1rem; margin-bottom: 1rem;">
                                            <strong style="font-size: 1rem;">@lang('lang_v1.change_return'):</strong><br/>
                                            <span class="lead text-bold change_return_span" style="font-size: 1.25rem; color: var(--success);">0</span>
                                            {!! Form::hidden("change_return", $change_return['amount'], ['class' => 'form-control change_return input_number', 'required', 'id' => "change_return"]); !!}
                                            @if(!empty($change_return['id']))
                                                <input type="hidden" name="change_return_id" value="{{$change_return['id']}}">
                                            @endif
                                        </div>
                                        
                                        <div class="row hide payment_row" id="change_return_payment_data">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    {!! Form::label("change_return_method" , __('lang_v1.change_return_payment_method') . ':*') !!}
                                                    <div class="input-group">
                                                        <span class="input-group-addon">
                                                            <i class="fas fa-money-bill-alt"></i>
                                                        </span>
                                                        @php
                                                            $_payment_method = empty($change_return['method']) && array_key_exists('cash', $payment_types) ? 'cash' : $change_return['method'];
                                                            $_payment_types = $payment_types;
                                                            if(isset($_payment_types['advance'])) {
                                                                unset($_payment_types['advance']);
                                                            }
                                                        @endphp
                                                        {!! Form::select("payment[change_return][method]", $_payment_types, $_payment_method, ['class' => 'form-control col-md-12 payment_types_dropdown', 'id' => 'change_return_method', 'style' => 'width:100%;']); !!}
                                                    </div>
                                                </div>
                                            </div>
                                            @if(!empty($accounts))
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    {!! Form::label("change_return_account" , __('lang_v1.change_return_payment_account') . ':') !!}
                                                    <div class="input-group">
                                                        <span class="input-group-addon">
                                                            <i class="fas fa-money-bill-alt"></i>
                                                        </span>
                                                        {!! Form::select("payment[change_return][account_id]", $accounts, !empty($change_return['account_id']) ? $change_return['account_id'] : '' , ['class' => 'form-control select2', 'id' => 'change_return_account', 'style' => 'width:100%;']); !!}
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                            @include('sale_pos.partials.payment_type_details', ['payment_line' => $change_return, 'row_index' => 'change_return'])
                                        </div>
                                        
                                        <div style="text-align: right; padding-top: 1rem; border-top: 1px solid var(--border);">
                                            <strong style="font-size: 1.125rem;">@lang('lang_v1.balance'): <span class="balance_due">0.00</span></strong>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endcan
                @endif

                <!-- Final Total -->
                <div style="margin-top: 2rem;">
                    @if(!empty($pos_settings['amount_rounding_method']) && $pos_settings['amount_rounding_method'] > 0)
                        <small id="round_off" style="display: block; text-align: right; margin-bottom: 0.5rem; color: var(--text-muted);">
                            (@lang('lang_v1.round_off'): <span id="round_off_text">0</span>)
                        </small>
                        <input type="hidden" name="round_off_amount" id="round_off_amount" value="0">
                    @endif
                    
                    <div class="modern-summary-card">
                        <div class="modern-summary-row">
                            <span class="modern-summary-label">@lang('sale.total_payable'):</span>
                            <span class="modern-summary-value">
                                <input type="hidden" name="final_total" id="final_total_input">
                                <span id="total_payable">0</span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
        
        <script>
        function toggleShippingPaymentSection() {
            const content = document.getElementById('shipping-payment-content');
            const icon = document.getElementById('shipping-payment-toggle-icon');
            
            if (content.style.display === 'none' || content.style.display === '') {
                content.style.display = 'block';
                content.style.opacity = '0';
                setTimeout(() => {
                    content.style.opacity = '1';
                }, 10);
                icon.className = 'fa fa-chevron-up';
            } else {
                content.style.opacity = '0';
                setTimeout(() => {
                    content.style.display = 'none';
                }, 300);
                icon.className = 'fa fa-chevron-down';
            }
        }

        function toggleDiscountSection() {
            const content = document.getElementById('discount-content');
            const icon = document.getElementById('discount-toggle-icon');
            
            if (content.style.display === 'none' || content.style.display === '') {
                content.style.display = 'block';
                content.style.opacity = '0';
                setTimeout(() => {
                    content.style.opacity = '1';
                }, 10);
                icon.className = 'fa fa-chevron-up';
            } else {
                content.style.opacity = '0';
                setTimeout(() => {
                    content.style.display = 'none';
                }, 300);
                icon.className = 'fa fa-chevron-down';
            }
        }

        function togglePricingDocumentSection() {
            const content = document.getElementById('pricing-document-content');
            const icon = document.getElementById('pricing-document-toggle-icon');
            
            if (content.style.display === 'none' || content.style.display === '') {
                content.style.display = 'block';
                content.style.opacity = '0';
                setTimeout(() => {
                    content.style.opacity = '1';
                }, 10);
                icon.className = 'fa fa-chevron-up';
            } else {
                content.style.opacity = '0';
                setTimeout(() => {
                    content.style.display = 'none';
                }, 300);
                icon.className = 'fa fa-chevron-down';
            }
        }

        // Enhanced Product Search Functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Check if jQuery is available
            if (typeof $ === 'undefined') {
                console.error('jQuery is required for product search functionality');
                return;
            }

            // Disable the original search input to prevent conflicts
            const originalSearchInput = document.getElementById('search_product');
            if (originalSearchInput) {
                originalSearchInput.style.display = 'none';
                originalSearchInput.disabled = true;
                // Remove any existing typeahead functionality
                if (typeof $.fn.typeahead !== 'undefined') {
                    $(originalSearchInput).typeahead('destroy');
                }
            }

            // Debug: Log available POS functions for troubleshooting
            console.log('Available POS-related functions:', 
                Object.keys(window).filter(key => 
                    key.toLowerCase().includes('product') || 
                    key.toLowerCase().includes('pos') ||
                    key.toLowerCase().includes('add') ||
                    key.toLowerCase().includes('sell')
                ).sort()
            );

            const searchInput = document.getElementById('search_product_new');
            const searchDropdown = document.getElementById('search_results_dropdown');
            const searchResults = document.querySelector('.search-results-content');
            const resultsCount = document.querySelector('.results-count');
            let searchTimeout;

            // Search input styling on focus
            searchInput.addEventListener('focus', function() {
                this.style.borderColor = 'var(--primary)';
                this.style.boxShadow = '0 0 0 3px rgba(102, 126, 234, 0.1)';
            });

            searchInput.addEventListener('blur', function() {
                setTimeout(() => {
                    this.style.borderColor = 'var(--border)';
                    this.style.boxShadow = '0 2px 8px rgba(0,0,0,0.08)';
                    // Hide dropdown after a delay to allow clicking on results
                    setTimeout(() => {
                        hideSearchDropdown();
                    }, 200);
                }, 100);
            });

            // Search functionality with debounce
            searchInput.addEventListener('input', function() {
                const query = this.value.trim();
                
                clearTimeout(searchTimeout);
                
                if (query.length < 2) {
                    hideSearchDropdown();
                    return;
                }

                // Show loading state
                showSearchDropdown();
                searchResults.innerHTML = `
                    <div style="padding: 2rem; text-align: center;">
                        <div style="display: inline-flex; align-items: center; gap: 0.75rem; color: var(--text-muted);">
                            <i class="fa fa-spinner fa-spin"></i>
                            <span>${I18N.searching_products}</span>
                        </div>
                    </div>
                `;

                searchTimeout = setTimeout(() => {
                    performProductSearch(query);
                }, 300);
            });

            function showSearchDropdown() {
                searchDropdown.style.display = 'block';
                setTimeout(() => {
                    searchDropdown.style.opacity = '1';
                    searchDropdown.style.transform = 'translateY(0)';
                }, 10);
            }

            function hideSearchDropdown() {
                searchDropdown.style.opacity = '0';
                searchDropdown.style.transform = 'translateY(-10px)';
                setTimeout(() => {
                    searchDropdown.style.display = 'none';
                }, 200);
            }

            function performProductSearch(query) {
                // Get location ID
                const locationId = $('#location_id').val() || $('#select_location_id').val();
                
                // Try different possible endpoints for product search
                const searchEndpoints = [
                    '/products/list',
                    '/pos/get-products',
                    '/sell/get-products'
                ];

                function trySearch(endpointIndex = 0) {
                    if (endpointIndex >= searchEndpoints.length) {
                        // All endpoints failed
                        showSearchError('No product search endpoint available');
                        return;
                    }

                    $.ajax({
                        method: 'GET',
                        url: searchEndpoints[endpointIndex],
                        dataType: 'json',
                        data: {
                            location_id: locationId,
                            term: query,
                            search: query,
                            not_for_selling: 0,
                            check_enable_stock: 1
                        },
                        success: function(data) {
                            let products = [];
                            
                            // Handle different response formats
                            if (data && data.results) {
                                products = data.results;
                            } else if (data && Array.isArray(data)) {
                                products = data;
                            } else if (data && data.data) {
                                products = data.data;
                            }

                            // Format products consistently
                            const formattedProducts = products.map(product => ({
                                id: product.product_id || product.id,
                                name: product.text || product.name || product.product_name,
                                sku: product.sku || product.sub_sku || 'N/A',
                                price: formatPrice(product.selling_price || product.default_sell_price || product.price || 0),
                                stock: formatStock(product),
                                variation_id: product.variation_id || product.id,
                                product_type: product.type || product.product_type || 'single',
                                enable_stock: product.enable_stock || false,
                                image: product.image 
                            }));

                            console.log('dropdown search with all products and images data', formattedProducts);
                            console.log('test images url data **********************************')
							    console.log('url for images ',formattedProducts[0].image);
                            renderSearchResults(formattedProducts, query);
                        },
                        error: function(xhr, status, error) {
                            console.log(`Endpoint ${searchEndpoints[endpointIndex]} failed:`, error);
                            // Try next endpoint
                            trySearch(endpointIndex + 1);
                        }
                    });
                }

                trySearch();
            }

            function formatPrice(price) {
                return parseFloat(price || 0).toFixed(2);
            }

            function formatStock(product) {
                if (!product.enable_stock) {
                    return I18N.stock_not_tracked;
                }
                
                const qty = product.qty_available || product.stock_quantity || product.quantity || 0;
                const unit = product.unit || '';
                
                if (qty <= 0) {
                    return I18N.out_of_stock;
                } else if (qty <= 5) {
                    return `${qty} ${unit} (${I18N.low_stock})`.trim();
                } else {
                    return `${qty} ${unit} ${I18N.in_stock}`.trim();
                }
            }

            function showSearchError(message) {
                searchResults.innerHTML = `
                    <div style="padding: 2rem; text-align: center;">
                        <div style="color: var(--danger);">
                            <i class="fa fa-exclamation-triangle" style="font-size: 2rem; margin-bottom: 1rem;"></i>
                            <p style="margin: 0; font-weight: 500;">${I18N.search_error}</p>
                            <p style="margin: 0.5rem 0 0 0; font-size: 0.875rem;">${message}</p>
                        </div>
                    </div>
                `;
                resultsCount.textContent = 'Error';
            }

            function getStockColor(stockText) {
                const text = stockText.toLowerCase();
                if (text.includes('out of stock')) {
                    return 'var(--danger)';
                } else if (text.includes('low stock')) {
                    return 'var(--warning)';
                } else if (text.includes('not tracked')) {
                    return 'var(--text-muted)';
                } else {
                    return 'var(--success)';
                }
            }

            function renderSearchResults(results, query) {
                if (results.length === 0) {
                    searchResults.innerHTML = `
                        <div style="padding: 2rem; text-align: center;">
                            <div style="color: var(--text-muted);">
                                <i class="fa fa-search" style="font-size: 2rem; margin-bottom: 1rem;"></i>
                                <p style="margin: 0; font-weight: 500;">${I18N.no_products_found}</p>
                                <p style="margin: 0.5rem 0 0 0; font-size: 0.875rem;">Try searching with different keywords</p>
                            </div>
                        </div>
                    `;
                    resultsCount.textContent = '0 results';
                    return;
                }

                resultsCount.textContent = `${results.length} result${results.length > 1 ? 's' : ''}`;
                
                searchResults.innerHTML = results.map(product => {
                    const stockColor = getStockColor(product.stock);
                    const isOutOfStock = product.stock.toLowerCase().includes('out of stock');
                    const productName = product.name.replace(/'/g, '&#39;').replace(/"/g, '&quot;');
             
                    // Debug: Log all image-related fields
                    console.log('Product image data:', {
                        id: product.id,
                        name: product.name,
                        image: product.image,
                        image_url: product.image,
                        product_image: product.product_image,
                        media: product.media,
                        fullProduct: product
                    });
                    
                    // Get product image URL with comprehensive field checking
                    let imageUrl = null;
                    
                    // Check various possible image fields, but skip default.png
                    const imageFields = [
                        product.image,
                        product.image_url,
                        product.product_image,
                        product.img,
                        product.photo,
                        product.picture,
                        product.thumbnail,
                        product.featured_image,
                        (product.media && product.media.length > 0) ? (product.media[0].display_url || product.media[0].url || product.media[0].path) : null,
                        (product.images && product.images.length > 0) ? product.images[0] : null
                    ];
                    
                    // Find the first non-null, non-empty image that's not the default
                    for (let field of imageFields) {
                        if (field && 
                            field.trim() !== '' && 
                            field !== 'null' && 
                            field !== 'undefined' &&
                            !field.includes('default.png') &&
                            !field.includes('img/default.png') &&
                            !field.includes('no-image') &&
                            !field.includes('placeholder')) {
                            imageUrl = field.trim();
                            break;
                        }
                    }
                    
                    // Normalize relative image paths
                    if (imageUrl && typeof window.normalizeProductImageUrl === 'function') {
                        imageUrl = window.normalizeProductImageUrl(imageUrl);
                    }

                    // If we got a default image URL, try to fetch the real image from database
                    if (!imageUrl || imageUrl.includes('default.png')) {
                        // The API is returning default image, try to get real image via AJAX
                        imageUrl = null; // Reset so we can try AJAX approach
                    }
                    
                    // If no real image found, immediately try to fetch from database
                    if (!imageUrl) {
                        console.log(`No real image found in API response for product ${product.id}, will try to fetch from database`);
                        
                        // Ensure we have valid IDs (not undefined or null)
                        const safeProductId = product.id || product.variation_id || '';
                        const safeVariationId = product.variation_id || product.id || '';
                        const safeSku = product.sku || '';
                        
                        // For immediate display, try common Laravel image paths
                        const possibleImageUrls = [];
                        
                        // Only add URLs if we have valid IDs
                        if (safeProductId) {
                            possibleImageUrls.push(
                                `/uploads/img/${safeProductId}.jpg`,
                                `/uploads/img/${safeProductId}.png`,
                                `/uploads/img/${safeProductId}.jpeg`,
                                `/uploads/img/product_${safeProductId}.jpg`,
                                `/uploads/img/product_${safeProductId}.png`,
                                `/storage/products/${safeProductId}.jpg`,
                                `/storage/products/${safeProductId}.png`,
                                `/storage/img/${safeProductId}.jpg`,
                                `/storage/img/${safeProductId}.png`
                            );
                        }
                        
                        if (safeSku) {
                            possibleImageUrls.push(
                                `/uploads/img/${safeSku}.jpg`,
                                `/uploads/img/${safeSku}.png`,
                                `/uploads/img/${safeSku}.jpeg`
                            );
                        }
                        
                        if (safeVariationId && safeVariationId !== safeProductId) {
                            possibleImageUrls.push(
                                `/uploads/img/${safeVariationId}.jpg`,
                                `/uploads/img/${safeVariationId}.png`
                            );
                        }
                        
                        // Add generic fallbacks
                        possibleImageUrls.push(
                            `/img/default.png`
                        );
                        
                        const normalizedFallbacks = typeof window.normalizeProductImageUrl === 'function'
                            ? possibleImageUrls.map(url => window.normalizeProductImageUrl(url))
                            : possibleImageUrls;

                        // Use the first one as default, error handler will try the rest
                        imageUrl = normalizedFallbacks[0];
                        
                        // Store all possible URLs for the error handler
                        window.productImageFallbacks = window.productImageFallbacks || {};
                        window.productImageFallbacks[product.id] = normalizedFallbacks;
                        
                        // Also try to fetch the real image immediately via AJAX (use variation_id as it's more reliable)
                        if (safeVariationId) {
                            setTimeout(() => {
                                fetchRealProductImage(safeVariationId);
                            }, 100);
                        }
                    }
                    
                    // Format the image URL properly
                    if (imageUrl) {
                        // Handle different URL formats
                        if (imageUrl.startsWith('http://') || imageUrl.startsWith('https://')) {
                            // Already a full URL, use as is
                        } else if (imageUrl.startsWith('/')) {
                            // Relative URL starting with /, use as is
                        } else {
                            // Relative path, add uploads prefix
                            if (!imageUrl.includes('uploads/') && !imageUrl.includes('storage/') && !imageUrl.includes('images/')) {
                                imageUrl = '/uploads/img/' + imageUrl;
                            } else {
                                imageUrl = '/' + imageUrl;
                            }
                        }
                    }
                    
                    // Fallback to default image if still no URL
                    if (!imageUrl) {
                        imageUrl = '/img/default.png';
                    }

                    if (typeof window.normalizeProductImageUrl === 'function') {
                        imageUrl = window.normalizeProductImageUrl(imageUrl);
                    }
                    
	                    console.log('Final image URL:', imageUrl);
	
	                    if (typeof window.cacheProductImage === 'function' && imageUrl && !/default\.png|no-image|placeholder/i.test(imageUrl)) {
	                        window.cacheProductImage(product.id, product.variation_id, imageUrl);
	                    }
	
                    const productIdJson = JSON.stringify(String(product.id));
                    const variationIdJson = JSON.stringify(product.variation_id ? String(product.variation_id) : '');
                    const fullImageUrl = `${window.location.origin}${imageUrl}`;
                    const imageUrlJson = JSON.stringify(fullImageUrl || '');
                    
                    return `
                    <div class="search-result-item" data-product-id="${product.id}" style="padding: 1rem; border-bottom: 1px solid var(--border); cursor: pointer; transition: all 0.2s ease; ${isOutOfStock ? 'opacity: 0.6;' : ''}" 
                         onmouseover="this.style.background='var(--surface-alt)'" 
                         onmouseout="this.style.background='white'"
                         onclick="selectProduct('${product.id}', '${product.variation_id}', '${productName}', '${product.sku}')">
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <div class="product-image-container" style="width: 3.5rem; height: 3.5rem; border-radius: 8px; overflow: hidden; flex-shrink: 0; position: relative; background: var(--surface-alt); border: 1px solid var(--border);">
                                <img src="${fullImageUrl}"
                                     alt="${productName}" 
                                     style="width: 100%; height: 100%; object-fit: cover; transition: all 0.3s ease; position: relative; z-index: 2;"
                                     data-product-id="${product.id}"
                                     data-variation-id="${product.variation_id || ''}"
                                     onerror="handleImageError(this, ${productIdJson}, ${variationIdJson});"
                                     onload="if(this.nextElementSibling) this.nextElementSibling.style.display='none'; window.onProductImageLoad && window.onProductImageLoad(this, ${productIdJson}, ${variationIdJson}, ${imageUrlJson});">
                                <div class="image-placeholder" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: ${isOutOfStock ? 'var(--danger)' : 'var(--surface-alt)'}; color: ${isOutOfStock ? 'white' : 'var(--text-muted)'}; z-index: 1;">
                                    <i class="fa ${isOutOfStock ? 'fa-times' : 'fa-image'} fa-lg"></i>
                                </div>
                                ${isOutOfStock ? '<div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(239, 68, 68, 0.8); display: flex; align-items: center; justify-content: center; color: white; z-index: 3;"><i class="fa fa-ban"></i></div>' : ''}
                            </div>
                            <div style="flex: 1; min-width: 0;">
                                <h4 style="margin: 0; font-size: 0.875rem; font-weight: 600; color: var(--text); line-height: 1.3; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">${product.name}</h4>
                                <div style="display: flex; align-items: center; gap: 1rem; margin-top: 0.25rem; flex-wrap: wrap;">
                                    <span style="font-size: 0.75rem; color: var(--text-muted); background: var(--surface-alt); padding: 0.125rem 0.5rem; border-radius: 4px; font-family: monospace;">SKU: ${product.sku}</span>
                                    <span style="font-size: 0.75rem; color: ${stockColor}; font-weight: 500; display: flex; align-items: center; gap: 0.25rem;">
                                        <i class="fa fa-cube fa-xs"></i> ${product.stock}
                                    </span>
                                </div>
                            </div>
                            <div style="text-align: right; flex-shrink: 0;">
                                <div style="font-weight: 700; color: var(--primary); font-size: 1rem;">$${product.price}</div>
                                <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.125rem; display: flex; align-items: center; justify-content: flex-end; gap: 0.25rem;">
                                    <i class="fa ${isOutOfStock ? 'fa-times-circle' : 'fa-plus-circle'}" style="color: ${isOutOfStock ? 'var(--danger)' : 'var(--success)'};"></i> 
                                    ${isOutOfStock ? I18N.out_of_stock : I18N.add_to_cart}
                                </div>
                            </div>
                        </div>
                    </div>
                    `;
                }).join('');
            }

            // Handle image loading errors with fallback URLs
	            window.handleImageError = function(img, productId, variationId) {
	                variationId = variationId || '';
	                if (img) {
	                    if (productId) {
	                        img.dataset.productId = String(productId);
	                    }
	                    if (variationId) {
	                        img.dataset.variationId = String(variationId);
	                    }
	                }
	                // Get the stored fallback URLs for this product, or use default list
	                const fallbackUrls = window.productImageFallbacks && window.productImageFallbacks[productId] 
	                    ? window.productImageFallbacks[productId]
	                    : [
                        `/uploads/img/products/product_${productId}.jpg`,
                        `/uploads/img/products/product_${productId}.png`,
                        `/uploads/img/products/${productId}.jpg`,
                        `/uploads/img/products/${productId}.png`,
                        `/uploads/img/products/${productId}.jpeg`,
                        `/uploads/img/${productId}.jpg`,
                        `/uploads/img/${productId}.png`,
                        `/uploads/img/${productId}.jpeg`,
                        `/images/products/${productId}.jpg`,
                        `/images/products/${productId}.png`,
                        `/storage/products/${productId}.jpg`,
                        `/storage/products/${productId}.png`,
                        `/storage/app/public/products/${productId}.jpg`,
                        `/storage/app/public/products/${productId}.png`,
                        `/uploads/media/products/${productId}.jpg`,
                        `/uploads/media/products/${productId}.png`,
                        `/uploads/media/${productId}.jpg`,
                        `/uploads/media/${productId}.png`,
                        `/img/product-placeholder.png`,
                        `/img/no-image.png`,
                        `/images/no-image.png`,
                        `/img/default.png`
                    ];
                
                // Get current attempt index
                let currentIndex = parseInt(img.dataset.attemptIndex || '0');
                
	                if (currentIndex < fallbackUrls.length - 1) {
	                    currentIndex++;
	                    img.dataset.attemptIndex = currentIndex;
	                    const nextUrl = typeof window.normalizeProductImageUrl === 'function'
	                        ? window.normalizeProductImageUrl(fallbackUrls[currentIndex])
	                        : fallbackUrls[currentIndex];
	                    img.onload = function() {
	                        if (typeof window.onProductImageLoad === 'function') {
	                            window.onProductImageLoad(img, productId, variationId, nextUrl);
	                        }
	                    };
	                    img.src = nextUrl;
	                    console.log(`Trying fallback image ${currentIndex} for product ${productId}: ${fallbackUrls[currentIndex]}`);
	                } else {
	                    // All attempts failed, try to fetch product details via AJAX for more image info
	                    if (!img.dataset.ajaxAttempted) {
	                        img.dataset.ajaxAttempted = 'true';
	                        fetchProductImageViaAjax(img, productId, variationId);
	                    } else {
	                        // Final fallback - hide image and show placeholder
	                        img.style.display = 'none';
	                        if (img.nextElementSibling) {
	                            img.nextElementSibling.style.display = 'flex';
	                        }
	                        console.log('All image fallbacks failed for product:', productId);
	                        if (typeof window.removeProductImageCache === 'function') {
	                            window.removeProductImageCache(productId, variationId);
	                        }
	                    }
	                }
	            };

	            // Fetch product image via special endpoint for real images
	            function fetchProductImageViaAjax(img, productId, variationId) {
	                console.log('Attempting to fetch real product image for product:', productId);
	                
                // Try to get the real image from your products table
                const ajaxEndpoints = [
                    `/products/${productId}/image`,        // Custom endpoint for just image
                    `/api/products/${productId}/image`,    // API version
                    `/products/${productId}`,              // Full product details
                    `/api/products/${productId}`,          // API full details
                    `/pos/get-product-image/${productId}`, // POS specific endpoint
                ];
                
                let endpointIndex = 0;
                
                function tryNextEndpoint() {
	                    if (endpointIndex >= ajaxEndpoints.length) {
	                        // All endpoints failed, show placeholder
	                        img.style.display = 'none';
	                        if (img.nextElementSibling) {
	                            img.nextElementSibling.style.display = 'flex';
	                        }
	                        console.log('All AJAX endpoints failed for product:', productId);
	                        if (typeof window.removeProductImageCache === 'function') {
	                            window.removeProductImageCache(productId, variationId);
	                        }
	                        return;
	                    }
                    
                    const currentEndpoint = ajaxEndpoints[endpointIndex];
                    console.log(`Trying endpoint ${endpointIndex + 1}: ${currentEndpoint}`);
                    
                    $.ajax({
                        method: 'GET',
                        url: currentEndpoint,
                        dataType: 'json',
                        success: function(productData) {
                            console.log(`Response from ${currentEndpoint}:`, productData);
                            
                            let realImageUrl = null;
                            
                            // Handle different response formats
                            const dataToCheck = productData.data || productData;
                            
                            if (dataToCheck) {
                                // Check for image field from your products table
                                const imageFields = [
                                    dataToCheck.image,
                                    dataToCheck.image_url,
                                    dataToCheck.product_image,
                                    dataToCheck.featured_image,
                                    (dataToCheck.media && dataToCheck.media.length > 0) ? dataToCheck.media[0].url : null,
                                ];
                                
                                for (let field of imageFields) {
                                    if (field && 
                                        field.trim() !== '' &&
                                        !field.includes('default.png') && 
                                        !field.includes('no-image') && 
                                        !field.includes('placeholder')) {
                                        
                                        // Construct proper image URL based on your Laravel setup
                                        if (field.startsWith('http')) {
                                            realImageUrl = field;
                                        } else {
                                            // Construct URL for Laravel storage
                                            realImageUrl = `/uploads/img/${field}`;
                                        }
                                        break;
                                    }
                                }
                            }
                            
                            if (realImageUrl) {
                                const normalizedAjaxUrl = typeof window.normalizeProductImageUrl === 'function'
                                    ? window.normalizeProductImageUrl(realImageUrl)
                                    : realImageUrl;
                                console.log('Found real image via AJAX:', normalizedAjaxUrl);
                                if (typeof window.cacheProductImage === 'function') {
                                    window.cacheProductImage(productId, variationId, normalizedAjaxUrl);
                                }
                                img.onload = function() {
                                    if (typeof window.onProductImageLoad === 'function') {
                                        window.onProductImageLoad(img, productId, variationId, normalizedAjaxUrl);
                                    }
                                };
                                img.src = normalizedAjaxUrl;
                            } else {
                                // Try next endpoint
                                endpointIndex++;
                                tryNextEndpoint();
                            }
                        },
                        error: function(xhr, status, error) {
                            console.log(`Endpoint ${currentEndpoint} failed:`, error);
                            // Try next endpoint
                            endpointIndex++;
                            tryNextEndpoint();
                        }
                    });
                }
                
                tryNextEndpoint();
            }

            // Fetch real product image immediately when search results are shown
            function fetchRealProductImage(productId) {
                console.log('Fetching real image for product:', productId);
                
                // Skip if productId is invalid
                if (!productId || productId === 'undefined' || productId === 'null') {
                    console.log('Invalid product ID, skipping fetch');
                    return;
                }
                
                // Try to get image from the products list endpoint instead
                // This is more reliable than individual product endpoints
                $.ajax({
                    method: 'GET',
                    url: '/products/list',
                    dataType: 'json',
                    data: {
                        product_id: productId,
                        location_id: $('#location_id').val() || $('#select_location_id').val()
                    },
                    success: function(response) {
                        console.log('Product list response for ID', productId, ':', response);
                        
                        let productData = null;
                        
                        // Handle different response formats
                        if (response && response.results && response.results.length > 0) {
                            productData = response.results[0];
                        } else if (response && Array.isArray(response) && response.length > 0) {
                            productData = response[0];
                        } else if (response && response.data) {
                            productData = response.data;
                        }
                        
                        if (!productData) {
                            console.log('No product data found in response');
                            return;
                        }
                        
                        let realImageFilename = null;
                        
                        // Check various image fields
                        const imageFields = [
                            productData.image,
                            productData.image_url,
                            productData.product_image
                        ];
                        
                        for (let field of imageFields) {
                            if (field && 
                                field.trim() !== '' &&
                                !field.includes('default.png') && 
                                !field.includes('no-image')) {
                                realImageFilename = field.trim();
                                break;
                            }
                        }
                        
                        if (realImageFilename) {
                            // Construct the proper image URL - be careful not to duplicate paths
                            let realImageUrl;
                            if (realImageFilename.startsWith('http://') || realImageFilename.startsWith('https://')) {
                                realImageUrl = realImageFilename;
                            } else if (realImageFilename.startsWith('/uploads/')) {
                                // Already has full path
                                realImageUrl = realImageFilename;
                            } else {
                                // Add uploads prefix
                                realImageUrl = `/uploads/img/${realImageFilename}`;
                            }
                            
                            console.log('Found real image filename:', realImageFilename, 'URL:', realImageUrl);
                            
                            // Update all images for this product that are currently showing
                            const productImages = document.querySelectorAll(`img[data-product-id="${productId}"]`);
                            console.log('Found', productImages.length, 'images to update for product', productId);
                            
                            productImages.forEach(img => {
                                // Test if the new image exists before updating
                                const testImg = new Image();
                                testImg.onload = function() {
                                    const normalizedUrl = typeof window.normalizeProductImageUrl === 'function'
                                        ? window.normalizeProductImageUrl(realImageUrl)
                                        : realImageUrl;
                                    if (typeof window.cacheProductImage === 'function') {
                                        window.cacheProductImage(productId, null, normalizedUrl);
                                    }
                                    img.onload = function() {
                                        if (typeof window.onProductImageLoad === 'function') {
                                            window.onProductImageLoad(img, productId, img.dataset.variationId || '', normalizedUrl);
                                        }
                                    };
                                    img.src = normalizedUrl;
                                    console.log('Updated image for product', productId, 'to:', realImageUrl);
                                };
                                testImg.onerror = function() {
                                    console.log('Real image URL failed to load:', realImageUrl);
                                };
                                testImg.src = realImageUrl;
                            });
                        } else {
                            console.log('No real image filename found in database for product:', productId);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log('Failed to fetch real product image:', error, 'Status:', status);
                    }
                });
            }

            // Add product selection function
            window.selectProduct = function(productId, variationId, productName, sku) {
                console.log('Selected product:', {productId, variationId, productName, sku});
                hideSearchDropdown();
                searchInput.value = '';
                
                let productAdded = false;
                
                // Method 1: Try pos_product_row function (available in console output)
                if (!productAdded && typeof pos_product_row === 'function') {
                    try {
                        // Get location ID
                        const locationId = $('#location_id').val() || $('#select_location_id').val();
                        
                        // Call pos_product_row with variation ID and location
                        pos_product_row(variationId || productId, locationId);
                        productAdded = true;
                        console.log('Product added using pos_product_row');
                        
                        // Update totals
                        if (typeof pos_total_row === 'function') {
                            pos_total_row();
                        }
                        
                        // Update products count and hide empty state
                        updateProductsCount();
                        toggleEmptyState();
                    } catch (error) {
                        console.log('pos_product_row failed:', error);
                    }
                }
                
                // Method 2: Try get_product_details and pos_each_row (both available)
                if (!productAdded && typeof get_product_details === 'function' && typeof pos_each_row === 'function') {
                    try {
                        const locationId = $('#location_id').val() || $('#select_location_id').val();
                        
                        // Get product details first
                        get_product_details(variationId || productId, locationId, function(response) {
                            if (response) {
                                // Add the product row
                                pos_each_row(response);
                                
                                // Update totals
                                if (typeof pos_total_row === 'function') {
                                    pos_total_row();
                                }
                                
                                // Update products count and hide empty state
                                updateProductsCount();
                                toggleEmptyState();
                                
                                console.log('Product added using get_product_details + pos_each_row');
                            }
                        });
                        productAdded = true;
                    } catch (error) {
                        console.log('get_product_details + pos_each_row failed:', error);
                    }
                }
                
                // Method 3: Try to use the original search input method
                if (!productAdded) {
                    try {
                        // Temporarily show and enable the original search input
                        const originalSearch = $('#search_product');
                        if (originalSearch.length > 0) {
                            originalSearch.show().prop('disabled', false);
                            
                            // Set the SKU/product name as search value
                            originalSearch.val(sku || productName);
                            
                            // Try to trigger the typeahead selection manually
                            if (typeof originalSearch.data('typeahead') !== 'undefined') {
                                // Create a suggestion object that matches the expected format
                                const suggestion = {
                                    variation_id: variationId,
                                    product_id: productId,
                                    name: productName,
                                    sku: sku,
                                    product: productName,
                                    value: sku || productName
                                };
                                
                                // Trigger the selection event
                                originalSearch.trigger('typeahead:select', suggestion);
                                productAdded = true;
                                console.log('Product added using original typeahead trigger');
                            }
                            
                            // Hide the original search again
                            setTimeout(() => {
                                originalSearch.hide().prop('disabled', true);
                            }, 100);
                        }
                    } catch (error) {
                        console.log('Original typeahead method failed:', error);
                    }
                }
                
                // Method 4: Try direct AJAX call to get product row HTML
                if (!productAdded) {
                    try {
                        const locationId = $('#location_id').val() || $('#select_location_id').val();
                        
                        $.ajax({
                            method: 'GET',
                            url: '/sells/pos/get_product_row/' + (variationId || productId) + '/' + locationId,
                            dataType: 'json',
                            success: function(response) {
                                if (response.success && response.html_content) {
                                    // Add the row to the table
                                    $('#pos_table tbody').append(response.html_content);
                                    
                                    // Update totals
                                    if (typeof pos_total_row === 'function') {
                                        pos_total_row();
                                    }
                                    
                                    // Update products count and hide empty state
                                    updateProductsCount();
                                    toggleEmptyState();
                                    
                                    productAdded = true;
                                    console.log('Product added via direct AJAX get product row');
                                } else {
                                    console.log('AJAX response not successful:', response);
                                }
                            },
                            error: function(xhr, status, error) {
                                console.log('Direct AJAX get product row failed:', error);
                            }
                        });
                    } catch (error) {
                        console.log('Direct AJAX call failed:', error);
                    }
                }
                
                // Method 5: Manual row creation as last resort
                if (!productAdded) {
                    try {
                        const rowCount = $('#pos_table tbody tr').length;
                        
                        // Analyze the header structure to create matching row
                        const $headerRow = $('#pos_table thead tr:first-child');
                        const $headerCells = $headerRow.find('th');
                        let newRowHTML = `<tr class="product_row" data-product-id="${productId}" data-variation-id="${variationId}">`;
                        
                        // Loop through each header column and create corresponding cell
                        $headerCells.each(function(index) {
                            const $headerCell = $(this);
                            const headerText = $headerCell.text().toLowerCase().trim();
                            const isHidden = $headerCell.hasClass('hide');
                            const hideClass = isHidden ? ' class="hide"' : '';
                            
                            console.log(`Header ${index}: "${headerText}", hidden: ${isHidden}`);
                            
                            if (headerText.includes('image')) {
                                // Image column
                                const imageClass = isHidden 
                                    ? ' class="hide product-image-cell"' 
                                    : ' class="product-image-cell"';
                                newRowHTML += `
                                    <td${imageClass} style="text-align: center; vertical-align: middle;">
                                        <div class="product-image-container">
                                            <div class="product-image-loading">
                                                <i class="fa fa-spinner fa-spin"></i>
                                            </div>
                                            <div class="product-image-placeholder">
                                                <i class="fa fa-image"></i>
                                            </div>
                                        </div>
                                    </td>`;
                            } else if (headerText.includes('product')) {
                                // Product name column
                                newRowHTML += `
                                    <td${hideClass} style="vertical-align: middle;">
                                        <div class="product-name" style="font-weight: 600; color: var(--text-primary); margin-bottom: 0.25rem;">${productName}</div>
                                        <div class="product-sku" style="font-size: 0.875rem; color: var(--text-muted);">SKU: ${sku}</div>
                                        <input type="hidden" name="products[${rowCount}][product_id]" value="${productId}">
                                        <input type="hidden" name="products[${rowCount}][variation_id]" value="${variationId}">
                                    </td>`;
                            } else if (headerText.includes('description')) {
                                // Description column
                                newRowHTML += `
                                    <td${hideClass}>
                                        <input type="text" name="products[${rowCount}][product_description]" class="form-control" placeholder="Description" style="width: 100%; padding: 0.5rem; border: 1px solid var(--border); border-radius: 6px;">
                                    </td>`;
                            } else if (headerText.includes('qty') || headerText.includes('quantity')) {
                                // Quantity column
                                newRowHTML += `
                                    <td${hideClass}>
                                        <input type="number" name="products[${rowCount}][quantity]" class="form-control pos_quantity" value="1" min="1" step="any" style="width: 100%; padding: 0.5rem; border: 1px solid var(--border); border-radius: 6px;">
                                    </td>`;
                            } else if (headerText.includes('service') || headerText.includes('staff')) {
                                // Service staff column
                                newRowHTML += `
                                    <td${hideClass}>
                                        <select name="products[${rowCount}][res_service_staff_id]" class="form-control" style="width: 100%; padding: 0.5rem; border: 1px solid var(--border); border-radius: 6px;">
                                            <option value="">${I18N.select_staff}</option>
                                        </select>
                                    </td>`;
                            } else if (headerText.includes('unit') && headerText.includes('price')) {
                                // Unit price column
                                newRowHTML += `
                                    <td${hideClass}>
                                        <input type="text" name="products[${rowCount}][unit_price]" class="form-control pos_unit_price" value="" style="width: 100%; padding: 0.5rem; border: 1px solid var(--border); border-radius: 6px;">
                                    </td>`;
                            } else if (headerText.includes('discount')) {
                                // Discount column
                                newRowHTML += `
                                    <td${hideClass}>
                                        <input type="text" name="products[${rowCount}][line_discount_amount]" class="form-control" value="0" style="width: 100%; padding: 0.5rem; border: 1px solid var(--border); border-radius: 6px;">
                                    </td>`;
                            } else if (headerText.includes('tax') && !headerText.includes('price')) {
                                // Tax percentage column
                                newRowHTML += `
                                    <td${hideClass} style="text-align: center;">
                                        <span class="tax_percent">0</span>%
                                    </td>`;
                            } else if (headerText.includes('price') && headerText.includes('tax')) {
                                // Price including tax column
                                newRowHTML += `
                                    <td${hideClass} style="text-align: center;">
                                        <span class="price_inc_tax">0.00</span>
                                    </td>`;
                            } else if (headerText.includes('warranty')) {
                                // Warranty column
                                newRowHTML += `
                                    <td${hideClass}>
                                        <select name="products[${rowCount}][warranty_id]" class="form-control" style="width: 100%; padding: 0.5rem; border: 1px solid var(--border); border-radius: 6px;">
                                            <option value="">No Warranty</option>
                                        </select>
                                    </td>`;
                            } else if (headerText.includes('subtotal')) {
                                // Subtotal column
                                newRowHTML += `
                                    <td${hideClass} style="text-align: center; font-weight: 600;">
                                        <span class="pos_line_total">0.00</span>
                                    </td>`;
                            } else if (index === $headerCells.length - 1) {
                                // Last column - delete button
                                newRowHTML += `
                                    <td${hideClass} style="text-align: center;">
                                        <button type="button" class="btn btn-xs btn-danger remove_product_row" title="${I18N.remove_product}" style="padding: 0.25rem 0.5rem; border-radius: 4px;">
                                            <i class="fa fa-times"></i>
                                        </button>
                                    </td>`;
                            } else {
                                // Default empty column for any unrecognized headers
                                newRowHTML += `<td${hideClass}>&nbsp;</td>`;
                            }
                        });
                        
                        newRowHTML += '</tr>';
                        
                        console.log('Generated row HTML:', newRowHTML);
                        $('#pos_table_body').append(newRowHTML);
                        
                        // Update totals and UI
                        if (typeof pos_total_row === 'function') {
                            pos_total_row();
                        }
                        
                        // Update products count and hide empty state
                        updateProductsCount();
                        toggleEmptyState();
                        
                        // Load product image
                        setTimeout(() => {
                            const newRow = $('#pos_table_body tr:last-child');
                            const appliedCache = typeof window.applyCachedProductImage === 'function'
                                ? window.applyCachedProductImage(newRow, productId, variationId, productName)
                                : false;
                            const imageContainer = ensureProductImageContainer(newRow);
                            if (!appliedCache && imageContainer && imageContainer.find('img').length === 0) {
                                loadProductImageForRow(productId, variationId);
                            }
                        }, 100);
                        
                        productAdded = true;
                        console.log('Product added using manual row creation');
                    } catch (error) {
                        console.log('Manual row creation failed:', error);
                    }
                }
                
                // Show success message
                if (typeof toastr !== 'undefined') {
                    toastr.success(I18N.product_added_to_cart.replace(':name', productName));
                } else {
                    console.log(`${productName} added to cart`);
                }
                
                // Debug: Show available functions if nothing worked
                if (!productAdded) {
                    console.error('All methods failed. Available POS functions:', 
                        Object.keys(window).filter(key => 
                            key.toLowerCase().includes('product') || 
                            key.toLowerCase().includes('pos')
                        ).sort()
                    );
                }
            };

            // Hover effects for add product button
            const addProductBtn = document.querySelector('.modern-add-product-btn');
            if (addProductBtn) {
                addProductBtn.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-50%) scale(1.05)';
                    this.style.boxShadow = '0 4px 12px rgba(34, 197, 94, 0.4)';
                });
                
                addProductBtn.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(-50%) scale(1)';
                    this.style.boxShadow = '0 2px 8px rgba(34, 197, 94, 0.3)';
                });
            }

            // Products count and empty state management
            function updateProductsCount() {
                const productCount = $('#pos_table_body tr').length;
                const badge = $('.products-count-badge');
                badge.text(I18N.items_count.replace(':count', productCount));
                badge.addClass('updated');
                setTimeout(() => badge.removeClass('updated'), 300);
            }

            function toggleEmptyState() {
                const productCount = $('#pos_table_body tr').length;
                const emptyState = $('#pos_table_empty_state');
                const tableWrapper = $('.pos-table-wrapper');
                const tableContainer = $('.modern-pos-table-container');
                
                console.log('=== Toggle Empty State ===');
                console.log('Product count:', productCount);
                console.log('Empty state element:', emptyState.length);
                console.log('Table wrapper element:', tableWrapper.length);
                
                if (productCount === 0) {
                    console.log('No products - showing empty state');
                    emptyState.show().css('display', 'flex');
                    tableWrapper.hide();
                    tableContainer.removeClass('has-products');
                } else {
                    console.log('Products found - hiding empty state');
                    // Use multiple methods to ensure it's hidden
                    emptyState.hide();
                    emptyState.css({
                        'display': 'none',
                        'visibility': 'hidden',
                        'height': '0',
                        'min-height': '0',
                        'padding': '0',
                        'margin': '0'
                    });
                    emptyState.addClass('hidden');
                    tableWrapper.show().css('display', 'block');
                    tableContainer.addClass('has-products');
                }
                
                console.log('Empty state visible after toggle:', emptyState.is(':visible'));
                console.log('Empty state display style:', emptyState.css('display'));
            }

            // Initialize empty state
            toggleEmptyState();
            
            // Force minimum heights on page load
            setTimeout(function() {
                $('.pos_product_div').css({
                    'min-height': '550px',
                    'height': 'auto'
                });
                $('.modern-pos-table-container').css('min-height', '500px');
                $('.pos-table-wrapper').css('min-height', '450px');
                $('#pos_table_empty_state').css('min-height', '400px');
                console.log('Forced minimum heights applied');
            }, 500);
            
            // Add mutation observer to watch for table changes
            if (window.MutationObserver) {
                const tableObserver = new MutationObserver(function(mutations) {
                    let shouldUpdate = false;
                    mutations.forEach(function(mutation) {
                        if (mutation.type === 'childList') {
                            shouldUpdate = true;
                        }
                    });
                    if (shouldUpdate) {
                        console.log('Table content changed, updating empty state');
                        setTimeout(function() {
                            updateProductsCount();
                            toggleEmptyState();
                        }, 100);
                    }
                });
                
                const tableBody = document.getElementById('pos_table_body');
                if (tableBody) {
                    tableObserver.observe(tableBody, { 
                        childList: true, 
                        subtree: true 
                    });
                    console.log('Table mutation observer set up');
                }
            }
            
            // Add a periodic check to ensure empty state behavior is correct
            setInterval(function() {
                const productCount = $('#pos_table_body tr').length;
                const emptyState = $('#pos_table_empty_state');
                
                if (productCount > 0 && emptyState.is(':visible')) {
                    console.log('Found visible empty state with products present - forcing hide');
                    toggleEmptyState();
                }
            }, 2000);
            
            // Handle scroll shadow indicator
            $('.pos-table-wrapper').on('scroll', function() {
                const wrapper = $(this);
                if (wrapper.scrollTop() > 0) {
                    wrapper.addClass('scrolled');
                } else {
                    wrapper.removeClass('scrolled');
                }
            });

            function ensureProductImageContainer(row) {
                if (!row || row.length === 0) {
                    return null;
                }

                let imageCell = row.find('td.product-image-cell');
                if (imageCell.length === 0) {
                    const firstCell = row.find('td:first-child');
                    const newCell = $(`
                        <td class="product-image-cell" style="text-align: center; vertical-align: middle;">
                            <div class="product-image-container">
                                <div class="product-image-loading">
                                    <i class="fa fa-spinner fa-spin"></i>
                                </div>
                                <div class="product-image-placeholder">
                                    <i class="fa fa-image"></i>
                                </div>
                            </div>
                        </td>
                    `);

                    if (firstCell.length > 0) {
                        firstCell.before(newCell);
                    } else {
                        row.prepend(newCell);
                    }

                    imageCell = newCell;
                } else if (imageCell.find('.product-image-container').length === 0) {
                    imageCell.html(`
                        <div class="product-image-container">
                            <div class="product-image-loading">
                                <i class="fa fa-spinner fa-spin"></i>
                            </div>
                            <div class="product-image-placeholder">
                                <i class="fa fa-image"></i>
                            </div>
                        </div>
                    `);
                }

                imageCell.css({
                    'text-align': 'center',
                    'vertical-align': 'middle'
                });

                return imageCell.find('.product-image-container');
            }

            // Function to load product image for a table row
            function loadProductImageForRow(productId, variationId) {
                let row = $(`tr[data-product-id="${productId}"][data-variation-id="${variationId}"]`);

                if (row.length === 0) {
                    row = $('#pos_table_body tr').filter(function() {
                        const $row = $(this);
                        const productField = $row.find('input[name*="[product_id]"]').val();
                        const variationField = $row.find('input[name*="[variation_id]"]').val();

                        if (!productField) {
                            return false;
                        }

                        const productMatch = productField == productId;
                        const variationMatch = !variationId || !variationField || variationField == variationId;

                        return productMatch && variationMatch;
                    }).first();
                }

	                if (row.length === 0) {
	                    return;
	                }

	                row.attr('data-product-id', productId);
	                if (variationId) {
	                    row.attr('data-variation-id', variationId);
	                }

	                if (typeof window.applyCachedProductImage === 'function' && window.applyCachedProductImage(row, productId, variationId)) {
	                    return;
	                }

	                const imageContainer = ensureProductImageContainer(row);
	                if (!imageContainer || imageContainer.length === 0) {
	                    return;
	                }

                const loadingElement = imageContainer.find('.product-image-loading');
                const placeholderElement = imageContainer.find('.product-image-placeholder');

                imageContainer.find('img').remove();

                // Show loading state
                loadingElement.show();
                placeholderElement.hide();

                // Try to get product image via AJAX
                const imageEndpoints = [
                    `/products/${productId}/image`,
                    `/api/products/${productId}`,
                    `/products/${productId}`,
                ];

                let endpointIndex = 0;

                function tryNextImageEndpoint() {
                    if (endpointIndex >= imageEndpoints.length) {
                        // All endpoints failed, show placeholder
                        loadingElement.hide();
                        placeholderElement.show();
                        console.log('All image endpoints failed for product:', productId);
                        return;
                    }

                    const endpoint = imageEndpoints[endpointIndex];
                    console.log(`Trying image endpoint: ${endpoint}`);

                    $.ajax({
                        method: 'GET',
                        url: endpoint,
                        dataType: 'json',
                        success: function(response) {
                            console.log(`Image response from ${endpoint}:`, response);
                            
                            let imageUrl = null;
                            const productData = response.data || response;

                            // Check for image in response
                            const imageFields = [
                                productData.image,
                                productData.image_url,
                                productData.product_image,
                                productData.featured_image,
                                (productData.media && productData.media.length > 0) ? 
                                    (productData.media[0].display_url || productData.media[0].url || productData.media[0].path) : null,
                            ];

                            for (let field of imageFields) {
                                if (field && 
                                    field.trim() !== '' &&
                                    !field.includes('default.png') && 
                                    !field.includes('no-image') && 
                                    !field.includes('placeholder')) {
                                    
                                    // Construct proper image URL
                                    if (field.startsWith('http')) {
                                        imageUrl = field;
                                    } else {
                                        // Try Laravel storage paths
                                        const possiblePaths = [
                                            `/uploads/img/${field}`,
                                            `/storage/img/${field}`,
                                            `/storage/products/${field}`,
                                            `/uploads/img/products/${field}`,
                                            field.startsWith('/') ? field : `/${field}`
                                        ];
                                        imageUrl = possiblePaths[0]; // Use first path as primary
                                    }
                                    break;
                                }
                            }

                            if (imageUrl) {
                                console.log('Found product image:', imageUrl);
                                
                                // Create and test image
                                const testImg = new Image();
                                testImg.onload = function() {
                                    // Image loaded successfully
                                    loadingElement.hide();
                                    placeholderElement.hide();
                                    
                                // Create and insert image element
                                const imgElement = $('<img>', {
                                    src: imageUrl,
                                    alt: productData.name || I18N.product_image,
                                    class: 'product-table-image',
                                    title: I18N.click_to_view_larger,
                                    loading: 'lazy'
                                }).css('cursor', 'pointer')
                                  .attr('data-preview-url', imageUrl)
                                  .attr('data-preview-title', productData.name || I18N.product_image);

                                imgElement.on('click', function() {
                                    showImagePreview(imageUrl, productData.name || I18N.product_image);
                                });

                                if (typeof window.cacheProductImage === 'function') {
                                    window.cacheProductImage(productId, variationId, imageUrl);
                                }
                                imgElement.on('load', function() {
                                    if (typeof window.onProductImageLoad === 'function') {
                                        window.onProductImageLoad(this, productId, variationId, imageUrl);
                                    }
                                });
                                imageContainer.append(imgElement);

                                console.log('in Selected Products section with full product data and full images url', {
                                    productId,
                                    variationId,
                                    imageUrl,
                                    productData
                                });
                                };
                                
	                                testImg.onerror = function() {
	                                    console.log('Image failed to load:', imageUrl);
	                                    if (typeof window.removeProductImageCache === 'function') {
	                                        window.removeProductImageCache(productId, variationId);
	                                    }
	                                    // Try next endpoint
	                                    endpointIndex++;
	                                    tryNextImageEndpoint();
	                                };
                                
                                testImg.src = imageUrl;
                            } else {
                                // No image found, try next endpoint
                                endpointIndex++;
                                tryNextImageEndpoint();
                            }
                        },
                        error: function(xhr, status, error) {
                            console.log(`Image endpoint ${endpoint} failed:`, error);
                            // Try next endpoint
                            endpointIndex++;
                            tryNextImageEndpoint();
                        }
                    });
                }

                tryNextImageEndpoint();
            }

            // Prepare any existing rows (e.g., when editing a sale) so the image
            // column aligns and loads assets correctly.
            $('#pos_table_body tr').each(function() {
                const $row = $(this);
                const productId = $row.find('input[name*="[product_id]"]').val();
                const variationId = $row.find('input[name*="[variation_id]"]').val();

	                const appliedCache = typeof window.applyCachedProductImage === 'function'
	                    ? window.applyCachedProductImage($row, productId, variationId)
	                    : false;
	                const imageContainer = ensureProductImageContainer($row);

	                if (!appliedCache && productId && imageContainer && imageContainer.find('img').length === 0) {
	                    loadProductImageForRow(productId, variationId);
	                }
            });

            // Function to show image preview modal
            function showImagePreview(imageUrl, title) {
                // Create modal if it doesn't exist
                if ($('#imagePreviewModal').length === 0) {
                    const modalHtml = `
                        <div class="modal fade" id="imagePreviewModal" tabindex="-1" role="dialog">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title">${I18N.product_image_preview}</h4>
                                        <button type="button" class="close" data-dismiss="modal">
                                            <span>&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body text-center" style="padding: 2rem;">
                                        <img id="previewImage" src="" alt="${I18N.product_image}" style="max-width: 100%; max-height: 500px; border-radius: 8px; box-shadow: 0 4px 20px rgba(0,0,0,0.1);">
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                    $('body').append(modalHtml);
                }
                
                // Update modal content and show
                $('#imagePreviewModal .modal-title').text(title);
                $('#previewImage').attr('src', imageUrl);
                $('#imagePreviewModal').modal('show');
            }

            // Event delegation for dynamically added remove buttons
            $(document).on('click', '.remove_product_row', function() {
                $(this).closest('tr').remove();
                if (typeof pos_total_row === 'function') {
                    pos_total_row();
                }
                // Update UI after removal
                updateProductsCount();
                toggleEmptyState();
            });

            // Event delegation for quantity changes
            $(document).on('input change', '.pos_quantity', function() {
                if (typeof pos_total_row === 'function') {
                    pos_total_row();
                }
            });

            // Enhanced product addition - override POS functions if they exist
            const originalPosProductRow = window.pos_product_row;
            if (originalPosProductRow) {
                window.pos_product_row = function(variationId, locationId) {
                    const result = originalPosProductRow.apply(this, arguments);
                    // Update UI after product addition
                    setTimeout(() => {
                        updateProductsCount();
                        toggleEmptyState();
                        
                        // Load image for the newly added product
                        // Find the most recently added row and load its image
                        const lastRow = $('#pos_table_body tr:last-child');
                        if (lastRow.length > 0) {
                            const productId = lastRow.find('input[name*="[product_id]"]').val() ||
                                            lastRow.data('product-id') || variationId;
                            const varId = lastRow.find('input[name*="[variation_id]"]').val() ||
                                        lastRow.data('variation-id') || variationId;

	                        if (productId) {
	                            lastRow.attr('data-product-id', productId);
	                            if (varId) {
	                                lastRow.attr('data-variation-id', varId);
	                            }

	                            const appliedCache = typeof window.applyCachedProductImage === 'function'
	                                ? window.applyCachedProductImage(lastRow, productId, varId)
	                                : false;
	                            const imageContainer = ensureProductImageContainer(lastRow);
	                            if (!appliedCache && imageContainer && imageContainer.find('img').length === 0) {
	                                loadProductImageForRow(productId, varId);
	                            }
	                        }
                        }
                    }, 100);
                    return result;
                };
            }

            const originalPosEachRow = window.pos_each_row;
            if (originalPosEachRow) {
                window.pos_each_row = function(productData) {
                    const result = originalPosEachRow.apply(this, arguments);
                    // Update UI after product addition
                    setTimeout(() => {
                        updateProductsCount();
                        toggleEmptyState();
                        
                        // Load image for the newly added product
                        if (productData && (productData.product_id || productData.variation_id)) {
                            const productId = productData.product_id || productData.variation_id;
                            const variationId = productData.variation_id || productData.product_id;
                            
                            // Find the most recently added row and add image container
                            const lastRow = $('#pos_table_body tr:last-child');
	                            if (lastRow.length > 0) {
	                                lastRow.attr('data-product-id', productId);
	                                if (variationId) {
	                                    lastRow.attr('data-variation-id', variationId);
	                                }

	                                const appliedCache = typeof window.applyCachedProductImage === 'function'
	                                    ? window.applyCachedProductImage(lastRow, productId, variationId)
	                                    : false;
	                                const imageContainer = ensureProductImageContainer(lastRow);
	                                if (!appliedCache && imageContainer && imageContainer.find('img').length === 0) {
	                                    loadProductImageForRow(productId, variationId);
	                                }
	                            }
	                        }
                    }, 100);
                    return result;
                };
            }
        });
        </script>

        <!-- Enhanced Product Search Styles -->
        <style>
        .modern-search-input:focus {
            border-color: var(--primary) !important;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1) !important;
            outline: none;
        }

        .modern-search-input:hover {
            border-color: var(--primary) !important;
        }

        #search_results_dropdown {
            transform: translateY(-10px);
            opacity: 0;
            transition: all 0.2s ease;
        }

        .search-result-item:last-child {
            border-bottom: none !important;
        }

        .search-result-item:hover {
            background: var(--surface-alt) !important;
        }

        .modern-add-product-btn:hover {
            background: #16a34a !important;
            transform: translateY(-50%) scale(1.05) !important;
        }

        .product-search-container {
            position: relative;
        }

        .search-stats .stat-item:hover {
            background: var(--surface-alt) !important;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .search-stats .stat-item {
            transition: all 0.2s ease;
        }

        /* Loading animation */
        @keyframes searchPulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        .search-loading {
            animation: searchPulse 1.5s ease-in-out infinite;
        }

        /* Scrollbar styling for search results */
        .search-results-content::-webkit-scrollbar {
            width: 6px;
        }

        .search-results-content::-webkit-scrollbar-track {
            background: var(--surface-alt);
            border-radius: 3px;
        }

        .search-results-content::-webkit-scrollbar-thumb {
            background: var(--border);
            border-radius: 3px;
        }

        .search-results-content::-webkit-scrollbar-thumb:hover {
            background: var(--text-muted);
        }

        /* Hide the original search input to avoid conflicts */
        #search_product {
            display: none !important;
        }

        /* Hide the original typeahead dropdown */
        .typeahead.dropdown-menu {
            display: none !important;
        }

        /* Product image styling */
        .product-image-container img {
            transition: all 0.3s ease;
        }

        .product-image-container:hover img {
            transform: scale(1.05);
        }

        .search-result-item:hover .product-image-container {
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            transform: translateY(-1px);
        }

        .search-result-item:hover .product-image-container img {
            transform: scale(1.1);
        }

        /* Image loading animation */
        .product-image-container img[src=""] {
            opacity: 0;
        }

        .product-image-container img {
            opacity: 1;
            transition: opacity 0.3s ease, transform 0.3s ease;
        }

        /* Placeholder for missing images */
        .product-image-placeholder {
            background: linear-gradient(45deg, #f3f4f6 25%, transparent 25%, transparent 75%, #f3f4f6 75%), 
                        linear-gradient(45deg, #f3f4f6 25%, transparent 25%, transparent 75%, #f3f4f6 75%);
            background-size: 8px 8px;
            background-position: 0 0, 4px 4px;
        }
        </style>
        
        @if(!empty($common_settings['is_enabled_export']) && $sale_type != 'sales_order')
            <!-- Export Information Card -->
            <div class="modern-glass-card slide-up">
                <div class="modern-card-header">
                    <div class="modern-card-icon">
                        <i class="fa fa-globe"></i>
                    </div>
                    <h2 class="modern-card-title">{{ __('custom.export_information') }}</h2>
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <div style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: var(--surface-alt); border-radius: var(--radius); border: 1px solid var(--border);">
                        <input type="checkbox" name="is_export" class="modern-checkbox" id="is_export" @if(!empty($walk_in_customer['is_export'])) checked @endif>
                        <label for="is_export" class="modern-label" style="margin: 0; cursor: pointer;">@lang('lang_v1.is_export')</label>
                    </div>
                </div>

                <div class="modern-grid-3 export_div" @if(empty($walk_in_customer['is_export'])) style="display: none;" @endif>
                    @for($i = 1; $i <= 6; $i++)
                        <div class="modern-form-group">
                            <label class="modern-label" for="export_custom_field_{{$i}}">@lang('lang_v1.export_custom_field'.$i)</label>
                            <div class="modern-input-wrapper">
                                <i class="fa fa-tag modern-input-icon"></i>
                                {!! Form::text('export_custom_fields_info[export_custom_field_'.$i.']', !empty($walk_in_customer['export_custom_field_'.$i]) ? $walk_in_customer['export_custom_field_'.$i] : null, ['class' => 'modern-input has-icon', 'placeholder' => __('lang_v1.export_custom_field'.$i), 'id' => 'export_custom_field_'.$i]); !!}
                            </div>
                        </div>
                    @endfor
                </div>
            </div>
        @endif
	@php
		$is_enabled_download_pdf = config('constants.enable_download_pdf');
		$payment_body_id = 'payment_rows_div';
		if ($is_enabled_download_pdf) {
			$payment_body_id = '';
		}
	@endphp

	
        <!-- Action Buttons -->
        <div class="modern-actions-bar slide-up">
            {!! Form::hidden('is_save_and_print', 0, ['id' => 'is_save_and_print']); !!}
            <button type="button" id="submit-sell" class="modern-btn modern-btn-primary">
                <i class="fa fa-save"></i>
                @lang('messages.save')
            </button>
          
        </div>

        @if(empty($pos_settings['disable_recurring_invoice']))
            @include('sale_pos.partials.recurring_invoice_modal')
        @endif

        {!! Form::close() !!}
    </div>
</div>

<div class="modal fade contact_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
	@include('contact.create', ['quick_add' => true])
</div>
<!-- /.content -->
<div class="modal fade register_details_modal" tabindex="-1" role="dialog" 
	aria-labelledby="gridSystemModalLabel">
</div>
<div class="modal fade close_register_modal" tabindex="-1" role="dialog" 
	aria-labelledby="gridSystemModalLabel">
</div>

<!-- quick product modal -->
<div class="modal fade quick_add_product_modal" tabindex="-1" role="dialog" aria-labelledby="modalTitle"></div>

@include('sale_pos.partials.configure_search_modal')

@stop

@section('javascript')
	<script src="{{ asset('js/pos.js?v=' . $asset_v) }}"></script>
	<script src="{{ asset('js/product.js?v=' . $asset_v) }}"></script>
	<script src="{{ asset('js/opening_stock.js?v=' . $asset_v) }}"></script>

	<!-- Call restaurant module if defined -->
    @if(in_array('tables' ,$enabled_modules) || in_array('modifiers' ,$enabled_modules) || in_array('service_staff' ,$enabled_modules))
    	<script src="{{ asset('js/restaurant.js?v=' . $asset_v) }}"></script>
    @endif
	    <script type="text/javascript">
	    	// Set current user ID for assignments
	    	window.currentUserId = {{ auth()->user()->id ?? 'null' }};
	    	const I18N = {
	    		add_as_customer: @json(__('custom.add_as_customer')),
	    		add_as_new_customer_with_name: @json(__('custom.add_as_new_customer_with_name')),
	    		address_information: @json(__('custom.address_information')),
	    		add_to_cart: @json(__('custom.add_to_cart')),
	    		business_information: @json(__('custom.business_information')),
	    		click_to_view_larger: @json(__('custom.click_to_view_larger')),
	    		contact_created_unexpected: @json(__('custom.contact_created_unexpected')),
	    		creating_customer: @json(__('custom.creating_customer')),
	    		customer_create_failed: @json(__('custom.customer_create_failed')),
	    		customer_created: @json(__('custom.customer_created')),
	    		customer_created_with_name: @json(__('custom.customer_created_with_name')),
	    		customer_data_not_found: @json(__('custom.customer_data_not_found')),
	    		customer_updated_with_name: @json(__('custom.customer_updated_with_name')),
	    		edit_customer_information: @json(__('custom.edit_customer_information')),
	    		edit_this_customer: @json(__('custom.edit_this_customer')),
	    		edit_inline: @json(__('custom.edit_inline')),
	    		lookup_failed: @json(__('custom.lookup_failed')),
	    		billing_address_label: @json(__('custom.billing_address_label')),
	    		billing_address_placeholder: @json(__('custom.billing_address_placeholder')),
	    		shipping_address_label: @json(__('custom.shipping_address_label')),
	    		shipping_address_placeholder: @json(__('custom.shipping_address_placeholder')),
	    		phone: @json(__('custom.phone')),
	    		phone_placeholder: @json(__('custom.phone_placeholder')),
	    		no_phone: @json(__('custom.no_phone')),
	    		no_billing_address: @json(__('custom.no_billing_address')),
	    		no_shipping_address: @json(__('custom.no_shipping_address')),
	    		save: @json(__('custom.save')),
	    		cancel: @json(__('custom.cancel')),
	    		no_products_found: @json(__('custom.no_products_found')),
	    		out_of_stock: @json(__('custom.out_of_stock')),
	    		personal_information: @json(__('custom.personal_information')),
	    		please_wait: @json(__('custom.please_wait')),
	    		product_added_to_cart: @json(__('custom.product_added_to_cart')),
	    		items_count: @json(__('custom.items_count')),
	    		product_image: @json(__('custom.product_image')),
	    		product_image_preview: @json(__('custom.product_image_preview')),
	    		remove_product: @json(__('custom.remove_product')),
	    		saving: @json(__('custom.saving')),
	    		search_error: @json(__('custom.search_error')),
	    		searching_products: @json(__('custom.searching_products')),
	    		select_staff: @json(__('custom.select_staff')),
	    		select_users_to_assign: @json(__('custom.select_users_to_assign')),
	    		customer_required: @json(__('custom.customer_required')),
	    		location_required: @json(__('custom.location_required')),
	    		status_required: @json(__('custom.status_required')),
	    		product_required: @json(__('custom.product_required')),
	    		stock_not_tracked: @json(__('custom.stock_not_tracked')),
	    		low_stock: @json(__('custom.low_stock')),
	    		in_stock: @json(__('custom.in_stock')),
	    		status_auto_changed: @json(__('custom.status_auto_changed')),
	    		tax_id_found: @json(__('custom.tax_id_found')),
	    		tax_id_not_found: @json(__('custom.tax_id_not_found')),
	    		tax_lookup_loading: @json(__('custom.tax_lookup_loading')),
	    		use_for_document: @json(__('custom.use_for_document')),
	    		use_for_document_with_name: @json(__('custom.use_for_document_with_name')),
	    		using_company_for_document: @json(__('custom.using_company_for_document')),
	    		using_customer_temp: @json(__('custom.using_customer_temp')),
	    		no_company_data: @json(__('custom.no_company_data')),
	    		address_saved: @json(__('custom.address_saved')),
	    		address_save_failed: @json(__('custom.address_save_failed'))
	    	};
	    	
	    	// Shared helpers for caching and applying product images across POS screens
	    	window.productImageCache = window.productImageCache || {};
	    	if (!window.getProductImageCacheKey) {
	    		window.getProductImageCacheKey = function(productId, variationId) {
	    			const pid = productId ? String(productId) : 'all_products';
	    			const vid = variationId ? String(variationId) : 'all_variations';
	    			return pid + '::' + vid;
	    		};
	    	}
	    	if (!window.cacheProductImage) {
	    		window.cacheProductImage = function(productId, variationId, imageUrl) {
	    			if (!imageUrl) {
	    				return;
	    			}
	    			const cleanedUrl = String(imageUrl).trim();
	    			if (cleanedUrl === '' || /default\.png|no-image|placeholder/i.test(cleanedUrl)) {
	    				return;
	    			}
	    			const keys = [
	    				window.getProductImageCacheKey(productId, variationId),
	    				window.getProductImageCacheKey(productId, null),
	    				window.getProductImageCacheKey(null, variationId)
	    			];
	    			keys.forEach(key => {
	    				if (key) {
	    					window.productImageCache[key] = cleanedUrl;
	    				}
	    			});
	    		};
	    	}
	    	if (!window.removeProductImageCache) {
	    		window.removeProductImageCache = function(productId, variationId) {
	    			const keys = [
	    				window.getProductImageCacheKey(productId, variationId),
	    				window.getProductImageCacheKey(productId, null),
	    				window.getProductImageCacheKey(null, variationId)
	    			];
	    			keys.forEach(key => {
	    				if (key && window.productImageCache[key]) {
	    					delete window.productImageCache[key];
	    				}
	    			});
	    		};
	    	}
	    	if (!window.normalizeProductImageUrl) {
	    		window.normalizeProductImageUrl = function(imageUrl) {
	    			if (!imageUrl) {
	    				return null;
	    			}
	    			let normalized = String(imageUrl).trim();
	    			if (normalized === '' || /default\.png|no-image|placeholder/i.test(normalized)) {
	    				return normalized.startsWith('/') ? normalized : '/' + normalized;
	    			}
	    			if (/^https?:\/\//i.test(normalized)) {
	    				return normalized;
	    			}
	    			if (normalized.startsWith('/')) {
	    				return normalized;
	    			}
	    			normalized = normalized.replace(/^\/+/, '');
	    			if (!normalized.startsWith('uploads/') && !normalized.startsWith('storage/') && !normalized.startsWith('images/')) {
	    				normalized = 'uploads/img/' + normalized;
	    			}
	    			const parts = normalized.split('/').map(segment => encodeURIComponent(segment.trim()).replace(/%2F/gi, '/'));
	    			return '/' + parts.join('/');
	    		};
	    	}
	    	if (!window.applyCachedProductImage) {
	    		window.applyCachedProductImage = function(row, productId, variationId, productName) {
	    			if (!row || row.length === 0) {
	    				return false;
	    			}
	    			const keys = [
	    				window.getProductImageCacheKey(productId, variationId),
	    				window.getProductImageCacheKey(productId, null),
	    				window.getProductImageCacheKey(null, variationId)
	    			];
	    			let imageUrl = null;
	    			for (const key of keys) {
	    				if (key && window.productImageCache[key]) {
	    					imageUrl = window.productImageCache[key];
	    					break;
	    				}
	    			}
	    			if (!imageUrl) {
	    				return false;
	    			}
	    			let imageContainer = typeof ensureProductImageContainer === 'function'
	    				? ensureProductImageContainer(row)
	    				: row.find('.product-image-container');
	    			if (!imageContainer || imageContainer.length === 0) {
	    				return false;
	    			}
	    			const currentImg = imageContainer.find('img');
	    			if (currentImg.length && currentImg.attr('src') === imageUrl) {
	    				return true;
	    			}
	    			imageContainer.find('img').remove();
	    			const loadingElement = imageContainer.find('.product-image-loading');
	    			const placeholderElement = imageContainer.find('.product-image-placeholder');
	    			if (loadingElement.length) {
	    				loadingElement.hide();
	    			}
	    			if (placeholderElement.length) {
	    				placeholderElement.hide();
	    			}
	    			const fallbackName = productName || row.find('.product-name').first().text().trim() || I18N.product_image;
	    			const imgElement = $('<img>', {
	    				src: imageUrl,
	    				alt: fallbackName,
	    				class: 'product-table-image',
	    				title: fallbackName,
	    				loading: 'lazy'
	    			}).css('cursor', 'pointer')
	    			  .attr('data-preview-url', imageUrl)
	    			  .attr('data-preview-title', fallbackName);
	    			imgElement.on('click', function() {
	    				if (typeof showImagePreview === 'function') {
	    					showImagePreview(imageUrl, fallbackName);
	    				}
	    			});
	    			imgElement.on('error', function() {
	    				$(this).remove();
	    				window.removeProductImageCache(productId, variationId);
	    				if (typeof loadProductImageForRow === 'function') {
	    					loadProductImageForRow(productId, variationId);
	    				}
	    			});
	    			imageContainer.append(imgElement);
	    			window.cacheProductImage(productId, variationId, imageUrl);
	    			return true;
	    		};
	    	}
	    	if (!window.onProductImageLoad) {
	    		window.onProductImageLoad = function(img, productId, variationId, imageUrl) {
	    			if (img && img.nextElementSibling) {
	    				img.nextElementSibling.style.display = 'none';
	    			}
	    			window.cacheProductImage(productId, variationId, imageUrl);
	    		};
	    	}
	    
	$(document).ready( function() {
		// Debug: Check if elements exist
		console.log('=== Invoice Scheme Auto-Change Debug ===');
		console.log('Status dropdown exists:', $('#status').length > 0);
		console.log('Invoice scheme dropdown exists:', $('#invoice_scheme_id').length > 0);
		console.log('Status dropdown value:', $('#status').val());
		console.log('Invoice scheme dropdown value:', $('#invoice_scheme_id').val());

		function formatCustomerSource(option) {
			if (!option || !option.id) {
				return option && option.text ? option.text : '';
			}
			var $element = $(option.element);
			var logoUrl = $element.data('logo');
			var text = option.text || '';
			if (!logoUrl) {
				return text;
			}
			var $container = $('<span class="customer-source-option"></span>');
			var $logo = $('<img>', {
				src: logoUrl,
				alt: text + ' logo',
				class: 'customer-source-logo',
				loading: 'lazy'
			});
			var $label = $('<span class="customer-source-text"></span>').text(text);
			$container.append($logo, $label);
			return $container;
		}

		function initCustomerSourceSelect() {
			var $select = $('#customer_source_id');
			if (!$select.length || !$.fn.select2) {
				return;
			}
			if ($select.data('select2')) {
				$select.select2('destroy');
			}
			$select.select2({
				width: '100%',
				dropdownAutoWidth: false,
				minimumResultsForSearch: Infinity,
				closeOnSelect: true,
				dropdownParent: $select.closest('.modern-form-group'),
				dropdownCssClass: 'customer-source-dropdown',
				templateResult: formatCustomerSource,
				templateSelection: formatCustomerSource
			});
		}

		function initResponsibleSalespersonSelect() {
			var $select = $('#responsible_salesperson_id');
			if (!$select.length || !$.fn.select2) {
				return;
			}
			if ($select.data('select2')) {
				$select.select2('destroy');
			}
			$select.select2({
				width: '100%',
				dropdownAutoWidth: false,
				closeOnSelect: true,
				dropdownParent: $select.closest('.modern-form-group'),
				dropdownCssClass: 'responsible-salesperson-dropdown'
			});
		}

		initCustomerSourceSelect();
		initResponsibleSalespersonSelect();

		$(document).on('select2:select', 'select', function() {
			var $select = $(this);
			if (!$select.prop('multiple') && $select.data('select2')) {
				$select.select2('close');
			}
		});

		$(document).on('select2:open', '#customer_source_id', function() {
			$(this).closest('.modern-form-group').addClass('select2-opened-elevated');
		});

		$(document).on('select2:close', '#customer_source_id', function() {
			$(this).closest('.modern-form-group').removeClass('select2-opened-elevated');
		});

		$(document).on('select2:open', '#customer_id', function() {
			var $select = $(this);
			var select2 = $select.data('select2');
			if (!select2 || !select2.$container || !select2.$dropdown) {
				return;
			}
			var dropdownWidth = select2.$container.outerWidth();
			var containerOffset = select2.$container.offset();
			select2.$dropdown.css({
				width: dropdownWidth + 'px',
				minWidth: dropdownWidth + 'px'
			});
			if (select2.$dropdownParent && select2.$dropdownParent.is('body') && containerOffset) {
				select2.$dropdown.css({
					left: containerOffset.left + 'px'
				});
			}
		});

		function reinitCustomerSelect2() {
			var $select = $('#customer_id');
			if (!$select.length || !$select.data('select2') || !$.fn.select2) {
				return;
			}
			var currentOptions = $.extend(true, {}, $select.data('select2').options.options);
			$select.select2('destroy');
			currentOptions.dropdownParent = $select.closest('.modern-form-group');
			currentOptions.dropdownAutoWidth = false;
			currentOptions.width = '100%';
			currentOptions.containerCssClass = 'customer-select2-container';
			currentOptions.dropdownCssClass = 'customer-select2-dropdown';
			$select.select2(currentOptions);
		}

		reinitCustomerSelect2();
		
		// Create global tax rates cache for easier access
		window.taxRatesCache = {
			'1': 7.0  // Based on your database: ID 1 = 7%
		};
		console.log('Tax rates cache initialized:', window.taxRatesCache);
		
		// Tax calculation functionality
		function calculateOrderTax() {
			console.log('=== Order Tax Calculation ===');
			
			var subtotal = 0;
			var totalTax = 0;
			var selectedTaxRate = 0;
			
			// Get selected tax rate with better element detection
			var taxRateSelect = $('#tax_rate_id');
			console.log('Tax rate select element exists:', taxRateSelect.length > 0);
			console.log('Tax rate select element HTML:', taxRateSelect.length > 0 ? taxRateSelect[0].outerHTML : 'Element not found');
			console.log('Selected tax rate ID:', taxRateSelect.val());
			
			// If tax_rate_id not found, search for other possible tax elements
			if (taxRateSelect.length === 0) {
				console.log('Searching for alternative tax elements...');
				var allSelects = $('select');
				console.log('Total select elements found:', allSelects.length);
				
				allSelects.each(function(index) {
					var selectElement = $(this);
					var name = selectElement.attr('name') || '';
					var id = selectElement.attr('id') || '';
					if (name.toLowerCase().includes('tax') || id.toLowerCase().includes('tax')) {
						console.log('Found tax-related select:', {
							index: index,
							id: id,
							name: name,
							value: selectElement.val(),
							html: this.outerHTML
						});
						
						// Use the first tax-related select we find
						if (!taxRateSelect.length) {
							taxRateSelect = selectElement;
							console.log('Using alternative tax select element');
						}
					}
				});
			}
			
			if (taxRateSelect.length && taxRateSelect.val()) {
				var selectedOption = taxRateSelect.find('option:selected');
				console.log('Selected option:', selectedOption[0]);
				
				// Debug: Log all attributes of the selected option
				var attributes = {};
				$.each(selectedOption[0].attributes, function() {
					attributes[this.name] = this.value;
				});
				console.log('All option attributes:', attributes);
				
				// Method 1: Try to get the tax rate from data-rate attribute
				var dataRate = selectedOption.attr('data-rate');
				console.log('Data-rate attribute:', dataRate);
				
				if (dataRate) {
					selectedTaxRate = parseFloat(dataRate);
					console.log('Using data-rate attribute:', selectedTaxRate);
				} else {
					// Method 2: Check tax rates cache
					var taxRateId = taxRateSelect.val();
					if (window.taxRatesCache && window.taxRatesCache[taxRateId]) {
						selectedTaxRate = window.taxRatesCache[taxRateId];
						console.log('Using cached tax rate for ID ' + taxRateId + ':', selectedTaxRate);
					} else {
						// Method 3: Fallback - Extract tax percentage from text
						var taxRateText = selectedOption.text();
						console.log('Tax rate text:', taxRateText);
						var taxMatch = taxRateText.match(/(\d+(?:\.\d+)?)%?/);
						if (taxMatch) {
							selectedTaxRate = parseFloat(taxMatch[1]);
							console.log('Extracted tax rate from text:', selectedTaxRate);
						}
					}
				}
				
				console.log('Selected option text:', selectedOption.text());
				console.log('Final selected tax rate:', selectedTaxRate);
			} else if (taxRateSelect.length > 0) {
				// Tax dropdown exists but no value selected - check if there's a default
				console.log('Tax dropdown exists but no value selected');
				var defaultTaxRate = taxRateSelect.attr('data-default');
				console.log('Default tax rate attribute:', defaultTaxRate);
				
				if (defaultTaxRate && window.taxRatesCache && window.taxRatesCache[defaultTaxRate]) {
					selectedTaxRate = window.taxRatesCache[defaultTaxRate];
					console.log('Using default cached tax rate:', selectedTaxRate);
				} else {
					// Fallback to 7% as that's your default
					selectedTaxRate = 7.0;
					console.log('Using hardcoded fallback tax rate:', selectedTaxRate);
				}
			} else {
				// No tax dropdown found at all - use default 7%
				console.log('No tax dropdown found, using default 7%');
				selectedTaxRate = 7.0;
			}
			
			console.log('Selected tax rate:', selectedTaxRate + '%');    			// Calculate subtotal from all products in the table
    			$('#pos_table tbody tr').each(function() {
    				var row = $(this);
    				var qtyInput = row.find('input[name*="quantity"]');
    				var priceInput = row.find('input[name*="unit_price"]');
    				
    				if (qtyInput.length && priceInput.length) {
    					var qty = parseFloat(qtyInput.val()) || 0;
    					var price = parseFloat(priceInput.val()) || 0;
    					var lineTotal = qty * price;
    					subtotal += lineTotal;
    					
    					console.log('Product line - Qty:', qty, 'Price:', price, 'Total:', lineTotal);
    				}
    			});
    			
    			// Calculate tax amount
    			if (selectedTaxRate > 0 && subtotal > 0) {
    				totalTax = (subtotal * selectedTaxRate) / 100;
    			}
    			
    			console.log('Subtotal:', subtotal);
    			console.log('Calculated tax:', totalTax);
    			
    			// Update the Order Tax display
    			var taxDisplay = $('#order_tax');
    			if (taxDisplay.length) {
    				// Format the tax amount (assuming the display_currency class handles formatting)
    				if (typeof __currency_convert_recursively === 'function') {
    					taxDisplay.text(__currency_convert_recursively(totalTax));
    				} else {
    					// Fallback formatting
    					taxDisplay.text(totalTax.toFixed(2));
    				}
    			}
    			
    			// Also update the hidden tax calculation amount field
    			$('#tax_calculation_amount').val(totalTax.toFixed(2));
    			
    			console.log('Order tax updated to:', totalTax.toFixed(2));
    			
    			return totalTax;
    		}
    		
    		// Bind tax calculation to relevant events
    		$(document).on('change keyup', '#pos_table input[name*="quantity"], #pos_table input[name*="unit_price"]', function() {
    			console.log('Product quantity or price changed, recalculating tax...');
    			setTimeout(calculateOrderTax, 100); // Small delay to ensure DOM is updated
    		});
    		
    		// Recalculate when tax rate changes
    		$(document).on('change', '#tax_rate_id', function() {
    			console.log('Tax rate changed, recalculating tax...');
    			setTimeout(calculateOrderTax, 100);
    		});
    		
    		// Recalculate when products are added/removed
    		$(document).on('DOMNodeInserted DOMNodeRemoved', '#pos_table tbody', function() {
    			console.log('Product table modified, recalculating tax...');
    			setTimeout(calculateOrderTax, 200);
    		});
    		
    		// Use MutationObserver for better performance (modern browsers)
    		if (window.MutationObserver) {
    			var tableObserver = new MutationObserver(function(mutations) {
    				var shouldRecalculate = false;
    				mutations.forEach(function(mutation) {
    					if (mutation.type === 'childList' && (mutation.addedNodes.length > 0 || mutation.removedNodes.length > 0)) {
    						shouldRecalculate = true;
    					}
    				});
    				if (shouldRecalculate) {
    					console.log('Product table mutated, recalculating tax...');
    					setTimeout(calculateOrderTax, 200);
    				}
    			});
    			
    			var tableBody = document.getElementById('pos_table_body');
    			if (tableBody) {
    				tableObserver.observe(tableBody, { childList: true, subtree: true });
    			}
    		}
    		
		// Initial calculation on page load (wait longer for elements to be ready)
		setTimeout(function() {
			console.log('Initial tax calculation on page load...');
			console.log('Checking tax rate dropdown again...');
			console.log('Tax rate dropdown exists now:', $('#tax_rate_id').length > 0);
			console.log('Tax rate dropdown HTML:', $('#tax_rate_id').length > 0 ? $('#tax_rate_id')[0].outerHTML : 'Still not found');
			calculateOrderTax();
		}, 3000);    		$('#status').change(function(){
    			var status = $(this).val();
    			console.log('Status changed to:', status); // Debug log
    			
    			if (status == 'final') {
    				$('#payment_rows_div').removeClass('hide');
    			} else {
    				$('#payment_rows_div').addClass('hide');
    			}
    			
    			// Update invoice scheme based on status
    			var invoice_scheme_dropdown = $('#invoice_scheme_id');
    			if (invoice_scheme_dropdown.length) {
    				var scheme_id = '';
    				
    				if (status == 'quotation') {
    					// For quotation: use scheme id 1 (QUOTE2025/)
    					scheme_id = '1';
    				} else if (status == 'proforma') {
    					// For proforma: use scheme id 4 (VT2025/ format)
    					scheme_id = '4';
    				} else if (status == 'final') {
    					// For final bills: use scheme id 5 (IPAY2025/ format)
    					scheme_id = '5';
    				} else if (status == 'draft') {
    					// For draft: use scheme id 4 (VT2025/ format)
    					scheme_id = '4';
    				}
    				
    				console.log('Setting invoice scheme to:', scheme_id); // Debug log
    				
    				if (scheme_id) {
    					// Force update Select2 dropdown
    					invoice_scheme_dropdown.val(scheme_id).trigger('change');
    					
    					// Also trigger Select2 specific events
    					if (invoice_scheme_dropdown.hasClass('select2-hidden-accessible')) {
    						invoice_scheme_dropdown.select2('val', scheme_id);
    					}
    					
    					console.log('Invoice scheme updated to:', invoice_scheme_dropdown.val());
    				} else {
    					// Clear selection
    					invoice_scheme_dropdown.val('').trigger('change');
    					if (invoice_scheme_dropdown.hasClass('select2-hidden-accessible')) {
    						invoice_scheme_dropdown.select2('val', '');
    					}
    				}
    			}
    		});
    		
    		// Auto change status when Invoice Scheme dropdown changes
    		// Use multiple event handlers to ensure it works with regular select and Select2
    		function handleInvoiceSchemeChange() {
    			var scheme_id = $('#invoice_scheme_id').val();
    			console.log('=== Invoice Scheme Change Handler ===');
    			console.log('Invoice scheme changed to:', scheme_id);
    			console.log('Invoice scheme element:', $('#invoice_scheme_id')[0]);
    			
    			var status_dropdown = $('#status');
    			console.log('Status dropdown found:', status_dropdown.length > 0);
    			console.log('Status dropdown disabled:', status_dropdown.prop('disabled'));
    			console.log('Current status value:', status_dropdown.val());
    			
    			if (status_dropdown.length) {
    				var new_status = '';
    				
				// Map invoice scheme ID to status
				if (scheme_id == '1') {
					// Quotation (ใบเสนอราคา)
					new_status = 'quotation';
					console.log('Mapped to quotation');
				} else if (scheme_id == '4') {
					// TAX-INVOICE (ใบกำกับภาษี / ใบแจ้งหนี้)
					new_status = 'proforma';
					console.log('Mapped to proforma');
				} else if (scheme_id == '5') {
					// BILLING-RECEIVE
					new_status = 'final';
					console.log('Mapped to final');
				} else {
					console.log('No mapping found for scheme ID:', scheme_id);
				}    				console.log('Determined new status:', new_status);
    				console.log('Current status different?', status_dropdown.val() !== new_status);
    				
    				if (new_status && status_dropdown.val() !== new_status) {
    					console.log('Updating status from', status_dropdown.val(), 'to', new_status);
    					
    					// Check if it's a hidden field (preset status) or dropdown (user changeable)
    					if (status_dropdown.attr('type') === 'hidden') {
    						console.log('Status is preset (hidden field) - updating value only');
    						// For hidden fields, just update the value
    						status_dropdown.val(new_status);
    						
    						// Also update the display text if there's a status indicator
    						var statusIndicator = $('.status-indicator');
    						if (statusIndicator.length) {
    							console.log('Found status indicator, updating display');
    							
    							// Remove old status classes
    							statusIndicator.removeClass('status-quotation status-proforma status-final status-draft');
    							statusIndicator.addClass('status-' + new_status);
    							
    							// Update the text content properly
    							var statusText = new_status.charAt(0).toUpperCase() + new_status.slice(1);
    							statusIndicator.html('<i class="fa fa-circle" style="font-size: 0.5rem;"></i> ' + statusText);
    							
    							console.log('Status indicator updated to:', statusText);
    						} else {
    							console.log('No status indicator found to update');
    						}
    					} else {
    						console.log('Status is user changeable (dropdown) - updating dropdown');
    						// For dropdowns, update value and trigger change
    						status_dropdown.val(new_status);
    						status_dropdown.trigger('change');
    						
    						// Also trigger Select2 specific events if using Select2
    						if (status_dropdown.hasClass('select2-hidden-accessible')) {
    							console.log('Triggering Select2 update');
    							status_dropdown.select2('val', new_status);
    						}
    					}
    					
    					console.log('Status updated to:', status_dropdown.val());
    					
    					// Show success message
    					if (typeof toastr !== 'undefined') {
    						toastr.info(I18N.status_auto_changed.replace(':status', new_status));
    					}
    				} else if (!new_status) {
    					console.log('No status change needed - no mapping for scheme');
    				} else {
    					console.log('No status change needed - already set to correct value');
    				}
    			} else {
    				console.log('Cannot update status - dropdown not found');
    			}
    		}
    		
    		// Attach event handlers
    		$('#invoice_scheme_id').on('change', handleInvoiceSchemeChange);
    		
    		// Also handle Select2 events if Select2 is being used
    		$('#invoice_scheme_id').on('select2:select', function(e) {
    			console.log('Select2 select event triggered');
    			setTimeout(handleInvoiceSchemeChange, 100); // Small delay to ensure value is set
    		});
    		
    		// Handle generic input change as fallback
    		$(document).on('change', '#invoice_scheme_id', function() {
    			console.log('Document-level change event triggered');
    			handleInvoiceSchemeChange();
    		});
    		
		// Add a manual test function for debugging
		window.testInvoiceSchemeChange = function(schemeId) {
			console.log('=== MANUAL TEST ===');
			console.log('Setting invoice scheme to:', schemeId);
			$('#invoice_scheme_id').val(schemeId).trigger('change');
		};
		
		// Add manual tax calculation test function
		window.testTaxCalculation = function() {
			console.log('=== MANUAL TAX CALCULATION TEST ===');
			calculateOrderTax();
		};    		// Add initialization check
    		setTimeout(function() {
    			console.log('=== 2 Second Delay Check ===');
    			console.log('Invoice scheme dropdown still exists:', $('#invoice_scheme_id').length > 0);
    			console.log('Status dropdown still exists:', $('#status').length > 0);
    			
    			// Test if we can manually trigger the change
    			console.log('You can test this by running: testInvoiceSchemeChange("1") in console');
    		}, 2000);
    		
    		// Enhanced debug form submission with error handling
    		$('#add_sell_form').on('submit', function(e) {
    			var status = $('#status').val();
    			var invoice_scheme = $('#invoice_scheme_id').val();
    			var contact_id = $('#customer_id').val();
    			var location_id = $('#location_id').val();
    			var products = $('#pos_table tbody tr').length;
    			
    			console.log('=== FORM SUBMISSION DEBUG ===');
    			console.log('Status:', status);
    			console.log('Invoice Scheme:', invoice_scheme);
    			console.log('Customer ID:', contact_id);
    			console.log('Location ID:', location_id);
    			console.log('Products Count:', products);
    			console.log('Form Action:', $(this).attr('action'));
    			console.log('Form Method:', $(this).attr('method'));
    			
    			// If using temporary company data, add it to the form
    			if (contact_id && contact_id.startsWith('temp_company_')) {
    				var selectedOption = $('#customer_id option:selected');
    				var companyName = selectedOption.text().replace(' - Temporary', '').split(' (Tax ID:')[0];
    				var taxNumber = contact_id.replace('temp_company_', '');
    				
    				// Add hidden fields with company data
    				if (!$('input[name="temp_company_name"]').length) {
    					$(this).append('<input type="hidden" name="temp_company_name" value="' + companyName + '">');
    				}
    				if (!$('input[name="temp_company_tax"]').length) {
    					$(this).append('<input type="hidden" name="temp_company_tax" value="' + taxNumber + '">');
    				}
    				
    				console.log('Added temp company data:', companyName, taxNumber);
    			}
    			
    			// Validate required fields
    			var errors = [];
    			if (!contact_id) errors.push(I18N.customer_required);
    			if (!location_id) errors.push(I18N.location_required);
    			if (!status) errors.push(I18N.status_required);
    			if (products === 0) errors.push(I18N.product_required);
    			
    			if (errors.length > 0) {
    				console.error('VALIDATION ERRORS:', errors);
    				alert('Please fix the following errors:\n' + errors.join('\n'));
    				e.preventDefault();
    				return false;
    			}
    			
    			console.log('Form validation passed, submitting...');
    		});
    		
    		// Add global error handler for AJAX responses
    		$(document).ajaxError(function(event, xhr, settings, thrownError) {
    			console.error('=== AJAX ERROR ===');
    			console.error('URL:', settings.url);
    			console.error('Status:', xhr.status);
    			console.error('Response:', xhr.responseText);
    			console.error('Error:', thrownError);
    			
    			// Try to parse JSON response for better error display
    			try {
    				var response = JSON.parse(xhr.responseText);
    				if (response.msg) {
    					console.error('Server Message:', response.msg);
    					if (response.debug_info) {
    						console.error('Debug Info:', response.debug_info);
    					}
    					if (response.error_id) {
    						console.error('Error ID:', response.error_id);
    					}
    				}
    			} catch (e) {
    				console.error('Could not parse error response as JSON');
    			}
    		});
    		
    		$('.paid_on').datetimepicker({
                format: moment_date_format + ' ' + moment_time_format,
                ignoreReadonly: true,
            });

            // Initialize Sale Date picker (date only)
            $('.transaction-date-picker').datetimepicker({
                format: moment_date_format + ' ' + moment_time_format, // Keep original datetime format
                ignoreReadonly: true,
                useCurrent: false,
                showTodayButton: true,
                showClear: true,
                showClose: true,
                defaultDate: moment(), // Set default to current date/time
                sideBySide: true,
                icons: {
                    time: 'fa fa-clock-o',
                    date: 'fa fa-calendar',
                    up: 'fa fa-chevron-up',
                    down: 'fa fa-chevron-down',
                    previous: 'fa fa-chevron-left',
                    next: 'fa fa-chevron-right',
                    today: 'fa fa-crosshairs',
                    clear: 'fa fa-trash',
                    close: 'fa fa-times'
                }
            });

            // Make calendar icon clickable to open date picker
            $(document).on('click', '.modern-input-icon.fa-calendar', function(e) {
                if ($(this).siblings('.transaction-date-picker').length) {
                    e.preventDefault();
                    $(this).siblings('.transaction-date-picker').focus();
                    $('.transaction-date-picker').datetimepicker('show');
                }
            });

            $('#shipping_documents').fileinput({
		        showUpload: false,
		        showPreview: false,
		        browseLabel: LANG.file_browse_label,
		        removeLabel: LANG.remove,
		    });

		    $(document).on('change', '#prefer_payment_method', function(e) {
			    var default_accounts = $('select#select_location_id').length ? 
			                $('select#select_location_id')
			                .find(':selected')
			                .data('default_payment_accounts') : $('#location_id').data('default_payment_accounts');
			    var payment_type = $(this).val();
			    if (payment_type) {
			        var default_account = default_accounts && default_accounts[payment_type]['account'] ? 
			            default_accounts[payment_type]['account'] : '';
			        var account_dropdown = $('select#prefer_payment_account');
			        if (account_dropdown.length && default_accounts) {
			            account_dropdown.val(default_account);
			            account_dropdown.change();
			        }
			    }
			});

		    function setPreferredPaymentMethodDropdown() {
			    var payment_settings = $('#location_id').data('default_payment_accounts');
			    payment_settings = payment_settings ? payment_settings : [];
			    enabled_payment_types = [];
			    for (var key in payment_settings) {
			        if (payment_settings[key] && payment_settings[key]['is_enabled']) {
			            enabled_payment_types.push(key);
			        }
			    }
			    if (enabled_payment_types.length) {
			        $("#prefer_payment_method > option").each(function() {
		                if (enabled_payment_types.indexOf($(this).val()) != -1) {
		                    $(this).removeClass('hide');
		                } else {
		                    $(this).addClass('hide');
		                }
			        });
			    }
			}
			
			setPreferredPaymentMethodDropdown();

			$('#is_export').on('change', function () {
	            if ($(this).is(':checked')) {
	                $('div.export_div').show();
	            } else {
	                $('div.export_div').hide();
	            }
	        });

			if($('.payment_types_dropdown').length){
				$('.payment_types_dropdown').change();
			}

			// Tax ID Lookup Functionality
			let taxLookupTimeout;
			let lastLookedUpTaxId = '';
			let companyData = null;
			let originalNoResults = null;

			console.log('Tax ID lookup functionality initialized');

			// Override Select2 noResults function to show Tax ID lookup options
			$(document).ready(function() {
				// Wait for Select2 to be initialized, then override its noResults function
				setTimeout(function() {
					if ($('#customer_id').data('select2')) {
						// Store the original noResults function globally if not already stored
						if (!originalNoResults) {
							originalNoResults = $('#customer_id').data('select2').options.options.language.noResults;
						}
						
						$('#customer_id').data('select2').options.options.language.noResults = function() {
							const searchTerm = $('#customer_id').data('select2').dropdown.$search.val().trim();
							console.log('noResults called with searchTerm:', searchTerm);
							console.log('companyData available:', companyData);
							
							// If it's a 13-digit Tax ID and we have company data, show custom options
							if (/^\d{13}$/.test(searchTerm) && companyData && companyData.companyNameTh) {
								console.log('Showing custom Tax ID options for:', companyData.companyNameTh);
								
								// Determine the label based on data source
								let statusLabel = '';
								if (companyData.dataSource === 'existing') {
									statusLabel = ' (ลูกค้าเก่า)'; // Existing customer
								} else if (companyData.dataSource === 'new') {
									statusLabel = ' (ลูกค้าใหม่)'; // New customer
								}
								
								let buttonHtml = '';
								if (companyData.dataSource === 'existing') {
									// For existing customers
									buttonHtml = `
										<button type="button" class="btn btn-success btn-sm tax-lookup-use-temp-btn" data-tax-id="${searchTerm}" style="margin: 2px; width: 48%; display: inline-block;">
											<i class="fa fa-file"></i> ${I18N.use_for_document}
										</button>
										<button type="button" class="btn btn-warning btn-sm tax-lookup-edit-existing-btn" data-tax-id="${searchTerm}" style="margin: 2px; width: 48%; display: inline-block;">
											<i class="fa fa-edit"></i> ${I18N.edit_this_customer}
										</button>
									`;
								} else {
									// For new customers
									buttonHtml = `
										<button type="button" class="btn btn-success btn-sm tax-lookup-use-btn" data-tax-id="${searchTerm}" style="margin: 2px; width: 100%;">
											<i class="fa fa-check"></i> ${I18N.use_for_document_with_name.replace(':name', companyData.companyNameTh)}
										</button>
										<button type="button" class="btn btn-primary btn-sm tax-lookup-add-btn" data-tax-id="${searchTerm}" style="margin: 2px; width: 100%;">
											<i class="fa fa-plus"></i> ${I18N.add_as_new_customer_with_name.replace(':name', companyData.companyNameTh)}
										</button>
									`;
								}
								
								return `
									<div style="padding: 10px;">
										<div style="margin-bottom: 8px; font-weight: bold; color: #333;">
											${I18N.tax_id_found} ${companyData.companyNameTh}${statusLabel}
										</div>
										${buttonHtml}
									</div>
								`;
							}
							
							console.log('Using default noResults');
							// Default behavior for non-Tax ID searches
							return originalNoResults.call(this);
						};
					}
				}, 1000);
			});

			// Watch for input changes in customer field (Select2 compatible)
			$(document).on('keyup', '.select2-search__field', function() {
				const input = $(this).val().trim();
				console.log('Select2 search field input:', input);
				
				// Clear previous status
				hideTaxLookupStatus();
				
				// Check if input is a 13-digit tax ID
				if (/^\d{13}$/.test(input)) {
					console.log('Valid 13-digit Tax ID detected:', input);
					// Only lookup if it's different from last lookup
					if (input !== lastLookedUpTaxId) {
						// Clear previous timeout
						clearTimeout(taxLookupTimeout);
						
						// For 13-digit Tax ID, call API immediately (shorter debounce)
						taxLookupTimeout = setTimeout(() => {
							lookupTaxId(input);
						}, 200);
					}
				} else if (input.length >= 2) {
					console.log('General search input detected:', input);
					// For general search (company name, phone, etc.), search in DB only
					if (input !== lastLookedUpTaxId) {
						// Clear previous timeout
						clearTimeout(taxLookupTimeout);
						
						// Debounce the search (wait 300ms after user stops typing)
						taxLookupTimeout = setTimeout(() => {
							searchInDatabaseOnly(input);
						}, 300);
					}
				} else {
					console.log('Input too short or not valid format:', input);
					// Input too short, hide status indicators
					hideTaxLookupStatus();
					lastLookedUpTaxId = '';
					companyData = null;
				}
			});

			// Also watch for manual input in the underlying field
			$(document).on('input keyup', '#customer_id', function() {
				const input = $(this).val();
				console.log('Customer field direct input:', input);
				
				if (input && typeof input === 'string') {
					const trimmedInput = input.trim();
					
					// Clear previous status
					hideTaxLookupStatus();
					
					// Check if input is a 13-digit tax ID
					if (/^\d{13}$/.test(trimmedInput)) {
						console.log('Valid 13-digit Tax ID detected (direct):', trimmedInput);
						// Only lookup if it's different from last lookup
						if (trimmedInput !== lastLookedUpTaxId) {
							// Clear previous timeout
							clearTimeout(taxLookupTimeout);
							
							// For 13-digit Tax ID, call API immediately (shorter debounce)
							taxLookupTimeout = setTimeout(() => {
								lookupTaxId(trimmedInput);
							}, 200);
						}
					} else if (trimmedInput.length >= 2) {
						console.log('General search input detected (direct):', trimmedInput);
						// For general search, search in DB only
						if (trimmedInput !== lastLookedUpTaxId) {
							// Clear previous timeout
							clearTimeout(taxLookupTimeout);
							
							// Debounce the search (wait 300ms after user stops typing)
							taxLookupTimeout = setTimeout(() => {
								searchInDatabaseOnly(trimmedInput);
							}, 300);
						}
					} else {
						console.log('Input too short (direct):', trimmedInput);
						// Input too short, hide status indicators
						hideTaxLookupStatus();
						lastLookedUpTaxId = '';
						companyData = null;
					}
				}
			});

			// Tax ID lookup function with parallel DB and API search
			function lookupTaxId(taxId) {
				console.log('Looking up Tax ID:', taxId);
				
				// Show loading status
				showTaxLookupLoading();
				lastLookedUpTaxId = taxId;
				
				// Create promises for both DB and API searches
				const dbSearch = searchCustomerInDB(taxId);
				const apiSearch = searchCompanyInAPI(taxId);
				
				// Execute both searches in parallel
				Promise.allSettled([dbSearch, apiSearch])
					.then(results => {
						const [dbResult, apiResult] = results;
						
						console.log('DB search result:', dbResult);
						console.log('API search result:', apiResult);
						
						const dbData = (dbResult.status === 'fulfilled' && dbResult.value) ? dbResult.value : null;
						const apiData = (apiResult.status === 'fulfilled' && apiResult.value) ? apiResult.value : null;
						
						if (dbData && apiData) {
							// Both found - prioritize database result, show only DB data first
							companyData = dbData;
							companyData.dataSource = 'existing';
							window.lastCompanyLookupData = companyData;
							showTaxLookupSuccess(dbData.companyNameTh, 'existing');
						} else if (dbData) {
							// Found in database only
							companyData = dbData;
							companyData.dataSource = 'existing';
							window.lastCompanyLookupData = companyData;
							showTaxLookupSuccess(dbData.companyNameTh, 'existing');
						} else if (apiData) {
							// Found in API only
							companyData = apiData;
							companyData.dataSource = 'new';
							window.lastCompanyLookupData = companyData;
							showTaxLookupSuccess(apiData.companyNameTh, 'new');
						} else {
							// Neither DB nor API found the Tax ID
							console.log('Tax ID not found in DB or API');
							showTaxLookupError(I18N.tax_id_not_found);
							companyData = null;
						}
					})
					.catch(error => {
						console.error('Tax ID lookup error:', error);
						showTaxLookupError(I18N.lookup_failed);
						companyData = null;
					});
			}
			
			// Search in database only for general searches (company name, phone, etc.)
			function searchInDatabaseOnly(searchTerm) {
				console.log('Searching in database only for:', searchTerm);
				
				// Show loading status
				showTaxLookupLoading();
				lastLookedUpTaxId = searchTerm;
				
				// Search in database with broader criteria
				$.ajax({
					url: '/contacts/search-general',
					method: 'GET',
					data: { 
						search_term: searchTerm 
					},
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					},
					timeout: 5000,
					success: function(response) {
						console.log('General DB search response:', response);
						if (response && response.success && response.data && response.data.length > 0) {
							// Found matches in database
							const customer = response.data[0]; // Take first match
							const dbData = {
								companyNameTh: customer.supplier_business_name || customer.name,
								companyNameEn: customer.supplier_business_name || customer.name,
								taxNumber: customer.tax_number || '',
								businessType: '',
								address: customer.address_line_1 || '',
								mobile: customer.mobile || '',
								customerId: customer.id,
								isExisting: true,
								dataSource: 'existing'
							};
							
							companyData = dbData;
							window.lastCompanyLookupData = companyData;
							showTaxLookupSuccess(dbData.companyNameTh, 'existing');
						} else {
							// No matches found
							console.log('No matches found in database');
							hideTaxLookupStatus();
							companyData = null;
						}
					},
					error: function(xhr, status, error) {
						console.log('General DB search failed:', status, error);
						hideTaxLookupStatus();
						companyData = null;
					}
				});
			}
			
			// Search customer in database by tax number
			function searchCustomerInDB(taxId) {
				return new Promise((resolve, reject) => {
					$.ajax({
						url: '/contacts/search-by-tax',
						method: 'GET',
						data: { tax_number: taxId },
						headers: {
							'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
						},
						timeout: 5000,
						success: function(response) {
							console.log('DB search response:', response);
							if (response && response.success && response.data) {
								// Convert DB format to match API format
								const dbData = {
									companyNameTh: response.data.supplier_business_name || response.data.name,
									companyNameEn: response.data.supplier_business_name || response.data.name,
									taxNumber: response.data.tax_number,
									businessType: '',
									address: response.data.address_line_1 || '',
									mobile: response.data.mobile || '',
									customerId: response.data.id, // Store customer ID for direct selection
									isExisting: true
								};
								resolve(dbData);
							} else {
								resolve(null); // Not found in DB
							}
						},
						error: function(xhr, status, error) {
							console.log('DB search failed:', status, error);
							resolve(null); // Treat as not found, don't reject to allow API fallback
						}
					});
				});
			}
			
			// Search company in API
			function searchCompanyInAPI(taxId) {
				return new Promise((resolve, reject) => {
					$.ajax({
						url: `https://api-shop.rubyshop.co.th/company/${taxId}`,
						method: 'GET',
						timeout: 10000,
						success: function(data) {
							console.log('API search response:', data);
							if (data && data.companyNameTh) {
								data.isExisting = false; // Mark as new customer
								resolve(data);
							} else {
								resolve(null);
							}
						},
						error: function(xhr, status, error) {
							console.log('API search failed:', status, error);
							resolve(null); // Treat as not found, don't reject
						}
					});
				});
			}

			// Show tax lookup status functions
			function showTaxLookupLoading() {
				console.log('Showing loading status');
				// Remove any existing status messages
				$('.tax-lookup-status').remove();
				
				// Add loading indicator near the customer field
				var statusHtml = '<div class="tax-lookup-status" style="margin-top: 5px; color: #007bff;"><i class="fa fa-spinner fa-spin"></i> ' + I18N.tax_lookup_loading + '</div>';
				$('#customer_id').closest('.form-group').append(statusHtml);
			}

			function showTaxLookupSuccess(companyName, dataSource) {
				console.log('Showing success status for:', companyName, 'Source:', dataSource);
				
				// Remove loading status
				$('.tax-lookup-status').remove();
				
				// Determine the label based on data source
				let statusLabel = '';
				if (dataSource === 'existing') {
					statusLabel = '(ลูกค้าเก่า)'; // Existing customer
				} else if (dataSource === 'new') {
					statusLabel = '(ลูกค้าใหม่)'; // New customer
				} else if (dataSource === 'both') {
					statusLabel = ''; // Will show both options separately
				}
				
				// Force Select2 to open 
				const $select2 = $('#customer_id');
				if ($select2.data('select2')) {
					$select2.select2('open');
					
					// Wait for dropdown to open, then inject our custom options
					setTimeout(function() {
						const dropdownContainer = $('.select2-results');
						if (dropdownContainer.length && window.lastCompanyLookupData) {
							let customHtml = '';
							
							if (dataSource === 'both') {
								// Show both DB and API results
								const dbData = window.lastCompanyLookupData.dbData;
								const apiData = window.lastCompanyLookupData.apiData;
								
								customHtml = `
									<div class="select2-results__option" style="padding: 10px; background: #f8f9fa; border-bottom: 1px solid #ddd;">
										<div style="margin-bottom: 5px; font-weight: bold; color: #333;">
											${I18N.tax_id_found} ${dbData.companyNameTh} (ลูกค้าเก่า)
										</div>
										<button type="button" class="btn btn-success btn-sm tax-lookup-use-temp-btn" data-tax-id="${dbData.taxNumber}" data-source="existing" style="margin: 2px; width: 48%; display: inline-block;">
											<i class="fa fa-file"></i> ${I18N.use_for_document}
										</button>
										<button type="button" class="btn btn-warning btn-sm tax-lookup-edit-existing-btn" data-tax-id="${dbData.taxNumber}" data-source="existing" style="margin: 2px; width: 48%; display: inline-block;">
											<i class="fa fa-edit"></i> ${I18N.edit_this_customer}
										</button>
									</div>
									<div class="select2-results__option" style="padding: 10px; background: #f0f8ff;">
										<div style="margin-bottom: 5px; font-weight: bold; color: #333;">
											${I18N.tax_id_found} ${apiData.companyNameTh} (ลูกค้าใหม่)
										</div>
										<button type="button" class="btn btn-success btn-sm tax-lookup-use-btn" data-tax-id="${apiData.taxNumber}" data-source="new" style="margin: 2px; width: 48%; display: inline-block;">
											<i class="fa fa-check"></i> ${I18N.use_for_document}
										</button>
										<button type="button" class="btn btn-primary btn-sm tax-lookup-add-btn" data-tax-id="${apiData.taxNumber}" data-source="new" style="margin: 2px; width: 48%; display: inline-block;">
											<i class="fa fa-plus"></i> ${I18N.add_as_customer}
										</button>
									</div>
								`;
							} else {
								// Single result (existing logic)
								let buttonHtml = '';
								
								if (dataSource === 'existing') {
									// For existing customers, show "Use for document" and "Edit this customer" buttons
									buttonHtml = `
										<button type="button" class="btn btn-success btn-sm tax-lookup-use-temp-btn" data-tax-id="${window.lastCompanyLookupData.taxNumber}" data-source="existing" style="margin: 2px; width: 48%; display: inline-block;">
											<i class="fa fa-file"></i> ${I18N.use_for_document}
										</button>
										<button type="button" class="btn btn-warning btn-sm tax-lookup-edit-existing-btn" data-tax-id="${window.lastCompanyLookupData.taxNumber}" data-source="existing" style="margin: 2px; width: 48%; display: inline-block;">
											<i class="fa fa-edit"></i> ${I18N.edit_this_customer}
										</button>
									`;
								} else {
									// For new customers, show "Use for document" and "Add as customer" buttons
									buttonHtml = `
										<button type="button" class="btn btn-success btn-sm tax-lookup-use-btn" data-tax-id="${window.lastCompanyLookupData.taxNumber}" data-source="new" style="margin: 2px; width: 48%; display: inline-block;">
											<i class="fa fa-check"></i> ${I18N.use_for_document}
										</button>
										<button type="button" class="btn btn-primary btn-sm tax-lookup-add-btn" data-tax-id="${window.lastCompanyLookupData.taxNumber}" data-source="new" style="margin: 2px; width: 48%; display: inline-block;">
											<i class="fa fa-plus"></i> ${I18N.add_as_customer}
										</button>
									`;
								}
								
								customHtml = `
									<div class="select2-results__option" style="padding: 10px; background: #f8f9fa;">
										<div style="margin-bottom: 5px; font-weight: bold; color: #333;">
											${I18N.tax_id_found} ${window.lastCompanyLookupData.companyNameTh} ${statusLabel}
										</div>
										${buttonHtml}
									</div>
								`;
							}
							
							dropdownContainer.html(customHtml);
						}
					}, 200);
				}
			}

			function showTaxLookupError(errorMsg) {
				console.log('Showing error status:', errorMsg);
				// Remove loading status
				$('.tax-lookup-status').remove();
				
				// Add error message
				var statusHtml = '<div class="tax-lookup-status" style="margin-top: 5px; color: #dc3545;"><i class="fa fa-exclamation-triangle"></i> ' + errorMsg + '</div>';
				$('#customer_id').closest('.form-group').append(statusHtml);
				
				// Auto-hide error after 5 seconds
				setTimeout(function() {
					$('.tax-lookup-status').fadeOut();
				}, 5000);
				
				companyData = null;
			}

			function hideTaxLookupStatus() {
				console.log('Hiding tax lookup status');
				// Remove any status messages
				$('.tax-lookup-status').remove();
				
				// Reset Select2 noResults to default
				if (originalNoResults && $('#customer_id').data('select2')) {
					$('#customer_id').data('select2').options.options.language.noResults = originalNoResults;
				}
			}

			// Helper function to extract address parts
			function extractAddressParts(address) {
				if (!address) return { city: '', state: '', zipCode: '' };
				
				console.log('Parsing address:', address);
				
				// Basic Thai address parsing
				// Look for postal code (5 digits at the end)
				const zipMatch = address.match(/(\d{5})$/);
				const zipCode = zipMatch ? zipMatch[1] : '';
				
				// Look for province (after "จ." or "จังหวัด")
				let state = '';
				const provinceMatch = address.match(/จ\.([^0-9\s]+)|จังหวัด([^0-9\s]+)/);
				if (provinceMatch) {
					state = (provinceMatch[1] || provinceMatch[2]).trim();
				}
				
				// Look for district (after "อำเภอ" or "อ.")
				let city = '';
				const districtMatch = address.match(/อำเภอ([^0-9\s]+)|อ\.([^0-9\s]+)/);
				if (districtMatch) {
					city = (districtMatch[1] || districtMatch[2]).trim();
				}
				
				// If no district found, try to extract from general pattern
				if (!city && state) {
					// Look for text before province
					const beforeProvince = address.split(/จ\.|จังหวัด/)[0];
					const parts = beforeProvince.split(' ');
					// Take the last meaningful part before province as city
					city = parts[parts.length - 1] || '';
				}
				
				const result = {
					city: city,
					state: state,
					zipCode: zipCode
				};
				
				console.log('Extracted parts:', result);
				return result;
			}

			// Function to create customer from company data
			function createCustomerFromCompanyData(companyData) {
				console.log('Creating customer from company data:', companyData);
				
				if (!companyData) {
					console.error('No company data provided');
					return;
				}

				// Extract city, state, and zip from address
				const address = companyData.address || '';
				const addressParts = extractAddressParts(address);

				// Prepare contact data
				const contactData = {
					name: companyData.companyNameTh,
					supplier_business_name: companyData.companyNameTh,
					tax_number: companyData.taxNumber,
					contact_type: 'business',
					type: 'customer',
					business_id: 1, // Assuming business_id is 1
					city: addressParts.city,
					state: addressParts.state,
					country: 'Thailand',
					address_line_1: address,
					zip_code: addressParts.zipCode,
					mobile: '-',
					shipping_address: address
				};

				// Send AJAX request to create contact
				$.ajax({
					url: '/contacts',
					method: 'POST',
					data: contactData,
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					},
					success: function(response) {
						console.log('Contact created successfully:', response);
						
						// Add the new customer to the dropdown and select it
						if (response && response.data && response.data.id) {
							const newOption = new Option(
								response.data.name,
								response.data.id,
								true,
								true
							);
							$('#customer_id').append(newOption).trigger('change');
							
							// Hide the lookup status and close dropdown
							hideTaxLookupStatus();
							$('#customer_id').select2('close');
							
							toastr.success(I18N.customer_created_with_name.replace(':name', response.data.name));
						} else {
							toastr.error(I18N.contact_created_unexpected);
						}
					},
					error: function(xhr, status, error) {
						console.error('Error creating contact:', xhr, status, error);
						let errorMsg = I18N.customer_create_failed;
						
						if (xhr.responseJSON && xhr.responseJSON.message) {
							errorMsg = xhr.responseJSON.message;
						}
						
						toastr.error(errorMsg);
					}
				});
			}

			// Event handlers for dropdown Tax ID lookup buttons
			$(document).on('click', '.tax-lookup-use-temp-btn', function(e) {
				e.preventDefault();
				e.stopPropagation();
				console.log('Use for document (existing customer) button clicked');
				
				const dataSource = $(this).data('source') || 'existing';
				let companyData = null;
				
				if (window.lastCompanyLookupData && window.lastCompanyLookupData.dbData && window.lastCompanyLookupData.apiData) {
					// Both results available - use DB data for existing customer
					companyData = window.lastCompanyLookupData.dbData;
				} else if (window.lastCompanyLookupData) {
					// Single result
					companyData = window.lastCompanyLookupData;
				}
				
				if (companyData) {
					console.log('Using existing customer data temporarily:', companyData);
					
					// Close the Select2 dropdown first
					$('#customer_id').select2('close');
					
					// Remove any existing temp options first
					$('#customer_id option[value^="temp_company_"]').remove();
					
					// Create a temporary option with company data and select it
					var tempOption = new Option(
						companyData.companyNameTh + ' (Tax ID: ' + companyData.taxNumber + ') - Temporary',
						'temp_company_' + companyData.taxNumber,
						true,
						true
					);
					$('#customer_id').append(tempOption).trigger('change');
					
					// Store the company data for later use when saving
					$('#customer_id').data('temp-company-data', companyData);
					
					console.log('Customer field updated with temp option for existing customer');
					
					// Update address displays
					updateAddressDisplays(companyData);
					
					// Hide any existing status messages
					hideTaxLookupStatus();
					
					// Show success message
					toastr.success(I18N.using_customer_temp.replace(':name', companyData.companyNameTh));
					console.log('Existing customer data applied temporarily');
				} else {
					console.error('No company data available');
					toastr.error(I18N.no_company_data);
				}
			});
			
			$(document).on('click', '.tax-lookup-edit-existing-btn', function(e) {
				e.preventDefault();
				e.stopPropagation();
				console.log('Edit existing customer button clicked');
				
				const dataSource = $(this).data('source') || 'existing';
				let companyData = null;
				
				if (window.lastCompanyLookupData && window.lastCompanyLookupData.dbData && window.lastCompanyLookupData.apiData) {
					// Both results available - use DB data for editing existing customer
					companyData = window.lastCompanyLookupData.dbData;
				} else if (window.lastCompanyLookupData && window.lastCompanyLookupData.customerId) {
					// Single result with customer ID
					companyData = window.lastCompanyLookupData;
				}
				
				if (companyData && companyData.customerId) {
					// Close the Select2 dropdown first
					$('#customer_id').select2('close');
					
					console.log('Opening edit modal for existing customer:', companyData);
					
					// Store the company data for the modal
					window.pendingCompanyData = companyData;
					window.pendingCompanyData.isEditMode = true; // Flag to indicate edit mode
					window.pendingCompanyData.existingCustomerId = companyData.customerId; // Store existing customer ID
					
					// Open the existing "Add Contact" modal
					$('.contact_modal').modal('show');
					
					// Pre-fill the modal with existing customer data
					setTimeout(function() {
						if (window.pendingCompanyData) {
							console.log('Filling modal fields for editing...');
							
							// Extract address parts first
							const addressParts = extractAddressParts(window.pendingCompanyData.address);
							console.log('Extracted address parts:', addressParts);
							
							// Try different field selectors
							const businessNameField = $('.contact_modal input[name="supplier_business_name"]');
							const firstNameField = $('.contact_modal input[name="first_name"]');
							const taxNumberField = $('.contact_modal input[name="tax_number"]');
							const addressField = $('.contact_modal textarea[name="address_line_1"], .contact_modal input[name="address_line_1"]');
							const cityField = $('.contact_modal input[name="city"]');
							const stateField = $('.contact_modal input[name="state"]');
							const zipField = $('.contact_modal input[name="zip_code"]');
							const countryField = $('.contact_modal input[name="country"]');
							const shippingAddressField = $('.contact_modal textarea[name="shipping_address"], .contact_modal input[name="shipping_address"]');
							const mobileField = $('.contact_modal input[name="mobile"]');
							
							// Fill the fields with existing data
							businessNameField.val(window.pendingCompanyData.companyNameTh);
							firstNameField.val(window.pendingCompanyData.companyNameTh);
							taxNumberField.val(window.pendingCompanyData.taxNumber);
							addressField.val(window.pendingCompanyData.address);
							cityField.val(addressParts.city);
							stateField.val(addressParts.state);
							zipField.val(addressParts.zipCode);
							countryField.val('Thailand');
							shippingAddressField.val(window.pendingCompanyData.address);
							mobileField.val(window.pendingCompanyData.mobile || ''); // Include existing mobile
							
							// Set contact type to business and show business fields
							$('.contact_modal input[name="contact_type_radio"][value="business"]').prop('checked', true).trigger('change');
							$('.contact_modal select[name="type"], .contact_modal select#contact_type').val('customer').trigger('change');
							
							// Show business fields and hide individual fields
							$('.contact_modal .business').show();
							$('.contact_modal .individual').hide();
							
							// Set assigned to current user if available
							const assignedToField = $('.contact_modal select[name="assigned_to_users[]"]');
							if (assignedToField.length && window.currentUserId) {
								assignedToField.val([window.currentUserId]).trigger('change');
							}
							
							// Store the customer ID in a hidden field for update
							let customerIdField = $('.contact_modal input[name="contact_id"]');
							if (customerIdField.length === 0) {
								// Create hidden field if it doesn't exist
								$('.contact_modal form').append('<input type="hidden" name="contact_id" value="' + window.pendingCompanyData.existingCustomerId + '">');
							} else {
								customerIdField.val(window.pendingCompanyData.existingCustomerId);
							}
							
							// Change modal title to indicate edit mode
							$('.contact_modal .modal-title').text('Edit Customer Information');
							
							console.log('Modal filled for editing existing customer with ID:', window.pendingCompanyData.existingCustomerId);
						}
					}, 300);
				} else {
					console.error('No customer data available for editing');
					toastr.error(I18N.customer_data_not_found);
				}
			});

			$(document).on('click', '.tax-lookup-use-btn', function(e) {
				e.preventDefault();
				e.stopPropagation();
				console.log('Use company button clicked');
				
				const dataSource = $(this).data('source') || 'new';
				let companyData = null;
				
				if (window.lastCompanyLookupData && window.lastCompanyLookupData.dbData && window.lastCompanyLookupData.apiData) {
					// Both results available - use API data for new customer
					companyData = window.lastCompanyLookupData.apiData;
				} else if (window.lastCompanyLookupData) {
					// Single result
					companyData = window.lastCompanyLookupData;
				}
				
				if (companyData) {
					console.log('Using company data:', companyData);
					
					// Close the Select2 dropdown first
					$('#customer_id').select2('close');
					
					// Remove any existing temp options first
					$('#customer_id option[value^="temp_company_"]').remove();
					
					// Create a temporary option with company data and select it
					var tempOption = new Option(
						companyData.companyNameTh + ' (Tax ID: ' + companyData.taxNumber + ')',
						'temp_company_' + companyData.taxNumber,
						true,
						true
					);
					$('#customer_id').append(tempOption).trigger('change');
					
					// Store the company data for later use when saving
					$('#customer_id').data('temp-company-data', companyData);
					
					console.log('Customer field updated with temp option');
					console.log('Selected value:', $('#customer_id').val());
					console.log('Selected text:', $('#customer_id option:selected').text());
					
					// Update address displays
					updateAddressDisplays(companyData);
					
					// Hide any existing status messages
					hideTaxLookupStatus();
					
					// Show success message
					toastr.success(I18N.using_company_for_document.replace(':name', companyData.companyNameTh));
					console.log('Company data applied to document successfully');
				} else {
					console.error('No company data available');
					toastr.error(I18N.no_company_data);
				}
			});
			
			// Helper function to update address displays
			function updateAddressDisplays(companyData) {
				var phoneValue = companyData.mobile || companyData.phone || '';
				var companyName = companyData.companyNameTh || companyData.companyNameEn || '';
				var taxNumber = companyData.taxNumber || '';
				// Update billing address display if exists
				var addressDisplay = $('#billing_address_div');
				if (addressDisplay.length) {
					var addressHtml = '<strong>' + companyData.companyNameTh + '</strong><br>' +
									 (companyData.address || '') + '<br>' +
									 '<em>Tax ID: ' + companyData.taxNumber + '</em>';
					addressDisplay.html(addressHtml);
					$('#billing_address_input').val(companyData.address || '');
					$('#billing_address_view').closest('.inline-address-card').attr('data-address', companyData.address || '');
					$('#billing_address_view').closest('.inline-address-card').attr('data-company-name', companyName);
					$('#billing_address_view').closest('.inline-address-card').attr('data-tax-number', taxNumber);
					$('#billing_address_view').closest('.inline-address-card').attr('data-contact-type', 'business');
					$('#billing_company_name_input').val(companyName);
					$('#billing_tax_number_input').val(taxNumber);
					$('#billing_phone_input').val(phoneValue);
					$('#billing_phone_text').attr('data-phone', phoneValue);
					$('#billing_phone_text .inline-address-phone-value').text(phoneValue || I18N.no_phone);
					console.log('Billing address updated');
				}
				
				// Update shipping address display if exists
				var shippingDisplay = $('#shipping_address_div');
				if (shippingDisplay.length) {
					var shippingHtml = '<strong>' + companyData.companyNameTh + '</strong><br>' +
									  (companyData.address || '');
					shippingDisplay.html(shippingHtml);
					$('#shipping_address_input').val(companyData.address || '');
					$('#shipping_address_view').closest('.inline-address-card').attr('data-address', companyData.address || '');
					$('#shipping_phone_input').val(phoneValue);
					$('#shipping_phone_text').attr('data-phone', phoneValue);
					$('#shipping_phone_text .inline-address-phone-value').text(phoneValue || I18N.no_phone);
					console.log('Shipping address updated');
				}
				
				// IMPORTANT: Also populate the actual form fields for billing/invoice
				// Extract address parts for proper field population
				const addressParts = extractAddressParts(companyData.address || '');
				console.log('Populating form fields with extracted address parts:', addressParts);
				
				// Store customer address data in the form for invoice generation
				// Update or create hidden fields that will be used when saving the invoice
				updateOrCreateHiddenField('customer_address_line_1', companyData.address || '');
				updateOrCreateHiddenField('customer_city', addressParts.city);
				updateOrCreateHiddenField('customer_state', addressParts.state);
				updateOrCreateHiddenField('customer_zip_code', addressParts.zipCode);
				updateOrCreateHiddenField('customer_country', 'Thailand');
				updateOrCreateHiddenField('customer_shipping_address', companyData.address || '');
				updateOrCreateHiddenField('customer_business_name', companyData.companyNameTh);
				updateOrCreateHiddenField('customer_tax_number', companyData.taxNumber);
				
				console.log('Form fields populated with customer address data');
			}
			
			// Helper function to update or create hidden fields
			function updateOrCreateHiddenField(name, value) {
				let field = $('input[name="' + name + '"]');
				if (field.length === 0) {
					// Create the field if it doesn't exist
					$('#add_sell_form').append('<input type="hidden" name="' + name + '" value="' + (value || '') + '">');
					console.log('Created hidden field:', name, '=', value);
				} else {
					// Update existing field
					field.val(value || '');
					console.log('Updated field:', name, '=', value);
				}
			}

			$(document).on('click', '.tax-lookup-add-btn', function(e) {
				e.preventDefault();
				e.stopPropagation();
				console.log('Add company button clicked');
				
				// Close the Select2 dropdown first
				$('#customer_id').select2('close');
				
				const dataSource = $(this).data('source') || 'new';
				let companyData = null;
				
				if (window.lastCompanyLookupData && window.lastCompanyLookupData.dbData && window.lastCompanyLookupData.apiData) {
					// Both results available - use API data for adding new customer
					companyData = window.lastCompanyLookupData.apiData;
				} else if (window.lastCompanyLookupData) {
					// Single result
					companyData = window.lastCompanyLookupData;
				}
				
				if (companyData) {
					// Store the company data for the modal
					window.pendingCompanyData = companyData;
					window.pendingCompanyData.isEditMode = false; // Flag to indicate add mode
					console.log('Company data to fill:', window.pendingCompanyData);
					
					// Open the existing "Add Contact" modal
					$('.contact_modal').modal('show');
					
					// Pre-fill the modal with company data
					setTimeout(function() {
						if (window.pendingCompanyData) {
							console.log('Filling modal fields...');
							
							// Reset the form first
							$('.contact_modal form')[0].reset();
							
							// Remove any existing contact_id field (for add mode)
							$('.contact_modal input[name="contact_id"]').remove();
							
							// Extract address parts first
							const addressParts = extractAddressParts(window.pendingCompanyData.address);
							console.log('Extracted address parts:', addressParts);
							
							// Try different field selectors
							const businessNameField = $('.contact_modal input[name="supplier_business_name"]');
							const firstNameField = $('.contact_modal input[name="first_name"]');
							const taxNumberField = $('.contact_modal input[name="tax_number"]');
							const addressField = $('.contact_modal textarea[name="address_line_1"], .contact_modal input[name="address_line_1"]');
							const cityField = $('.contact_modal input[name="city"]');
							const stateField = $('.contact_modal input[name="state"]');
							const zipField = $('.contact_modal input[name="zip_code"]');
							const countryField = $('.contact_modal input[name="country"]');
							const shippingAddressField = $('.contact_modal textarea[name="shipping_address"], .contact_modal input[name="shipping_address"]');
							
							console.log('Fields found:');
							console.log('Business name field:', businessNameField.length);
							console.log('First name field:', firstNameField.length);
							console.log('Tax number field:', taxNumberField.length);
							console.log('Address field:', addressField.length);
							console.log('City field:', cityField.length);
							console.log('State field:', stateField.length);
							console.log('Zip field:', zipField.length);
							console.log('Country field:', countryField.length);
							console.log('Shipping address field:', shippingAddressField.length);
							
							// Fill the fields
							businessNameField.val(window.pendingCompanyData.companyNameTh);
							firstNameField.val(window.pendingCompanyData.companyNameTh);
							taxNumberField.val(window.pendingCompanyData.taxNumber);
							addressField.val(window.pendingCompanyData.address);
							cityField.val(addressParts.city);
							stateField.val(addressParts.state);
							zipField.val(addressParts.zipCode);
							countryField.val('Thailand');
							shippingAddressField.val(window.pendingCompanyData.address);
							
							// Set contact type to business and show business fields
							$('.contact_modal input[name="contact_type_radio"][value="business"]').prop('checked', true).trigger('change');
							$('.contact_modal select[name="type"], .contact_modal select#contact_type').val('customer').trigger('change');
							
							// Show business fields and hide individual fields
							$('.contact_modal .business').show();
							$('.contact_modal .individual').hide();
							
							// Set assigned to current user if available
							const assignedToField = $('.contact_modal select[name="assigned_to_users[]"]');
							if (assignedToField.length && window.currentUserId) {
								assignedToField.val([window.currentUserId]).trigger('change');
							}
							
							console.log('Fields filled with values:');
							console.log('City:', addressParts.city);
							console.log('State:', addressParts.state);
							console.log('Zip:', addressParts.zipCode);
						}
					}, 300);
				}
			});

			// Test function for manual testing
			window.testTaxLookup = function() {
				console.log('Manual test triggered');
				lookupTaxId('0103555019171'); // Test with known working Tax ID
			};

			// Handle create contact with company data
			$(document).on('click', '#tax_lookup_create_btn', function() {
				if (!companyData) {
					toastr.error(I18N.no_company_data);
					return;
				}

				// Extract city, state, and zip from address
				const address = companyData.address || '';
				const addressParts = extractAddressParts(address);

				// Prepare contact data
				const contactData = {
					name: companyData.companyNameTh,
					supplier_business_name: companyData.companyNameTh,
					tax_number: companyData.taxNumber,
					contact_type: 'business',
					type: 'customer',
					business_id: 1, // Assuming business_id is 1
					city: addressParts.city,
					state: addressParts.state,
					country: 'Thailand',
					address_line_1: address,
					zip_code: addressParts.zipCode,
					mobile: '-',
					shipping_address: address
				};

				console.log('Creating contact with data:', contactData);

				// Show loading
				toastr.info(I18N.creating_customer, I18N.please_wait);

				// Create contact via AJAX
				$.ajax({
					url: '{{ route("contacts.store") }}',
					method: 'POST',
					data: contactData,
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					},
					success: function(response) {
						if (response.success) {
							toastr.success(I18N.customer_created);
							
							// Add the new contact to the dropdown and select it
							const newOption = new Option(response.data.name, response.data.id, true, true);
							$('#customer_id').append(newOption).trigger('change');
							
							// Hide lookup status
							hideTaxLookupStatus();
							
							// Clear the company data
							companyData = null;
							lastLookedUpTaxId = '';
						} else {
							toastr.error(response.msg || I18N.customer_create_failed);
						}
					},
					error: function(xhr, status, error) {
						console.error('Contact creation failed:', xhr, status, error);
						let errorMsg = I18N.customer_create_failed;
						
						if (xhr.responseJSON && xhr.responseJSON.msg) {
							errorMsg = xhr.responseJSON.msg;
						} else if (xhr.responseJSON && xhr.responseJSON.message) {
							errorMsg = xhr.responseJSON.message;
						}
						
						toastr.error(errorMsg);
					}
				});
			});

			// Handle edit contact data (open modal)
			$(document).on('click', '#tax_lookup_edit_btn', function() {
				if (!companyData) {
					toastr.error(I18N.no_company_data);
					return;
				}

				// Store company data and trigger the add new customer modal
				$('.add_new_customer').data('company-data', companyData);
				$('.add_new_customer').trigger('click');
			});

			// Override the add_new_customer click handler to handle company data
			$(document).off('click', '.add_new_customer').on('click', '.add_new_customer', function() {
				$('#customer_id').select2('close');
				var name = $(this).data('name');
				var companyData = $(this).data('company-data');
				
				// Clear the modal first
				$('.contact_modal').find('form')[0].reset();
				
				if (companyData) {
					// Pre-fill with company data
					const addressParts = extractAddressParts(companyData.address || '');
					
					// Set contact type to business first and trigger change to show/hide fields
					$('.contact_modal').find('input[name="contact_type_radio"][value="business"]').prop('checked', true).trigger('change');
					$('.contact_modal').find('select#contact_type').val('customer').trigger('change');
					
					// Fill business fields
					$('.contact_modal').find('input[name="supplier_business_name"]').val(companyData.companyNameTh);
					$('.contact_modal').find('input[name="first_name"]').val(companyData.companyNameTh);
					$('.contact_modal').find('input[name="tax_number"]').val(companyData.taxNumber);
					$('.contact_modal').find('input[name="address_line_1"]').val(companyData.address);
					$('.contact_modal').find('input[name="city"]').val(addressParts.city);
					$('.contact_modal').find('input[name="state"]').val(addressParts.state);
					$('.contact_modal').find('input[name="country"]').val('Thailand');
					$('.contact_modal').find('input[name="zip_code"]').val(addressParts.zipCode);
					$('.contact_modal').find('input[name="mobile"]').val('-');
					$('.contact_modal').find('input[name="shipping_address"]').val(companyData.address);
					
					// Show business fields and hide individual fields
					$('.contact_modal').find('.business').show();
					$('.contact_modal').find('.individual').hide();
					
					// Set assigned to current user
					const assignedToField = $('.contact_modal select[name="assigned_to_users[]"]');
					if (assignedToField.length && window.currentUserId) {
						assignedToField.val([window.currentUserId]).trigger('change');
					}
					
					// Clear company data after use
					$(this).removeData('company-data');
				} else {
					// Regular behavior - set to individual by default
					$('.contact_modal').find('input[name="contact_type_radio"][value="individual"]').prop('checked', true);
					$('.contact_modal').find('input[name="first_name"]').val(name);
					
					// Show individual fields and hide business fields
					$('.contact_modal').find('.individual').show();
					$('.contact_modal').find('.business').hide();
				}
				
				$('.contact_modal')
					.find('select#contact_type')
					.val('customer')
					.closest('div.contact_type_div')
					.addClass('hide');
				$('.contact_modal').modal('show');
			});

			// Handle successful contact creation/update from modal
			$(document).on('submit', '.contact_modal form', function(e) {
				const isEditMode = window.pendingCompanyData && window.pendingCompanyData.isEditMode;
				const existingCustomerId = window.pendingCompanyData && window.pendingCompanyData.existingCustomerId;
				
				console.log('Contact form submitted. Edit mode:', isEditMode, 'Customer ID:', existingCustomerId);
				
				// Don't prevent default - let the form submit normally
				// But listen for the success response
			});

			// Listen for AJAX success on contact form
			$(document).ajaxSuccess(function(event, xhr, settings) {
				// Check if this is a contact creation/update response
				if (settings.url && (settings.url.includes('/contacts') || settings.url.endsWith('/contacts'))) {
					try {
						const response = JSON.parse(xhr.responseText);
						
						if (response && response.success && response.data) {
							console.log('Contact saved successfully:', response.data);
							
							// Check if this was an edit operation
							const isEditMode = window.pendingCompanyData && window.pendingCompanyData.isEditMode;
							
							if (isEditMode) {
								// This was an update - refresh the customer dropdown to show updated info
								toastr.success(I18N.customer_updated_with_name.replace(':name', response.data.name));
								
								// Update the customer dropdown option if it exists
								const customerId = response.data.id;
								const existingOption = $('#customer_id option[value="' + customerId + '"]');
								if (existingOption.length > 0) {
									existingOption.text(response.data.name);
									existingOption.attr('selected', true);
									$('#customer_id').trigger('change');
								} else {
									// Add new option and select it
									const newOption = new Option(response.data.name, customerId, true, true);
									$('#customer_id').append(newOption).trigger('change');
								}
							} else {
								// This was a new contact creation
								toastr.success(I18N.customer_created_with_name.replace(':name', response.data.name));
								
								// Add the new customer to the dropdown and select it
								const newOption = new Option(response.data.name, response.data.id, true, true);
								$('#customer_id').append(newOption).trigger('change');
							}
							
							// Close the modal
							$('.contact_modal').modal('hide');
							
							// Clear pending data
							window.pendingCompanyData = null;
							
							// Hide tax lookup status
							hideTaxLookupStatus();
						}
					} catch (e) {
						console.log('Response parsing failed:', e);
					}
				}
			});

			// Function to extract address parts
			function extractAddressParts(address) {
				const parts = {
					city: '',
					state: '',
					zipCode: ''
				};

				if (!address) return parts;

				console.log('Parsing Thai address:', address);

				// Extract zip code (5 digits at the end)
				const zipMatch = address.match(/(\d{5})$/);
				if (zipMatch) {
					parts.zipCode = zipMatch[1];
				}

				// Common Thai provinces - comprehensive list
				const thaiProvinces = [
					'กรุงเทพมหานคร', 'กระบี่', 'กาญจนบุรี', 'กาฬสินธุ์', 'กำแพงเพชร', 'ขอนแก่น', 'จันทบุรี', 'ฉะเชิงเทรา',
					'ชลบุรี', 'ชัยนาท', 'ชัยภูมิ', 'ชุมพร', 'เชียงราย', 'เชียงใหม่', 'ตรัง', 'ตราด', 'ตาก', 'นครนายก',
					'นครปฐม', 'นครพนม', 'นครราชสีมา', 'นครศรีธรรมราช', 'นครสวรรค์', 'นนทบุรี', 'นราธิวาส', 'น่าน',
					'บึงกาฬ', 'บุรีรัมย์', 'ปทุมธานี', 'ประจวบคีรีขันธ์', 'ปราจีนบุรี', 'ปัตตานี', 'พระนครศรีอยุธยา', 'พะเยา',
					'พังงา', 'พัทลุง', 'พิจิตร', 'พิษณุโลก', 'เพชรบุรี', 'เพชรบูรณ์', 'แพร่', 'ภูเก็ต', 'มหาสารคาม',
					'มุกดาหาร', 'แม่ฮ่องสอน', 'ยโสธร', 'ยะลา', 'ร้อยเอ็ด', 'ระนอง', 'ระยอง', 'ราชบุรี', 'ลพบุรี',
					'ลำปาง', 'ลำพูน', 'เลย', 'ศรีสะเกษ', 'สกลนคร', 'สงขลา', 'สตูล', 'สมุทรปราการ', 'สมุทรสงคราม',
					'สมุทรสาคร', 'สระแก้ว', 'สระบุรี', 'สิงห์บุรี', 'สุโขทัย', 'สุพรรณบุรี', 'สุราษฎร์ธานี', 'สุรินทร์',
					'หนองคาย', 'หนองบัวลำภู', 'อ่างทอง', 'อำนาจเจริญ', 'อุดรธานี', 'อุตรดิตถ์', 'อุทัยธานี', 'อุบลราชธานี'
				];

				// Remove zip code from address for better parsing
				let addressWithoutZip = address.replace(/\s*\d{5}\s*$/, '').trim();
				
				// Method 1: Look for explicit province patterns
				let provinceFound = '';
				
				// Check for "จ." or "จังหวัด" prefix
				let provinceMatch = addressWithoutZip.match(/(?:จ\.|จังหวัด)\s*([ก-๙]+)/);
				if (provinceMatch) {
					provinceFound = provinceMatch[1].trim();
				}

				// Method 2: If no explicit pattern, search for known provinces in the address
				if (!provinceFound) {
					for (let province of thaiProvinces) {
						if (addressWithoutZip.includes(province)) {
							provinceFound = province;
							break;
						}
					}
				}

				// Method 3: If still not found, try to extract the last significant word before zip code
				if (!provinceFound) {
					// Split by spaces and look for the last meaningful word
					const words = addressWithoutZip.split(/\s+/);
					for (let i = words.length - 1; i >= 0; i--) {
						const word = words[i].trim();
						// Skip common non-province words
						if (word && !word.match(/^(ถนน|ซอย|แขวง|เขต|อำเภอ|ตำบล|หมู่|ม\.|ต\.|อ\.)/) && word.length > 2) {
							// Check if this word is a province
							const matchedProvince = thaiProvinces.find(p => p.includes(word) || word.includes(p));
							if (matchedProvince) {
								provinceFound = matchedProvince;
								break;
							}
						}
					}
				}

				// Set both city and state to the found province (Thai standard)
				if (provinceFound) {
					parts.city = provinceFound;
					parts.state = provinceFound;
				}

				console.log('Extracted address parts:', parts);
				return parts;
			}

			// Modern Contact Modal Enhancements
			$(document).ready(function() {
				// Initialize modern contact modal
				$('.contact_modal').on('show.bs.modal', function() {
					console.log('Contact modal opening - applying modern styling');
					
					// Add modern classes
					$(this).addClass('modern-modal');
					
					// Create modern contact type toggle if it doesn't exist
					setTimeout(function() {
						modernizeContactModal();
					}, 100);
				});
				
				// Add debug function for testing Select2 functionality
				window.testAssignedToField = function() {
					console.log('=== Testing Assigned To Field ===');
					const $field = $('.contact_modal select[name="assigned_to_users[]"]');
					console.log('Field found:', $field.length > 0);
					console.log('Is Select2:', $field.hasClass('select2-hidden-accessible'));
					console.log('Current values:', $field.val());
					
					// Test adding a value
					if (window.currentUserId) {
						$field.val([window.currentUserId]).trigger('change');
						console.log('Added current user, new values:', $field.val());
					}
				};
				
				// Handle contact type changes
				$(document).on('change', '.contact_modal input[name="contact_type_radio"]', function() {
					const isIndividual = $(this).val() === 'individual';
					const $modal = $(this).closest('.contact_modal');
					
					// Toggle sections with animation
					if (isIndividual) {
						$modal.find('.business').slideUp(300);
						$modal.find('.individual').slideDown(300);
					} else {
						$modal.find('.individual').slideUp(300);
						$modal.find('.business').slideDown(300);
					}
				});
				
				// Add loading states to form submission
				$(document).on('submit', '.contact_modal form', function() {
					const $submitBtn = $(this).find('button[type="submit"], input[type="submit"]');
					const originalText = $submitBtn.text();
					
					$submitBtn.prop('disabled', true)
							  .html('<i class="fa fa-spinner fa-spin"></i> ' + I18N.saving)
							  .addClass('loading');
					
					// Reset button after 5 seconds as fallback
					setTimeout(function() {
						$submitBtn.prop('disabled', false)
								  .text(originalText)
								  .removeClass('loading');
					}, 5000);
				});
			});
			
			function modernizeContactModal() {
				const $modal = $('.contact_modal');
				
				// Modernize the modal title
				let $title = $modal.find('.modal-title');
				if ($title.length && !$title.hasClass('modernized')) {
					$title.addClass('modernized');
				}
				
				// Create modern contact type toggle
				const $contactTypeRadios = $modal.find('input[name="contact_type_radio"]');
				if ($contactTypeRadios.length && !$modal.find('.contact-type-toggle').length) {
					const $toggleContainer = $('<div class="contact-type-toggle"></div>');
					
					$contactTypeRadios.each(function() {
						const $radio = $(this);
						const $label = $('label[for="' + $radio.attr('id') + '"]');
						const value = $radio.val();
						const text = $label.text() || (value === 'individual' ? 'Individual' : 'Business');
						
						const $toggleItem = $(`
							<div>
								<input type="radio" name="contact_type_radio" value="${value}" id="modern_${value}" ${$radio.is(':checked') ? 'checked' : ''}>
								<label for="modern_${value}">${text}</label>
							</div>
						`);
						
						$toggleContainer.append($toggleItem);
					});
					
					// Replace old radio buttons
					$contactTypeRadios.closest('.form-group').replaceWith($toggleContainer);
				}
				
				// Add icons to form groups
				addIconsToFormFields($modal);
				
				// Add section dividers
				addSectionDividers($modal);
				
				// Enhance buttons
				enhanceModalButtons($modal);
				
				// Fix Assigned To field
				enhanceAssignedToField($modal);
			}
			
			function addIconsToFormFields($modal) {
				const fieldIcons = {
					'first_name': 'fa-user',
					'last_name': 'fa-user',
					'supplier_business_name': 'fa-building',
					'mobile': 'fa-phone',
					'email': 'fa-envelope',
					'tax_number': 'fa-hashtag',
					'address_line_1': 'fa-map-marker',
					'city': 'fa-map-pin',
					'state': 'fa-map',
					'zip_code': 'fa-mail-bulk',
					'country': 'fa-globe'
				};
				
				Object.keys(fieldIcons).forEach(function(fieldName) {
					const $field = $modal.find(`input[name="${fieldName}"], textarea[name="${fieldName}"]`);
					if ($field.length && !$field.closest('.input-group').length) {
						$field.wrap('<div class="input-group"></div>');
						$field.before(`<span class="input-group-addon"><i class="fa ${fieldIcons[fieldName]}"></i></span>`);
					}
				});
			}
			
			function addSectionDividers($modal) {
				// Add section dividers for better organization
				const sections = [
					{ selector: '.business', title: I18N.business_information },
					{ selector: '.individual', title: I18N.personal_information },
					{ selector: '[name="address_line_1"]', title: I18N.address_information }
				];
				
				sections.forEach(function(section) {
					const $element = $modal.find(section.selector).first().closest('.form-group');
					if ($element.length && !$element.prev('.section-divider').length) {
						$element.before(`<div class="section-divider" data-title="${section.title}"></div>`);
					}
				});
			}
			
			function enhanceModalButtons($modal) {
				// Add icons to buttons
				const $saveBtn = $modal.find('button[type="submit"], input[type="submit"]').first();
				if ($saveBtn.length && $saveBtn.find('i').length === 0) {
					const originalText = $saveBtn.text().trim();
					$saveBtn.html('<i class="fa fa-save"></i> ' + originalText);
				}
				
				const $cancelBtn = $modal.find('[data-dismiss="modal"], .btn-secondary').first();
				if ($cancelBtn.length && $cancelBtn.find('i').length === 0) {
					const originalText = $cancelBtn.text().trim();
					$cancelBtn.html('<i class="fa fa-times"></i> ' + originalText);
				}
			}
			
			// Fix Assigned To field functionality
			function enhanceAssignedToField($modal) {
				const $assignedToField = $modal.find('select[name="assigned_to_users[]"]');
				
				if ($assignedToField.length) {
					console.log('Enhancing Assigned To field');
					
					// Destroy any existing Select2 instance first
					if ($assignedToField.hasClass('select2-hidden-accessible')) {
						$assignedToField.select2('destroy');
					}
					
					// Initialize Select2 with proper configuration
					$assignedToField.select2({
						width: '100%',
						placeholder: I18N.select_users_to_assign,
						allowClear: true,
						closeOnSelect: false,
						dropdownParent: $modal // Ensure dropdown renders within modal
					});
					
					// Handle selection events for debugging
					$assignedToField.on('select2:select', function(e) {
						console.log('User selected in Assigned To field:', e.params.data);
					});
					
					$assignedToField.on('select2:unselect', function(e) {
						console.log('User unselected from Assigned To field:', e.params.data);
					});
					
					// Don't interfere with Select2's remove button - let it handle naturally
					// Remove any conflicting event handlers
					$(document).off('click', '.contact_modal .select2-selection__choice__remove');
					
					// Set assigned to current user if available and field is empty
					setTimeout(function() {
						const currentValues = $assignedToField.val() || [];
						if (window.currentUserId && currentValues.length === 0) {
							$assignedToField.val([window.currentUserId]).trigger('change');
							console.log('Auto-assigned current user to contact');
						}
					}, 500);
				}
			}

    	});

		(function() {
			if (window.inlineAddressHandlersBound) {
				return;
			}
			window.inlineAddressHandlersBound = true;

			function updateOrCreateHiddenField(name, value) {
				let field = $('input[name="' + name + '"]');
				if (field.length === 0) {
					$('#add_sell_form').append('<input type="hidden" name="' + name + '" value="' + (value || '') + '">');
				} else {
					field.val(value || '');
				}
			}

			function formatInlineAddress(address) {
				if (!address) {
					return '';
				}
				return $('<div/>').text(address).html().replace(/\n/g, '<br>');
			}

			function toggleInlineAddress(type, isEditing) {
				var $card = $('.inline-address-card[data-address-type="' + type + '"]');
				$card.find('.inline-address-view').toggle(!isEditing);
				$card.find('.inline-address-edit').toggle(isEditing);
			}

			function updateInlineAddressDisplay(type, address, phone) {
				var $card = $('.inline-address-card[data-address-type="' + type + '"]');
				var safeAddress = formatInlineAddress(address);
				var phoneValue = phone || '';
				var addressFallback = type === 'billing' ? I18N.no_billing_address : I18N.no_shipping_address;
				var companyName = $card.attr('data-company-name') || '';
				var taxNumber = $card.attr('data-tax-number') || '';

				$card.attr('data-address', address || '');
				$card.attr('data-phone', phoneValue);

				if (type === 'billing') {
					var billingHtml = '';
					if (companyName) {
						billingHtml += '<strong>' + $('<div/>').text(companyName).html() + '</strong><br>';
					}
					billingHtml += safeAddress || addressFallback;
					if (taxNumber) {
						billingHtml += '<br><em>Tax ID: ' + $('<div/>').text(taxNumber).html() + '</em>';
					}
					$('#billing_address_div').html(billingHtml);
					$('#billing_phone_text .inline-address-phone-value').text(phoneValue || I18N.no_phone);
				} else {
					$('#shipping_address_div').html(safeAddress || addressFallback);
					$('#shipping_phone_text .inline-address-phone-value').text(phoneValue || I18N.no_phone);
				}
			}

			function updateInlinePhoneDisplay(phone) {
				var phoneValue = phone || '';
				$('.inline-address-card').attr('data-phone', phoneValue);
				$('#billing_phone_text').attr('data-phone', phoneValue);
				$('#shipping_phone_text').attr('data-phone', phoneValue);
				$('#billing_phone_text .inline-address-phone-value').text(phoneValue || I18N.no_phone);
				$('#shipping_phone_text .inline-address-phone-value').text(phoneValue || I18N.no_phone);
			}

			function showInlineAddressToast(message, isError) {
				if (window.Swal && typeof window.Swal.fire === 'function') {
					window.Swal.fire({
						toast: true,
						position: 'top-end',
						icon: isError ? 'error' : 'success',
						title: message,
						showConfirmButton: false,
						timer: 2200,
						timerProgressBar: true
					});
				} else if (window.toastr) {
					if (isError) {
						toastr.error(message);
					} else {
						toastr.success(message);
					}
				} else {
					alert(message);
				}
			}

			function applyCustomerAddressData(data) {
				if (!data) {
					return;
				}

				if (typeof update_shipping_address === 'function') {
					update_shipping_address(data);
				}

				var billingAddress = data.address_line_1 || '';
				var shippingAddress = data.shipping_address || billingAddress;
				var phoneValue = data.mobile || '';
				var rawTextName = data.text ? String(data.text) : '';
				var cleanedTextName = rawTextName.replace(/\s*\([^)]*\)\s*$/, '');
				var companyName = data.supplier_business_name || data.name || cleanedTextName || '';
				var taxNumber = data.tax_number || data.taxNumber || '';
				var contactType = data.supplier_business_name ? 'business' : 'individual';

				$('.inline-address-card[data-address-type="billing"]').attr('data-address', billingAddress);
				$('.inline-address-card[data-address-type="billing"]').attr('data-company-name', companyName);
				$('.inline-address-card[data-address-type="billing"]').attr('data-tax-number', taxNumber);
				$('.inline-address-card[data-address-type="billing"]').attr('data-contact-type', contactType);
				$('#billing_address_input').val(billingAddress);
				$('#billing_company_name_input').val(companyName);
				$('#billing_tax_number_input').val(taxNumber);
				$('#billing_phone_input').val(phoneValue);

				$('.inline-address-card[data-address-type="shipping"]').attr('data-address', shippingAddress);
				$('#shipping_address_input').val(shippingAddress);
				$('#shipping_phone_input').val(phoneValue);

				updateInlinePhoneDisplay(phoneValue);

				if (billingAddress) {
					updateOrCreateHiddenField('customer_address_line_1', billingAddress);
				}
				if (shippingAddress) {
					updateOrCreateHiddenField('customer_shipping_address', shippingAddress);
				}
			}

			$(document).on('change', '#customer_id', function() {
				var contactId = $(this).val();
				if (!contactId || String(contactId).startsWith('temp_company_')) {
					return;
				}

				var selected = null;
				if ($(this).data('select2')) {
					var data = $(this).select2('data');
					if (data && data.length) {
						selected = data[0];
					}
				}

				if (selected && (selected.address_line_1 || selected.shipping_address || selected.mobile || selected.name || selected.text)) {
					applyCustomerAddressData(selected);
					return;
				}

				$.ajax({
					url: '/contacts/details/' + contactId,
					method: 'GET',
					success: function(response) {
						if (response && response.success && response.data) {
							applyCustomerAddressData(response.data);
						}
					}
				});
			});

			$(document).on('click', '.inline-address-edit-btn', function() {
				var type = $(this).data('address');
				var $card = $('.inline-address-card[data-address-type="' + type + '"]');
				var addressValue = $card.attr('data-address') || '';
				var phoneValue = $card.attr('data-phone') || '';
				var companyName = $card.attr('data-company-name') || '';
				var taxNumber = $card.attr('data-tax-number') || '';

				if (!addressValue) {
					addressValue = type === 'billing' ? $('#billing_address_div').text().trim() : $('#shipping_address_div').text().trim();
				}
				if (!phoneValue) {
					phoneValue = type === 'billing'
						? $('#billing_phone_text .inline-address-phone-value').text().trim()
						: $('#shipping_phone_text .inline-address-phone-value').text().trim();
				}

				if (type === 'billing') {
					$('#billing_company_name_input').val(companyName);
					$('#billing_address_input').val(addressValue);
					$('#billing_tax_number_input').val(taxNumber);
					$('#billing_phone_input').val(phoneValue);
				} else {
					$('#shipping_address_input').val(addressValue);
					$('#shipping_phone_input').val(phoneValue);
				}

				toggleInlineAddress(type, true);
			});

			$(document).on('click', '.inline-address-cancel-btn', function() {
				var type = $(this).data('address');
				toggleInlineAddress(type, false);
			});

			$(document).on('click', '.inline-address-save-btn', function() {
				var type = $(this).data('address');
				var contactId = $('#customer_id').val() || $('#default_customer_id').val();
				var addressValue = '';
				var phoneValue = '';
				var companyName = '';
				var taxNumber = '';
				var contactType = 'individual';

				if (type === 'billing') {
					companyName = $('#billing_company_name_input').val().trim();
					addressValue = $('#billing_address_input').val().trim();
					taxNumber = $('#billing_tax_number_input').val().trim();
					phoneValue = $('#billing_phone_input').val().trim();
					contactType = $('.inline-address-card[data-address-type="billing"]').attr('data-contact-type') || 'individual';
				} else {
					addressValue = $('#shipping_address_input').val().trim();
					phoneValue = $('#shipping_phone_input').val().trim();
				}

				if (!contactId) {
					if ($('#customer_id').data('select2')) {
						var selectedData = $('#customer_id').select2('data');
						if (selectedData && selectedData.length && selectedData[0].id) {
							contactId = selectedData[0].id;
						}
					}
				}

				if (!contactId || String(contactId).startsWith('temp_company_')) {
					showInlineAddressToast(I18N.customer_required, true);
					return;
				}

				var payload = {
					_token: $('meta[name="csrf-token"]').attr('content'),
					mobile: phoneValue
				};

				if (type === 'billing') {
					payload.billing_address = addressValue;
					payload.company_name = companyName;
					payload.tax_number = taxNumber;
					payload.contact_type = contactType;
				} else {
					payload.shipping_address = addressValue;
				}

				$.ajax({
					url: '/contacts/' + contactId + '/inline-update',
					method: 'POST',
					data: payload,
					success: function(response) {
						if (response && response.success) {
							if (type === 'billing') {
								var $billingCard = $('.inline-address-card[data-address-type="billing"]');
								$billingCard.attr('data-company-name', companyName);
								$billingCard.attr('data-tax-number', taxNumber);
								$billingCard.attr('data-contact-type', contactType);
							}
							updateInlineAddressDisplay(type, addressValue, phoneValue);
							updateInlinePhoneDisplay(phoneValue);
							if (type === 'billing') {
								updateOrCreateHiddenField('customer_address_line_1', addressValue);
							} else {
								updateOrCreateHiddenField('customer_shipping_address', addressValue);
							}
							toggleInlineAddress(type, false);
							showInlineAddressToast(I18N.address_saved, false);
						} else {
							showInlineAddressToast(I18N.address_save_failed, true);
						}
					},
					error: function() {
						showInlineAddressToast(I18N.address_save_failed, true);
					}
				});
			});
		})();
    </script>
@endsection
