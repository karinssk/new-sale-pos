const express = require('express');
const mysql = require('mysql2/promise');
const puppeteer = require('puppeteer');
const cors = require('cors');
require('dotenv').config();

const app = express();
const PORT = process.env.PORT || 3000;

// Middleware
app.use(cors());
app.use(express.json());

// Database configuration
const dbConfig = {
    host: 'localhost',
    port: 3306,
    user: 'root',
    password: '',
    database: 'rubyshop.co.th_shop',
};

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

// Main PDF generation endpoint
app.post('/generate-quotation-pdf/:id', async (req, res) => {
    const quotationId = req.params.id;

    try {
        const connection = await mysql.createConnection(dbConfig);

        // Get quotation data
        const [quotationRows] = await connection.execute(
            `
            SELECT 
                t.*,
                c.name as customer_name,
                c.mobile as customer_mobile,
                c.email as customer_email,
                c.address_line_1,
                c.address_line_2,
                c.city,
                c.state,
                c.country,
                c.zip_code,
                c.supplier_business_name,
                c.contact_id as customer_contact_id,
                c.tax_number as customer_tax_number,
                bl.name as location_name,
                bl.landmark,
                bl.city as location_city,
                bl.state as location_state,
                bl.country as location_country,
                bl.zip_code as location_zip,
                bl.mobile as location_mobile,
                bl.email as location_email,
                b.name as business_name,
                u.first_name as seller_first_name,
                u.last_name as seller_last_name,
                u.username as seller_username
            FROM transactions t
            LEFT JOIN contacts c ON t.contact_id = c.id
            LEFT JOIN business_locations bl ON t.location_id = bl.id
            LEFT JOIN business b ON bl.business_id = b.id
            LEFT JOIN users u ON t.created_by = u.id
            WHERE t.id = ? AND t.type IN ('sell', 'quotation', 'draft')
        `,
            [quotationId]
        );

        if (quotationRows.length === 0) {
            await connection.end();
            return res.status(404).json({ error: 'Quotation not found' });
        }

        const quotation = quotationRows[0];

        // Get quotation line items (only main products, not sub-products)
        const [lineItems] = await connection.execute(
            `
            SELECT 
                tsl.*,
                p.name as product_name,
                p.product_description,
                p.sku,
                v.name as variation_name,
                u.short_name as unit_name
            FROM transaction_sell_lines tsl
            LEFT JOIN products p ON tsl.product_id = p.id
            LEFT JOIN variations v ON tsl.variation_id = v.id
            LEFT JOIN units u ON p.unit_id = u.id
            WHERE tsl.transaction_id = ? AND tsl.parent_sell_line_id IS NULL
            ORDER BY tsl.id
        `,
            [quotationId]
        );

        await connection.end();

        // Generate HTML
        const html = generateQuotationHTML(quotation, lineItems);

        // Generate PDF
        const browser = await puppeteer.launch({
            headless: true,
            args: ['--no-sandbox', '--disable-setuid-sandbox'],
        });

        const page = await browser.newPage();
        await page.setContent(html, { waitUntil: 'networkidle0' });

        const pdf = await page.pdf({
            format: 'A4',
            printBackground: true,
            margin: {
                top: '0mm',
                right: '0mm',
                bottom: '0mm',
                left: '0mm',
            },
        });

        await browser.close();

        res.setHeader('Content-Type', 'application/pdf');
        res.setHeader('Content-Disposition', `attachment; filename="quotation-${quotationId}.pdf"`);
        res.send(pdf);
    } catch (error) {
        console.error('Error generating PDF:', error);
        res.status(500).json({ error: 'Failed to generate PDF' });
    }
});

// Function to generate HTML content with pagination support
function generateQuotationHTML(quotation, lineItems) {
    // Use database values instead of recalculating
    const subtotal = parseFloat(quotation.total_before_tax) || 0;
    const discount = parseFloat(quotation.discount_amount) || 0;
    const freight = parseFloat(quotation.shipping_charges) || 0;
    const vatAmount = parseFloat(quotation.tax_amount) || 0;
    const total = parseFloat(quotation.final_total) || 0;

    // Simple pagination logic
    const itemsPerPage = 3;
    const totalItems = lineItems.length;
    const totalPages = Math.max(1, Math.ceil(totalItems / itemsPerPage));

    let allPagesHTML = '';

    for (let currentPage = 1; currentPage <= totalPages; currentPage++) {
        const startIndex = (currentPage - 1) * itemsPerPage;
        const endIndex = Math.min(startIndex + itemsPerPage, totalItems);
        const pageItems = lineItems.slice(startIndex, endIndex);
        const isLastPage = currentPage === totalPages;

        const pageContent = generatePageContent(
            quotation,
            pageItems,
            subtotal,
            discount,
            freight,
            vatAmount,
            total,
            currentPage,
            totalPages,
            isLastPage
        );

        allPagesHTML += pageContent;
    }

    return wrapInHTMLDocument(allPagesHTML);
}

