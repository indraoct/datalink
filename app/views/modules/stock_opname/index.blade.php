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
                                                            "url": "{{ URL::to('/') }}/inventory/opname/get_data", // ajax source
                                                            
                                                    },
                                                    "order": [
                                                            [1, "asc"]
                                                    ], // set first column as a default sort by asc
                                                    "columns": [
                                                            { data: 'no', className: "alignCenter" },
                                                            { data: 'trx_no',},
                                                            { data: 'trx_date',},
                                                            { data: 'note',},
                                                            { data: 'action', bSortable: false, }
                                                    ]
                                            }
                                    });
		/* END DATA TABLE */
            
	
	       /* DEFINE ELEMENT & OPTIONS HERE */
		
		if (jQuery().datepicker) {
                    $('.date-picker').datepicker({
                        rtl: Metronic.isRTL(),
                        orientation: "left",
                        autoclose: true
                    });
                }
	
		/* END DEFINE ELEMENT  */
               
		
		// tombol delete
		$("#datatable tbody").on('click', "a.do_delete", function(){
					
			var oTable = grid.getDataTable();
			saveDelete(oTable.row($(this).parents('tr')).data().id);
		});
               
		
		/* END JS FUNCTION */

	};
		
	jQuery(document).ready(function() {     
		Metronic.init(); // init metronic core components
		Layout.init(); // init current layout
		construct();
                
                @if(Session::get('alert')=='success')
			toastr['success']('{{ Session::get("alert_msg") }}');
		@endif
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
					<a href="#">Stock Opname</a>
				</li>
				
			</ul>
			<!-- END PAGE TITLE & BREADCRUMB-->
		</div>
	</div>
	<!-- END PAGE HEADER-->
        
        
	<!-- BEGIN PAGE CONTENT-->
	<div class="row">
		<div class="col-md-12">
		
                    <div class="row">
					<div class="col-md-12">
						<div class="portlet"><div class="portlet-title">
               
			<div class="portlet-body">
                            <div class="portlet-title">
                                @if(hasPrivilege('stock_opname','new'))
					<div class="btn-group">
						<a href="{{ URL::to('/') }}/inventory/opname/new" class="btn btn green filter-submit tooltips" data-original-title="Create New" id="add"><i class="fa fa-plus"></i></a>
					</div>
                                @endif
			     </div>
					<div class="table-container">
						<table class="table table-striped table-bordered table-hover" id="datatable">
						<thead>
						<tr role="row" class="heading">
										<?php
											foreach($tableHeader as $content)
											{
												echo "<th>{$content['label']}</th>";
											}
										?>
                                                </tr>
						<tr role="row" class="filter">
                                                      <td>
                                                        
                                                      </td>
							<td>
								<input id="trx_no" type="text" class="form-control form-filter input-sm" name="filter[trx_no]">
							</td>
                                                        
							<td>
                                                            
								<div class="input-group date date-picker margin-bottom-5" data-date-format="{{ Config::get('format.date.default') }}">
                                                                        {{ Form::text('filter[date_from]','',array('class'=>'form-control form-filter input-sm','placeholder'=>'From','readonly'=>true)) }}
                                                                        <span class="input-group-btn">
                                                                        <button class="btn btn-sm default" type="button"><i class="fa fa-calendar"></i></button>
                                                                        </span>
                                                                </div>
                                                                <div class="input-group date date-picker" data-date-format="{{ Config::get('format.date.default') }}">
                                                                        {{ Form::text('filter[date_to]','',array('class'=>'form-control form-filter input-sm','placeholder'=>'To','readonly'=>true)) }}
                                                                        <span class="input-group-btn">
                                                                        <button class="btn btn-sm default" type="button"><i class="fa fa-calendar"></i></button>
                                                                        </span>
                                                                </div>
							</td>
							<td>
								<input id="note" type="text"  class="form-control form-filter input-sm" name="filter[note]">
							</td>
                                                      
							<td>
								<button id="cari" class="btn btn-sm yellow filter-submit tooltips" data-original-title="Cari"><i class="fa fa-search"></i> </button>
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
                         </div>
			<!-- End: life time stats -->
		</div>
	</div>
   </div>     
 
@stop