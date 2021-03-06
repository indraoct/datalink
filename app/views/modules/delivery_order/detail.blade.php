@extends('layouts.admin.default')

@section('css')
@stop

@section('js')
	<script>
	jQuery(document).ready(function() {     
		Metronic.init(); // init metronic core components
		Layout.init(); // init current layout
		
		@if(Session::get('alert')=='success')
			toastr['success']('{{ Session::get("alert_msg") }}');
		@endif

	});
	</script>
@stop

@section('content')	
	<div class="row">
		<div class="col-md-12">
			<!-- BEGIN PAGE TITLE & BREADCRUMB-->
			<h3 class="page-title">
			{{{ $title or '' }}} <small>{{{ $title_desc or '' }}}</small>
			</h3>
			<ul class="page-breadcrumb breadcrumb">
				<li>
					<i class="fa fa-home"></i>
					<a href="#">Inventory</a>
					<i class="fa fa-angle-right"></i>
				</li>
				<li>
					<a href="{{ URL::to('/') }}/inventory/delivery_order">Delivery Order</a>
					<i class="fa fa-angle-right"></i>
				</li>
				<li>
					<a href="#">New</a>
				</li>
			</ul>
			<!-- END PAGE TITLE & BREADCRUMB-->
		</div>
	</div>
<!-- BEGIN Content-->
	<div class="row">
		<div class="col-md-12">
			<div class="tab-pane " id="tab_2">
				<div class="portlet box blue">
					<div class="portlet-title">
						<div class="caption">
							<i class="fa fa-gift"></i>Delivery Order
						</div>
					</div>
					<div class="portlet-body form">
						<!-- BEGIN FORM-->
							<div class="form-body">
								<h3 class="form-section">Delivery Order Detail</h3>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label class="control-label col-md-3">DO No</label>
											<div class="col-md-9">
												<p class="form-control-static">
													DO-{{ $data['trx_no'] }}
												</p>
											</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label class="control-label col-md-3">Vendor</label>
											<div class="col-md-9">
												<p class="form-control-static">
													{{ $data['customer_name'] }}
												</p>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label class="control-label col-md-3">Date</label>
											<div class="col-md-9">
												<p class="form-control-static">
													{{ $data['trx_date'] }}
												</p>
											</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label class="control-label col-md-3">Project</label>
											<div class="col-md-9">
												<p class="form-control-static">
													{{ $data['project_name'] }}
												</p>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label class="control-label col-md-3">Notes</label>
											<div class="col-md-9">
												<p class="form-control-static">
													{{ $data['note'] }}
												</p>
											</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label class="control-label col-md-3">No Ref PO</label>
											<div class="col-md-9">
												<p class="form-control-static">
													BOM-{{ $data['no_ref'] }}
												</p>
											</div>
										</div>
									</div>
								</div>
								<h3 class="form-section">Item</h3>
									<div class="table-container">
										<table class="table table-striped table-bordered table-hover">
											<thead>
												<tr role="row" class="heading">
													<th width="3%" tabindex="0" rowspan="1" colspan="1">
														#
													</th>
													<th width="20%" tabindex="0" rowspan="1" colspan="1">
														Item Code
													</th>
													<th width="20%" tabindex="0" rowspan="1" colspan="1">
														Item Name
													</th>
													<th width="18%" tabindex="0" rowspan="1" colspan="2">
														Qty To Deliver
													</th>
													<th width="18%" tabindex="0" rowspan="1" colspan="2">
														Qty Delivered
													</th>
												</tr>
											</thead>
							@if(!empty($item))
											<tbody>
								@foreach ($item as $row)
												<tr role="row" class="item_row">
													<td align="center">
													</td>
													<td>
														{{$row->id_item}}
													</td>
													<td>
														{{$row->item_name}}
													</td>
													<td>
														{{displayNumeric($row->qty_open)}}
													</td>
													<td>
														{{$row->unit}}
													</td>
													<td>
														{{displayNumeric($row->qty_received)}}
													</td>
													<td>
														{{$row->unit}}
													</td>
												</tr>
								@endforeach
											</tbody>
							@endif
										</table>
									</div>
							</div>
							
						<!-- END FORM-->
					</div>
				</div>
			</div>
		</div>
	</div>
<!-- END Content-->

@stop
