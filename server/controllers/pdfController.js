// const DatabaseService = require('../services/databaseService');
// const PDFService = require('../services/pdfService');
// const { generateQuotationHTML, generateTaxInvoiceHTML, generateBillingReceiptHTML } = require('../services/htmlService');

// // Helper function to format invoice number for filename/URL
// function formatInvoiceNumber(invoiceNo) {
//     if (!invoiceNo) return null;
//     // Convert to lowercase and replace slash with dash
//     // Example: VT2025/0922 -> vt2025-0922
//     return invoiceNo.toLowerCase().replace(/\//g, '-');
// }

// class PDFController {
//     // Generate Quotation PDF
//     static async generateQuotationPDF(req, res) {
//         const quotationId = req.params.id;

//         try {
//             // Get data from database
//             const data = await DatabaseService.getQuotationData(quotationId);
            
//             if (!data) {
//                 return res.status(404).json({ error: 'Quotation not found' });
//             }

//             const { quotation, lineItems } = data;

//             // Generate HTML
//             const html = generateQuotationHTML(quotation, lineItems);

//             // Generate PDF
//             const pdf = await PDFService.generatePDF(html);

//             // Format filename using invoice number or fallback to ID
//             const formattedInvoiceNo = formatInvoiceNumber(quotation.invoice_no);
//             const filename = formattedInvoiceNo ? `${formattedInvoiceNo}.pdf` : `quotation-${quotationId}.pdf`;

//             res.setHeader('Content-Type', 'application/pdf');
//             res.setHeader('Content-Disposition', `attachment; filename="${filename}"`);
//             res.setHeader('X-Suggested-Filename', filename); // Custom header for frontend
//             res.send(pdf);
//         } catch (error) {
//             console.error('Error generating PDF:', error);
//             res.status(500).json({ error: 'Failed to generate PDF' });
//         }
//     }

//     // Generate Tax Invoice PDF
//     static async generateTaxInvoicePDF(req, res) {
//         const quotationId = req.params.id;

//         try {
//             // Get data from database
//             const data = await DatabaseService.getQuotationData(quotationId);
            
//             if (!data) {
//                 return res.status(404).json({ error: 'Tax Invoice not found' });
//             }

//             const { quotation, lineItems } = data;

//             // Generate HTML for tax invoice
//             const html = generateTaxInvoiceHTML(quotation, lineItems);

//             // Generate PDF
//             const pdf = await PDFService.generatePDF(html);

//             // Format filename using invoice number or fallback to ID
//             const formattedInvoiceNo = formatInvoiceNumber(quotation.invoice_no);
//             const filename = formattedInvoiceNo ? `${formattedInvoiceNo}.pdf` : `tax-invoice-${quotationId}.pdf`;

//             res.setHeader('Content-Type', 'application/pdf');
//             res.setHeader('Content-Disposition', `attachment; filename="${filename}"`);
//             res.setHeader('X-Suggested-Filename', filename); // Custom header for frontend
//             res.send(pdf);
//         } catch (error) {
//             console.error('Error generating Tax Invoice PDF:', error);
//             res.status(500).json({ error: 'Failed to generate Tax Invoice PDF' });
//         }
//     }

//     // Generate Billing Receipt PDF
//     static async generateBillingReceiptPDF(req, res) {
//         const quotationId = req.params.id;

//         try {
//             // Get data from database
//             const data = await DatabaseService.getQuotationData(quotationId);
            
//             if (!data) {
//                 return res.status(404).json({ error: 'Billing Receipt not found' });
//             }

//             const { quotation, lineItems } = data;

//             // Generate HTML for billing receipt
//             const html = generateBillingReceiptHTML(quotation, lineItems);

//             // Generate PDF
//             const pdf = await PDFService.generatePDF(html);

//             // Format filename using invoice number or fallback to ID
//             const formattedInvoiceNo = formatInvoiceNumber(quotation.invoice_no);
//             const filename = formattedInvoiceNo ? `${formattedInvoiceNo}.pdf` : `billing-receipt-${quotationId}.pdf`;

//             res.setHeader('Content-Type', 'application/pdf');
//             res.setHeader('Content-Disposition', `attachment; filename="${filename}"`);
//             res.setHeader('X-Suggested-Filename', filename); // Custom header for frontend
//             res.send(pdf);
//         } catch (error) {
//             console.error('Error generating Billing Receipt PDF:', error);
//             res.status(500).json({ error: 'Failed to generate Billing Receipt PDF' });
//         }
//     }

