@extends('layouts.admin.default')

@section('css')
@stop

@section('js')
	<script>
	jQuery(document).ready(function() {     
		Metronic.init(); // init metronic core components
		Layout.init(); // init current layout
		
		@if(Session::get('alert'))
			toastr['{{Session::get("alert")}}']('{{ Session::get("alert_msg") }}');
		@endif

		$(document).on('click', ".act", function()
		{			
			$('.act').attr('disabled',true);
			var action = $(this).val();
			$("#action").val(action);
			
			$("#form").submit();
		});
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
						<form id="form" method="post" action="{{ URL::to('/') }}/inventory/good_receipt/new/save" class="form-horizontal">
							<div class="form-body">
								<h3 class="form-section">Good Receipt Detail</h3>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label class="control-label col-md-3">GR No</label>
											<div class="col-md-9">
												<p class="form-control-static">
													{{ 'GR-'.$trx_no }}
												</p>
											</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label class="control-label col-md-3">Vendor</label>
											<div class="col-md-9">
												<p class="form-control-static">
													{{ $vendor_name }}
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
													{{ $trx_date }}
												</p>
											</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label class="control-label col-md-3">Project</label>
											<div class="col-md-9">
												<p class="form-control-static">
													{{ $project_name }}
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
													{{ $note }}
												</p>
											</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label class="control-label col-md-3">No Ref PO</label>
											<div class="col-md-9">
												<p class="form-control-static">
													{{ $no_ref }}
												</p>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-md-3">Recipient</label>
											<div class="col-md-9">
												<p class="form-control-static">
													{{ $listEmp[$id_recipient] }}
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
														{{$row['id_item']}}
													</td>
													<td>
														{{$row['item_name']}}
													</td>
													<td class="alignRight">
														{{displayNumeric($row['qty'])}}
													</td>
													<td>
														{{$row['item_unit']}}
													</td>
													<td class="alignRight">
														{{$row['qty_received']}}
													</td>
													<td>
														{{$row['item_unit']}}
													</td>
													<td>
														{{$listWarehouse[$row['id_warehouse']]}}
													</td>
												</tr>
								@endforeach
											</tbody>
							@endif
										</table>
									</div>
							</div>
							
	@if(!isset($view))
							<div class="form-actions fluid">
								<div class="row">
									<div class="col-md-12">
										<div class="col-md-offset-4 col-md-12">
											{{ Form::hidden('action','',array('id'=>'action')) }}
											<button type="button" value="back" id="back" class="btn default act">Back</button>
											<button type="button" value="process" id="process" class="btn blue act">Save</button>
										</div>
									</div>
								</div>
							</div>
	@endif
						{{ Form::close() }}
						<!-- END FORM-->
					</div>
				</div>
			</div>
		</div>
	</div>
<!-- END Content-->

@stop