// Function to generate page content
function generatePageContent(
    quotation,
    lineItems,
    subtotal,
    discount,
    freight,
    vatAmount,
    total,
    currentPage,
    totalPages,
    isLastPage
) {
    return `
        <div class="page" data-page="${currentPage}">
            <!-- Header -->
            <div class="header">
                <div class="receipt-type thai-text">BILLING RECEIPT (Original)</div>
                <div class="page-number thai-text">หน้าที่ ${currentPage}/${totalPages}</div>
                <div class="company-name">RUBYSHOP</div>
                <div class="company-subtitle thai-text">ห้างหุ้นส่วนจำกัดรูบี้ช็อป</div>
            </div>
            
            <!-- Contact Bar -->
            <div class=" header-red">
                97/60 ม.4 • Larkland • Sq 1, Viphavadi-Rangit Road, Saikun, Dongmuang, Bangkok 10210 THAILAND
            </div>
                <div class="contact-bar ">
                TEL:(+66) 8 9 666 7802 FAX : (662) 981-1584 Email: info@rubyshop.co.th www.rubyshop.co.th 
            </div>



       










            <!-- Main Content -->
            <div class="main-content">

                 
           
           
                           <div class="left-section">
                           <div class="vertical-text-client-eng">
                         
                           </div>
                             <div class="vertical-text-client-eng-text">
                               <p>CLIENT INFORMATION</p>
                           </div>
                          <div class="vertical-text-client-thai">
                                <p>ข้อมูลลูกค้า</p>
                           </div>
                            
                             
                          <div class="vertical-text-company-thai">
                                <p>ข้อมูลผู้เสนอราคา</p>
                           </div>
                         
                    <div class="section-title thai-text section-title-bill-number">Date : ${formatDate(
                        quotation.transaction_date
                    )} เลขที่ใบเสร็จรับเงิน : ${quotation.invoice_no}</div>
                    <div class="info-row">
                        <span class="info-label-left thai-text info-label-left-name">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ชื่อบริษัท:</span>
                        <span class="info-left-label-name">${
                            quotation.supplier_business_name || quotation.customer_name || ''
                        }</span>
                       
                    </div>
                    <div class="info-row">
                        <span class="info-label-left thai-text info-label-left-texnumber">เลขประจำตัวผู้เสียภาษี:</span>
                        <span class="info-left-label-texnumber">${
                            quotation.customer_tax_number || '-'
                        }</span>
                    </div>
                 <div class="info-row">
                       <span class="info-label info-value-address">ที่อยู่:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                       <span class="info-value-address">
                  ${quotation.shipping_address || getCustomerAddress(quotation) || '-'}
                    </span>
</div>

                    <div class="info-row">
                        <span class="info-label-left thai-text info-label-left-phone">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;โทรศัพท์:</span>
                        <span class="info-left-label-phone">${
                            quotation.customer_mobile || quotation.mobile || '-'
                        }</span>
                    </div>
                </div>
               
                <div class="right-section">
                  
                       <div class="vertical-text-company-eng">
                          
                           </div>
                
                     <div class="vertical-text-company-eng-text-eng">
                               <p>COOMPANY INFORMATION</p>
                           </div>
                       <div class="vertical-text-company-text-th">
                               <p>ข้อมูลบริษัทผู้ขาย</p>
                           </div>
                           <div class="thai-text section-title-company">หจก.รูบี้ช็อป (สำนักงานใหญ่)</div>
                    <div class="info-row">
                        <span class=" section-title-company-sub">เลขที่ 97/60 หมู่บ้านหลักสี่แลนด์ ซอยโกสุมรวมใจ39<br>
                       แขวงดอนเมือง เขตดอนเมือง กรุงเทพฯ 10210 </span>
                    </div>
                    <div class="info-row">
                        <span class=" tax-number-company">เลขประจำตัวผู้เสียภาษี:</span>
                        <span class="info-value info-value-company-detail">&nbsp;&nbsp;  0103555019171</span>
                    </div>
                    <div class="info-row">
                        <span class="phone-company-text ">เบอร์โทรศัพท์: </span>
                        <span class="info-value info-value-company-detail-phone">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;089-666-7802</span>
                    </div>
                    <div class="info-row">
                        <span class="info-value-company-detail-email-th">อีเมล: &nbsp;&nbsp;&nbsp;info@rubyshop.co.th</span>
                        <!-- <span class="info-value nfo-value-company-detail-email">&nbsp;&nbsp;&nbsp;info@rubyshop.co.th</span> -->
                    </div>
                 
                    <div class="info-row">
                        <span class="info-value-company-detail-sellers">ชื่อผู้ขาย: ${getSellerName(
                            quotation
                        )}</span>
                        <!-- <span class="info-value ">${getSellerName(quotation)}</span> -->
                    </div>
                </div>
            </div>

        
    <div class="vertical-products-services"> 
                         
                           </div>
                            <div class="vertical-products-services-eng">
                               <p>PRODUCTS AND SERVICES DESCRIPTION </p>
                           </div>
                          <div class="vertical-products-services-thai">
                                <p>สินค้าและบริการ</p>
                           </div>
                     

            <!-- Items Table -->
            <table class="items-table">
                <thead class="items-header">
                    <tr>
                        <th style="width: 5%;">ลำดับ</th>
                        <th style="width: 50%;">
                            <div class="english-text">Description of Services and Goods</div>
                        </th>
                        <th style="width: 10%;">
                            <div class="english-text">Quantity</div>
                        </th>
                        <th style="width: 15%;">
                            <div class="english-text">Price Per Unit</div>
                            <div class="thai-text">(บาท)</div>
                        </th>
                        <th style="width: 15%;">
                            <div class="english-text">Amount</div>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    ${generateItemRows(lineItems, currentPage, totalPages)}
                </tbody>
            </table>

            <!-- Notes Section - Left side -->
            <div class="products-services">
                <h4 class="thai-text">หมายเหตุ</h4>
                <p class="thai-text">
                    * ใบเสร็จรับเงินจะสมบูรณ์เมื่อเช็คผ่าน หจก.รูบี้ช็อป โดยสามารถตรวจสอบได้ หรือโทรสอบถามเพิ่มเติมได้ที่เรียงรายละเอียดข้างล่าง
                </p>
            </div>

            <!-- Totals Section - Right side -->
            <div class="totals-section">
                <div class="totals-table">
                    ${generateTotalsSection(
                        subtotal,
                        discount,
                        freight,
                        vatAmount,
                        total,
                        isLastPage,
                        totalPages
                    )}
                </div>
            </div>

            <!-- Approval Section -->
            <div class="approve-section thai-text">
                Approve By/ผู้อนุมัติรายการนี้
            </div>

            <!-- Footer -->
            <div class="footer">
                <div class="payment-info">
                    <strong class="thai-text">การชำระเงิน</strong><br>
                    <span class="english-text">Payment Information</span>
                    <div class="checkbox-group">
                        <div class="checkbox-item">
                            <span class="checkbox"></span>
                            <span class="thai-text">เงินสด</span><br>
                            <span class="english-text">Cash</span>
                        </div>
                        <div class="checkbox-item">
                            <span class="checkbox"></span>
                            <span class="thai-text">เช็ค</span><br>
                            <span class="english-text">Cheque</span>
                        </div>
                        <div class="checkbox-item">
                            <span class="checkbox"></span>
                            <span class="thai-text">โอนเงิน</span><br>
                            <span class="english-text">Transfer</span>
                        </div>
                    </div>
                
                </div>
                
                <div class="collector-info">
                    <strong class="thai-text">ผู้รับเงิน</strong><br>
                    <span class="english-text">Collector</span>
                    <div style="margin-top: 30px; border-bottom: 1px solid #333; width: 150px; margin-left: auto;"></div>
              
                </div>
            </div>
        </div>
    `;
}