//     // Health check
//     static async healthCheck(req, res) {
//         res.json({ status: 'OK', message: 'PDF Generator Service is running' });
//     }

//     // Database test
//     static async testDatabase(req, res) {
//         try {
//             const result = await DatabaseService.testConnection();
//             if (result.success) {
//                 res.json({
//                     status: 'OK',
//                     message: result.message,
//                 });
//             } else {
//                 res.status(500).json({
//                     status: 'ERROR',
//                     message: 'Database connection failed',
//                     error: result.error,
//                 });
//             }
//         } catch (error) {
//             console.error('Database test error:', error);
//             res.status(500).json({
//                 status: 'ERROR',
//                 message: 'Database connection failed',
//                 error: error.message,
//             });
//         }
//     }

//     // Test HTML generation
//     static async testHTML(req, res) {
//         const quotationId = req.params.id;

//         try {
//             // Get data from database
//             const data = await DatabaseService.getQuotationData(quotationId);
            
//             if (!data) {
//                 return res.status(404).json({ error: 'Quotation not found' });
//             }

//             const { quotation, lineItems } = data;

//             // Generate HTML
//             const html = generateQuotationHTML(quotation, lineItems);

//             res.setHeader('Content-Type', 'text/html');
//             res.send(html);
//         } catch (error) {
//             console.error('Error generating HTML:', error);
//             res.status(500).json({ error: 'Failed to generate HTML' });
//         }
//     }

//     // Debug HTML generation
//     static async debugHTML(req, res) {
//         const quotationId = req.params.id;

//         try {
//             // Get data from database
//             const data = await DatabaseService.getQuotationData(quotationId);
            
//             if (!data) {
//                 return res.status(404).json({ error: 'Quotation not found' });
//             }

//             const { quotation, lineItems } = data;

//             // Generate HTML
//             const html = generateQuotationHTML(quotation, lineItems);

//             res.setHeader('Content-Type', 'text/html');
//             res.send(html);
//         } catch (error) {
//             console.error('Error generating HTML:', error);
//             res.status(500).json({ error: 'Failed to generate HTML' });
//         }
//     }
// }

// module.exports = PDFController;


const DatabaseService = require('../services/databaseService');
const PDFService = require('../services/pdfService');
const { generateQuotationHTML, generateTaxInvoiceHTML, generateBillingReceiptHTML, formatInvoiceNumber } = require('../services/htmlService');

