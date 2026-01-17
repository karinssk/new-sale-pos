<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class MigrationUpdateController extends Controller
{
    /**
     * Old POS database configuration
     */
    private $oldDbConfig = [
        'host' => '127.0.0.1',
        'port' => '8889',
        'database' => 'sale_pos',  // Old CodeIgniter database
        'username' => 'root',
        'password' => 'root',
    ];

    /**
     * User ID mapping from old POS to new POS
     */
    private $userIdMapping = [
        4 => 3,  // nui.rubyshop (old) -> rungarun.ruby@gmail.com (new)
        5 => 18, // lek-rubyshop (old) -> arocha598@gmail.com (new)
    ];

    /**
     * Map old user ID to new user ID
     * Returns mapped ID or fallback to 1 if not found
     */
    private function mapUserId($oldUserId)
    {
        return $this->userIdMapping[$oldUserId] ?? 1;
    }

    /**
     * Show migration update page
     */
    public function index()
    {
        return view('migrate-update-data.index');
    }

    /**
     * Run migration with real-time streaming output
     */
    public function runMigration(Request $request)
    {
        // Set unlimited execution time and increase memory
        set_time_limit(0);
        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time', '0');

        // Disable output buffering for real-time streaming
        if (ob_get_level()) {
            ob_end_clean();
        }

        // Set headers for SSE (Server-Sent Events)
        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');
        header('Connection: keep-alive');
        header('X-Accel-Buffering: no'); // Disable nginx buffering

        // Send initial keep-alive comment
        echo ": ping\n\n";
        flush();

        // Function to send log message to browser
        $sendLog = function($message, $type = 'info') {
            $data = json_encode([
                'message' => $message,
                'type' => $type,
                'timestamp' => date('Y-m-d H:i:s')
            ]);
            echo "data: {$data}\n\n";

            // Send keep-alive ping every few messages
            if (rand(1, 5) == 1) {
                echo ": ping\n\n";
            }

            flush();

            // Prevent Apache/PHP timeout
            if (connection_status() != CONNECTION_NORMAL) {
                exit;
            }
        };

        try {
            $sendLog('[START] Starting migration process...', 'info');
            $sendLog('Connecting to old POS database...', 'info');

            // Connect to old database
            config(['database.connections.old_pos' => [
                'driver' => 'mysql',
                'host' => $this->oldDbConfig['host'],
                'port' => $this->oldDbConfig['port'],
                'database' => $this->oldDbConfig['database'],
                'username' => $this->oldDbConfig['username'],
                'password' => $this->oldDbConfig['password'],
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
            ]]);

            DB::purge('old_pos');

            $sendLog('[OK] Connected to old POS database', 'success');

            // Get business_id from session
            $business_id = session('user.business_id', 1);
            $sendLog("Using business_id: {$business_id}", 'info');

            // Step 1: Migrate Contacts
            $sendLog('[STEP 1] Migrating Contacts...', 'info');
            $contactsCount = $this->migrateContacts($business_id, $sendLog);
            $sendLog("[OK] Migrated {$contactsCount} contacts", 'success');

            // Step 2: Migrate Products
            $sendLog('[STEP 2] Migrating Products...', 'info');
            $productsCount = $this->migrateProducts($business_id, $sendLog);
            $sendLog("[OK] Migrated {$productsCount} products", 'success');

            // Step 3: Migrate Sales Transactions (VT)
            $sendLog('[STEP 3] Migrating Sales (VT)...', 'info');
            $vtCount = $this->migrateSales($business_id, $sendLog);
            $sendLog("[OK] Migrated {$vtCount} sales transactions", 'success');

            // Step 4: Migrate IPAY Transactions
            $sendLog('[STEP 4] Migrating IPAY Transactions...', 'info');
            $ipayCount = $this->migrateIPAY($business_id, $sendLog);
            $sendLog("[OK] Migrated {$ipayCount} IPAY transactions", 'success');

            // Step 5: Link VT and IPAY Relationships
            $sendLog('[STEP 5] Linking VT <-> IPAY Relationships...', 'info');
            $linkedCount = $this->linkVTandIPAY($business_id, $sendLog);
            $sendLog("[OK] Linked {$linkedCount} VT-IPAY pairs", 'success');

            // Step 6: Migrate Payments
            $sendLog('[STEP 6] Migrating Payments...', 'info');
            $paymentsCount = $this->migratePayments($business_id, $sendLog);
            $sendLog("[OK] Migrated {$paymentsCount} payments", 'success');

            // Summary
            $sendLog('', 'info');
            $sendLog('═══════════════════════════════════════', 'info');
            $sendLog('[SUCCESS] MIGRATION COMPLETED SUCCESSFULLY!', 'success');
            $sendLog('═══════════════════════════════════════', 'info');
            $sendLog("Total Contacts: {$contactsCount}", 'success');
            $sendLog("Total Products: {$productsCount}", 'success');
            $sendLog("Total Sales (VT): {$vtCount}", 'success');
            $sendLog("Total IPAY: {$ipayCount}", 'success');
            $sendLog("Total Linked Pairs: {$linkedCount}", 'success');
            $sendLog("Total Payments: {$paymentsCount}", 'success');
            $sendLog('═══════════════════════════════════════', 'info');

            echo "data: " . json_encode(['message' => 'DONE', 'type' => 'done']) . "\n\n";
            flush();

        } catch (Exception $e) {
            $sendLog('[ERROR] ' . $e->getMessage(), 'error');
            $sendLog('Stack trace: ' . $e->getTraceAsString(), 'error');
            echo "data: " . json_encode(['message' => 'ERROR', 'type' => 'error']) . "\n\n";
            flush();
        }

        exit;
    }

    /**
     * Migrate contacts from old POS
     */
    private function migrateContacts($business_id, $sendLog)
    {
        $sendLog('Fetching contacts from old database...', 'info');

        $total = DB::connection('old_pos')->table('sma_companies')->count();
        $sendLog("Found {$total} contacts to migrate", 'info');

        $count = 0;
        $processed = 0;
        $chunkSize = 200;

        DB::connection('old_pos')
            ->table('sma_companies')
            ->orderBy('id')
            ->chunk($chunkSize, function($oldContacts) use ($business_id, $sendLog, &$count, &$processed, $total) {
                foreach ($oldContacts as $oldContact) {
                    // Check if contact already exists
                    $exists = DB::table('contacts')
                        ->where('business_id', $business_id)
                        ->where('name', $oldContact->name)
                        ->exists();

                    if (!$exists) {
                        // Default to user ID 1 for contacts (no created_by in old table)
                        DB::table('contacts')->insert([
                            'business_id' => $business_id,
                            'type' => 'customer',
                            'name' => $oldContact->name,
                            'email' => $oldContact->email ?? null,
                            'mobile' => $oldContact->phone ?? null,
                            'address_line_1' => $oldContact->address ?? null,
                            'city' => $oldContact->city ?? null,
                            'state' => $oldContact->state ?? null,
                            'country' => $oldContact->country ?? null,
                            'zip_code' => $oldContact->postal_code ?? null,
                            'tax_number' => $oldContact->vat_no ?? null,
                            'created_by' => 1,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                        $count++;
                    }
                    $processed++;
                }

                $sendLog("Processed {$processed}/{$total} contacts ({$count} new)", 'info');
            });

        return $count;
    }

    /**
     * Migrate products from old POS
     */
    private function migrateProducts($business_id, $sendLog)
    {
        $sendLog('Fetching products from old database...', 'info');

        $total = DB::connection('old_pos')->table('sma_products')->count();
        $sendLog("Found {$total} products to migrate", 'info');

        $count = 0;
        $processed = 0;
        $chunkSize = 200;

        DB::connection('old_pos')
            ->table('sma_products')
            ->orderBy('id')
            ->chunk($chunkSize, function($oldProducts) use ($business_id, $sendLog, &$count, &$processed, $total) {
                foreach ($oldProducts as $oldProduct) {
                    // Check if product already exists
                    $exists = DB::table('products')
                        ->where('business_id', $business_id)
                        ->where('name', $oldProduct->name)
                        ->exists();

                    if (!$exists) {
                        // Default to user ID 1 for products (no created_by in old table)
                        $productId = DB::table('products')->insertGetId([
                            'name' => $oldProduct->name,
                            'business_id' => $business_id,
                            'type' => 'single',
                            'sku' => $oldProduct->code ?? 'SKU-' . time() . rand(1000, 9999),
                            'barcode_type' => 'C128',
                            'alert_quantity' => $oldProduct->alert_quantity ?? 10,
                            'created_by' => 1,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);

                        // Create variation for the product
                        DB::table('variations')->insert([
                            'name' => 'DUMMY',
                            'product_id' => $productId,
                            'sub_sku' => $oldProduct->code ?? 'SKU-' . time(),
                            'default_purchase_price' => $oldProduct->cost ?? 0,
                            'default_sell_price' => $oldProduct->price ?? 0,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);

                        $count++;
                    }
                    $processed++;
                }

                $sendLog("Processed {$processed}/{$total} products ({$count} new)", 'info');
            });

        return $count;
    }

    /**
     * Migrate sales transactions (VT)
     */
    private function migrateSales($business_id, $sendLog)
    {
        $sendLog('Fetching sales from old database...', 'info');

        $total = DB::connection('old_pos')
            ->table('sma_sales')
            ->where('sale_status', 'completed')
            ->count();
        $sendLog("Found {$total} sales to migrate", 'info');

        $count = 0;
        $processed = 0;
        $chunkSize = 100;

        DB::connection('old_pos')
            ->table('sma_sales')
            ->where('sale_status', 'completed')
            ->orderBy('id')
            ->chunk($chunkSize, function($oldSales) use ($business_id, $sendLog, &$count, &$processed, $total) {
                foreach ($oldSales as $oldSale) {
                    // Check if sale already migrated
                    $exists = DB::table('transactions')
                        ->where('business_id', $business_id)
                        ->where('invoice_no', $oldSale->reference_no)
                        ->exists();

                    if (!$exists) {
                        // Find or create contact
                        $contact = DB::table('contacts')
                            ->where('business_id', $business_id)
                            ->where('name', 'LIKE', '%' . ($oldSale->customer ?? 'Walk-in Customer') . '%')
                            ->first();

                        if (!$contact) {
                            $contactId = DB::table('contacts')->insertGetId([
                                'business_id' => $business_id,
                                'type' => 'customer',
                                'name' => $oldSale->customer ?? 'Walk-in Customer',
                                'created_by' => 1,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
                        } else {
                            $contactId = $contact->id;
                        }

                        // Map the old user ID to new user ID
                        $mappedUserId = $this->mapUserId($oldSale->created_by ?? null);

                        // Insert transaction
                        DB::table('transactions')->insert([
                            'business_id' => $business_id,
                            'location_id' => 1,
                            'type' => 'sell',
                            'status' => 'final',
                            'sub_status' => 'proforma',
                            'invoice_no' => $oldSale->reference_no,
                            'ref_no' => $oldSale->reference_no,
                            'contact_id' => $contactId,
                            'transaction_date' => $oldSale->date ?? now(),
                            'total_before_tax' => $oldSale->total ?? 0,
                            'tax_amount' => $oldSale->tax ?? 0,
                            'final_total' => $oldSale->grand_total ?? 0,
                            'payment_status' => 'paid',
                            'created_by' => $mappedUserId,
                            'created_at' => $oldSale->date ?? now(),
                            'updated_at' => now(),
                        ]);

                        $count++;
                    }
                    $processed++;
                }

                $percentage = round(($processed / $total) * 100, 1);
                $sendLog("Processed {$processed}/{$total} sales ({$count} new) - {$percentage}%", 'info');
            });

        return $count;
    }

    /**
     * Migrate IPAY transactions
     * In old database: sma_payments has sale_id that links to sma_sales.id
     * sma_payments.reference_no = IPAY number (e.g., IPAY2025/10452)
     * sma_sales.reference_no = VT number (e.g., VT2025/1362)
     */
    private function migrateIPAY($business_id, $sendLog)
    {
        $sendLog('Fetching IPAY from old database (joining payments with sales)...', 'info');

        // Count IPAY records (payments with IPAY reference_no)
        $total = DB::connection('old_pos')
            ->table('sma_payments')
            ->where('reference_no', 'LIKE', 'IPAY%')
            ->count();
        $sendLog("Found {$total} IPAY payment records to process", 'info');

        $count = 0;
        $skipped = 0;
        $processed = 0;
        $chunkSize = 200;

        // Join sma_payments with sma_sales to get the VT reference
        DB::connection('old_pos')
            ->table('sma_payments as p')
            ->leftJoin('sma_sales as s', 'p.sale_id', '=', 's.id')
            ->select(
                'p.id as payment_id',
                'p.reference_no as ipay_number',
                'p.sale_id',
                'p.date as payment_date',
                'p.amount',
                'p.paid_by',
                'p.created_by as payment_created_by',
                's.reference_no as vt_number',
                's.customer_id',
                's.grand_total'
            )
            ->where('p.reference_no', 'LIKE', 'IPAY%')
            ->orderBy('p.id')
            ->chunk($chunkSize, function($oldPayments) use ($business_id, $sendLog, &$count, &$skipped, &$processed, $total) {
                foreach ($oldPayments as $oldPayment) {
                    // Check if IPAY already migrated by invoice_no
                    $exists = DB::table('transactions')
                        ->where('business_id', $business_id)
                        ->where('invoice_no', $oldPayment->ipay_number)
                        ->exists();

                    if ($exists) {
                        $skipped++;
                        $processed++;
                        continue;
                    }

                    // Skip if no VT number linked
                    if (empty($oldPayment->vt_number)) {
                        $skipped++;
                        $processed++;
                        continue;
                    }

                    // Find related VT in new database
                    $vtTransaction = DB::table('transactions')
                        ->where('business_id', $business_id)
                        ->where('invoice_no', $oldPayment->vt_number)
                        ->first();

                    if ($vtTransaction) {
                        // Create IPAY transaction with proper linking
                        $ipayId = DB::table('transactions')->insertGetId([
                            'business_id' => $business_id,
                            'location_id' => $vtTransaction->location_id,
                            'type' => 'sell',
                            'status' => 'final',
                            'sub_status' => null,
                            'invoice_no' => $oldPayment->ipay_number,
                            'ref_no' => $oldPayment->vt_number, // Store VT number as ref for linking
                            'contact_id' => $vtTransaction->contact_id,
                            'transaction_date' => $oldPayment->payment_date ?? now(),
                            'total_before_tax' => $vtTransaction->total_before_tax,
                            'tax_amount' => $vtTransaction->tax_amount,
                            'final_total' => $oldPayment->amount ?? $vtTransaction->final_total,
                            'payment_status' => 'paid',
                            // Use transfer_parent_id for migrated data linking (matches existing migration)
                            'transfer_parent_id' => $vtTransaction->id,
                            'created_by' => $vtTransaction->created_by ?? 1,
                            'created_at' => $oldPayment->payment_date ?? now(),
                            'updated_at' => now(),
                        ]);

                        // Also link VT to IPAY (bidirectional - for new POS style linking)
                        DB::table('transactions')
                            ->where('id', $vtTransaction->id)
                            ->update(['linked_billing_receive_id' => $ipayId]);

                        $count++;
                    } else {
                        $skipped++;
                    }
                    $processed++;
                }

                $percentage = $total > 0 ? round(($processed / $total) * 100, 1) : 0;
                $sendLog("Processed {$processed}/{$total} IPAY records ({$count} created, {$skipped} skipped) - {$percentage}%", 'info');
            });

        return $count;
    }

    /**
     * Link VT and IPAY relationships
     * Supports multiple linking strategies:
     * 1. IPAY.ref_no = VT.invoice_no (from migrateIPAY)
     * 2. IPAY.transfer_parent_id = VT.id (from existing migration scripts)
     */
    private function linkVTandIPAY($business_id, $sendLog)
    {
        $sendLog('Finding unlinked VT-IPAY pairs...', 'info');

        // Find all VT transactions without linked IPAY
        $total = DB::table('transactions')
            ->where('business_id', $business_id)
            ->where('invoice_no', 'LIKE', 'VT%')
            ->whereNull('linked_billing_receive_id')
            ->count();

        $sendLog("Found {$total} VT transactions to check for linking", 'info');

        $count = 0;
        $processed = 0;
        $chunkSize = 200;

        DB::table('transactions')
            ->where('business_id', $business_id)
            ->where('invoice_no', 'LIKE', 'VT%')
            ->whereNull('linked_billing_receive_id')
            ->orderBy('id')
            ->chunk($chunkSize, function($vtTransactions) use ($business_id, $sendLog, &$count, &$processed, $total) {
                foreach ($vtTransactions as $vt) {
                    $ipay = null;
                    
                    // Strategy 1: Find IPAY by ref_no = VT invoice
                    $ipay = DB::table('transactions')
                        ->where('business_id', $business_id)
                        ->where('invoice_no', 'LIKE', 'IPAY%')
                        ->where('ref_no', $vt->invoice_no)
                        ->first();
                    
                    // Strategy 2: Find IPAY by transfer_parent_id = VT id
                    if (!$ipay) {
                        $ipay = DB::table('transactions')
                            ->where('business_id', $business_id)
                            ->where('invoice_no', 'LIKE', 'IPAY%')
                            ->where('transfer_parent_id', $vt->id)
                            ->first();
                    }

                    if ($ipay) {
                        // Link VT → IPAY (new POS style)
                        DB::table('transactions')
                            ->where('id', $vt->id)
                            ->update(['linked_billing_receive_id' => $ipay->id]);

                        // Link IPAY → VT (both new POS style and migrated data style)
                        DB::table('transactions')
                            ->where('id', $ipay->id)
                            ->update([
                                'linked_tax_invoice_id' => $vt->id,
                                'transfer_parent_id' => $vt->id  // Also set for consistency
                            ]);

                        $count++;
                    }
                    $processed++;
                }

                $percentage = $total > 0 ? round(($processed / $total) * 100, 1) : 0;
                $sendLog("Processed {$processed}/{$total} VT records ({$count} linked) - {$percentage}%", 'info');
            });

        return $count;
    }

    /**
     * Migrate payments
     * In old database: sma_payments has sale_id that links to sma_sales.id
     * Payments should be linked to the VT (proforma) transaction in new database
     */
    private function migratePayments($business_id, $sendLog)
    {
        $sendLog('Fetching payments from old database (joining with sales)...', 'info');

        $total = DB::connection('old_pos')->table('sma_payments')->count();
        $sendLog("Found {$total} payment records to process", 'info');

        $count = 0;
        $skipped = 0;
        $processed = 0;
        $chunkSize = 200;

        // Join sma_payments with sma_sales to get the VT reference
        DB::connection('old_pos')
            ->table('sma_payments as p')
            ->leftJoin('sma_sales as s', 'p.sale_id', '=', 's.id')
            ->select(
                'p.id as payment_id',
                'p.reference_no as payment_reference',
                'p.sale_id',
                'p.date as payment_date',
                'p.amount',
                'p.paid_by',
                'p.created_by as payment_created_by',
                's.reference_no as vt_number'
            )
            ->orderBy('p.id')
            ->chunk($chunkSize, function($oldPayments) use ($business_id, $sendLog, &$count, &$skipped, &$processed, $total) {
                foreach ($oldPayments as $oldPayment) {
                    // Skip if no VT number
                    if (empty($oldPayment->vt_number)) {
                        $skipped++;
                        $processed++;
                        continue;
                    }

                    // Find VT transaction in new database
                    $transaction = DB::table('transactions')
                        ->where('business_id', $business_id)
                        ->where('invoice_no', $oldPayment->vt_number)
                        ->first();

                    if ($transaction) {
                        // Check if payment already exists (by amount and reference)
                        $exists = DB::table('transaction_payments')
                            ->where('transaction_id', $transaction->id)
                            ->where('amount', $oldPayment->amount)
                            ->where('payment_ref_no', $oldPayment->payment_reference)
                            ->exists();

                        if (!$exists) {
                            DB::table('transaction_payments')->insert([
                                'transaction_id' => $transaction->id,
                                'business_id' => $business_id,
                                'amount' => $oldPayment->amount ?? 0,
                                'method' => strtolower($oldPayment->paid_by ?? 'cash'),
                                'paid_on' => $oldPayment->payment_date ?? now(),
                                'payment_ref_no' => $oldPayment->payment_reference ?? null,
                                'created_by' => $transaction->created_by ?? 1,
                                'created_at' => $oldPayment->payment_date ?? now(),
                                'updated_at' => now(),
                            ]);

                            $count++;
                        } else {
                            $skipped++;
                        }
                    } else {
                        $skipped++;
                    }
                    $processed++;
                }

                $percentage = $total > 0 ? round(($processed / $total) * 100, 1) : 0;
                $sendLog("Processed {$processed}/{$total} payment records ({$count} new, {$skipped} skipped) - {$percentage}%", 'info');
            });

        return $count;
    }

    /**
     * Clean migrated data from new database
     * This removes:
     * - IPAY transactions
     * - VT-IPAY linking data
     * - Migrated payments
     */
    public function cleanMigratedData()
    {
        // Set unlimited execution time
        set_time_limit(0);
        ini_set('memory_limit', '512M');

        // Disable output buffering for real-time streaming
        if (ob_get_level()) {
            ob_end_clean();
        }

        // Set headers for SSE
        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');
        header('Connection: keep-alive');
        header('X-Accel-Buffering: no');

        echo ": ping\n\n";
        flush();

        $sendLog = function($message, $type = 'info') {
            $data = json_encode([
                'message' => $message,
                'type' => $type,
                'timestamp' => date('Y-m-d H:i:s')
            ]);
            echo "data: {$data}\n\n";
            flush();

            if (connection_status() != CONNECTION_NORMAL) {
                exit;
            }
        };

        try {
            $sendLog('[CLEANUP] Starting data cleanup...', 'warning');

            $business_id = session('user.business_id', 1);
            $sendLog("Using business_id: {$business_id}", 'info');

            // Step 1: Delete IPAY transactions
            $sendLog('[STEP 1] Deleting IPAY transactions...', 'info');
            
            $ipayCount = DB::table('transactions')
                ->where('business_id', $business_id)
                ->where('invoice_no', 'LIKE', 'IPAY%')
                ->count();
            
            $sendLog("Found {$ipayCount} IPAY transactions to delete", 'info');
            
            if ($ipayCount > 0) {
                // First get the IDs of IPAY transactions
                $ipayIds = DB::table('transactions')
                    ->where('business_id', $business_id)
                    ->where('invoice_no', 'LIKE', 'IPAY%')
                    ->pluck('id');
                
                // Delete related transaction_sell_lines
                $linesDeleted = DB::table('transaction_sell_lines')
                    ->whereIn('transaction_id', $ipayIds)
                    ->delete();
                $sendLog("Deleted {$linesDeleted} IPAY sell lines", 'info');
                
                // Delete related payments
                $paymentsDeleted = DB::table('transaction_payments')
                    ->whereIn('transaction_id', $ipayIds)
                    ->delete();
                $sendLog("Deleted {$paymentsDeleted} IPAY payment records", 'info');
                
                // Delete the IPAY transactions
                $deleted = DB::table('transactions')
                    ->where('business_id', $business_id)
                    ->where('invoice_no', 'LIKE', 'IPAY%')
                    ->delete();
                $sendLog("[OK] Deleted {$deleted} IPAY transactions", 'success');
            }

            // Step 2: Clear VT linking data
            $sendLog('[STEP 2] Clearing VT linking data...', 'info');
            
            $vtWithLinks = DB::table('transactions')
                ->where('business_id', $business_id)
                ->where('invoice_no', 'LIKE', 'VT%')
                ->where(function($query) {
                    $query->whereNotNull('linked_billing_receive_id')
                          ->orWhereNotNull('linked_tax_invoice_id');
                })
                ->count();
            
            $sendLog("Found {$vtWithLinks} VT transactions with linking data", 'info');
            
            $updatedVT = DB::table('transactions')
                ->where('business_id', $business_id)
                ->where('invoice_no', 'LIKE', 'VT%')
                ->update([
                    'linked_billing_receive_id' => null,
                    'linked_tax_invoice_id' => null
                ]);
            $sendLog("[OK] Cleared linking data from {$updatedVT} VT transactions", 'success');

            // Step 3: Delete migrated payments (payments with IPAY reference)
            $sendLog('[STEP 3] Deleting migrated payments...', 'info');
            
            $migratedPayments = DB::table('transaction_payments')
                ->where('business_id', $business_id)
                ->where('payment_ref_no', 'LIKE', 'IPAY%')
                ->count();
            
            $sendLog("Found {$migratedPayments} migrated payments (IPAY reference)", 'info');
            
            $deletedPayments = DB::table('transaction_payments')
                ->where('business_id', $business_id)
                ->where('payment_ref_no', 'LIKE', 'IPAY%')
                ->delete();
            $sendLog("[OK] Deleted {$deletedPayments} migrated payments", 'success');

            // Summary
            $sendLog('', 'info');
            $sendLog('═══════════════════════════════════════', 'info');
            $sendLog('[SUCCESS] CLEANUP COMPLETED!', 'success');
            $sendLog('═══════════════════════════════════════', 'info');
            $sendLog("IPAY Transactions Deleted: {$ipayCount}", 'success');
            $sendLog("VT Links Cleared: {$updatedVT}", 'success');
            $sendLog("Migrated Payments Deleted: {$deletedPayments}", 'success');
            $sendLog('═══════════════════════════════════════', 'info');
            $sendLog('You can now run a fresh migration.', 'info');

            echo "data: " . json_encode(['message' => 'DONE', 'type' => 'done']) . "\n\n";
            flush();

        } catch (Exception $e) {
            $sendLog('[ERROR] ' . $e->getMessage(), 'error');
            echo "data: " . json_encode(['message' => 'ERROR', 'type' => 'error']) . "\n\n";
            flush();
        }

        exit;
    }

    /**
     * Migrate sell lines (product items) from old POS to new POS
     * Maps products by SKU/code between databases
     */
    public function migrateSellLines()
    {
        // Set unlimited execution time and increase memory
        set_time_limit(0);
        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time', '0');

        // Disable output buffering for real-time streaming
        if (ob_get_level()) {
            ob_end_clean();
        }

        // Set headers for SSE
        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');
        header('Connection: keep-alive');
        header('X-Accel-Buffering: no');

        echo ": ping\n\n";
        flush();

        $sendLog = function($message, $type = 'info', $progress = null) {
            $data = [
                'message' => $message,
                'type' => $type,
                'timestamp' => date('Y-m-d H:i:s')
            ];
            if ($progress !== null) {
                $data['progress'] = $progress;
            }
            echo "data: " . json_encode($data) . "\n\n";

            if (rand(1, 5) == 1) {
                echo ": ping\n\n";
            }

            flush();

            if (connection_status() != CONNECTION_NORMAL) {
                exit;
            }
        };

        try {
            $sendLog('[START] Starting sell lines migration...', 'info');
            $sendLog('Connecting to old POS database...', 'info');

            // Connect to old database
            config(['database.connections.old_pos' => [
                'driver' => 'mysql',
                'host' => $this->oldDbConfig['host'],
                'port' => $this->oldDbConfig['port'],
                'database' => $this->oldDbConfig['database'],
                'username' => $this->oldDbConfig['username'],
                'password' => $this->oldDbConfig['password'],
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
            ]]);

            DB::purge('old_pos');

            $sendLog('[OK] Connected to old POS database', 'success');

            $business_id = session('user.business_id', 1);
            $sendLog("Using business_id: {$business_id}", 'info');

            // Step 1: Build product mapping (old code -> new product_id and variation_id)
            $sendLog('[STEP 1] Building product mapping by SKU/code...', 'info');

            $productMap = [];
            $newProducts = DB::table('products')
                ->leftJoin('variations', 'variations.product_id', '=', 'products.id')
                ->where('products.business_id', $business_id)
                ->whereNotNull('products.sku')
                ->where('products.sku', '!=', '')
                ->select('products.id as product_id', 'products.sku', 'variations.id as variation_id')
                ->get();

            foreach ($newProducts as $p) {
                $productMap[$p->sku] = [
                    'product_id' => $p->product_id,
                    'variation_id' => $p->variation_id
                ];
            }
            $sendLog("[OK] Product mapping built: " . count($productMap) . " products", 'success');

            // Step 2: Get all VT transactions without sell lines
            $sendLog('[STEP 2] Finding VT transactions without sell lines...', 'info');

            $vtTransactions = DB::table('transactions as t')
                ->leftJoin('transaction_sell_lines as tsl', 'tsl.transaction_id', '=', 't.id')
                ->where('t.business_id', $business_id)
                ->where('t.invoice_no', 'LIKE', 'VT%')
                ->whereNull('tsl.id')
                ->select('t.id', 't.invoice_no')
                ->get();

            $totalVT = $vtTransactions->count();
            $sendLog("Found {$totalVT} VT transactions without sell lines", 'info');

            if ($totalVT == 0) {
                $sendLog('[OK] All VT transactions already have sell lines. Nothing to migrate.', 'success');
                echo "data: " . json_encode(['message' => 'DONE', 'type' => 'done']) . "\n\n";
                flush();
                exit;
            }

            // Step 3: Migrate sell lines
            $sendLog('[STEP 3] Migrating sell lines...', 'info');

            $migrated = 0;
            $skipped = 0;
            $itemsMigrated = 0;
            $productNotFound = 0;
            $errors = 0;
            $batchSize = 100;
            $processed = 0;

            foreach ($vtTransactions as $vt) {
                try {
                    // Find matching sale in old database
                    $oldSale = DB::connection('old_pos')
                        ->table('sma_sales')
                        ->where('reference_no', $vt->invoice_no)
                        ->first();

                    if (!$oldSale) {
                        $skipped++;
                        $processed++;
                        continue;
                    }

                    // Get sale items from old database
                    $saleItems = DB::connection('old_pos')
                        ->table('sma_sale_items')
                        ->where('sale_id', $oldSale->id)
                        ->get();

                    if ($saleItems->isEmpty()) {
                        $skipped++;
                        $processed++;
                        continue;
                    }

                    // Insert each sale item as a sell line
                    foreach ($saleItems as $item) {
                        $productCode = $item->product_code;

                        if (!isset($productMap[$productCode])) {
                            $productNotFound++;
                            continue;
                        }

                        $newProduct = $productMap[$productCode];

                        // Parse discount
                        $discountType = 'fixed';
                        $discountAmount = 0;
                        if (!empty($item->discount)) {
                            if (strpos($item->discount, '%') !== false) {
                                $discountType = 'percentage';
                                $discountAmount = floatval(str_replace('%', '', $item->discount));
                            } else {
                                $discountAmount = floatval($item->discount);
                            }
                        }

                        // Calculate unit_price_inc_tax
                        $unitPriceIncTax = $item->unit_price + $item->item_tax;

                        DB::table('transaction_sell_lines')->insert([
                            'transaction_id' => $vt->id,
                            'product_id' => $newProduct['product_id'],
                            'variation_id' => $newProduct['variation_id'],
                            'quantity' => $item->quantity,
                            'unit_price_before_discount' => $item->unit_price,
                            'unit_price' => $item->net_unit_price ?: $item->unit_price,
                            'unit_price_inc_tax' => $unitPriceIncTax,
                            'item_tax' => $item->item_tax,
                            'tax_id' => null,
                            'line_discount_type' => $discountType,
                            'line_discount_amount' => $discountAmount,
                            'sell_line_note' => $item->comment ?? null,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);

                        $itemsMigrated++;
                    }

                    $migrated++;
                    $processed++;

                    // Progress update
                    if ($migrated % $batchSize == 0) {
                        $progress = round(($processed / $totalVT) * 100, 1);
                        $sendLog("Progress: {$processed}/{$totalVT} - Migrated: {$migrated}, Items: {$itemsMigrated}, Skipped: {$skipped}", 'info', $progress);
                    }

                } catch (Exception $e) {
                    $errors++;
                    if ($errors <= 10) {
                        $sendLog("Error processing {$vt->invoice_no}: " . $e->getMessage(), 'warning');
                    }
                    $processed++;
                }
            }

            // Summary
            $sendLog('', 'info');
            $sendLog('═══════════════════════════════════════', 'info');
            $sendLog('[SUCCESS] SELL LINES MIGRATION COMPLETED!', 'success');
            $sendLog('═══════════════════════════════════════', 'info');
            $sendLog("Total VT processed: {$totalVT}", 'success');
            $sendLog("Successfully migrated: {$migrated}", 'success');
            $sendLog("Total Sell Lines: {$itemsMigrated}", 'success');
            $sendLog("Skipped (no match): {$skipped}", 'success');
            $sendLog("Products not found: {$productNotFound}", 'warning');
            $sendLog("Errors: {$errors}", $errors > 0 ? 'warning' : 'success');
            $sendLog('═══════════════════════════════════════', 'info');

            echo "data: " . json_encode(['message' => 'DONE', 'type' => 'done']) . "\n\n";
            flush();

        } catch (Exception $e) {
            $sendLog('[ERROR] ' . $e->getMessage(), 'error');
            $sendLog('Stack trace: ' . $e->getTraceAsString(), 'error');
            echo "data: " . json_encode(['message' => 'ERROR', 'type' => 'error']) . "\n\n";
            flush();
        }

        exit;
    }
}
