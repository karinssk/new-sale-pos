<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Dompdf\Dompdf;
use Dompdf\Options;

class LoadThaiFont extends Command
{
    protected $signature = 'font:load-thai';
    protected $description = 'Load Thai fonts for DomPDF';

    public function handle()
    {
        $fontDir = storage_path('fonts/');
        
        // Create options instance
        $options = new Options();
        $options->set('fontDir', $fontDir);
        $options->set('fontCache', $fontDir);
        $options->set('isRemoteEnabled', true);
        
        // Create Dompdf instance
        $dompdf = new Dompdf($options);
        
        // Get font metrics
        $fontMetrics = $dompdf->getFontMetrics();
        
        try {
            // Register normal font
            $fontMetrics->registerFont([
                'family' => 'THSarabunNew',
                'style' => 'normal',
                'weight' => 'normal'
            ], $fontDir . 'THSarabunNew.ttf');
            
            // Register bold font
            $fontMetrics->registerFont([
                'family' => 'THSarabunNew',
                'style' => 'normal', 
                'weight' => 'bold'
            ], $fontDir . 'THSarabunNew-Bold.ttf');
            
            $this->info('Thai fonts loaded successfully!');
            
        } catch (\Exception $e) {
            $this->error('Failed to load fonts: ' . $e->getMessage());
        }
    }
}
