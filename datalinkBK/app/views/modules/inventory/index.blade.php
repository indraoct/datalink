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
                                                            "url": "{{ URL::to('/') }}/inventory/data/get_data", // ajax source
                                                            "data": { "filter": {"id_warehouse" : $('#id_warehouse').val(),
                                                                                "item_code" : $('#item_code').val(),
                                                                                "item_name" : $('#item_name').val(),
                                                                                "qty_from" : $('#qty_from').val(),
                                                                                "qty_to" : $('#qty_to').val(),
                                                                                "stock_min_from" : $('#stock_min_from').val(),
                                                                                "stock_min_to" : $('#stock_min_to').val(),
                                                                                "stock_max_from" : $('#stock_max_from').val(),
                                                                                "stock_max_to" : $('#stock_max_to').val()}},
                                                    },
                                                    "order": [
                                                            [1, "asc"]
                                                    ], // set first column as a default sort by asc
                                                    "columns": [
                                                            { data: 'no', className: "alignCenter" },
                                                            { data: 'item_code',},
                                                            { data: 'item_name_link',},
                                                            { data: 'qty',className: "alignRight"},
                                                            { data: 'min_stock',className: "alignRight"},
                                                            { data: 'max_stock',className: "alignRight"},
                                                            { data: 'action', bSortable: false, }
                                                    ]
                                            }
                                    });
		/* END DATA TABLE */
                
                function showDataStock(){
                                    /* BEGIN DATA TABLE */
                                    if ( $.fn.dataTable.isDataTable( '#datatable' ) ) {
                                        $('#datatable').DataTable().destroy();   
                                    }

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
                                                                                "url": "{{ URL::to('/') }}/inventory/data/get_data", // ajax source
                                                                                "data": { "filter": {"id_warehouse" : $('#id_warehouse').val(),
                                                                                                    "item_code" : $('#item_code').val(),
                                                                                                    "item_name" : $('#item_name').val(),
                                                                                                    "qty_from" : $('#qty_from').val(),
                                                                                                    "qty_to" : $('#qty_to').val(),
                                                                                                    "stock_min_from" : $('#stock_min_from').val(),
                                                                                                    "stock_min_to" : $('#stock_min_to').val(),
                                                                                                    "stock_max_from" : $('#stock_max_from').val(),
                                                                                                    "stock_max_to" : $('#stock_max_to').val()}},
                                                                        },
                                                                        "order": [
                                                                                [1, "asc"]
                                                                        ], // set first column as a default sort by asc
                                                                        "columns": [
                                                                                { data: 'no', className: "alignCenter" },
                                                                                { data: 'item_code',},
                                                                                { data: 'item_name_link',},
                                                                                { data: 'qty',className: "alignRight"},
                                                                                { data: 'min_stock',className: "alignRight"},
                                                                                { data: 'max_stock',className: "alignRight"},
                                                                                { data: 'action', bSortable: false, }
                                                                        ]
                                                                }
                                                        });
                                    /* END DATA TABLE */
                                }
	
		/* BEGIN EVENT HANDLER */
                
                $('#cari').on('click',function(){
                   showDataStock(); 
                });
		
		// tombol delete
		$("#datatable tbody").on('click', "a.do_delete", function(){
					
			var oTable = grid.getDataTable();
			saveDelete(oTable.row($(this).parents('tr')).data().id);
		});
                
                

		function saveDelete(id)
		{
			bootbox.confirm("Are you sure want to delete this data?", function(result) {
			   if(result)
			   {
					$.ajax({
						url: "{{ URL::to('/') }}/inventory/data/delete",
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
					<a href="#">View Stock</a>
				</li>
				
			</ul>
			<!-- END PAGE TITLE & BREADCRUMB-->
		</div>
	</div>
	<!-- END PAGE HEADER-->
        
        {{ Form::open(array('action' => app()->make('router')->currentRouteAction(),'id'=>'iniForm')) }}
	<!-- BEGIN PAGE CONTENT-->
	<div class="row">
		<div class="col-md-12">
			<!-- Begin: life time stats -->
				<div class="col-md-5">
					<div class="form-body">
						<div class="form-group">  
								<label class="col-md-3 control-label"> Warehouse:</label>
								<div class="col-md-5">
										{{ Form::select('filter[id_warehouse]',$listWarehouse,null,array('id'=>'id_warehouse','class'=>'form-control form-filter select2 input-sm')) }}

									 </div>
									 <div class="col-md-4">
										 <input value="Process" type="button" onclick="$('#cari').click()" class="btn blue" />
										</div> 
						  </div>
                      </div>
				  </div>
                    <script>
				function exportData(thisD,xport)
				{
					var $form = $('#iniForm');
					console.log($form);
					var data = $form.find('input[name="export"]').val(xport);
					$form.submit();
				}
	            </script>    
                    <div class="row">
					<div class="col-md-12">
						<div class="portlet"><div class="portlet-title">
                                                    <div class="caption">
                                                    </div>
                                                                 <!-- BEGIN EXPORT -->
                                                    <div class="actions"> 
                                   <div class="btn-group">
                                                    <a class="btn default yellow-stripe" href="javascript:;" data-toggle="dropdown" data-hover="dropdown" data-delay="1000" data-close-others="true">
                                                    <!--<i class="fa fa-print"></i>-->
                                                    <span class="hidden-480">
                                                            Export {{ Form::hidden('export','',array()) }}
                                                            {{ Form::hidden('report',$report,array()) }}
                                                    </span>
                                                    <i class="fa fa-angle-down"></i>
                                                    </a>
                                                    <ul class="dropdown-menu pull-right">
                                                            <li>
                                                                    <a href="javascript:;" onClick = "exportData(this,'csv')">
                                                                            Export CSV
                                                                    </a>
                                                            </li>
                                                            <li>
                                                                    <a href="javascript:;" class="print" onClick = "exportData(this,'pdf')">
                                                                            Export PDF
                                                                    </a>
                                                            </li>
                                                            <li>
                                                                    <a href="javascript:;" class="print" onClick = "exportData(this,'xls')">
                                                                            Export Excel
                                                                    </a>
                                                            </li>
                                                    </ul>
                                    </div> 
                                </div>  
                                                             <!-- END EXPORT -->
				
                            
			<div class="portlet-body">
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
								<input id="item_code" type="text" class="form-control form-filter input-sm" name="filter[item_code]">
							</td>
							<td>
								<input id="item_name" type="text" class="form-control form-filter input-sm" name="filter[item_name]">
							</td>
							<td>
								<input id="qty_from" type="text" placeholder="From" class="form-control form-filter input-sm" name="filter[qty_from]">
                                                                <br/>
                                                                <input id="qty_to" type="text" placeholder="To" class="form-control form-filter input-sm" name="filter[qty_to]">
							</td>
                                                        <td>
                                                                <input id="stock_min_from" type="text" placeholder="From" class="form-control form-filter input-sm" name="filter[stock_min_from]">
                                                                <br/>
                                                                <input id="stock_min_to" type="text" placeholder="To" class="form-control form-filter input-sm" name="filter[stock_min_to]">
								
							</td>
                                                        <td>
								<input id="stock_max_from" type="text" placeholder="From" class="form-control form-filter input-sm" name="filter[stock_max_from]">
                                                                <br/>
                                                                <input id="stock_max_to" type="text" placeholder="To" class="form-control form-filter input-sm" name="filter[stock_max_to]">
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
   <!-- End Dialog Form -->
{{ Form::close() }}     
@stop