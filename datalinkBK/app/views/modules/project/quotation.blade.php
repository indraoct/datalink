@extends('modules.project.detail')

@section('additionCss')
	<link href="{{ URL::asset('/assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css') }}" rel="stylesheet" type="text/css"/>
	<link href="{{ URL::asset('/assets/global/plugins/bootstrap-modal/css/bootstrap-modal.css') }}" rel="stylesheet" type="text/css"/>
@stop

@section('additionJs')
<!-- BEGIN PAGE LEVEL PLUGINS -->
	<script type="text/javascript" src="{{ URL::asset('/assets/global/plugins/datatables/media/js/jquery.dataTables.min.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('/assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('/assets/global/scripts/datatable.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('/assets/global/plugins/bootstrap-modal/js/bootstrap-modal.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('/assets/global/plugins/bootstrap-modal/js/bootstrap-modalmanager.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('/assets/global/plugins/bootbox/bootbox.min.js') }}"></script>
<!-- END PAGE LEVEL PLUGINS -->
@stop

@section('construct')

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
							"url": "{{ URL::to('/') }}/project/quotation/get_data/{{$id}}", // ajax source
						},
						"order": [
							[1, "asc"]
						], // set first column as a default sort by asc
						"columns": [
							{ data: 'no',className: "alignCenter" },
							{ data: 'revision',},
							{ data: 'created_by',},
							{ data: 'created_at',},
							{ data: 'action',},
						]
					}
				});
		/* END DATA TABLE */
	
		/* BEGIN EVENT HANDLER */
		
		/* END JS FUNCTION */

@stop

@section('readyFunction')
@stop

@section('quotation')
<!-- BEGIN Content-->
	<div class="row">
		<div class="col-md-12">
			<div class="portlet blue box "style="border:none">
					<!-- Begin: life time stats -->
				<div class="portlet-body form">
					@if($new)
					<div class="portlet-title">
						<div class="btn-group">
							<a href="{{ URL::to('/') }}/project/quotation/add/{{$id}}"><button class="btn btn green filter-submit tooltips" data-original-title="Create New" id="add"><i class="fa fa-plus"></i></button></a>
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
								<th width="10%">
									 Revision
								</th>
								<th width="20%">
									 Created By
								</th>
								<th width="20%">
									 Created At
								</th>
								<th width="10%">
									 Actions
								</th>
							</tr>
							<tr role="row" class="filter">
								<td>
								</td>
								<td>
								</td>
								<td>
								</td>
								<td>
								</td>
								<td>
								</td>
							</tr>
							</thead>
							<tbody>
							</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			<!-- End: life time stats -->
		</div>
	</div>
<!-- END Content-->
@stop