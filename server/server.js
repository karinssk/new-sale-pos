const express = require('express');
const cors = require('cors');
require('dotenv').config();

const app = express();
const PORT = process.env.PORT || 3000;

// Environment-based configuration
const isProduction = process.env.NODE_ENV === 'development';
const API_BASE_URL = process.env.API_BASE_URL || (isProduction 
    ? 'https://api-shop.rubyshop.co.th' 
    : 'http://localhost:8888');

// Database configuration for production
const DB_CONFIG = {
    host: process.env.DB_HOST || (isProduction ? 'api-shop.rubyshop.co.th' : 'localhost'),
    port: process.env.DB_PORT || (isProduction ? 3306 : 8889),
    user: process.env.DB_USER ,
    password: process.env.DB_PASSWORD ,
    database: process.env.DB_NAME ,
};

// Middleware
app.use(cors({
    origin: isProduction ? [
        'https://api-shop.rubyshop.co.th',
        'https://shop.rubyshop.co.th',
         'https://sale.rubyshop.co.th',
         'http://localhost:8888'
    ] : '*',
    methods: '*',
    allowedHeaders: '*'
}));

app.use(express.json());

// Make configuration available to routes
app.locals.apiBaseUrl = API_BASE_URL;
app.locals.dbConfig = DB_CONFIG;
app.locals.isProduction = isProduction;

// Routes
const pdfRoutes = require('./routes/pdfRoutes');
app.use('/', pdfRoutes);

// Start server
app.listen(PORT, () => {
    console.log(`PDF Generator Server running on http://localhost:${PORT}`);
    console.log(`Environment: ${process.env.NODE_ENV || 'development'}`);
    console.log(`API Base URL: ${API_BASE_URL}`);
    console.log(`Database: ${DB_CONFIG.host}:${DB_CONFIG.port}/${DB_CONFIG.database}`);
    console.log(`Health check: http://localhost:${PORT}/health`);
    console.log(`Generate Quotation PDF: POST http://localhost:${PORT}/generate-quotation-pdf/:id`);
    console.log(
        `Generate Tax Invoice PDF: POST http://localhost:${PORT}/generate-tax-invoice-pdf/:id`
    );
    console.log(
        `Generate Billing Receipt PDF: POST http://localhost:${PORT}/generate-billing-receipt-pdf/:id`
    );
});

module.exports = app;