// Helper function to generate item rows with correct numbering
function generateItemRows(pageItems, currentPage, totalPages) {
    const itemsPerPage = 3;
    const startIndex = (currentPage - 1) * itemsPerPage;

    // Generate rows for actual items
    let rows = pageItems
        .map((item, index) => {
            const globalIndex = startIndex + index + 1;

            // Format product name and description with proper line breaks
            let productDisplay = item.product_name;
            if (item.product_description && item.product_description.trim()) {
                const description = item.product_description.trim();

                // Use existing HTML structure (ul/li tags) from the database
                // Add dashes to li elements if they don't already have them
                let formattedDescription = description.replace(/<li>/g, '<li>- ');
                // Remove double dashes if they already exist
                formattedDescription = formattedDescription.replace(/<li>- - /g, '<li>- ');
                productDisplay += `<div class="product-description">${formattedDescription}</div>`;
            }

            return `
            <tr>
                <td>${globalIndex}</td>
                <td class="item-description">${productDisplay}</td>
                <td>${formatNumber(item.quantity)}</td>
                <td>${formatCurrency(item.unit_price_inc_tax)}</td>
                <td>${formatCurrency(item.unit_price_inc_tax * item.quantity)}</td>
            </tr>
        `;
        })
        .join('');

    return rows;
}

