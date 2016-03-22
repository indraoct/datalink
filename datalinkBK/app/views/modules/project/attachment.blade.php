@extends('modules.project.detail')

@section('additionCss')
	<link href="{{ URL::asset('/assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css') }}" rel="stylesheet" type="text/css"/>
	<link href="{{ URL::asset('/assets/global/plugins/bootstrap-modal/css/bootstrap-modal.css') }}" rel="stylesheet" type="text/css"/>
	<link href="{{ URL::asset('/assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css') }}" rel="stylesheet" type="text/css"/>
@stop

@section('additionJs')
<!-- BEGIN PAGE LEVEL PLUGINS -->
	<script type="text/javascript" src="{{ URL::asset('/assets/global/plugins/datatables/media/js/jquery.dataTables.min.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('/assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('/assets/global/scripts/datatable.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('/assets/global/plugins/bootstrap-modal/js/bootstrap-modal.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('/assets/global/plugins/bootstrap-modal/js/bootstrap-modalmanager.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('/assets/global/plugins/bootbox/bootbox.min.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('/assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js') }}"></script>
<!-- END PAGE LEVEL PLUGINS -->
@stop

@section('construct')
<?php
if(!empty($return))
{
?>
	Metronic.unblockUI();
	if('<?=$return?>' == 'success')
	{	
		toastr['success']('Successfully added.');
	}
	else
	{
		toastr['error']('Addition failed. Please contact your administrator.');
	}
<?php
}
?>
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
							"url": "{{ URL::to('/') }}/project/attachment/get_data/{{$id}}", // ajax source
						},
						"order": [
							[1, "asc"]
						], // set first column as a default sort by asc
						"columns": [
							{ data: 'no',className: "alignCenter" },
							{ data: 'attachment_name',},
							{ data: 'description',},
							{ data: 'filename',},
							{ data: 'action',},
						]
					}
				});
		/* END DATA TABLE */
	
		/* BEGIN EVENT HANDLER */
		
		// tombol delete
		$("#datatable tbody").on('click', "a.do_delete", function(){
					
			var oTable = grid.getDataTable();
			saveDelete(oTable.row($(this).parents('tr')).data().id);
		});

		function saveDelete(id)
		{
			bootbox.confirm("Yakin ingin menghapus data ini?", function(result) {
			   if(result)
			   {
					$.ajax({
						url: "{{ URL::to('/') }}/project/attachment/delete/{{$id}}",
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

@stop

@section('readyFunction')
@stop

@section('attachment')
<!-- BEGIN Content-->
	<div class="row">
		<div class="col-md-12">
			<div class="portlet blue box "style="border:none">
					<!-- Begin: life time stats -->
				<div class="portlet-body form">
					<div class="portlet-title">
						<div class="btn-group">
							<a href="#" onClick="$('#addForm').slideDown();$('#attachment_name').focus();$('html,body').animate({scrollTop: $('#addForm').offset().top},'slow');"><button class="btn btn green filter-submit tooltips" data-original-title="Create New" id="add"><i class="fa fa-plus"></i></button></a>
						</div>
					</div>
					<div class="portlet-body">
						<div class="table-container">
							<table class="table table-striped table-bordered table-hover" id="datatable">
							<thead>
							<tr role="row" class="heading">
								<th width="5%">
									#
								</th>
								<th width="30%">
									 Name
								</th>
								<th width="30%">
									 Description
								</th>
								<th width="25%">
									 Filename
								</th>
								<th width="10%">
									 Actions
								</th>
							</tr>
							<tr role="row" class="filter">
								<td>
								</td>
								<td>
									<input type="text" class="form-control form-filter input-sm" name="filter[attachment_name]">
								</td>
								<td>
									<input type="text" class="form-control form-filter input-sm" name="filter[description]">
								</td>
								<td>
									<input type="text" class="form-control form-filter input-sm" name="filter[attachment_filename]">
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
			</div>
			<!-- End: life time stats -->
		</div>
	</div>
	<div class="row" id="addForm" style="display:none">
		{{ Form::open(array('method'=>'POST','class'=>'form-horizontal','files'=>true,)) }}
		<div class="col-md-6">
			<!-- BEGIN FORM-->
			<div class="portlet box blue ">
				<div class="portlet-title">
					<div class="caption">
						<i class="fa fa-gift"></i> Add Attachment
					</div>
				</div>
				<div class="portlet-body form">
					<div class="form-body">
						<div class="form-group">
							<label class="col-md-4 control-label">Attachment Filename</label>
							<div class="col-md-8">
								{{ Form::hidden('idProject',$id,array('id'=>'id')) }}
								{{ Form::text('attachment_name','',array('id'=>'attachment_name','class'=>'form-control','placeholder'=>'Attachment Names')) }}
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-4 control-label">Description</label>
							<div class="col-md-8">
								{{ Form::textarea('description','',array('id'=>'description','class'=>'form-control','size'=>'20x5')) }}
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-4">Attachment</label>
							<div class="col-md-8">
								<div class="fileinput fileinput-new" data-provides="fileinput">
									<span class="btn default btn-file">
									<span class="fileinput-new">
									Select file </span>
									<span class="fileinput-exists">
									Change </span>
									<input type="file" name="attachment_filename">
									</span>
									<span class="fileinput-filename">
									</span>
									&nbsp; <a href="#" class="close fileinput-exists" data-dismiss="fileinput">
									</a>
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-offset-3 col-md-9">
								<button type="submit" class="btn blue">Submit</button>
								<button type="button" class="btn default" onClick="$('#addForm').slideUp()">Cancel</button>
							</div>
						</div>
					</div>
				</div>
				{{ Form::close() }}
			</div>
		</div>
		{{ Form::close() }}
		<!-- END FORM-->
	</div>
<!-- END Content-->
@stop