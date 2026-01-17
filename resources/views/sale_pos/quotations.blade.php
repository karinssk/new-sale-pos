@extends('layouts.app')
@section('title', __( 'lang_v1.quotation'))

@push('css')
<link rel="stylesheet" href="{{ asset('css/pdf-loader.css') }}">
<style>
    /* Highlight newly created tax invoice */
    .highlight-new-tax-invoice {
        background-color: #ffebee !important;
        border: 2px solid #f44336 !important;
        animation: pulse-highlight-red 3s ease-in-out;
    }
    
    @keyframes pulse-highlight-red {
        0% { background-color: #ffcdd2; }
        25% { background-color: #ef5350; }
        50% { background-color: #f44336; }
        75% { background-color: #ef5350; }
        100% { background-color: #ffebee; }
    }
    
    /* Loading overlay for table refresh */
    .table-loading-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(255, 255, 255, 0.8);
        z-index: 1000;
        display: none;
        align-items: center;
        justify-content: center;
        flex-direction: column;
    }
    
    .table-loading-overlay .loading-spinner {
        border: 4px solid #f3f3f3;
        border-top: 4px solid #2196F3;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        animation: spin 1s linear infinite;
        margin-bottom: 10px;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    #sell_table_wrapper {
        position: relative;
    }
</style>
@endpush

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header no-print">
    <h1 class="tw-text-xl md:tw-text-3xl tw-font-bold tw-text-black">@lang('lang_v1.list_quotations')
        <small></small>
    </h1>
</section>

<!-- Main content -->
<section class="content no-print">
        @component('components.filters', ['title' => __('report.filters')])
        <div class="col-md-3">
            <div class="form-group">
                {!! Form::label('sell_list_filter_location_id',  __('purchase.business_location') . ':') !!}

                {!! Form::select('sell_list_filter_location_id', $business_locations, null, ['class' => 'form-control select2', 'style' => 'width:100%', 'placeholder' => __('lang_v1.all') ]); !!}
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                {!! Form::label('sell_list_filter_customer_id',  __('contact.customer') . ':') !!}
                {!! Form::select('sell_list_filter_customer_id', $customers, null, ['class' => 'form-control select2', 'style' => 'width:100%', 'placeholder' => __('lang_v1.all')]); !!}
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                {!! Form::label('sell_list_filter_date_range', __('report.date_range') . ':') !!}
                {!! Form::text('sell_list_filter_date_range', null, ['placeholder' => __('lang_v1.select_a_date_range'), 'class' => 'form-control', 'readonly']); !!}
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                {!! Form::label('created_by',  __('report.user') . ':') !!}
                {!! Form::select('created_by', $sales_representative, null, ['class' => 'form-control select2', 'style' => 'width:100%']); !!}
            </div>
        </div>
    @endcomponent
    @component('components.widget', ['class' => 'box-primary'])
        @slot('tool')
            <div class="box-tools">

                <a class="tw-dw-btn tw-bg-gradient-to-r tw-from-indigo-600 tw-to-blue-500 tw-font-bold tw-text-white tw-border-none tw-rounded-full pull-right"
                    href="{{action([\App\Http\Controllers\SellController::class, 'create'], ['status' => 'quotation'])}}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="icon icon-tabler icons-tabler-outline icon-tabler-plus">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M12 5l0 14" />
                        <path d="M5 12l14 0" />
                    </svg> @lang('lang_v1.add_quotation')
                </a>
            </div>
        @endslot
        <div id="sell_table_wrapper" style="position: relative;">
          
            <div class="table-responsive">
                <table class="table table-bordered table-striped ajax_view" id="sell_table">
                    <thead>
                        <tr>
                            <th>@lang('messages.date')</th>
                            <th>@lang('purchase.ref_no')</th>
                            <th>@lang('sale.customer_name')</th>
                            <th>@lang('lang_v1.contact_no')</th>
                            <th>@lang('sale.location')</th>
                            <th>@lang('lang_v1.total_items')</th>
                            <th>@lang('lang_v1.added_by')</th>
                            <th>@lang('messages.action')</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    @endcomponent
</section>
<!-- /.content -->
 
    <!-- Create Tax-Invoice Modal -->
    <div class="modal fade border-radius: 86px;" id="createTaxInvoiceModal" tabindex="-1" role="dialog" aria-labelledby="createTaxInvoiceModalLabel">
        <div class="modal-dialog border-radius: 86px;" style="max-width: 500px; margin: 50px auto; display : flex; align-items: center; justify-content: center; min-height: calc(100vh - 100px); border-radius: 86px;">
            <div class="modal-content" style="border-radius: 86px; box-shadow: 0 10px 40px rgba(0,0,0,0.15); border: none; width: 100%; background: white;">
                <div class="modal-header" style="background: white; border-bottom: none; padding: 30px 30px 0 30px; position: relative;">
                    <button type="button" class="close" data-dismiss="modal" style="position: absolute; top: 20px; right: 20px; color: #999; opacity: 0.6; font-size: 24px; background: none; border: none; font-weight: 300;">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body" style="padding: 20px 40px 40px 40px; text-align: center; background: white;">
                    <h4 style="color: #333; font-weight: 600; margin-bottom: 20px; font-size: 24px; line-height: 1.3;">
                        Are you sure?
                    </h4>
                    <p style="color: #666; margin-bottom: 40px; font-size: 16px; line-height: 1.5; font-weight: 400;">
                        Are you sure you want to create a Tax-Invoice (Proforma) from this quotation? This action will convert your quotation.
                    </p>
                    
                    <div style="display: flex; gap: 15px; justify-content: center;">
                        <button type="button" class="btn confirm-create-tax-invoice" style="padding: 12px 30px; border-radius: 8px; font-size: 16px; font-weight: 500; background: #e91e63; border: 2px solid #e91e63; color: white; min-width: 120px;">
                            Create
                        </button>
                        <button type="button" class="btn" data-dismiss="modal" style="padding: 12px 30px; border-radius: 8px; font-size: 16px; font-weight: 500; background: white; border: 2px solid #ddd; color: #666; min-width: 120px;">
                            Cancel
                        </button>
                      
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('javascript')
<script src="{{ asset('js/pdf-loader.js') }}"></script>
<script type="text/javascript">
$(document).ready( function(){
    // Initialize clean state: ensure all modals are properly closed
    $('.modal').modal('hide');
    $('.modal-backdrop').remove();
    $('body').removeClass('modal-open').css('padding-right', '');

    // Custom date range for quotations: January 1st of last year to today
    var lastYear = moment().subtract(1, 'year').year();
    var quotationDateRangeSettings = {
        ranges: ranges,
        startDate: moment(lastYear + '-01-01'),
        endDate: moment(),
        locale: {
            cancelLabel: LANG.clear,
            applyLabel: LANG.apply,
            customRangeLabel: LANG.custom_range,
            format: moment_date_format,
            toLabel: '~',
        },
    };

    //Date range as a button
    $('#sell_list_filter_date_range').daterangepicker(
        quotationDateRangeSettings,
        function (start, end) {
            $('#sell_list_filter_date_range').val(start.format(moment_date_format) + ' ~ ' + end.format(moment_date_format));
            sell_table.ajax.reload();
        }
    );

    // Set initial value
    $('#sell_list_filter_date_range').val(
        moment(lastYear + '-01-01').format(moment_date_format) + ' ~ ' + moment().format(moment_date_format)
    );
    $('#sell_list_filter_date_range').on('cancel.daterangepicker', function(ev, picker) {
        $('#sell_list_filter_date_range').val('');
        sell_table.ajax.reload();
    });

    // Handle confirm create tax invoice button click
    $(document).on('click', '.confirm-create-tax-invoice', function(e) {
        e.preventDefault();
        
        var quotationId = window.pendingTaxInvoiceQuotationId;
        
        if (!quotationId) {
            toastr.error('Quotation ID not found');
            return;
        }
        
        // Close modal
        $('#createTaxInvoiceModal').modal('hide');
        
        // Execute the creation
        setTimeout(function() {
            executeCreateTaxInvoice(quotationId);
        }, 300); // Small delay for smooth modal transition
    });

    // Handle modal Create Tax-Invoice button click
    $(document).on('click', '.modal-create-tax-invoice-btn', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        var quotationId = $(this).data('quotation-id');
        
        console.log('Modal Create Tax-Invoice button clicked:', quotationId);
        
        if (!quotationId) {
            toastr.error('Quotation ID not found');
            return;
        }
        
        // Close the quotation details modal first
        $('.view_modal').modal('hide');
        
        // Store quotation ID for later use
        window.pendingTaxInvoiceQuotationId = quotationId;
        
        // Show the Create Tax-Invoice confirmation modal
        setTimeout(function() {
            $('#createTaxInvoiceModal').modal('show');
        }, 300); // Small delay for smooth modal transition
    });
    
    sell_table = $('#sell_table').DataTable({
        processing: true,
        serverSide: true,
        aaSorting: [[0, 'desc']],
        "ajax": {
            "url": '/sells/draft-dt?is_quotation=1',
            "data": function ( d ) {
                console.log('=== DataTable Request Parameters ===');
                if($('#sell_list_filter_date_range').val()) {
                    var start = $('#sell_list_filter_date_range').data('daterangepicker').startDate.format('YYYY-MM-DD');
                    var end = $('#sell_list_filter_date_range').data('daterangepicker').endDate.format('YYYY-MM-DD');
                    d.start_date = start;
                    d.end_date = end;
                }

                if($('#sell_list_filter_location_id').length) {
                    d.location_id = $('#sell_list_filter_location_id').val();
                }
                d.customer_id = $('#sell_list_filter_customer_id').val();

                if($('#created_by').length) {
                    d.created_by = $('#created_by').val();
                }

                console.log('Request Data:', d);
            },
            "dataSrc": function (json) {
                console.log('=== DataTable Response ===');
                console.log('Full Response:', json);
                console.log('Total Records:', json.recordsTotal);
                console.log('Filtered Records:', json.recordsFiltered);
                console.log('Data Array Length:', json.data ? json.data.length : 0);
                if (json.data && json.data.length > 0) {
                    console.log('First Row Sample:', json.data[0]);
                } else {
                    console.log('NO DATA RETURNED!');
                }
                return json.data;
            },
            "error": function (xhr, error, code) {
                console.error('=== DataTable AJAX Error ===');
                console.error('Status:', xhr.status);
                console.error('Error:', error);
                console.error('Code:', code);
                console.error('Response:', xhr.responseText);
            }
        },
        columnDefs: [ {
            "targets": 7,
            "orderable": false,
            "searchable": false
        } ],
        columns: [
            { data: 'transaction_date', name: 'transaction_date'  },
            { data: 'invoice_no', name: 'invoice_no'},
            { data: 'conatct_name', name: 'conatct_name'},
            { data: 'mobile', name: 'contacts.mobile'},
            { data: 'business_location', name: 'bl.name'},
            { data: 'total_items', name: 'total_items', "searchable": false},
            { data: 'added_by', name: 'added_by'},
            { data: 'action', name: 'action'}
        ],
        "fnDrawCallback": function (oSettings) {
            __currency_convert_recursively($('#purchase_table'));
        },
        createdRow: function(row, data, dataIndex) {
            // Add data-href attribute for row clicking
            if (data.DT_RowData && data.DT_RowData.href) {
                $(row).attr('data-href', data.DT_RowData.href);
            }
            // Make rows clickable
            $(row).css('cursor', 'pointer');
        }
    });
    
    $(document).on('change', '#sell_list_filter_location_id, #sell_list_filter_customer_id, #created_by',  function() {
        sell_table.ajax.reload();
    });

    // Row click to view quotation in modal
    $(document).off('click', 'table#sell_table tbody tr').on('click', 'table#sell_table tbody tr', function(e) {
        // Don't trigger if clicking on action buttons, dropdowns, links
        if ($(e.target).closest('a, button, .dropdown, .btn-group').length) {
            return;
        }
        
        var href = $(this).data('href');
        var quotationId = $(this).find('td:eq(1)').text().trim(); // Get quotation ref from 2nd column
        
        console.log('Row clicked, href:', href, 'quotationId:', quotationId);
        
        if (href) {
            // Ensure clean modal state
            $('.modal').modal('hide');
            $('.modal-backdrop').remove();
            $('body').removeClass('modal-open').css('padding-right', '');
            
            // Show loading modal
            $('.view_modal').html(`
                <div class="modal-dialog modal-xl" style="width: 95%; max-width: 1200px;">
                    <div class="modal-content" style="border-radius: 8px; box-shadow: 0 10px 30px rgba(0,0,0,0.2);">
                        <div class="modal-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 8px 8px 0 0; padding: 20px; border-bottom: none;">
                            <h4 class="modal-title" style="font-size: 20px; font-weight: 600; margin: 0;">
                                <i class="fa fa-spinner fa-spin"></i> Loading Quotation Details...
                            </h4>
                            <button type="button" class="close" data-dismiss="modal" style="color: white; opacity: 0.8; font-size: 28px; background: none; border: none;">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body text-center" style="padding: 50px; background-color: #f8f9fa;">
                            <div class="loading-spinner">
                                <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </div>
                            <p class="mt-3 text-muted">Please wait while we load the quotation details...</p>
                        </div>
                    </div>
                </div>
            `).modal('show');
            
            console.log('Loading quotation from:', href);
            
            // Load quotation details
            $.ajax({
                method: 'GET',
                url: href,
                dataType: 'html',
                timeout: 15000,
                success: function(result) {
                    console.log('Quotation data loaded successfully');
                    
                    // Extract the main content from the response
                    var $result = $(result);
                    var content = $result.find('.content-wrapper, .content, .box-body').first().html();
                    if (!content) {
                        content = result; // fallback to full response
                    }
                    
                    // Remove any existing buttons from content to avoid duplicates
                    var cleanContent = content;
                    
                    // Remove close buttons
                    cleanContent = cleanContent.replace(/<button[^>]*class="[^"]*close[^"]*"[^>]*>.*?<\/button>/gi, '');
                    cleanContent = cleanContent.replace(/<a[^>]*class="[^"]*close[^"]*"[^>]*>.*?<\/a>/gi, '');
                    
                    // Remove print buttons and other action buttons
                    cleanContent = cleanContent.replace(/<button[^>]*print[^>]*>.*?<\/button>/gi, '');
                    cleanContent = cleanContent.replace(/<a[^>]*print[^>]*>.*?<\/a>/gi, '');
                    cleanContent = cleanContent.replace(/<button[^>]*Print[^>]*>.*?<\/button>/gi, '');
                    cleanContent = cleanContent.replace(/<a[^>]*Print[^>]*>.*?<\/a>/gi, '');
                    
                    // Remove any button containing action words
                    cleanContent = cleanContent.replace(/<button[^>]*>(.*?)(Create|Edit|Delete|Print|Save)(.*?)<\/button>/gi, '');
                    cleanContent = cleanContent.replace(/<a[^>]*>(.*?)(Create|Edit|Delete|Print|Save)(.*?)<\/a>/gi, '');
                    
                    // Remove entire footer sections that might contain buttons
                    cleanContent = cleanContent.replace(/<div[^>]*class="[^"]*modal-footer[^"]*"[^>]*>.*?<\/div>/gi, '');
                    cleanContent = cleanContent.replace(/<footer[^>]*>.*?<\/footer>/gi, '');
                    
                    // Extract quotation ID from the content or URL
                    var quotationIdFromUrl = href.split('/').pop();
                    
                    // Create modal with Create Tax-Invoice button in footer
                    var modalHtml = `
                        <div class="modal-dialog modal-xl" style="width: 95%; max-width: 1200px;">
                            <div class="modal-content" style="border-radius: 8px; box-shadow: 0 10px 30px rgba(0,0,0,0.2);">
                                <div class="modal-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 8px 8px 0 0; padding: 20px; border-bottom: none;">
                                    <h4 class="modal-title" style="font-size: 20px; font-weight: 600; margin: 0;">
                                        <i class="fa fa-file-text"></i> Quotation Details
                                    </h4>
                                    <button type="button" class="close" data-dismiss="modal" style="color: white; opacity: 0.8; font-size: 28px; background: none; border: none;">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body" style="max-height: 75vh; overflow-y: auto; padding: 30px; background-color: #f8f9fa;">
                                    <div style="background: white; padding: 25px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                                        ${cleanContent}
                                    </div>
                                </div>
                                <div class="modal-footer" style="background-color: #f8f9fa; border-top: 1px solid #e9ecef; border-radius: 0 0 8px 8px; padding: 20px; display: flex; justify-content: flex-end; align-items: center; gap: 10px;">
                                    <button type="button" class="btn btn-warning modal-create-tax-invoice-btn" 
                                            data-quotation-id="${quotationIdFromUrl}"
                                            style="padding: 10px 20px; border-radius: 5px; font-weight: 500;">
                                        <i class="fa fa-file-invoice"></i> Create Tax-Invoice (Proforma)
                                    </button>
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal" style="padding: 10px 25px; border-radius: 5px;">
                                        <i class="fa fa-times"></i> Close
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;
                    
                    // Replace modal content
                    $('.view_modal').html(modalHtml);
                    
                    // Apply currency conversion to the modal content
                    __currency_convert_recursively($('.view_modal'));
                },
                error: function(xhr, status, error) {
                    console.error('Failed to load quotation:', error);
                    
                    var errorHtml = `
                        <div class="modal-dialog modal-lg" style="width: 90%; max-width: 800px;">
                            <div class="modal-content" style="border-radius: 8px; box-shadow: 0 10px 30px rgba(0,0,0,0.2);">
                                <div class="modal-header" style="background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%); color: white; border-radius: 8px 8px 0 0; padding: 20px;">
                                    <h4 class="modal-title" style="font-size: 20px; font-weight: 600; margin: 0;">
                                        <i class="fa fa-exclamation-triangle"></i> Error Loading Quotation
                                    </h4>
                                    <button type="button" class="close" data-dismiss="modal" style="color: white; opacity: 0.8; font-size: 28px;">
                                        <span>&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body text-center" style="padding: 50px 40px; background-color: #f8f9fa;">
                                    <div style="background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                                        <i class="fas fa-exclamation-triangle fa-4x text-warning" style="margin-bottom: 20px;"></i>
                                        <h5 style="color: #666; font-weight: 600; margin-bottom: 15px;">Failed to load quotation details</h5>
                                        <p class="text-muted">Error: ${error}</p>
                                        <p class="text-muted">Status: ${xhr.status}</p>
                                    </div>
                                </div>
                                <div class="modal-footer" style="background-color: #f8f9fa; border-top: 1px solid #e9ecef; border-radius: 0 0 8px 8px; padding: 20px;">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal" style="padding: 10px 25px; border-radius: 5px;">
                                        <i class="fa fa-times"></i> Close
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;
                    
                    $('.view_modal').html(errorHtml);
                }
            });
        }
    });

    $(document).on('click', 'a.convert-to-proforma', function(e){
        e.preventDefault();
        swal({
            title: LANG.sure,
            icon: 'warning',
            buttons: true,
            dangerMode: true,
        }).then(confirm => {
            if (confirm) {
                var url = $(this).attr('href');
                $.ajax({
                    method: 'GET',
                    url: url,
                    dataType: 'json',
                    success: function(result) {
                        if (result.success == true) {
                            toastr.success(result.msg);
                            sell_table.ajax.reload();
                        } else {
                            toastr.error(result.msg);
                        }
                    },
                });
            }
        });
    });
});

// Function to create proforma from quotation
function createProforma(quotationId) {
    if (confirm('Are you sure you want to create a Tax-Invoice (Proforma) from this quotation?')) {
        $.ajax({
            method: 'POST',
            url: '/quotations/' + quotationId + '/create-proforma',
            dataType: 'json',
            data: {
                '_token': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(result) {
                if (result.success == true) {
                    toastr.success(result.msg);
                    sell_table.ajax.reload();
                } else {
                    toastr.error(result.msg);
                }
            },
            error: function(xhr, status, error) {
                toastr.error('An error occurred while creating the proforma.');
            }
        });
    }
}

// Function to create Billing-receive from Tax-Invoice
function createBillingReceive(id) {
    if (confirm('Are you sure you want to create a Billing-receive from this Tax-Invoice?')) {
        $.ajax({
            url: '/sells/create-billing-receive/' + id,
            type: 'POST',
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.msg);
                    if (response.redirect_url) {
                        window.location.href = response.redirect_url;
                    } else {
                        // Reload the current page if no redirect URL
                        if (typeof sell_table !== 'undefined') {
                            sell_table.ajax.reload();
                        } else {
                            location.reload();
                        }
                    }
                } else {
                    toastr.error(response.msg);
                }
            },
            error: function(xhr, status, error) {
                toastr.error('Error creating Billing-receive: ' + error);
            }
        });
    }
}

// Function to create Tax-Invoice (Proforma) from quotation
function createTaxInvoice(id) {
    console.log('createTaxInvoice called with ID:', id);
    
    // Store quotation ID for later use
    window.pendingTaxInvoiceQuotationId = id;
    
    // Show styled modal instead of confirm dialog
    $('#createTaxInvoiceModal').modal('show');
}

// Functions to manage tax invoice highlighting with localStorage
function storeNewTaxInvoiceId(id) {
    const data = {
        id: id,
        timestamp: Date.now()
    };
    localStorage.setItem('newTaxInvoiceHighlight', JSON.stringify(data));
}

function getNewTaxInvoiceId() {
    const stored = localStorage.getItem('newTaxInvoiceHighlight');
    if (!stored) return null;
    
    const data = JSON.parse(stored);
    const twoMinutes = 2 * 60 * 1000; // 2 minutes in milliseconds
    
    // Check if more than 2 minutes has passed
    if (Date.now() - data.timestamp > twoMinutes) {
        localStorage.removeItem('newTaxInvoiceHighlight');
        return null;
    }
    
    return data.id;
}

function clearNewTaxInvoiceId() {
    localStorage.removeItem('newTaxInvoiceHighlight');
}

// Functions to show/hide table loading overlay
function showTableLoading() {
    $('#table-loading-overlay').css('display', 'flex');
}

function hideTableLoading() {
    $('#table-loading-overlay').css('display', 'none');
}

// Function to actually create the tax invoice
function executeCreateTaxInvoice(quotationId) {
    console.log('Executing createTaxInvoice for ID:', quotationId);
    
    // Check if CSRF token exists
    var csrfToken = $('meta[name="csrf-token"]').attr('content');
    console.log('CSRF Token:', csrfToken);
    
    if (!csrfToken) {
        toastr.error('CSRF token not found! Please refresh the page.');
        return;
    }
    
    // Show loading overlay
    showTableLoading();
    
    console.log('User confirmed, sending AJAX request...');
    
    $.ajax({
        url: "{{ url('sells/create-tax-invoice') }}/" + quotationId,
        method: "POST",
        dataType: "json",
        data: {
            _token: csrfToken
        },
        beforeSend: function() {
            console.log('AJAX request starting...');
        },
        success: function(response) {
            console.log('AJAX success response:', response);
            
            if (response.success == 1 || response.success === true) {
                toastr.success(response.msg);
                console.log('Success! Tax Invoice ID:', response.tax_invoice_id);
                
                // Store the new tax invoice ID for highlighting
                if (response.tax_invoice_id) {
                    storeNewTaxInvoiceId(response.tax_invoice_id);
                }
                
                // Hide loading overlay and reload table
                hideTableLoading();
                
                // Redirect to sells page to show the new tax invoice
                setTimeout(function() {
                    window.location.href = response.redirect_url || '/sells/summary-sales';
                }, 1000);
                
            } else {
                console.log('Response indicates failure:', response.msg);
                toastr.error(response.msg || 'Unknown error occurred');
                hideTableLoading();
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error Details:');
            console.error('Status:', status);
            console.error('Error:', error);
            console.error('Response Text:', xhr.responseText);
            console.error('Status Code:', xhr.status);
            
            hideTableLoading();
            
            // Try to parse error response
            try {
                var errorResponse = JSON.parse(xhr.responseText);
                console.error('Parsed Error Response:', errorResponse);
                toastr.error(errorResponse.message || 'Error creating Tax-Invoice');
            } catch(e) {
                console.error('Could not parse error response');
                toastr.error('Error creating Tax-Invoice. Status: ' + xhr.status);
            }
        },
        complete: function() {
            console.log('AJAX request completed');
        }
    });
}

// Function to create Billing-receive from Tax-Invoice (Proforma)
function createBillingReceive(taxInvoiceId) {
    if (confirm('Are you sure you want to create a Billing-receive from this Tax-Invoice?')) {
        $.ajax({
            url: '/sells/create-billing-receive/' + taxInvoiceId,
            type: 'POST',
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.msg);
                    if (response.redirect_url) {
                        window.location.href = response.redirect_url;
                    } else {
                        // Reload the current page if no redirect URL
                        if (typeof sell_table !== 'undefined') {
                            sell_table.ajax.reload();
                        } else {
                            location.reload();
                        }
                    }
                } else {
                    toastr.error(response.msg);
                }
            },
            error: function(xhr, status, error) {
                toastr.error('Error creating Billing-receive: ' + error);
            }
        });
    }
}

// Function to create final bill from proforma
function createFinalBill(proformaId) {
    if (confirm('Are you sure you want to create a Billing Receipt (Final) from this proforma?')) {
        $.ajax({
            method: 'POST',
            url: '/proforma/' + proformaId + '/create-final-bill',
            dataType: 'json',
            data: {
                '_token': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(result) {
                if (result.success == true) {
                    toastr.success(result.msg);
                    sell_table.ajax.reload();
                } else {
                    toastr.error(result.msg);
                }
            },
            error: function(xhr, status, error) {
                toastr.error('An error occurred while creating the final bill.');
            }
        });
    }
}
</script>
	
@endsection