// Helper function to format invoice number for filename/URL
function formatInvoiceNumberForFilename(invoiceNo, documentType) {
    if (!invoiceNo) return null;
    
    // Use the formatInvoiceNumber from htmlService to get proper prefix
    const formattedInvoiceNo = formatInvoiceNumber(invoiceNo, documentType);
    
    // Convert to lowercase and replace slash with dash for filename
    // Example: VT2025/0922 -> vt2025-0922
    return formattedInvoiceNo.toLowerCase().replace(/\//g, '-');
}

class PDFController {
    // Generate Quotation PDF
    static async generateQuotationPDF(req, res) {
        const quotationId = req.params.id;

        try {
            // Get data from database
            const data = await DatabaseService.getQuotationData(quotationId);
            
            if (!data) {
                return res.status(404).json({ error: 'Quotation not found' });
            }

            const { quotation, lineItems } = data;

            // Generate HTML
            const html = generateQuotationHTML(quotation, lineItems);

            // Generate PDF
            const pdf = await PDFService.generatePDF(html);

            // Format filename using invoice number or fallback to ID
            const formattedInvoiceNo = formatInvoiceNumberForFilename(quotation.invoice_no, 'quotation');
            const filename = formattedInvoiceNo ? `${formattedInvoiceNo}.pdf` : `quotation-${quotationId}.pdf`;

            res.setHeader('Content-Type', 'application/pdf');
            res.setHeader('Content-Disposition', `attachment; filename="${filename}"`);
            res.setHeader('X-Suggested-Filename', filename); // Custom header for frontend
            res.send(pdf);
        } catch (error) {
            console.error('Error generating PDF:', error);
            res.status(500).json({ error: 'Failed to generate PDF' });
        }
    }

    // Generate Tax Invoice PDF
    static async generateTaxInvoicePDF(req, res) {
        const quotationId = req.params.id;

        try {
            // Get data from database
            const data = await DatabaseService.getQuotationData(quotationId);
            
            if (!data) {
                return res.status(404).json({ error: 'Tax Invoice not found' });
            }

            const { quotation, lineItems } = data;

            // Generate HTML for tax invoice
            const html = generateTaxInvoiceHTML(quotation, lineItems);

            // Generate PDF
            const pdf = await PDFService.generatePDF(html);

            // Format filename using invoice number or fallback to ID
            const formattedInvoiceNo = formatInvoiceNumberForFilename(quotation.invoice_no, 'tax-invoice');
            const filename = formattedInvoiceNo ? `${formattedInvoiceNo}.pdf` : `tax-invoice-${quotationId}.pdf`;

            res.setHeader('Content-Type', 'application/pdf');
            res.setHeader('Content-Disposition', `attachment; filename="${filename}"`);
            res.setHeader('X-Suggested-Filename', filename); // Custom header for frontend
            res.send(pdf);
        } catch (error) {
            console.error('Error generating Tax Invoice PDF:', error);
            res.status(500).json({ error: 'Failed to generate Tax Invoice PDF' });
        }
    }

    // Generate Billing Receipt PDF
    static async generateBillingReceiptPDF(req, res) {
        const quotationId = req.params.id;

        try {
            // Get data from database
            const data = await DatabaseService.getQuotationData(quotationId);
            
            if (!data) {
                return res.status(404).json({ error: 'Billing Receipt not found' });
            }

            const { quotation, lineItems } = data;

            // Generate HTML for billing receipt
            const html = generateBillingReceiptHTML(quotation, lineItems);

            // Generate PDF
            const pdf = await PDFService.generatePDF(html);

            // Format filename using invoice number or fallback to ID
            const formattedInvoiceNo = formatInvoiceNumberForFilename(quotation.invoice_no, 'billing-receipt');
            const filename = formattedInvoiceNo ? `${formattedInvoiceNo}.pdf` : `billing-receipt-${quotationId}.pdf`;

            res.setHeader('Content-Type', 'application/pdf');
            res.setHeader('Content-Disposition', `attachment; filename="${filename}"`);
            res.setHeader('X-Suggested-Filename', filename); // Custom header for frontend
            res.send(pdf);
        } catch (error) {
            console.error('Error generating Billing Receipt PDF:', error);
            res.status(500).json({ error: 'Failed to generate Billing Receipt PDF' });
        }
    }

    // Enhanced health check with system information
    static async healthCheck(req, res) {
        const os = require('os');
        const fs = require('fs');
        
        try {
            // Basic health info
            const healthInfo = {
                status: 'OK',
                message: 'PDF Generator Service is running',
                timestamp: new Date().toISOString(),
                uptime: process.uptime(),
                memory: process.memoryUsage(),
                system: {
                    platform: os.platform(),
                    arch: os.arch(),
                    cpus: os.cpus().length,
                    totalMemory: os.totalmem(),
                    freeMemory: os.freemem(),
                    loadAvg: os.loadavg()
                },
                chrome: {
                    executablePath: process.env.CHROME_BIN || 'auto-detect',
                    puppeteerVersion: require('puppeteer/package.json').version
                },
                environment: {
                    nodeVersion: process.version,
                    nodeEnv: process.env.NODE_ENV || 'development'
                }
            };
            
            // Test Chrome availability
            try {
                const puppeteer = require('puppeteer');
                const browser = await puppeteer.launch({
                    headless: "new",
                    args: ['--no-sandbox', '--disable-setuid-sandbox'],
                    timeout: 10000
                });
                await browser.close();
                healthInfo.chrome.status = 'Available';
            } catch (chromeError) {
                healthInfo.chrome.status = 'Error';
                healthInfo.chrome.error = chromeError.message;
            }
            
            res.json(healthInfo);
        } catch (error) {
            console.error('Health check error:', error);
            res.status(500).json({
                status: 'ERROR',
                message: 'Health check failed',
                error: error.message,
                timestamp: new Date().toISOString()
            });
        }
    }

    // System diagnostics endpoint
    static async diagnostics(req, res) {
        const { exec } = require('child_process');
        
        try {
            const diagnostics = {
                timestamp: new Date().toISOString(),
                processes: {},
                disk: {},
                memory: process.memoryUsage()
            };
            
            // Check Chrome processes
            exec('ps aux | grep -E "(chrome|chromium)" | grep -v grep', (error, stdout) => {
                diagnostics.processes.chrome = stdout ? stdout.split('\n').filter(line => line.trim()) : [];
            });
            
            // Check disk space
            exec('df -h /tmp', (error, stdout) => {
                diagnostics.disk.tmp = stdout || 'N/A';
            });
            
            setTimeout(() => {
                res.json(diagnostics);
            }, 1000);
            
        } catch (error) {
            res.status(500).json({
                error: 'Diagnostics failed',
                message: error.message
            });
        }
    }

    // Database test
    static async testDatabase(req, res) {
        try {
            const result = await DatabaseService.testConnection();
            if (result.success) {
                res.json({
                    status: 'OK',
                    message: result.message,
                });
            } else {
                res.status(500).json({
                    status: 'ERROR',
                    message: 'Database connection failed',
                    error: result.error,
                });
            }
        } catch (error) {
            console.error('Database test error:', error);
            res.status(500).json({
                status: 'ERROR',
                message: 'Database connection failed',
                error: error.message,
            });
        }
    }

    // Test HTML generation
    static async testHTML(req, res) {
        const quotationId = req.params.id;

        try {
            // Get data from database
            const data = await DatabaseService.getQuotationData(quotationId);
            
            if (!data) {
                return res.status(404).json({ error: 'Quotation not found' });
            }

            const { quotation, lineItems } = data;

            // Generate HTML
            const html = generateQuotationHTML(quotation, lineItems);

            res.setHeader('Content-Type', 'text/html');
            res.send(html);
        } catch (error) {
            console.error('Error generating HTML:', error);
            res.status(500).json({ error: 'Failed to generate HTML' });
        }
    }

    // Debug HTML generation
    static async debugHTML(req, res) {
        const quotationId = req.params.id;

        try {
            // Get data from database
            const data = await DatabaseService.getQuotationData(quotationId);
            
            if (!data) {
                return res.status(404).json({ error: 'Quotation not found' });
            }

            const { quotation, lineItems } = data;

            // Generate HTML
            const html = generateQuotationHTML(quotation, lineItems);

            res.setHeader('Content-Type', 'text/html');
            res.send(html);
        } catch (error) {
            console.error('Error generating HTML:', error);
            res.status(500).json({ error: 'Failed to generate HTML' });
        }
    }

    // Generate PDF by invoice number - automatically detects document type
    static async generatePDFByInvoiceNumber(req, res) {
        const invoiceNumber = req.params.invoiceNumber;

        try {
            // Get data from database by invoice number
            const data = await DatabaseService.getQuotationByInvoiceNumber(invoiceNumber);
            
            if (!data) {
                return res.status(404).json({ 
                    error: 'Document not found', 
                    invoiceNumber: invoiceNumber 
                });
            }

            const { quotation, lineItems } = data;

            // Determine document type based on invoice number prefix or transaction type
            let documentType = 'quotation'; // default
            let html = '';
            
            if (invoiceNumber.startsWith('VT') || invoiceNumber.includes('VT')) {
                documentType = 'tax-invoice';
                html = generateTaxInvoiceHTML(quotation, lineItems);
            } else if (invoiceNumber.startsWith('IPAY') || invoiceNumber.includes('IPAY')) {
                documentType = 'billing-receipt';
                html = generateBillingReceiptHTML(quotation, lineItems);
            } else if (invoiceNumber.startsWith('QUOTE') || invoiceNumber.includes('QUOTE')) {
                documentType = 'quotation';
                html = generateQuotationHTML(quotation, lineItems);
            } else {
                // Fallback: try to determine from transaction type or status
                if (quotation.type === 'sell' && quotation.status === 'final') {
                    documentType = 'tax-invoice';
                    html = generateTaxInvoiceHTML(quotation, lineItems);
                } else if (quotation.payment_status === 'paid') {
                    documentType = 'billing-receipt';
                    html = generateBillingReceiptHTML(quotation, lineItems);
                } else {
                    documentType = 'quotation';
                    html = generateQuotationHTML(quotation, lineItems);
                }
            }

            // Generate PDF
            const pdf = await PDFService.generatePDF(html);

            // Format filename
            const formattedInvoiceNo = formatInvoiceNumberForFilename(quotation.invoice_no, documentType);
            const filename = formattedInvoiceNo ? `${formattedInvoiceNo}.pdf` : `${documentType}-${invoiceNumber}.pdf`;

            res.setHeader('Content-Type', 'application/pdf');
            res.setHeader('Content-Disposition', `attachment; filename="${filename}"`);
            res.setHeader('X-Suggested-Filename', filename);
            res.send(pdf);
        } catch (error) {
            console.error('Error generating PDF by invoice number:', error);
            res.status(500).json({ error: 'Failed to generate PDF' });
        }
    }
}

module.exports = PDFController;

