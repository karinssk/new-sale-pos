const express = require('express');
const router = express.Router();
const PDFController = require('../controllers/pdfController');
const axios = require('axios');
const cheerio = require('cheerio');

// Function: ดึงข้อมูลบริษัทจาก dataforthai.com
async function getCompanyInfo(taxId) {
  try {
    const url = `https://www.dataforthai.com/company/${taxId}/`;
    const { data } = await axios.get(url, {
      headers: {
        "User-Agent":
          "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0 Safari/537.36",
      },
      timeout: 10000, // 10 second timeout
    });

    const $ = cheerio.load(data);

    // ชื่อไทย (h2 ภายใน #maindata)
    const companyNameTh = $("#maindata h2").first().text().trim();

    // ชื่ออังกฤษ (h3.noselect ภายใน #maindata)
    const companyNameEn = $("#maindata h3.noselect").first().text().trim();

    // Tax ID
    const taxRow = $("td")
      .filter((i, el) => $(el).text().trim() === "เลขทะเบียน")
      .closest("tr");
    const taxNumber = taxRow.find("td").eq(1).text().trim();

    // Business type
    const businessRow = $("td")
      .filter((i, el) => $(el).text().trim() === "ประเภทธุรกิจ")
      .closest("tr");
    const businessType = businessRow.find("td").eq(1).text().trim();

    // Address (Google Maps link ที่ class="noselect")
    const address = $("a.noselect").first().text().trim();
    console.log( companyNameTh, companyNameEn, taxNumber, businessType, address)
    return { companyNameTh, companyNameEn, taxNumber, businessType, address };
  } catch (error) {
    console.error("❌ Error fetching company data:", error.message);
    return null;
  }
}

// Health check endpoint (must come before catch-all)
router.get('/health', PDFController.healthCheck);

// Company lookup endpoint
router.get('/company/:taxId', async (req, res) => {
  try {
    const { taxId } = req.params;
    
    // Validate tax ID format (should be 13 digits)
    if (!/^\d{13}$/.test(taxId)) {
      return res.status(400).json({ 
        error: "Invalid tax ID format. Tax ID must be 13 digits." 
      });
    }
    
    console.log(`🔍 Looking up company with Tax ID: ${taxId}`);
    
    const info = await getCompanyInfo(taxId);

    if (!info || !info.companyNameTh) {
      return res.status(404).json({ 
        error: "Company not found",
        taxId: taxId
      });
    }

    console.log(`✅ Company found: ${info.companyNameTh}`);
    res.json(info);
  } catch (error) {
    console.error('❌ Company lookup error:', error);
    res.status(500).json({ 
      error: "Internal server error while fetching company data",
      details: error.message
    });
  }
});

// System diagnostics endpoint
router.get('/diagnostics', PDFController.diagnostics);

// Database test endpoint
router.get('/test-db', PDFController.testDatabase);

// List sample invoice numbers from database
router.get('/list-invoices', async (req, res) => {
    try {
        const DatabaseService = require('../services/databaseService');
        const mysql = require('mysql2/promise');
        
        // Use dynamic database configuration
        const dbConfig = DatabaseService.getDbConfig(req);
        
        const connection = await mysql.createConnection(dbConfig);
        
        const [rows] = await connection.execute(
            'SELECT id, invoice_no, type, status, payment_status, created_at FROM transactions ORDER BY created_at DESC LIMIT 20'
        );
        
        await connection.end();
        
        res.json({
            total: rows.length,
            sample_invoices: rows
        });
    } catch (error) {
        res.status(500).json({ error: error.message });
    }
});

// Test endpoint to return HTML for debugging
router.get('/test-html/:id', PDFController.testHTML);

// Debug endpoint to see generated HTML
router.post('/debug-html/:id', PDFController.debugHTML);

// Main PDF generation endpoints - supporting both GET and POST
router.get('/public/quotations/:id/pdf-print-nodejs', PDFController.generateQuotationPDF);
router.post('/generate-quotation-pdf/:id', PDFController.generateQuotationPDF);

router.get('/tax-invoice/:id/pdf-print-nodejs', PDFController.generateTaxInvoicePDF);
router.post('/generate-tax-invoice-pdf/:id', PDFController.generateTaxInvoicePDF);

router.get('/billing-receipt/:id/pdf-print-nodejs', PDFController.generateBillingReceiptPDF);
router.post('/generate-billing-receipt-pdf/:id', PDFController.generateBillingReceiptPDF);

// Catch-all route to handle invoice numbers directly (must come last)
router.get('/:invoiceNumber', PDFController.generatePDFByInvoiceNumber);

module.exports = router;
