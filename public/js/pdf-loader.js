/**
 * PDF Loader with Progress Bar
 * Enhanced PDF generation with loading screen and progress indication
 */

class PDFLoader {
    constructor() {
        this.isLoading = false;
        this.currentProgress = 0;
        this.progressInterval = null;
        this.init();
    }

    init() {
        // Create loading modal HTML
        this.createLoadingModal();
        // Bind events
        this.bindEvents();
    }

    createLoadingModal() {
        const modalHTML = `
            <div id="pdf-loading-modal" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-body text-center" style="padding: 40px;">
                            <div class="pdf-loader-container">
                                <div class="pdf-icon-container mb-3">
                                    <i class="fas fa-file-pdf" style="font-size: 48px; color: #e74c3c;"></i>
                                </div>
                                <h4 class="mb-3" style="color: #333;">กำลังสร้างไฟล์ PDF</h4>
                                <p class="text-muted mb-4">กรุณารอสักครู่...</p>
                                
                                <div class="progress mb-3" style="height: 8px;">
                                    <div id="pdf-progress-bar" class="progress-bar progress-bar-striped progress-bar-animated" 
                                         role="progressbar" style="width: 0%; background-color: #e74c3c;" 
                                         aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                                    </div>
                                </div>
                                
                                <div class="progress-text">
                                    <span id="progress-percentage">0%</span>
                                    <span id="progress-status" class="text-muted ml-2">เริ่มต้น...</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;

        // Remove existing modal if any
        $('#pdf-loading-modal').remove();
        
        // Add modal to body
        $('body').append(modalHTML);
    }

    bindEvents() {
        // Intercept PDF print clicks
        $(document).on('click', 'a.pdf-print-btn, a[href*="pdf-print"]', (e) => {
            e.preventDefault();
            const url = $(e.currentTarget).attr('href');
            this.generatePDF(url);
        });
    }

    async generatePDF(url) {
        if (this.isLoading) return;

        try {
            this.showLoading();
            
            // Extract transaction ID and document type from URL
            let matches = url.match(/\/(quotations|tax-invoice|billing-receipt)\/(\d+)\/pdf-print/);
            if (!matches) {
                throw new Error('Invalid URL format');
            }
            
            const documentType = matches[1];
            const transactionId = matches[2];
            
            // Determine the correct Node.js endpoint
            let nodeJsUrl;
            let filename;
            
            // Get base URL from current location (handles subdirectory installations)
            const getBaseUrl = () => {
                // Try to get base URL from Laravel's app_url meta tag if available
                const appUrlMeta = document.querySelector('meta[name="app-url"]');
                if (appUrlMeta) {
                    return appUrlMeta.getAttribute('content').replace(/\/$/, '');
                }
                
                // Fallback: extract base path from current URL
                // This handles URLs like /migration-projects/sale-pos-new-version/public/
                const path = window.location.pathname;
                const publicIndex = path.indexOf('/public/');
                if (publicIndex !== -1) {
                    return path.substring(0, publicIndex + 7); // include '/public'
                }
                
                // If no /public/ found, try to extract from known patterns
                const sellsIndex = path.indexOf('/sells/');
                if (sellsIndex !== -1) {
                    return path.substring(0, sellsIndex);
                }
                
                // Last resort: assume root
                return '';
            };
            
            const baseUrl = getBaseUrl();
            
            // Use new authenticated routes directly
            this.setProgress(15, 'เตรียมสร้าง PDF...');
            
            switch (documentType) {
                case 'quotations':
                    nodeJsUrl = `${baseUrl}/quotations/${transactionId}/pdf-print-nodejs`;
                    filename = `quotation-${transactionId}.pdf`;
                    break;
                case 'tax-invoice':
                    nodeJsUrl = `${baseUrl}/tax-invoice/${transactionId}/pdf-print-nodejs`;
                    filename = `tax-invoice-${transactionId}.pdf`;
                    break;
                case 'billing-receipt':
                    nodeJsUrl = `${baseUrl}/billing-receipt/${transactionId}/pdf-print-nodejs`;
                    filename = `billing-receipt-${transactionId}.pdf`;
                    break;
                default:
                    throw new Error('Unsupported document type');
            }
            
            // Start progress simulation
            this.startProgressSimulation();
            
            // Add a small delay to show initial progress
            await new Promise(resolve => setTimeout(resolve, 500));
            
            // Make request to Node.js service
            const response = await fetch(nodeJsUrl, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json', // Request JSON response for custom URLs
                }
            });

            if (!response.ok) {
                // Check if it's a 404 (transaction not found)
                if (response.status === 404) {
                    throw new Error('ไม่พบเอกสารนี้ในระบบ');
                }
                throw new Error(`เกิดข้อผิดพลาดจากเซิร์ฟเวอร์ (${response.status})`);
            }

            // Complete progress
            this.setProgress(100, 'เสร็จสิ้น');
            
            // Check if response is JSON (new custom URL format) or PDF (old format)
            const contentType = response.headers.get('content-type');
            
            if (contentType && contentType.includes('application/json')) {
                // New format - get custom URL
                const result = await response.json();
                
                if (result.success && result.pdf_url) {
                    // Open custom URL directly
                    const newWindow = window.open(result.pdf_url, '_blank');
                    
                    if (newWindow) {
                        // Show success message
                        const documentTitle = result.filename.replace('.pdf', '').toUpperCase();
                        if (typeof toastr !== 'undefined') {
                            toastr.success(`เปิด PDF สำเร็จ: ${documentTitle}`);
                        }
                    } else {
                        // Popup blocked - redirect to URL
                        window.location.href = result.pdf_url;
                    }
                } else {
                    throw new Error('ไม่สามารถสร้าง PDF URL ได้');
                }
            } else {
                // Old format - handle as blob (fallback)
                const blob = await response.blob();
                
                // Get filename from response headers
                const suggestedFilename = response.headers.get('X-Suggested-Filename');
                const finalFilename = suggestedFilename || filename;
                
                // Verify it's actually a PDF
                if (blob.type !== 'application/pdf' && !blob.type.includes('pdf')) {
                    throw new Error('ไฟล์ที่ได้รับไม่ใช่ PDF');
                }
                
                // Create object URL and open in new tab
                const pdfUrl = URL.createObjectURL(blob);
                
                // Open in new tab with custom title based on filename
                const newWindow = window.open('', '_blank');
                
                if (newWindow) {
                    // Set custom title for the new window
                    const documentTitle = finalFilename.replace('.pdf', '').toUpperCase();
                    newWindow.document.title = `PDF: ${documentTitle}`;
                    
                    // Navigate to the blob URL
                    newWindow.location.href = pdfUrl;
                    
                    // Show success message
                    if (typeof toastr !== 'undefined') {
                        toastr.success(`เปิด PDF สำเร็จ: ${documentTitle}`);
                    }
                } else {
                    // Check if popup was blocked - fallback to download
                    const link = document.createElement('a');
                    link.href = pdfUrl;
                    link.download = finalFilename;
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                    
                    // Show download message
                    if (typeof toastr !== 'undefined') {
                        toastr.success('ไฟล์ PDF ถูกดาวน์โหลดแล้ว');
                    }
                }
                
                // Clean up
                setTimeout(() => {
                    URL.revokeObjectURL(pdfUrl);
                }, 1000);
            }

        } catch (error) {
            console.error('PDF generation error:', error);
            this.showError(error.message || 'เกิดข้อผิดพลาดในการสร้าง PDF กรุณาลองใหม่อีกครั้ง');
        } finally {
            setTimeout(() => {
                this.hideLoading();
            }, 500);
        }
    }

    showLoading() {
        this.isLoading = true;
        this.currentProgress = 0;
        $('#pdf-loading-modal').modal('show');
        this.setProgress(0, 'เริ่มต้น...');
    }

    hideLoading() {
        this.isLoading = false;
        this.currentProgress = 0;
        if (this.progressInterval) {
            clearInterval(this.progressInterval);
            this.progressInterval = null;
        }
        $('#pdf-loading-modal').modal('hide');
    }

    startProgressSimulation() {
        const stages = [
            { progress: 10, status: 'เชื่อมต่อเซิร์ฟเวอร์...' },
            { progress: 25, status: 'ดึงข้อมูลใบเสนอราคา...' },
            { progress: 45, status: 'สร้างเทมเพลต HTML...' },
            { progress: 65, status: 'แปลงเป็น PDF...' },
            { progress: 85, status: 'ปรับแต่งรูปแบบ...' },
            { progress: 95, status: 'เกือบเสร็จแล้ว...' }
        ];

        let currentStage = 0;
        
        this.progressInterval = setInterval(() => {
            if (currentStage < stages.length && this.currentProgress < 95) {
                const stage = stages[currentStage];
                if (this.currentProgress < stage.progress) {
                    this.currentProgress += 1;
                    this.setProgress(this.currentProgress, stage.status);
                } else {
                    currentStage++;
                }
            }
        }, 100);
    }

    setProgress(percentage, status) {
        this.currentProgress = percentage;
        $('#pdf-progress-bar').css('width', percentage + '%').attr('aria-valuenow', percentage);
        $('#progress-percentage').text(percentage + '%');
        $('#progress-status').text(status);
    }

    showError(message) {
        // Hide loading modal
        $('#pdf-loading-modal').modal('hide');
        
        // Show error using toastr or alert
        if (typeof toastr !== 'undefined') {
            toastr.error(message);
        } else {
            alert(message);
        }
    }
}

// Initialize PDF Loader when document is ready
$(document).ready(function() {
    window.pdfLoader = new PDFLoader();
});