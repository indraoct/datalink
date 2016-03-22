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
					<a href="{{ URL::to('/') }}/inventory/good_receipt">Good Receipt</a>
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
							<i class="fa fa-gift"></i>Good Receipt
						</div>
					</div>
					<div class="portlet-body form">
						<!-- BEGIN FORM-->
							<div class="form-body">
								<h3 class="form-section">Good Receipt Detail</h3>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label class="control-label col-md-3">GR No</label>
											<div class="col-md-9">
													{{ 'GR-'.$data['trx_no'] }}
											</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label class="control-label col-md-3">Vendor</label>
											<div class="col-md-9">
													{{ $data['vendor_name'] }}
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label class="control-label col-md-3">Date</label>
											<div class="col-md-9">
													{{ $data['trx_date'] }}
											</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label class="control-label col-md-3">Project</label>
											<div class="col-md-9">
													{{ $data['project_name'] }}
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label class="control-label col-md-3">Notes</label>
											<div class="col-md-9">
													{{ $data['note'] }}
											</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label class="control-label col-md-3">No Ref PO</label>
											<div class="col-md-9">
													{{ $data['no_ref'] }}
											</div>
										</div>
									</div>
									<div class="col-md-6">
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label class="control-label col-md-3">Recipient</label>
											<div class="col-md-9">
													{{ $data['recipient'] }}
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
														Qty Open
													</th>
													<th width="18%" tabindex="0" rowspan="1" colspan="2">
														Qty Received
													</th>
													<th width="20%" tabindex="0" rowspan="1" colspan="1">
														Warehouse
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
													<td class="alignRight">
														{{displayNumeric($row->qty_open)}}
													</td>
													<td>
														{{$row->unit}}
													</td>
													<td class="alignRight">
														{{displayNumeric($row->qty_received)}}
													</td>
													<td>
														{{$row->unit}}
													</td>
													<td>
														{{$listWarehouse[$row->id_warehouse]}}
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
