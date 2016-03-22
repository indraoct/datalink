@extends('layouts.admin.default')

@section('css')
	<!-- BEGIN PAGE LEVEL STYLES -->
	<link href="{{ URL::asset('/assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css') }}" rel="stylesheet" type="text/css"/>
	<link href="{{ URL::asset('/assets/global/plugins/bootstrap-modal/css/bootstrap-modal.css') }}" rel="stylesheet" type="text/css"/>
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
	<!-- END PAGE LEVEL PLUGINS -->
	<script>
	
	function construct() {

		/* BEGIN DATA TABLE */
		var grid = new Datatable();

				grid.init({
					src: $("#datatable"),
					onSuccess: function (grid) {
						// execute some code after table records loaded
					},
					onError: function (grid) {
						// execute some code on network or other general error  
					},
					dataTable: { // here you can define a typical datatable settings from http://datatables.net/usage/options 
						"ajax": {
							"url": "{{ URL::to('/') }}/project_category/get_data", // ajax source
						},
						"order": [
							[1, "asc"]
						], // set first column as a default sort by asc
						"columns": [
							{ data: 'no', className: "alignCenter" },
							{ data: 'category_code' },
							{ data: 'category_name' },
							{ data: 'action', bSortable: false, className: "alignCenter" }
						]
					}
				});
		/* END DATA TABLE */
	
		/* BEGIN EVENT HANDLER */
		
		// tombol add
		$(document).on('click', "#add", function(){
			
			$('#act').val('add');
			$('#dialog_title').text('Create New {{{ $title or '' }}}');
			$('#form').find('.form-control').removeAttr('disabled');
			$('#modal').modal('show');
		});
		
		// tombol delete
		$("#datatable tbody").on('click', "a.do_delete", function(){
					
			var oTable = grid.getDataTable();
			saveDelete(oTable.row($(this).parents('tr')).data().id);
		});
		
		// event ketika modal ditampilkan
		$('#modal').on('show', function(e) {
			
			if($('#act').val()=='add')
				clearForm('#form');
			
			clearError();
		});
		
		// tombol save
		$("#modal").on('click', "#save", function(){
			
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
                url: "{{ URL::to('/') }}/project_category/"+$('#act').val(),
				type:"post",
                data: $('#form').serialize(),
				beforeSend:function(){
					Metronic.blockUI();
				},
                success:function(result){
					var result = eval('('+result+')');
					Metronic.unblockUI();
					// alert(result);
					var tipe = '';
					var msg = '';
                    if(result.status)
					{
						clearForm('#form');
						$('#modal').modal('hide');
						
						toastr['success'](result.alert_msg);
						
						// Refresh table
						var oTable = grid.getDataTable();
						oTable.draw();
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
		
		function saveDelete(id)
		{			
			bootbox.confirm("Are you sure want to delete this data?", function(result) {
			   if(result)
			   {
					$.ajax({
						url: "{{ URL::to('/') }}/project_category/delete",
						type:"post",
						data: 'id='+id,
						beforeSend:function(){
							Metronic.blockUI();
						},
						success:function(result){
							var result = eval('('+result+')');
							Metronic.unblockUI();
							// alert(result);
							if(result.status)
							{
								toastr['success'](result.alert_msg);
								
								// Refresh table
								var oTable = grid.getDataTable();
								oTable.draw();
							}
							else
							{
								toastr['error'](result.alert_msg);
							}
						},
						error:function(x,h,r)
						{
							alert(r);
						}
					})
			   }
			}); 
		}
		
		/* END JS FUNCTION */

	};
		
	jQuery(document).ready(function() {     
		Metronic.init(); // init metronic core components
		Layout.init(); // init current layout
		construct();
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
					<a href="#">Project Management & Sales</a>
					<i class="fa fa-angle-right"></i>
				</li>
				<li>
					<a href="#">Master</a>
					<i class="fa fa-angle-right"></i>
				</li>
				<li>
					<a href="#">Project Category</a>
				</li>
			</ul>
			<!-- END PAGE TITLE & BREADCRUMB-->
		</div>
	</div>
	<!-- END PAGE HEADER-->
	<!-- BEGIN PAGE CONTENT-->
	<div class="row">
		<div class="col-md-12">
			<!-- Begin: life time stats -->
			<div class="portlet">
				@if($new)
				<div class="portlet-title">
					<div class="btn-group">
						<button class="btn btn green filter-submit tooltips" data-original-title="Create New" id="add"><i class="fa fa-plus"></i></button>
					</div>
				</div>
				@endif
			<div class="portlet-body">
					<div class="table-container">
						<table class="table table-striped table-bordered table-hover" id="datatable">
						<thead>
						<tr role="row" class="heading">
							<th width="5%">
								#
							</th>
							<th width="30%">
								 Category Code
							</th>
							<th width="55%">
								 Category Name
							</th>
							<th width="10%">
								 Actions
							</th>
						</tr>
						<tr role="row" class="filter">
							<td>
							</td>
							<td>
								<input type="text" class="form-control form-filter input-sm" name="filter[category_code]">
							</td>
							<td>
								<input type="text" class="form-control form-filter input-sm" name="filter[category_name]">
							</td>
							<td>
								<button class="btn btn-sm yellow filter-submit tooltips" data-original-title="Cari"><i class="fa fa-search"></i> </button>
								<button class="btn btn-sm red filter-cancel tooltips" data-original-title="Reset"><i class="fa fa-times"></i> </button>
							</td>
						</tr>
						</thead>
						<tbody>
						</tbody>
						</table>
					</div>
				</div>
			</div>
			<!-- End: life time stats -->
		</div>
	</div>
	
	<!-- Begin Dialog Form -->
	<div id="modal" class="modal fade" tabindex="-1" data-focus-on="input:first" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
					<h4 class="modal-title"><span id="dialog_title"></span></h4>
				</div>
				<div class="modal-body">
					<div class="scroller" style="height:86px" data-always-visible="1" data-rail-visible1="1">
						<form role="form" id="form" class="form-horizontal" action="#">
							<div class="form-body">
								<div class="form-group">
									<label class="col-md-3 control-label">Category Code <font color="red"><strong> * </strong></font></label>
									<div class="col-md-5">
										{{ Form::hidden('act','',array('id'=>'act')) }}
										{{ Form::text('category_code','',array('id'=>'category_code','class'=>'form-control')) }}
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 control-label">Category Name <font color="red"><strong> * </strong></font></label>
									<div class="col-md-5">
										{{ Form::hidden('act','',array('id'=>'act')) }}
										{{ Form::text('category_name','',array('id'=>'category_name','class'=>'form-control')) }}
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" data-dismiss="modal" class="btn default">Cancel</button>
					<button type="button" id="save" class="btn blue">Save</button>
				</div>
			</div>
		</div>
	</div>
	<!-- End Dialog Form -->
	
	<!-- END PAGE CONTENT-->
@stop