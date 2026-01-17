@extends('layouts.app')

@section('title', 'Data Migration Update')

@section('content')
<section class="content-header">
    <h1>Data Migration Update
        <small>Migrate fresh data from old POS to new POS</small>
    </h1>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        <i class="fa fa-database"></i> Migration Control Panel
                    </h3>
                </div>
                <div class="box-body">
                    <!-- Migration Info -->
                    <div class="alert alert-info">
                        <h4><i class="icon fa fa-info"></i> Migration Information</h4>
                        <p>This tool will migrate fresh data from the old POS system to the new POS system.</p>
                        <strong>Migration includes:</strong>
                        <ul>
                            <li>Contacts (Customers & Suppliers)</li>
                            <li>Products & Variations</li>
                            <li>Sales Transactions (VT - Tax Invoices)</li>
                            <li>IPAY Transactions (Billing Receipts)</li>
                            <li>VT <-> IPAY Relationships</li>
                            <li>Payment Records</li>
                        </ul>
                    </div>

                    <!-- Warning -->
                    <div class="alert alert-warning">
                        <h4><i class="icon fa fa-warning"></i> Important!</h4>
                        <p><strong>Before running migration:</strong></p>
                        <ul>
                            <li>Backup your database</li>
                            <li>Ensure old POS database is accessible</li>
                            <li>This process may take several minutes</li>
                            <li>Don't close this page during migration</li>
                        </ul>
                    </div>

                    <!-- Database Configuration -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Old POS Database:</label>
                                <input type="text" class="form-control" value="sale_pos (127.0.0.1:8889)" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>New POS Database:</label>
                                <input type="text" class="form-control" value="shop_rubyshop_pos (Current)" readonly>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="text-center" style="margin-top: 20px;">
                        <button type="button" id="cleanDataBtn" class="btn btn-danger btn-lg" style="margin-right: 10px;">
                            <i class="fa fa-trash"></i> Clean Migrated Data
                        </button>
                        <button type="button" id="startMigrationBtn" class="btn btn-primary btn-lg" style="margin-right: 10px;">
                            <i class="fa fa-play"></i> Start Migration
                        </button>
                        <button type="button" id="migrateSellLinesBtn" class="btn btn-success btn-lg">
                            <i class="fa fa-cubes"></i> Migrate Sell Lines (Products)
                        </button>
                        <button type="button" id="stopMigrationBtn" class="btn btn-warning btn-lg" style="display: none;">
                            <i class="fa fa-stop"></i> Stop
                        </button>
                    </div>
                    <div class="text-center" style="margin-top: 10px;">
                        <small class="text-muted">
                            <i class="fa fa-info-circle"></i>
                            Click "Clean Migrated Data" first if you want a fresh migration.
                            Use "Migrate Sell Lines" to copy product items from old sales to new transactions.
                        </small>
                    </div>
                </div>
            </div>

            <!-- Migration Progress -->
            <div class="box box-success" id="migrationProgressBox" style="display: none;">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        <i class="fa fa-refresh fa-spin"></i> Migration Progress
                    </h3>
                </div>
                <div class="box-body">
                    <!-- Progress Bar -->
                    <div class="progress">
                        <div id="progressBar" class="progress-bar progress-bar-striped active" role="progressbar" style="width: 0%">
                            <span id="progressText">0%</span>
                        </div>
                    </div>

                    <!-- Log Output -->
                    <div style="margin-top: 20px;">
                        <label>Real-time Migration Log:</label>
                        <div id="logOutput" style="
                            background-color: #1e1e1e;
                            color: #d4d4d4;
                            padding: 15px;
                            border-radius: 5px;
                            font-family: 'Courier New', monospace;
                            font-size: 13px;
                            max-height: 500px;
                            overflow-y: auto;
                            white-space: pre-wrap;
                        ">
                            <span style="color: #4EC9B0;">Waiting to start migration...</span>
                        </div>
                    </div>

                    <!-- Statistics -->
                    <div class="row" style="margin-top: 20px;">
                        <div class="col-md-2">
                            <div class="info-box bg-aqua">
                                <span class="info-box-icon"><i class="fa fa-users"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Contacts</span>
                                    <span class="info-box-number" id="contactsCount">0</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="info-box bg-green">
                                <span class="info-box-icon"><i class="fa fa-cubes"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Products</span>
                                    <span class="info-box-number" id="productsCount">0</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="info-box bg-yellow">
                                <span class="info-box-icon"><i class="fa fa-file-text"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Sales (VT)</span>
                                    <span class="info-box-number" id="vtCount">0</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="info-box bg-blue">
                                <span class="info-box-icon"><i class="fa fa-receipt"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">IPAY</span>
                                    <span class="info-box-number" id="ipayCount">0</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="info-box bg-purple">
                                <span class="info-box-icon"><i class="fa fa-link"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Linked</span>
                                    <span class="info-box-number" id="linkedCount">0</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="info-box bg-red">
                                <span class="info-box-icon"><i class="fa fa-money"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Payments</span>
                                    <span class="info-box-number" id="paymentsCount">0</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="info-box bg-teal">
                                <span class="info-box-icon"><i class="fa fa-list"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Sell Lines</span>
                                    <span class="info-box-number" id="sellLinesCount">0</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@section('javascript')
