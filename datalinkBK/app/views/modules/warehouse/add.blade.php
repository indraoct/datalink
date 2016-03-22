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
	
		$("#credit").hide();
		/* BEGIN EVENT HANDLER */
		// tombol save
		$("#button").on('click', "#save", function(){
			$("textarea").each(function(){
				$(this).val($(this).val().replace(/\r\n|\r|\n/g,"<br />"));
			});
			save();
		});
		
		/* END EVENT HANDLER */
		
		/* BEGIN JS FUNCTION */
		
		
		function clearError()
		{
			$('.form-group').removeClass('has-error'); 
			$('.form-control').attr('data-original-title','');
		}

		function save()
		{			
			clearError();
            $.ajax({
                url: "{{ URL::to('/') }}/warehouse/{{$act}}",
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
						clearForm('#form');
						toastr['success'](result.alert_msg);
						setTimeout('', 500);
						window.location.href ="{{ URL::to('/') }}/warehouse";
                    }
					else
					{
						toastr['error'](result.alert_msg);
						var errors = result.error_msg;
						Object.keys(errors).forEach(function(key) {
							if(errors[key])
							{
								$('#'+key).closest('.form-group').addClass('has-error');
								$('#'+key).attr("data-original-title", errors[key]).attr("data-placement", "bottom").tooltip();
								$('#s2id_'+key).attr("data-original-title", errors[key]).attr("data-placement", "bottom").tooltip();
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
		
		if (jQuery().datepicker) {
            $('.date-picker').datepicker({
                rtl: Metronic.isRTL(),
                orientation: "left",
                autoclose: true
            });
		}

		/* END JS FUNCTION */

	};

	<?php
		$proCode = array();
		if($act == 'Edit')
		{
			$proCode = array('disabled' => 'disabled');
	?>
	function renderData()
	{
		$('#id_warehouse').val( '{{$warehouse->id_warehouse}}' );
		$('#warehouse_name').val( '{{$warehouse->warehouse_name}}' ); 
		$('#address').val( '{{$warehouse->address}}' ); 
		$('#description').val( '{{$warehouse->description}}' ); 
		$('#head_person').select2('val', '{{$warehouse->head_person}}' ); 
                
                $("textarea").each(function(){
				$(this).val($(this).val().replace(/<br\s?\/?>/g,"\n"));
		});
	}
	<?php
		}
		else{
			echo "function renderData(){}";
		}
	?>
	function credit()
	{
		$("#credit").hide();
		if( $('#rTop').val() == 2)
		{
			$("#credit").show();
		}
	}

	jQuery(document).ready(function() {     
		Metronic.init(); // init metronic core components
		Layout.init(); // init current layout
		construct();
		renderData();
	});
	</script>
@stop
@section('content')
<!-- BEGIN PAGE HEADER-->
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
					<a href="#">Master</a>
					<i class="fa fa-angle-right"></i>
				</li>
				<li>
					<a href="{{ URL::to('/') }}/warehouse">Warehouse</a>
					<i class="fa fa-angle-right"></i>
				</li>
				<li>
					<a href="#">{{$act}}</a>
				</li>
			</ul>
			<!-- END PAGE TITLE & BREADCRUMB-->
		</div>
	</div>
	<!-- END PAGE HEADER-->
	<!-- BEGIN PAGE CONTENT-->
		<form role="form" id="form" class="form-horizontal" action="#">
			<div class="form-group">
				<label class="col-md-3 control-label">Warehouse Name</label>
				<div class="col-md-5">
					{{ Form::hidden('act','',array('id'=>'act')) }}
					{{ Form::hidden('id','',array('id'=>'id_warehouse')) }}
					{{ Form::text('warehouse_name','',array('id'=>'warehouse_name','class'=>'form-control')) }}
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label">Address</label>
				<div class="col-md-5">
					{{ Form::textarea('address','',array('id'=>'address','class'=>'form-control','size'=>'10x3')) }}
				</div>
			</div>
		        <div class="form-group">
				<label class="col-md-3 control-label">Head Of Warehouse</label>
				<div class="col-md-5">
					{{Form::select('head_person',$list_head_person,0,array('id'=>'head_person','class'=>'form-control select2')) }}
				</div>
			</div>
				<div class="form-group">
				<label class="col-md-3 control-label">Description</label>
				<div class="col-md-5">
					{{ Form::textarea('description','',array('id'=>'description','class'=>'form-control','size'=>'10x3')) }}
				</div>
			</div>
		</form>
	<!-- END PAGE CONTENT-->

				<div id="button" class="modal-footer">
					<a href="{{ URL::to('/') }}/warehouse"><button type="button" class="btn default" >Back</button></a>
					<button type="button" id="save" class="btn blue">Save</button>
				</div>
	<!-- End Dialog Form -->
	
	<!-- END PAGE CONTENT-->
@stop