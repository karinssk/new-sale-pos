@extends('layouts.app')

@section('title', 'View Product - Multi-Level Categories')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <h1>View Product
            <small>{{ $product->name }}</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{ route('products-v2.index') }}">Products V2</a></li>
            <li class="active">View</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-8">
                <!-- Product Information -->
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Product Information</h3>
                        <div class="box-tools pull-right">
                            <a href="{{ route('products.edit', $product) }}" class="btn btn-primary btn-sm">
                                <i class="fa fa-edit"></i> Edit Product
                            </a>
                        </div>
                    </div>
                    
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-striped">
                                    <tr>
                                        <th width="30%">Name:</th>
                                        <td>{{ $product->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>SKU:</th>
                                        <td>{{ $product->sku }}</td>
                                    </tr>
                                    <tr>
                                        <th>Type:</th>
                                        <td>
                                            <span class="label label-{{ $product->type == 'single' ? 'success' : 'warning' }}">
                                                {{ ucfirst($product->type) }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Unit:</th>
                                        <td>{{ $product->unit->actual_name ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Brand:</th>
                                        <td>{{ $product->brand->name ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Barcode Type:</th>
                                        <td>{{ $product->barcode_type }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-striped">
                                    <tr>
                                        <th width="30%">Stock Management:</th>
                                        <td>
                                            <span class="label label-{{ $product->enable_stock ? 'success' : 'default' }}">
                                                {{ $product->enable_stock ? 'Enabled' : 'Disabled' }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Alert Quantity:</th>
                                        <td>{{ $product->alert_quantity ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Weight:</th>
                                        <td>{{ $product->weight ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Tax Type:</th>
                                        <td>{{ ucfirst($product->tax_type) }}</td>
                                    </tr>
                                    <tr>
                                        <th>Created:</th>
                                        <td>{{ $product->created_at->format('M d, Y H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Updated:</th>
                                        <td>{{ $product->updated_at->format('M d, Y H:i') }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        @if($product->product_description)
                            <div class="row">
                                <div class="col-md-12">
                                    <h4>Description</h4>
                                    <div class="well">
                                        {!! nl2br(e($product->product_description)) !!}
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Multi-Level Categories -->
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Multi-Level Categories (L1-L5)</h3>
                    </div>
                    
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <h4>Category Path</h4>
                                <div class="well well-lg" style="background-color: #f9f9f9;">
                                    @if($product->getCategoryPath())
                                        <h5 class="text-primary">
                                            <i class="fa fa-sitemap"></i> 
                                            {{ $product->getCategoryPath() }}
                                        </h5>
                                    @else
                                        <em class="text-muted">No categories assigned</em>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-striped">
                                    <tr>
                                        <th width="40%">Level 1 (L1):</th>
                                        <td>{{ $product->categoryL1->name ?? 'Not assigned' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Level 2 (L2):</th>
                                        <td>{{ $product->categoryL2->name ?? 'Not assigned' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Level 3 (L3):</th>
                                        <td>{{ $product->categoryL3->name ?? 'Not assigned' }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-striped">
                                    <tr>
                                        <th width="40%">Level 4 (L4):</th>
                                        <td>{{ $product->categoryL4->name ?? 'Not assigned' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Level 5 (L5):</th>
                                        <td>{{ $product->categoryL5->name ?? 'Not assigned' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Total Levels:</th>
                                        <td>
                                            <span class="label label-info">
                                                {{ collect([$product->categoryL1, $product->categoryL2, $product->categoryL3, $product->categoryL4, $product->categoryL5])->filter()->count() }} levels assigned
                                            </span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Business Locations -->
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Business Locations</h3>
                    </div>
                    
                    <div class="box-body">
                        @if($product->product_locations->count() > 0)
                            <div class="row">
                                @foreach($product->product_locations as $location)
                                    <div class="col-md-4">
                                        <div class="info-box">
                                            <span class="info-box-icon bg-green"><i class="fa fa-map-marker"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">{{ $location->name }}</span>
                                                <span class="info-box-number">Available</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted">This product is not available at any business location.</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <!-- Product Image -->
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Product Image</h3>
                    </div>
                    
                    <div class="box-body text-center">
                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" 
                             class="img-responsive" style="max-width: 100%; height: auto;">
                    </div>
                </div>

                <!-- Product Variations -->
                @if($product->variations->count() > 0)
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Product Variations</h3>
                        </div>
                        
                        <div class="box-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-condensed">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>SKU</th>
                                            <th>Price</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($product->variations as $variation)
                                            <tr>
                                                <td>{{ $variation->name }}</td>
                                                <td>{{ $variation->sub_sku }}</td>
                                                <td>{{ number_format($variation->sell_price_inc_tax, 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Quick Actions -->
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Quick Actions</h3>
                    </div>
                    
                    <div class="box-body">
                        <div class="btn-group-vertical btn-block">
                            <a href="{{ route('products.edit', $product) }}" class="btn btn-primary">
                                <i class="fa fa-edit"></i> Edit Product
                            </a>
                            <a href="{{ route('products-v2.index') }}" class="btn btn-default">
                                <i class="fa fa-list"></i> Back to Products
                            </a>
                            <form action="{{ route('products-v2.destroy', $product) }}" method="POST" 
                                  style="display: inline-block; width: 100%;"
                                  onsubmit="return confirm('Are you sure you want to delete this product?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-block">
                                    <i class="fa fa-trash"></i> Delete Product
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection