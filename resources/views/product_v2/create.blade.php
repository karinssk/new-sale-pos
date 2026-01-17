@extends('layouts.app')

@section('title', 'Create Product - Multi-Level Categories')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <h1>Create Product
            <small>Add new product with multi-level categories</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{ route('products-v2.index') }}">Products V2</a></li>
            <li class="active">Create</li>
        </ol>
    </section>

    <section class="content">
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('products-v2.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Product Information</h3>
                </div>
                
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Product Name *</label>
                                <input type="text" class="form-control" id="name" name="name" 
                                       value="{{ old('name') }}" required>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="sku">SKU *</label>
                                <input type="text" class="form-control" id="sku" name="sku" 
                                       value="{{ old('sku') }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="type">Product Type *</label>
                                <select class="form-control" id="type" name="type" required>
                                    <option value="single" {{ old('type') == 'single' ? 'selected' : '' }}>Single</option>
                                    <option value="variable" {{ old('type') == 'variable' ? 'selected' : '' }}>Variable</option>
                                    <option value="combo" {{ old('type') == 'combo' ? 'selected' : '' }}>Combo</option>
                                    <option value="modifier" {{ old('type') == 'modifier' ? 'selected' : '' }}>Modifier</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="unit_id">Unit *</label>
                                <select class="form-control" id="unit_id" name="unit_id" required>
                                    <option value="">Select Unit</option>
                                    @foreach($units as $unit)
                                        <option value="{{ $unit->id }}" {{ old('unit_id') == $unit->id ? 'selected' : '' }}>
                                            {{ $unit->actual_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="brand_id">Brand</label>
                                <select class="form-control" id="brand_id" name="brand_id">
                                    <option value="">Select Brand</option>
                                    @foreach($brands as $brand)
                                        <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                            {{ $brand->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="product_description">Product Description</label>
                                <textarea class="form-control" id="product_description" name="product_description" 
                                          rows="3">{{ old('product_description') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="image">Product Image</label>
                                <input type="file" class="form-control" id="image" name="image" accept="image/*">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="barcode_type">Barcode Type</label>
                                <select class="form-control" id="barcode_type" name="barcode_type">
                                    <option value="C128" {{ old('barcode_type') == 'C128' ? 'selected' : '' }}>Code 128</option>
                                    <option value="C39" {{ old('barcode_type') == 'C39' ? 'selected' : '' }}>Code 39</option>
                                    <option value="EAN13" {{ old('barcode_type') == 'EAN13' ? 'selected' : '' }}>EAN-13</option>
                                    <option value="EAN8" {{ old('barcode_type') == 'EAN8' ? 'selected' : '' }}>EAN-8</option>
                                    <option value="UPCA" {{ old('barcode_type') == 'UPCA' ? 'selected' : '' }}>UPC-A</option>
                                    <option value="UPCE" {{ old('barcode_type') == 'UPCE' ? 'selected' : '' }}>UPC-E</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Multi-Level Categories Section -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Multi-Level Categories (L1-L5)</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse">
                            <i class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>
                
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-info">
                                <i class="fa fa-info-circle"></i>
                                Select categories from Level 1 to Level 5. Each level will be populated based on your previous selection.
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="category_l1_id">Level 1 Category</label>
                                <select class="form-control" id="category_l1_id" name="category_l1_id">
                                    <option value="">Select Level 1 Category</option>
                                    @foreach($l1_categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_l1_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="category_l2_id">Level 2 Category</label>
                                <select class="form-control" id="category_l2_id" name="category_l2_id" disabled>
                                    <option value="">Select Level 2 Category</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="category_l3_id">Level 3 Category</label>
                                <select class="form-control" id="category_l3_id" name="category_l3_id" disabled>
                                    <option value="">Select Level 3 Category</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="category_l4_id">Level 4 Category</label>
                                <select class="form-control" id="category_l4_id" name="category_l4_id" disabled>
                                    <option value="">Select Level 4 Category</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="category_l5_id">Level 5 Category</label>
                                <select class="form-control" id="category_l5_id" name="category_l5_id" disabled>
                                    <option value="">Select Level 5 Category</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Category Path:</label>
                                <div class="well well-sm" id="category_path" style="min-height: 40px; background-color: #f9f9f9;">
                                    <em class="text-muted">No categories selected</em>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stock & Pricing Section -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Stock & Pricing</h3>
                </div>
                
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" id="enable_stock" name="enable_stock" value="1" 
                                               {{ old('enable_stock') ? 'checked' : '' }}>
                                        Enable Stock Management
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="alert_quantity">Alert Quantity</label>
                                <input type="number" class="form-control" id="alert_quantity" name="alert_quantity" 
                                       value="{{ old('alert_quantity') }}" step="0.01">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="weight">Weight</label>
                                <input type="text" class="form-control" id="weight" name="weight" 
                                       value="{{ old('weight') }}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tax">Tax Rate</label>
                                <select class="form-control" id="tax" name="tax">
                                    <option value="">No Tax</option>
                                    @foreach($tax_rates as $tax)
                                        <option value="{{ $tax->id }}" {{ old('tax') == $tax->id ? 'selected' : '' }}>
                                            {{ $tax->name }} ({{ $tax->amount }}%)
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tax_type">Tax Type</label>
                                <select class="form-control" id="tax_type" name="tax_type">
                                    <option value="inclusive" {{ old('tax_type') == 'inclusive' ? 'selected' : '' }}>Inclusive</option>
                                    <option value="exclusive" {{ old('tax_type') == 'exclusive' ? 'selected' : '' }}>Exclusive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Locations Section -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Business Locations</h3>
                </div>
                
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Available at locations:</label>
                                <div class="checkbox-list">
                                    @foreach($locations as $location)
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="product_locations[]" value="{{ $location->id }}"
                                                       {{ in_array($location->id, old('product_locations', [])) ? 'checked' : '' }}>
                                                {{ $location->name }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="box">
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-save"></i> Save Product
                    </button>
                    <a href="{{ route('products-v2.index') }}" class="btn btn-default">
                        <i class="fa fa-arrow-left"></i> Back to Products
                    </a>
                </div>
            </div>
        </form>
    </section>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Multi-level category cascading dropdowns
    function loadCategoriesByParent(parentId, targetSelect, clearSelects = []) {
        if (!parentId) {
            $(targetSelect).html('<option value="">Select Category</option>').prop('disabled', true);
            clearSelects.forEach(function(select) {
                $(select).html('<option value="">Select Category</option>').prop('disabled', true);
            });
            updateCategoryPath();
            return;
        }

        $.ajax({
            url: '{{ route("categories.by-parent") }}',
            type: 'GET',
            data: {
                parent_id: parentId,
                type: 'product'
            },
            success: function(response) {
                if (response.success) {
                    var options = '<option value="">Select Category</option>';
                    response.categories.forEach(function(category) {
                        options += '<option value="' + category.id + '">' + category.name + '</option>';
                    });
                    
                    $(targetSelect).html(options).prop('disabled', false);
                    
                    // Clear subsequent selects
                    clearSelects.forEach(function(select) {
                        $(select).html('<option value="">Select Category</option>').prop('disabled', true);
                    });
                    
                    updateCategoryPath();
                }
            },
            error: function() {
                console.error('Error loading categories');
            }
        });
    }

    function updateCategoryPath() {
        var path = [];
        
        $('#category_l1_id option:selected').each(function() {
            if ($(this).val()) path.push($(this).text());
        });
        
        $('#category_l2_id option:selected').each(function() {
            if ($(this).val()) path.push($(this).text());
        });
        
        $('#category_l3_id option:selected').each(function() {
            if ($(this).val()) path.push($(this).text());
        });
        
        $('#category_l4_id option:selected').each(function() {
            if ($(this).val()) path.push($(this).text());
        });
        
        $('#category_l5_id option:selected').each(function() {
            if ($(this).val()) path.push($(this).text());
        });
        
        if (path.length > 0) {
            $('#category_path').html('<strong>' + path.join(' > ') + '</strong>');
        } else {
            $('#category_path').html('<em class="text-muted">No categories selected</em>');
        }
    }

    // Category level change handlers
    $('#category_l1_id').on('change', function() {
        var selectedId = $(this).val();
        loadCategoriesByParent(selectedId, '#category_l2_id', ['#category_l3_id', '#category_l4_id', '#category_l5_id']);
    });

    $('#category_l2_id').on('change', function() {
        var selectedId = $(this).val();
        loadCategoriesByParent(selectedId, '#category_l3_id', ['#category_l4_id', '#category_l5_id']);
    });

    $('#category_l3_id').on('change', function() {
        var selectedId = $(this).val();
        loadCategoriesByParent(selectedId, '#category_l4_id', ['#category_l5_id']);
    });

    $('#category_l4_id').on('change', function() {
        var selectedId = $(this).val();
        loadCategoriesByParent(selectedId, '#category_l5_id', []);
    });

    $('#category_l5_id').on('change', function() {
        updateCategoryPath();
    });

    // Initialize category path on page load
    updateCategoryPath();
});
</script>
@endpush
@endsection