<script>
$(document).ready(function() {
    let eventSource = null;
    let migrationRunning = false;

    // Start Migration
    $('#startMigrationBtn').click(function() {
        if (migrationRunning) {
            return;
        }

        // Confirm before starting
        if (!confirm('Are you sure you want to start the migration? This may take several minutes.')) {
            return;
        }

        // Show progress box
        $('#migrationProgressBox').slideDown();
        $('#startMigrationBtn').hide();
        $('#stopMigrationBtn').show();
        migrationRunning = true;

        // Clear log
        $('#logOutput').html('<span style="color: #4EC9B0;"> Connecting to server...</span>\n');

        // Reset counters
        $('#contactsCount, #productsCount, #vtCount, #ipayCount, #linkedCount, #paymentsCount').text('0');
        $('#progressBar').css('width', '0%');
        $('#progressText').text('0%');

        // Create EventSource for SSE
        eventSource = new EventSource('{{ route("migrate-update-data.run") }}', {
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });

        let progress = 0;

        eventSource.onmessage = function(event) {
            try {
                const data = JSON.parse(event.data);

                // Check if done
                if (data.type === 'done') {
                    eventSource.close();
                    migrationRunning = false;
                    $('#startMigrationBtn').show();
                    $('#stopMigrationBtn').hide();
                    $('#progressBar').removeClass('active').addClass('progress-bar-success');
                    $('#progressBar').css('width', '100%');
                    $('#progressText').text('100%');
                    return;
                }

                // Check if error
                if (data.type === 'error') {
                    appendLog(data.message, 'error');
                    eventSource.close();
                    migrationRunning = false;
                    $('#startMigrationBtn').show();
                    $('#stopMigrationBtn').hide();
                    $('#progressBar').removeClass('active').addClass('progress-bar-danger');
                    return;
                }

                // Append log message
                appendLog(data.message, data.type);

                // Update progress
                progress += 2;
                if (progress > 95) progress = 95;
                $('#progressBar').css('width', progress + '%');
                $('#progressText').text(progress + '%');

                // Extract counts from messages
                extractCounts(data.message);

            } catch (e) {
                console.error('Error parsing SSE data:', e);
            }
        };

        eventSource.onerror = function(error) {
            console.error('EventSource error:', error);
            appendLog('❌ Connection error. Please check server logs.', 'error');
            eventSource.close();
            migrationRunning = false;
            $('#startMigrationBtn').show();
            $('#stopMigrationBtn').hide();
        };
    });

    // Stop Migration
    $('#stopMigrationBtn').click(function() {
        if (eventSource) {
            eventSource.close();
        }
        migrationRunning = false;
        $('#startMigrationBtn').show();
        $('#cleanDataBtn').show();
        $('#stopMigrationBtn').hide();
        appendLog('⚠️ Operation stopped by user', 'warning');
    });

    // Clean Migrated Data
    $('#cleanDataBtn').click(function() {
        if (migrationRunning) {
            return;
        }

        // Confirm before cleaning
        if (!confirm('⚠️ WARNING: This will DELETE all migrated data:\n\n• IPAY transactions\n• VT-IPAY linking data\n• Migrated payments\n\nAre you sure you want to proceed?')) {
            return;
        }

        // Double confirm
        if (!confirm('This action cannot be undone. Continue?')) {
            return;
        }

        // Show progress box
        $('#migrationProgressBox').slideDown();
        $('#startMigrationBtn').hide();
        $('#cleanDataBtn').hide();
        $('#stopMigrationBtn').show();
        migrationRunning = true;

        // Clear log
        $('#logOutput').html('<span style="color: #FFA726;"> Starting data cleanup...</span>\n');

        // Reset counters
        $('#contactsCount, #productsCount, #vtCount, #ipayCount, #linkedCount, #paymentsCount').text('0');
        $('#progressBar').css('width', '0%').removeClass('progress-bar-success progress-bar-danger').addClass('active');
        $('#progressText').text('0%');

        // Create EventSource for cleanup
        eventSource = new EventSource('{{ route("migrate-update-data.clean") }}');

        let progress = 0;

        eventSource.onmessage = function(event) {
            try {
                const data = JSON.parse(event.data);

                if (data.type === 'done') {
                    eventSource.close();
                    migrationRunning = false;
                    $('#startMigrationBtn').show();
                    $('#cleanDataBtn').show();
                    $('#stopMigrationBtn').hide();
                    $('#progressBar').removeClass('active').addClass('progress-bar-success');
                    $('#progressBar').css('width', '100%');
                    $('#progressText').text('100%');
                    return;
                }

                if (data.type === 'error') {
                    appendLog(data.message, 'error');
                    eventSource.close();
                    migrationRunning = false;
                    $('#startMigrationBtn').show();
                    $('#cleanDataBtn').show();
                    $('#stopMigrationBtn').hide();
                    $('#progressBar').removeClass('active').addClass('progress-bar-danger');
                    return;
                }

                appendLog(data.message, data.type);

                progress += 15;
                if (progress > 95) progress = 95;
                $('#progressBar').css('width', progress + '%');
                $('#progressText').text(progress + '%');

            } catch (e) {
                console.error('Error parsing SSE data:', e);
            }
        };

        eventSource.onerror = function(error) {
            console.error('EventSource error:', error);
            appendLog('❌ Connection error. Please check server logs.', 'error');
            eventSource.close();
            migrationRunning = false;
            $('#startMigrationBtn').show();
            $('#cleanDataBtn').show();
            $('#migrateSellLinesBtn').show();
            $('#stopMigrationBtn').hide();
        };
    });

    // Migrate Sell Lines (Products)
    $('#migrateSellLinesBtn').click(function() {
        if (migrationRunning) {
            return;
        }

        // Confirm before starting
        if (!confirm('This will migrate product items (sell lines) from old sales to new transactions.\n\nThis process maps products by SKU/code and may take several minutes.\n\nContinue?')) {
            return;
        }

        // Show progress box
        $('#migrationProgressBox').slideDown();
        $('#startMigrationBtn').hide();
        $('#cleanDataBtn').hide();
        $('#migrateSellLinesBtn').hide();
        $('#stopMigrationBtn').show();
        migrationRunning = true;

        // Clear log
        $('#logOutput').html('<span style="color: #4EC9B0;">🚀 Starting sell lines migration...</span>\n');

        // Reset counters
        $('#contactsCount, #productsCount, #vtCount, #ipayCount, #linkedCount, #paymentsCount, #sellLinesCount').text('0');
        $('#progressBar').css('width', '0%').removeClass('progress-bar-success progress-bar-danger').addClass('active');
        $('#progressText').text('0%');

        // Create EventSource for sell lines migration
        eventSource = new EventSource('{{ route("migrate-update-data.sell-lines") }}');

        let progress = 0;

        eventSource.onmessage = function(event) {
            try {
                const data = JSON.parse(event.data);

                if (data.type === 'done') {
                    eventSource.close();
                    migrationRunning = false;
                    $('#startMigrationBtn').show();
                    $('#cleanDataBtn').show();
                    $('#migrateSellLinesBtn').show();
                    $('#stopMigrationBtn').hide();
                    $('#progressBar').removeClass('active').addClass('progress-bar-success');
                    $('#progressBar').css('width', '100%');
                    $('#progressText').text('100%');
                    return;
                }

                if (data.type === 'error') {
                    appendLog(data.message, 'error');
                    eventSource.close();
                    migrationRunning = false;
                    $('#startMigrationBtn').show();
                    $('#cleanDataBtn').show();
                    $('#migrateSellLinesBtn').show();
                    $('#stopMigrationBtn').hide();
                    $('#progressBar').removeClass('active').addClass('progress-bar-danger');
                    return;
                }

                appendLog(data.message, data.type);

                // Update progress from message
                if (data.progress) {
                    progress = data.progress;
                } else {
                    progress += 1;
                    if (progress > 95) progress = 95;
                }
                $('#progressBar').css('width', progress + '%');
                $('#progressText').text(Math.round(progress) + '%');

                // Extract sell lines count
                extractCounts(data.message);

            } catch (e) {
                console.error('Error parsing SSE data:', e);
            }
        };

        eventSource.onerror = function(error) {
            console.error('EventSource error:', error);
            appendLog('❌ Connection error. Please check server logs.', 'error');
            eventSource.close();
            migrationRunning = false;
            $('#startMigrationBtn').show();
            $('#cleanDataBtn').show();
            $('#migrateSellLinesBtn').show();
            $('#stopMigrationBtn').hide();
        };
    });

    // Append log message
    function appendLog(message, type) {
        const colors = {
            'info': '#4EC9B0',
            'success': '#4CAF50',
            'warning': '#FFA726',
            'error': '#F44336'
        };

        const color = colors[type] || '#d4d4d4';
        const timestamp = new Date().toLocaleTimeString();
        const logEntry = `<span style="color: #808080;">[${timestamp}]</span> <span style="color: ${color};">${message}</span>\n`;

        $('#logOutput').append(logEntry);

        // Auto-scroll to bottom
        const logDiv = document.getElementById('logOutput');
        logDiv.scrollTop = logDiv.scrollHeight;
    }

    // Extract counts from messages
    function extractCounts(message) {
        // Extract contacts count
        const contactsMatch = message.match(/Migrated (\d+) contacts/);
        if (contactsMatch) {
            $('#contactsCount').text(contactsMatch[1]);
        }

        // Extract products count
        const productsMatch = message.match(/Migrated (\d+) products/);
        if (productsMatch) {
            $('#productsCount').text(productsMatch[1]);
        }

        // Extract VT count
        const vtMatch = message.match(/Migrated (\d+) sales/);
        if (vtMatch) {
            $('#vtCount').text(vtMatch[1]);
        }

        // Extract IPAY count
        const ipayMatch = message.match(/Migrated (\d+) IPAY/);
        if (ipayMatch) {
            $('#ipayCount').text(ipayMatch[1]);
        }

        // Extract linked count
        const linkedMatch = message.match(/Linked (\d+) VT-IPAY/);
        if (linkedMatch) {
            $('#linkedCount').text(linkedMatch[1]);
        }

        // Extract payments count
        const paymentsMatch = message.match(/Migrated (\d+) payments/);
        if (paymentsMatch) {
            $('#paymentsCount').text(paymentsMatch[1]);
        }

        // Summary counts
        if (message.includes('Total Contacts:')) {
            const match = message.match(/Total Contacts: (\d+)/);
            if (match) $('#contactsCount').text(match[1]);
        }
        if (message.includes('Total Products:')) {
            const match = message.match(/Total Products: (\d+)/);
            if (match) $('#productsCount').text(match[1]);
        }
        if (message.includes('Total Sales')) {
            const match = message.match(/Total Sales.*?: (\d+)/);
            if (match) $('#vtCount').text(match[1]);
        }
        if (message.includes('Total IPAY:')) {
            const match = message.match(/Total IPAY: (\d+)/);
            if (match) $('#ipayCount').text(match[1]);
        }
        if (message.includes('Total Linked Pairs:')) {
            const match = message.match(/Total Linked Pairs: (\d+)/);
            if (match) $('#linkedCount').text(match[1]);
        }
        if (message.includes('Total Payments:')) {
            const match = message.match(/Total Payments: (\d+)/);
            if (match) $('#paymentsCount').text(match[1]);
        }

        // Extract sell lines count
        const sellLinesMatch = message.match(/Sell lines created: (\d+)/);
        if (sellLinesMatch) {
            $('#sellLinesCount').text(sellLinesMatch[1]);
        }
        if (message.includes('Items:')) {
            const match = message.match(/Items: (\d+)/);
            if (match) $('#sellLinesCount').text(match[1]);
        }
        if (message.includes('Total Sell Lines:')) {
            const match = message.match(/Total Sell Lines: (\d+)/);
            if (match) $('#sellLinesCount').text(match[1]);
        }
    }
});
</script>
@endsection
