// Helper functions
function formatCurrency(amount) {
    return new Intl.NumberFormat('th-TH', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    }).format(amount);
}

function formatNumber(number) {
    return new Intl.NumberFormat('th-TH').format(number);
}

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('th-TH', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
    });
}

function getCustomerAddress(quotation) {
    let address = '';
    if (quotation.address_line_1) {
        address += quotation.address_line_1;
    }
    if (quotation.address_line_2) {
        address += (address ? ', ' : '') + quotation.address_line_2;
    }
    if (quotation.city) {
        address += (address ? ', ' : '') + quotation.city;
    }
    if (quotation.state) {
        address += (address ? ', ' : '') + quotation.state;
    }
    if (quotation.zip_code) {
        address += (address ? ' ' : '') + quotation.zip_code;
    }
    return address || null;
}

function getSellerName(quotation) {
    // Try to get seller name from user data
    if (quotation.seller_first_name) {
        let name = quotation.seller_first_name;
        if (quotation.seller_last_name) {
            name += ' ' + quotation.seller_last_name;
        }
        return name;
    }

    // Fallback to username if available
    if (quotation.seller_username) {
        return quotation.seller_username;
    }

    // Final fallback to created_by ID or default
    return quotation.created_by ? `User ID: ${quotation.created_by}` : 'Aroocha';
}

// Function to get product image URL
function getProductImageUrl(item, baseUrl = 'https://shop.rubyshop.co.th') {
    let imageUrl = null;
    
    // Check for main product image first
    if (item.product_image) {
        // Handle different image path formats
        if (item.product_image.startsWith('http')) {
            imageUrl = item.product_image;
        } else {
            imageUrl = `${baseUrl}/uploads/img/${item.product_image}`;
        }
    }
    
    // Check for additional images if main image not found
    if (!imageUrl && item.additional_image_path && item.additional_image) {
        if (item.additional_image_path.startsWith('http')) {
            imageUrl = `${item.additional_image_path}/${item.additional_image}`;
        } else {
            imageUrl = `${baseUrl}/${item.additional_image_path}/${item.additional_image}`;
        }
    }
    
    return imageUrl;
}

module.exports = {
    formatCurrency,
    formatNumber,
    formatDate,
    getCustomerAddress,
    getSellerName,
    getProductImageUrl
};