// Helper function to generate totals section
function generateTotalsSection(
    subtotal,
    discount,
    freight,
    vatAmount,
    total,
    isLastPage,
    totalPages
) {
    // For multiple pages: only show values on the last page
    // For single page: always show values
    let showValues = true;
    console.log('totalPages',totalPages)
    if (totalPages === 1) {
        // Single page - always show values
            console.log('page = 1 ok',totalPages)
        showValues = true;
    } else if (totalPages > 1 && isLastPage) {
        console.log('totalPages > 1 && isLastPage ok',totalPages)
        // Multiple pages - only show values on last page
        showValues = true;
    } else {
        // Multiple pages - don't show values on first/middle pages
           console.log('else con',totalPages)
        showValues = false;
    }

    if (showValues) {
        // Show actual values on single page or last page of multiple pages
        return `
            <div class="total-row">
                <span class="total-label">
                    <span class="english-text">Subtotal</span><br>
                    <span class="thai-text">ยอดรวม</span>
                </span>
                <span class="total-amount">${formatCurrency(subtotal)}</span>
            </div>
            
            <div class="total-row">
                <span class="total-label">
                    <span class="english-text">Discount</span><br>
                    <span class="thai-text">ส่วนลด</span>
                </span>
                <span class="total-amount">${formatCurrency(discount)}</span>
            </div>
             
            <div class="total-row">
                <span class="total-label">
                    <span class="english-text">Freight Cost</span><br>
                    <span class="thai-text">ค่าขนส่ง</span>
                </span>
                <span class="total-amount">${formatCurrency(freight)}</span>
            </div>
            
            <div class="total-row">
                <span class="total-label">
                    <span class="english-text">VAT 7%</span><br>
                    <span class="thai-text">ภาษีมูลค่าเพิ่ม</span>
                </span>
                <span class="total-amount">${formatCurrency(vatAmount)}</span>
            </div>
            
            <div class="total-row grand-total">
                <span class="total-label">
                    <span class="english-text">Total Price</span><br>
                    <span class="thai-text">ราคารวมสุทธิ</span>
                </span>
                <span class="total-amount">${formatCurrency(
                    total
                )} <span class="thai-text">บาท</span></span>
            </div>
        `;
    } else {
        // Show empty values on non-last pages when multiple pages exist
        return `
            <div class="total-row">
                <span class="total-label">
                    <span class="english-text">Subtotal</span><br>
                    <span class="thai-text">ยอดรวม</span>
                </span>
                <span class="total-amount">-</span>
            </div>
            
            <div class="total-row">
                <span class="total-label">
                    <span class="english-text">Discount</span><br>
                    <span class="thai-text">ส่วนลด</span>
                </span>
                <span class="total-amount">-</span>
            </div>
             
            <div class="total-row">
                <span class="total-label">
                    <span class="english-text">Freight Cost</span><br>
                    <span class="thai-text">ค่าขนส่ง</span>
                </span>
                <span class="total-amount">-</span>
            </div>
            
            <div class="total-row">
                <span class="total-label">
                    <span class="english-text">VAT 7%</span><br>
                    <span class="thai-text">ภาษีมูลค่าเพิ่ม</span>
                </span>
                <span class="total-amount">-</span>
            </div>
            
            <div class="total-row grand-total">
                <span class="total-label">
                    <span class="english-text">Total Price</span><br>
                    <span class="thai-text">ราคารวมสุทธิ</span>
                </span>
                <span class="total-amount">- <span class="thai-text">บาท</span></span>
            </div>
        `;
    }
}

