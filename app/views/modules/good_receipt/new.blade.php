@extends('layouts.admin.default')

@section('css')
	<!-- BEGIN PAGE LEVEL STYLES -->
	<link href="{{ URL::asset('/assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css') }}" rel="stylesheet" type="text/css"/>
	<link href="{{ URL::asset('/assets/global/plugins/bootstrap-modal/css/bootstrap-modal.css') }}" rel="stylesheet" type="text/css"/>
	<link href="{{ URL::asset('/assets/global/plugins/bootstrap-datepicker/css/datepicker3.css') }}" rel="stylesheet" type="text/css"/>
	<!-- END PAGE LEVEL SCRIPTS -->
@stop

@section('js')
	<!-- BEGIN PAGE LEVEL PLUGINS -->
	<script type="text/javascript" src="{{ URL::asset('/assets/global/plugins/datatables/media/js/jquery.dataTables.min.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('/assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('/assets/global/scripts/datatable.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('/assets/global/plugins/bootstrap-modal/js/bootstrap-modal.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('/assets/global/plugins/bootstrap-modal/js/bootstrap-modalmanager.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('/assets/global/plugins/bootbox/bootbox.min.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') }}"></script>
	<!-- END PAGE LEVEL PLUGINS -->
	<script>
	
	function construct() {

		$(".form").on('click', "#process", function(){
			validate();
		});
	
		/* BEGIN EVENT HANDLER */
		
		// tombol save
		$("#modal").on('click', "#save", function(){
			save();
		});
		
		/* END EVENT HANDLER */
		
		/* BEGIN JS FUNCTION */
		function clearError()
		{
			$('.item_row').removeClass('has-error'); 
			$('.form-group').removeClass('has-error'); 
			$('.form-control').attr('data-original-title','');
			$('td').removeClass('has-error'); 
		}
		
		function validate()
		{			
			clearError();
            $.ajax({
                url: "{{ URL::to('/') }}/inventory/good_receipt/new/validate",
				type:"post",
                data: $('#form').serialize(),
				beforeSend:function(){
					Metronic.blockUI();
				},
                success:function(result){
					var result = eval('('+result+')');
					Metronic.unblockUI();
					// alert(result);
					var alert_msg = '';
                    if(result.status)
					{
						$("#form").submit();
                    }
					else
					{
						toastr['error'](result.alert_msg);
						var errors = result.error_msg;
						Object.keys(errors).forEach(function(key) {
							if(errors[key])
							{
								if(key=="item")
								{
									Object.keys(errors[key]).forEach(function(n) {
										Object.keys(errors[key][n]).forEach(function(keyP) {
											$('.'+keyP+':eq('+n+')').closest('td').addClass('has-error'); 
											$('.'+keyP+':eq('+n+')').attr("data-original-title", errors[key][n][keyP]).tooltip();
										});
									});
								}
								else if(key =='idPo')
								{
									toastr['error']('The id po has already been taken.');
								}
								else
								{
									$('#'+key).closest('.form-group').addClass('has-error'); 
									$('#'+key).attr("data-original-title", errors[key]).tooltip();
									$('#s2id_'+key).attr("data-original-title", errors[key]).tooltip();
								}
							}
						});
                    }
                },
                error:function(x,h,r)
				{
                    alert(r);
                }
            })
		}
		/* END JS FUNCTION */

	};

	$('.date-picker').datepicker({
        rtl: Metronic.isRTL(),
        orientation: "left",
        autoclose: true
    });

	jQuery(document).ready(function() {     
		Metronic.init(); // init metronic core components
		Layout.init(); // init current layout
		construct();
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
						<form id="form" method="post" action="{{ URL::to('/') }}/inventory/good_receipt/new" class="form-horizontal">
							<div class="form-body">
								<h3 class="form-section">Good Receipt Detail</h3>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label class="control-label col-md-3">GR No</label>
											<div class="col-md-1">
												{{ Form::hidden('idPo',$idPo,array('id'=>'id')) }}
												<p class="form-control-static">GR-</p>
											</div>
											<div class="col-md-8">
												{{ Form::text('trx_no',$poData['master']['trx_no'],array('id'=>'trx_no','class'=>'form-control','readonly'=>true)) }}
											</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label class="control-label col-md-3">Vendor</label>
											<div class="col-md-9">
												{{ Form::hidden('id_vendor',$poData['master']['id_vendor'])}}
												{{ Form::select('id_vendor',$listVendor,$poData['master']['id_vendor'],array('id'=>'id_vendor','class'=>'form-control','disabled'=>true)) }}
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label class="control-label col-md-3">Date</label>
											<div class="col-md-9">
												<div class="input-group  date date-picker margin-bottom-5" data-date-format="{{ Config::get('format.date.default') }}">
													{{ Form::text('trx_date',$poData['master']['trx_date'],array('id'=>'trx_date','class'=>'form-control form-filter input-sm fix-var','readonly'=>true)) }}
													<span class="input-group-btn">
													<button class="btn btn-sm default" type="button"><i class="fa fa-calendar"></i></button>
													</span>
												</div>
											</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label class="control-label col-md-3">Project</label>
											<div class="col-md-9">
												{{ Form::select('id_project',$listProject,$poData['master']['id_project'],array('id'=>'id_project','class'=>'form-control select2')) }}
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label class="control-label col-md-3">Notes</label>
											<div class="col-md-9">
												{{ Form::textarea('note',$poData['master']['note'],array('id'=>'note','class'=>'form-control','size'=>'10x3')) }}
											</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label class="control-label col-md-3">No Ref PO</label>
											<div class="col-md-9">
												{{ Form::text('no_ref',('PO-'.$poData['master']['no_ref']),array('id'=>'no_ref','class'=>'form-control','readonly'=>true)) }}
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-md-3">Recipient</label>
											<div class="col-md-9">
												{{ Form::select('id_recipient',$listEmp,$poData['master']['id_recipient'],array('id'=>'id_recipient','class'=>'form-control select2')) }}
											</div>
										</div>
									</div>
								</div>
								<h3 class="form-section">Item</h3>
									<div class="table-container">
										<table class="table table-striped table-bordered table-hover" id="tableContent">
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
							@if(!empty($poData['detail']))
								<?php $i = 0;?>
											<tbody>
								@foreach ($poData['detail'] as $row)
												<tr role="row" class="item_row">
													<td align="center">
														<a href="javascript:void(0)" id="remove" onClick="$(this).closest('tr').remove()"><span class="badge badge-danger">x</span></a>
													</td>
													<td>
														{{ Form::hidden('item['.$i.'][id_detail]',$row->id_detail,array('id'=>'id')) }}
														{{ Form::hidden('item['.$i.'][id_item]',$row->id_item,array('id'=>'id')) }}
														{{ Form::hidden('item['.$i.'][qty]',$row->qty,array('id'=>'id')) }}
														{{ Form::hidden('item['.$i.'][item_unit]',$row->item_unit,array('id'=>'id')) }}
														{{ Form::hidden('item['.$i.'][item_name]',$row->item_name,array('id'=>'id')) }}
														{{$row->id_item}}
													</td>
													<td>
														{{$row->item_name}}
													</td>
													<td class="alignRight">
														{{displayNumeric($row->qty)}}
													</td>
													<td>
														{{$row->item_unit}}
													</td>
													<td>
														<?php $qty_received = isset($poData['item'][$i]['qty_received']) ? $poData['item'][$i]['qty_received'] : ''; ?>
														{{ Form::text("item[".$i."][qty_received]",$qty_received,array('class'=>'form-control currency input-sm qty_received')) }} 
													</td>
													<td>
														{{$row->item_unit}}
													</td>
													<td>
														<?php $warehouse = isset($poData['item'][$i]['id_warehouse']) ? $poData['item'][$i]['id_warehouse'] : '';?>
														{{ Form::select("item[".$i."][id_warehouse]",$listWarehouse,$warehouse,array('class'=>'form-control id_warehouse')) }}
													</td>
												</tr>
								<?php $i += 1;?>
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
											<button type="button" class="btn default" onClick='window.location.href ="{{ URL::to('/') }}/inventory/good_receipt"'>Cancel</button>
											<button type="button" id="process" class="btn blue">Process</button>
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