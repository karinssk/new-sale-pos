<div class="modal-dialog modal-xl" role="document">
	<div class="modal-content">
		<div class="modal-header">
		    <button type="button" class="close no-print" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		      <h4 class="modal-title" id="modalTitle">{{$product->name}}</h4>
	    </div>
	    <div class="modal-body">
      		<div class="row">
      			<div class="col-sm-9">
	      			<div class="col-sm-4 invoice-col">
	      				<b>SKU:</b>
						{{$product->sku }}<br>
						<b>Brand: </b>
						{{$product->brand->name ?? '--' }}<br>
						<b>Unit: </b>
						{{$product->unit->short_name ?? '--' }}<br>
						<b>Barcode Type: </b>
						{{$product->barcode_type ?? '--' }}
						<br>
						<strong>Available in locations:</strong>
						@if(count($product->product_locations) > 0)
							{{implode(', ', $product->product_locations->pluck('name')->toArray())}}
						@else
							None
						@endif
						@if(!empty($product->media->first())) <br>
							<strong>Product Brochure:</strong>
							<a href="{{$product->media->first()->display_url}}" download="{{$product->media->first()->display_name}}">
								<span class="label label-info">
									<i class="fas fa-download"></i>
									{{$product->media->first()->display_name}}
								</span>
							</a>
						@endif
	      			</div>

	      			<div class="col-sm-4 invoice-col">
						<b>Multi-Level Categories:</b><br>
						@if($product->getCategoryPath())
							<div class="well well-sm" style="background-color: #f9f9f9; margin-top: 5px;">
								<strong class="text-primary">
									<i class="fa fa-sitemap"></i> 
									{{ $product->getCategoryPath() }}
								</strong>
							</div>
						@else
							<em class="text-muted">No categories assigned</em>
						@endif
						
						<div style="margin-top: 10px;">
							@if($product->categoryL1)
								<b>L1:</b> {{$product->categoryL1->name}}<br>
							@endif
							@if($product->categoryL2)
								<b>L2:</b> {{$product->categoryL2->name}}<br>
							@endif
							@if($product->categoryL3)
								<b>L3:</b> {{$product->categoryL3->name}}<br>
							@endif
							@if($product->categoryL4)
								<b>L4:</b> {{$product->categoryL4->name}}<br>
							@endif
							@if($product->categoryL5)
								<b>L5:</b> {{$product->categoryL5->name}}<br>
							@endif
						</div>
						
						<div style="margin-top: 10px;">
							<b>Manage Stock: </b>
							@if($product->enable_stock)
								Yes
							@else
								No
							@endif
							<br>
							@if($product->enable_stock)
								<b>Alert Quantity: </b>
								{{$product->alert_quantity ?? '--' }}
							@endif

							@if(!empty($product->warranty))
								<br>
								<b>Warranty: </b>
								{{$product->warranty->display_name }}
							@endif
						</div>
	      			</div>
					
	      			<div class="col-sm-4 invoice-col">
	      				<b>Expires in: </b>
	      				@php
	  						$expiry_array = ['months'=>'Months', 'days'=>'Days', '' =>'Not Applicable' ];
	  					@endphp
	      				@if(!empty($product->expiry_period) && !empty($product->expiry_period_type))
							{{$product->expiry_period}} {{$expiry_array[$product->expiry_period_type]}}
						@else
							{{$expiry_array['']}}
	      				@endif
	      				<br>
						@if($product->weight)
							<b>Weight: </b>
							{{$product->weight }}<br>
						@endif
						<b>Applicable Tax: </b>
						{{$product->product_tax->name ?? 'None' }}<br>
						@php
							$tax_type = ['inclusive' => 'Inclusive', 'exclusive' => 'Exclusive'];
						@endphp
						<b>Selling Price Tax Type: </b>
						{{$tax_type[$product->tax_type] ?? 'N/A' }}<br>
						<b>Product Type: </b>
						{{ ucfirst($product->type) }}
						
	      			</div>
	      			<div class="clearfix"></div>
	      			<br>
      				<div class="col-sm-12">
      					{!! $product->product_description !!}
      				</div>
	      		</div>
      			<div class="col-sm-3 col-md-3 invoice-col">
      				<div class="thumbnail">
      					<img src="{{$product->image_url}}" alt="Product image" style="max-width: 100%; height: auto;">
      				</div>
      			</div>
      		</div>
      		
      		@if($product->type == 'single')
      			@include('product.partials.single_product_details')
      		@elseif($product->type == 'variable')
      			@include('product.partials.variable_product_details')
      		@elseif($product->type == 'combo')
      			@include('product.partials.combo_product_details')
      		@endif
      		
      		@if($product->enable_stock == 1)
	      		<div class="row">
	      			<div class="col-md-12">
	      				<strong>Product Stock Details</strong>
	      			</div>
	      			<div class="col-md-12" id="view_product_stock_details" data-product_id="{{$product->id}}">
	      			</div>
	      		</div>
      		@endif
      	</div>
      	<div class="modal-footer">
      		<button type="button" class="tw-dw-btn tw-dw-btn-primary tw-text-white no-print" 
	        aria-label="Print" 
	          onclick="$(this).closest('div.modal').printThis();">
	        <i class="fa fa-print"></i> Print
	      </button>
	      	<button type="button" class="tw-dw-btn tw-dw-btn-neutral tw-text-white no-print" data-dismiss="modal">Close</button>
	    </div>
	</div>
</div>