// Helper function to wrap content in HTML document
function wrapInHTMLDocument(content) {
    return `
    <!DOCTYPE html>
    <html lang="th">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>ใบเสนอราคา</title>
        <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600&family=Sarabun:wght@300;400;500;600&display=swap" rel="stylesheet">
        <style>
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }
            
            body {
                font-family: 'Prompt', 'TH Sarabun New', Arial, sans-serif;
                font-size: 11px;
                line-height: 1.3;
                color: #333;
                background: white;
            }
             .info-left-label-name{
             font-size: 11px;
                          margin-left: -15px;
             }
                        .info-left-label-texnumber{
                          font-size: 11px;
                          margin-left: -15px;
                        }
                        .info-left-label-address {
                            font-size: 11px;
                            margin-left: -15px;
                        }
                        .info-left-label-phone {
                            font-size: 11px;
                            margin-left: -15px;
                        }

            .vertical-products-services{
             width:664px;
            height:50px;
            padding-left:-50px; 
            margin-right:-500px;
            margin-left:-316px;
            transform: rotate(-90deg);
            background-color:#797b7d;
              
                top:308px;
                position: relative;
                border:1px solid rgb(255, 255, 255);

            }
              .vertical-products-services-eng{
                   
                    transform: rotate(-90deg);
                    position: relative;
                       width:350px;
                      height:20px;
                    background-color:transparent;
                    padding-left:100px; 
                    left:-155px;
                    top:200px;
                          color:#ffffff;
              }

.vertical-products-services-thai {
                    color:#fff;
                    transform: rotate(-90deg);
                    position: relative;
                    left:100px;
                     background-color:transparent;
                     left:-140px;
                          width:350px;
                      height:20px;
                    top:0px
              }


            .vertical-text-client-eng-text{
                         background-color:transparent;
                          
            width:180px;
            height:40px;
            position:relative;
            transform: rotate(-90deg);
            color:#fff;
            font-size: 14px;
            font-weight: 100;
            text-align: center;
            /* display: flex; */
            align-items: center;
            justify-content: center;
            font-family: 'Prompt', 'TH Sarabun New', Arial, sans-serif;

            margin-left:-85px; 
            top:32px;
            z-index: 999 !important;
            }


          .vertical-text-client-eng {
          
                background-color:#797b7d;
            width:212px;
            height: 40px;
            position:relative;
            transform: rotate(-90deg);
            color:#fff;
            font-size: 14px;
            font-weight: 100;
            text-align: center;
            /* display: flex; */
            align-items: center;
            justify-content: center;
            font-family: 'Prompt', 'TH Sarabun New', Arial, sans-serif;
            font-size: 14px;
            margin-left:-106px; 
            top:72px;
           
            
          }
          .vertical-text-client-thai {
                            background-color:transparent;
                          
            width:180px;
            height:40px;
            position:relative;
            transform: rotate(-90deg);
            color:#fff;
            font-size: 12px;
            font-weight: 100;
            text-align: center;
            /* display: flex; */
            align-items: center;
            justify-content: center;
            font-family: 'Prompt', 'TH Sarabun New', Arial, sans-serif;
            font-size: 12px;
            margin-left:-70px; 
            top:-15px;
          }

       

           
          .vertical-text-company-eng {
             background-color:#797b7d;
            width:200px;
            height:45px;
            position:relative;
            transform: rotate(-90deg);
            color:#fff;
            font-size: 14px;
            font-weight: 100;
            text-align: center;
            margin-left:-86px;
            align-items: center;
            justify-content: center;
            font-family: 'Prompt',  Arial, sans-serif;
            letter-spacing: 5px;
            top: 62px;
                
          }

        
         .vertical-text-company-eng-text-eng{
            font-weight: 100;         
            transform: rotate(-90deg);   
            width:180px;
            right:-20px;
            color:#fff;
            font-size: 12px;
             margin-left:-75px;
             margin-top:10px;
         }


         .vertical-text-company-text-th{
            font-weight: 100;         
            transform: rotate(-90deg);   
            width:180px;
            right:-20px;
            color:#fff;
            font-size: 12px;
             margin-left:-62px;
             margin-top:-50px;
         }



         .vertical-text-company-thai {
                        background-color:transparent;
                   
            width:180px;
            height:40px;
            position:absolute;
            transform: rotate(-90deg);
            color:#fff;
            font-size: 12px;
            font-weight: 100;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Prompt', 'TH Sarabun New', Arial, sans-serif;
            font-size: 12px;
             left:-93px; 
            top:10px;
          }





            .page {
                width: 100%;
                min-height: 100vh;
                padding: 0;
                margin: 0;
                page-break-inside: avoid;
            }
            
            .page:not(:last-child) {
                page-break-after: always;
            }
            
            /* Header Section - Red like in image */
            .header {
                /* background: #dc3545; */
                color: white;
                padding: 15px 20px;
                position: relative;
            
               
                padding-top:-60px;
            }
            
          .company-name {
    font-size: 32px;
    font-weight: 900;
    margin-top:0px;
    font-family: 'Prompt', sans-serif;
    margin-bottom: -2px;
    letter-spacing: 2px;
    color: red;
    text-shadow: 0 0 1px red; /* ทำให้ดูหนาขึ้น */
}

            .company-subtitle {
                font-size: 12px;
                font-family: 'Prompt', sans-serif;
                font-weight: 500;
                margin-bottom: 8px;
                color:#171616;
               
            }
            
            .receipt-type {
                position: absolute;
                top: 5px;
                right: 20px;
                font-size: 14px;
                font-weight: 500;
                color:#171616;
                 margin-top: 10px;
                font-family: 'Prompt', sans-serif;
            }
            
            .page-number {
                position: absolute;
                top: 55px;
                right: 20px;
                font-size: 12px;
                color:#171616;
                font-weight: 400;
                margin-bottom: -20px;
                font-family: 'Prompt', sans-serif;
            }
            
            /* Contact Bar - Gray like in image */
            .contact-bar {
                background: #6c757d;
                color: white;
                padding: 0px 20px;
                font-size: 13px;
                padding-top:12px;
                text-align: center;
                font-family: 'Prompt', sans-serif;
                padding-bottom: 6px;
                z-index: 999 !important;
            }
               .header-red{
                margin-top:-10px;
                color: white;
                padding: 0px 20px;
                  padding-top:12px;
                  padding-bottom: 6px;
                font-size: 13px;
                text-align: center;
                font-family: 'Prompt', sans-serif;
                z-index: 999 !important;
            background: #ff050d;
          }
            /* Main Content Section */
            .main-content {
                display: flex;
                margin: 15px 20px;
                gap: 15px;
            }
            
            .left-section {
             
              width:62%;
          
              height: 170px;
              margin-right:-6px;
           
                
            }
            .right-section  {
                      flex: 1;
            padding-left:-10px;
         
            }
            .section-title {
                font-weight: 600;
                margin-bottom: 8px;
                font-size: 12px;
                font-family: 'Prompt', sans-serif;
                color: #171616;
                /* border-bottom: 1px solid #ddd; */
                padding-bottom: 4px;
                 /* background: #dbe3ea; */
                margin-top: -135px;
                padding-top:10px;
                padding-left: 160px;
                  
            }

            .section-title-bill-number{

                    font-weight: 600;
                margin-bottom: 8px;
                font-size: 14px;
                font-family: 'Prompt', sans-serif;
                color: #000000;
                /* border-bottom: 1px solid #ddd; */
                padding-bottom: 4px;
                 background: #dbe3ea;
                margin-top: -134px;
                padding-top:10px;
                padding:10;
                padding-left: 50px;
            }

            .section-title-company{
                             font-weight: 300;
                margin-bottom: 8px;
                font-size: 18px;
                font-family: 'Prompt', sans-serif;
                color: #333;
                /* border-bottom: 1px solid #ddd; */
                padding-bottom: 4px;
               /* background: #dbe3ea; */
                    margin-top: -50px;
                padding-top:10px;
                padding-left: 20px;
                          margin-left: 40px;

            }
            .section-title-company-sub {
                  font-size: 10px;
                  
                  padding-left: 60px;
                  margin-left: -10px;
                  font-weight:300;
                     
            }
            .phone-company-text{
                   font-size: 10px;
                  
                  padding-left: 60px;
                  margin-left: -10px;
                  font-weight:300;
            }
            .tax-number-company{
                      font-size: 10px;
                  
                  padding-left: 60px;
                  margin-left: -10px;
                  font-weight:300;
            }
            .info-row {
                display: flex;
                margin-bottom: 3px;
                font-size: 10px;
                align-items: flex-start;
                padding-left: 0px; 
               
            }
            
            .info-label {
                min-width: 80px;
                font-weight: 500;
                font-family: 'Prompt', sans-serif;
                color: #555;
              
              
            }
            .info-label-left{
                        min-width: 90px;
                font-weight: 500;
                font-family: 'Prompt', sans-serif;
                color: #555;
                /* margin-right: -52px; */

            }

            .info-label-left-texnumber {
                    margin-right: -1px;
            }
            .info-value {
                flex: 1;
                font-family: 'Prompt', sans-serif;
                color: #333;
                margin-left: -10px;
                font-size: 14px;
                font-weight: 300;
            }

            .info-value-company-detail{
                  font-family: 'Prompt', sans-serif;
                color: #333;
                   font-size: 10px;
                 font-weight: 300;

            }
             .info-label-left-phone,.info-label-left-name{
                 margin-right: 20px;
                 font-size: 12px;
                 font-weight: 300;
                 margin-left: 40px;
            }
            .info-label-left-address {
              
                   margin-left: 40px;
                 font-size: 12px;
                 font-weight: 300;
            }
            .info-label-left-texnumber{
                        font-size: 12px;
                   margin-right: 20px;
                    margin-left: 62px;
                    font-weight: 300;
            }
            .taxt-number-company {
                margin-right: 0px;
                 font-size: 12px;
                 font-weight: 300;
              
            }
            .info-value-company-detail-email-th {
                 margin-left: 50px;
                     font-size: 10px;
                 font-weight: 300;

            }
            .info-value-company-detail-sellers{
                  margin-left: 50px;
                     font-size: 10px;
                 font-weight: 300;
            }

            .info-value-company-detail-phone{
                  margin-left: -18px;
                     font-size: 10px;
                 font-weight: 300;
            }
            .info-value-company-detail-email {
                margin-left: 52px;
                   font-size: 10px;
                 font-weight: 300;
            }
            .info-value-company-detail-seller{
margin-left: -42px;
   font-size: 10px;
                 font-weight: 300;
            }


            .info-value-address {
                display: table-row;
           
                 margin-left: 146px;
                 margin-right: -320px;
                 width:200px;
            }

            .info-label, .info-value-address {
    display: table-cell;
    padding: 2px 5px;
    vertical-align: top;
}
            /* Table Section - Pink headers like in image */
            .items-table {
                width: calc(100% - 40px);
                /* margin: 15px 20px; */
                border-collapse: collapse;
                border: 1px solid #ddd;
                margin-top: -90px;
                margin-left: 41px;
            }
            
            .items-header {
                background: #dc8285;
                color: white;
            }
            
            .items-header th {
                
                z-index:999;
                text-align: center;
                font-weight: 500;
                font-size: 11px;
                font-family: 'Prompt', sans-serif;
                border-right: 1px solid rgba(255,255,255,0.3);
            }
            
            .items-header th:last-child {
                border-right: none;
            }
            




         














            .items-table td {
                padding: 8px;
                text-align: center;
                border-bottom: 1px solid #ddd;
                border-right: 1px solid #ddd;
                font-size: 12px;
                font-weight: 300;
                font-family: 'Prompt', sans-serif;
                color: #333;
            }
            
            .items-table td:last-child {
                border-right: none;
            }
            
            .item-description {
                text-align: left !important;
                font-family: 'Prompt', sans-serif;
            }
            
            .item-description * {
                list-style: none !important;
                margin: 0 !important;
                padding: 0 !important;
            }
            
            .product-description {
                font-size: 11px;
                color: #666;
                font-style: italic;
                font-family: 'Prompt', sans-serif;
                line-height: 1.4;
                margin-top: 3px;
                margin-bottom: 2px;
                display: block;
            }
            
            .product-description ul {
                margin: 0;
                padding: 0;
                list-style: none;
            }
            
            .product-description li {
                margin: 2px 0;
                padding: 0;
                list-style: none;
            }
            
            /* Notes Section - Left side horizontal  350px. , 160*/
            .products-services {
                position: fixed;
                bottom: 110px;
                left: 40px;
                width: 436px;
                height: 260px;
                padding: 12px;
                background: #f8f9fa;
                border: 1px solid #e9ecef;
                z-index: 1000;
            }
            
            .products-services h4 {
                margin-bottom: 8px;
                color: #333;
                font-size: 12px;
                font-weight: 600;
                font-family: 'Prompt', sans-serif;
            }
            
            .products-services p {
                font-size: 10px;
                line-height: 1.4;
                font-family: 'Prompt', sans-serif;
                color: #666;
                margin: 0;
            }

            /* Totals Section - Right side horizontal  defualt 160px*/
            .totals-section {
                position: fixed;
                bottom: 160px;
                right: 20px;
                width: 300px;
                z-index: 1000;
               
                 background-color: transparent;
            }
            
            .totals-table {
                width: 100%;
                background: white;
                border: 1px solid #ddd;
            }
            
            .total-row {
                display: flex;
                justify-content: space-between;
                padding: 6px 12px;
                border-bottom: 1px solid #eee;
                font-size: 10px;
            }
            
            .total-label {
                font-family: 'Prompt', sans-serif;
                color: #555;
                text-align: left;
            }
            
            .total-amount {
                font-family: 'Prompt', sans-serif;
                color: #333;
                font-weight: 500;
                text-align: right;
            }
            
            .grand-total {
                background: #f8f9fa;
                font-weight: 700;
                font-size: 14px;
                border-top: 2px solid #dc3545;
                color: #333;
            }
            
            .grand-total .total-amount {
                font-size: 18px;
                font-weight: 700;
            }
            
            /* Approval Section - Red button like in image */
            .approve-section {
                position: fixed;
                bottom: 110px;
                /* left: 20px; */
                right: 20px;
                display: flex;
                justify-content: center;
                text-align: center;
                background: #dc3545;
                color: white;
                width: 39%;
                padding: 15px;
                font-weight: 600;
                font-family: 'Prompt', sans-serif;
                font-size: 14px;
                z-index: 999;
                // border-radius: 4px;
            }
            
            /* Footer Section */
            .footer {
                position: fixed;
                bottom: 0px;
                left: 20px;
                right: 20px;
                display: flex;
                justify-content: space-between;
                font-size: 10px;
                border-top: 1px solid #000000;
                padding-top: 10px;
                padding-bottom: 10px;
                background-color: white;
                z-index: 998;
            }
            
            .payment-info, .collector-info {
                flex: 1;
                font-family: 'Prompt', sans-serif;
            }
            
            .collector-info {
                text-align: right;
            }
            
            .checkbox-group {
                display: flex;
                gap: 15px;
                margin-top: 8px;
                flex-wrap: wrap;
            }
            
            .checkbox-item {
                display: flex;
                align-items: center;
                gap: 5px;
                font-size: 10px;
            }
            
            .checkbox {
                width: 12px;
                height: 12px;
                border: 1px solid #333;
                display: inline-block;
            }
            
            /* Thai text specific styling */
            .thai-text {
                font-family: 'Prompt', 'TH Sarabun New', sans-serif !important;
            }
            
            .english-text {
                font-family: Arial, sans-serif;
            }
            
            @media print {
                .page {
                    page-break-after: always;
                }
                .page:last-child {
                    page-break-after: avoid;
                }
                body {
                    -webkit-print-color-adjust: exact;
                    print-color-adjust: exact;
                }
            }
        </style>
    </head>
    <body>
        ${content}
    </body>
    </html>
    `;
}

