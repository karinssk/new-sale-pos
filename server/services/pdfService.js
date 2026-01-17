const fs = require('fs');
const puppeteer = require('puppeteer');

// Prefer a stable local Chrome build when available to avoid crashes with the bundled Chromium on macOS Sequoia
function resolveChromePath() {
    if (process.env.CHROME_BIN && fs.existsSync(process.env.CHROME_BIN)) {
        return process.env.CHROME_BIN;
    }

    const candidates = [];

    if (process.platform === 'darwin') {
        candidates.push(
            '/Applications/Google Chrome.app/Contents/MacOS/Google Chrome',
            '/Applications/Google Chrome for Testing.app/Contents/MacOS/Google Chrome for Testing'
        );
    } else if (process.platform === 'win32') {
        candidates.push(
            'C:\\Program Files\\Google\\Chrome\\Application\\chrome.exe',
            'C:\\Program Files (x86)\\Google\\Chrome\\Application\\chrome.exe'
        );
    } else {
        candidates.push(
            '/usr/bin/google-chrome',
            '/usr/bin/chromium-browser',
            '/usr/bin/chromium'
        );
    }

    return candidates.find((candidate) => fs.existsSync(candidate)) || null;
}

class PDFService {
    static async generatePDF(html) {
        let browser;
        
        try {
            const executablePath = resolveChromePath();

            // Enhanced browser launch options for server deployment
            const launchOptions = {
                headless: "new", // Use new headless mode
                args: [
                    '--no-sandbox',
                    '--disable-setuid-sandbox',
                    '--disable-dev-shm-usage',
                    '--disable-accelerated-2d-canvas',
                    '--no-first-run',
                    '--no-zygote',
                    '--single-process',
                    '--disable-gpu',
                    '--disable-web-security',
                    '--disable-features=VizDisplayCompositor'
                ],
                executablePath: executablePath || undefined
            };

            browser = await puppeteer.launch(launchOptions);
            
            const page = await browser.newPage();
            
            // Set viewport for consistent rendering
            await page.setViewport({ width: 1200, height: 800 });
            
            await page.setContent(html, { 
                waitUntil: 'networkidle0',
                timeout: 30000 
            });

            const pdf = await page.pdf({
                format: 'A4',
                printBackground: true,
                margin: {
                    top: '0mm',
                    right: '0mm',
                    bottom: '0mm',
                    left: '0mm',
                },
                timeout: 30000
            });

            return pdf;
        } catch (error) {
            console.error('PDF generation error:', error);
            throw error;
        } finally {
            if (browser) {
                await browser.close();
            }
        }
    }
}

module.exports = PDFService;
