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
							"url": "{{ URL::to('/') }}/open_project/get_data", // ajax source
						},
						"order": [
							[1, "asc"]
						], // set first column as a default sort by asc
						"columns": [
							{ data: 'no',className: "alignCenter" },
							{ data: 'project_code',},
							{ data: 'detail',},
							{ data: 'customer_name',},
							{ data: 'emp_firstname',},
							{ data: 'status_desc',},
							{ data: 'action', bSortable: false, }
						]
					}
				});
		/* END DATA TABLE */
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
					<a href="#">Open Project</a>
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
			<div class="portlet-body">
					<div class="table-container">
						<table class="table table-striped table-bordered table-hover" id="datatable">
						<thead>
						<tr role="row" class="heading">
							<th width="5%">
								#
							</th>
							<th width="10%">
								 Code
							</th>
							<th width="20%">
								 Name
							</th>
							<th width="20%">
								 Client
							</th>
							<th width="20%">
								 Sales Person
							</th>
							<th width="15%">
								 Status
							</th>
							<th width="10%">
								 Actions
							</th>
						</tr>
						<tr role="row" class="filter">
							<td>
							</td>
							<td>
								<input type="text" class="form-control form-filter input-sm" name="filter[id_project]">
							</td>
							<td>
								<input type="text" class="form-control form-filter input-sm" name="filter[project_name]">
							</td>
							<td>
								{{ Form::select('filter[custId]',$listCustomer,null,array('class'=>'form-control select2 form-filter input-sm')) }}
							</td>
							<td>
								{{ Form::select('filter[sales_id]',$listSales,null,array('class'=>'form-control select2 form-filter input-sm')) }}
							</td>
							<td>
								{{ Form::select('filter[status]',$listStatus,null,array('class'=>'form-control select2 form-filter input-sm')) }}
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
@stop