// Health check endpoint
app.get('/health', (req, res) => {
    res.json({ status: 'OK', message: 'PDF Generator Service is running' });
});

// Database test endpoint
app.get('/test-db', async (req, res) => {
    try {
        const connection = await mysql.createConnection(dbConfig);
        const [rows] = await connection.execute('SELECT COUNT(*) as count FROM transactions');
        await connection.end();

        res.json({
            status: 'OK',
            message: 'Database connection successful',
            total_transactions: rows[0].count,
        });
    } catch (error) {
        console.error('Database test error:', error);
        res.status(500).json({
            status: 'ERROR',
            message: 'Database connection failed',
            error: error.message,
        });
    }
});

// Test endpoint to return HTML for debugging
app.get('/test-html/:id', async (req, res) => {
    const quotationId = req.params.id;

    try {
        const connection = await mysql.createConnection(dbConfig);

        // Get quotation data
        const [quotationRows] = await connection.execute(
            `
            SELECT 
                t.*,
                c.name as customer_name,
                c.mobile as customer_mobile,
                c.email as customer_email,
                c.address_line_1,
                c.address_line_2,
                c.city,
                c.state,
                c.country,
                c.zip_code,
                c.supplier_business_name,
                c.contact_id as customer_contact_id,
                c.tax_number as customer_tax_number,
                bl.name as location_name,
                bl.landmark,
                bl.city as location_city,
                bl.state as location_state,
                bl.country as location_country,
                bl.zip_code as location_zip,
                bl.mobile as location_mobile,
                bl.email as location_email,
                b.name as business_name,
                u.first_name as seller_first_name,
                u.last_name as seller_last_name,
                u.username as seller_username
            FROM transactions t
            LEFT JOIN contacts c ON t.contact_id = c.id
            LEFT JOIN business_locations bl ON t.location_id = bl.id
            LEFT JOIN business b ON bl.business_id = b.id
            LEFT JOIN users u ON t.created_by = u.id
            WHERE t.id = ? AND t.type IN ('sell', 'quotation', 'draft')
        `,
            [quotationId]
        );

        if (quotationRows.length === 0) {
            await connection.end();
            return res.status(404).json({ error: 'Quotation not found' });
        }

        const quotation = quotationRows[0];

        // Get quotation line items
        const [lineItems] = await connection.execute(
            `
            SELECT 
                tsl.*,
                p.name as product_name,
                p.product_description,
                p.sku,
                v.name as variation_name,
                u.short_name as unit_name
            FROM transaction_sell_lines tsl
            LEFT JOIN products p ON tsl.product_id = p.id
            LEFT JOIN variations v ON tsl.variation_id = v.id
            LEFT JOIN units u ON p.unit_id = u.id
            WHERE tsl.transaction_id = ? AND tsl.parent_sell_line_id IS NULL
            ORDER BY tsl.id
        `,
            [quotationId]
        );

        await connection.end();

        // Generate HTML
        const html = generateQuotationHTML(quotation, lineItems);

        res.setHeader('Content-Type', 'text/html');
        res.send(html);
    } catch (error) {
        console.error('Error generating HTML:', error);
        res.status(500).json({ error: 'Failed to generate HTML' });
    }
});

// Debug endpoint to see generated HTML
app.post('/debug-html/:id', async (req, res) => {
    const quotationId = req.params.id;

    try {
        const connection = await mysql.createConnection(dbConfig);

        // Get quotation data
        const [quotationRows] = await connection.execute(
            `
            SELECT 
                t.*,
                c.name as customer_name,
                c.mobile as customer_mobile,
                c.email as customer_email,
                c.address_line_1,
                c.address_line_2,
                c.city,
                c.state,
                c.country,
                c.zip_code,
                c.supplier_business_name,
                c.contact_id as customer_contact_id,
                c.tax_number as customer_tax_number,
                bl.name as location_name,
                bl.landmark,
                bl.city as location_city,
                bl.state as location_state,
                bl.country as location_country,
                bl.zip_code as location_zip,
                bl.mobile as location_mobile,
                bl.email as location_email,
                b.name as business_name,
                u.first_name as seller_first_name,
                u.last_name as seller_last_name,
                u.username as seller_username
            FROM transactions t
            LEFT JOIN contacts c ON t.contact_id = c.id
            LEFT JOIN business_locations bl ON t.location_id = bl.id
            LEFT JOIN business b ON bl.business_id = b.id
            LEFT JOIN users u ON t.created_by = u.id
            WHERE t.id = ? AND t.type IN ('sell', 'quotation', 'draft')
        `,
            [quotationId]
        );

        if (quotationRows.length === 0) {
            await connection.end();
            return res.status(404).json({ error: 'Quotation not found' });
        }

        const quotation = quotationRows[0];

        // Get quotation line items (only main products, not sub-products)
        const [lineItems] = await connection.execute(
            `
            SELECT 
                tsl.*,
                p.name as product_name,
                p.product_description,
                p.sku,
                v.name as variation_name,
                u.short_name as unit_name
            FROM transaction_sell_lines tsl
            LEFT JOIN products p ON tsl.product_id = p.id
            LEFT JOIN variations v ON tsl.variation_id = v.id
            LEFT JOIN units u ON p.unit_id = u.id
            WHERE tsl.transaction_id = ? AND tsl.parent_sell_line_id IS NULL
            ORDER BY tsl.id
        `,
            [quotationId]
        );

        await connection.end();

        // Generate HTML
        const html = generateQuotationHTML(quotation, lineItems);

        res.setHeader('Content-Type', 'text/html');
        res.send(html);
    } catch (error) {
        console.error('Error generating HTML:', error);
        res.status(500).json({ error: 'Failed to generate HTML' });
    }
});

// Start server
app.listen(PORT, () => {
    console.log(`PDF Generator Server running on http://localhost:${PORT}`);
    console.log(`Health check: http://localhost:${PORT}/health`);
    console.log(`Generate PDF: POST http://localhost:${PORT}/generate-quotation-pdf/:id`);
});

module.exports = app;
