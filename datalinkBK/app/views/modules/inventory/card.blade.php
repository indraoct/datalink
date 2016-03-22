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

                
                function showDataStock(id_warehouse){
                
                /*
                 * fungsi untuk mendestroy #datatable supaya dapat re initialize datatable
                 */
                 if ( $.fn.dataTable.isDataTable( '#datatable' ) ) {
                             $('#datatable').DataTable().destroy();   
                         }
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
                                                                "url": "{{ URL::to('/') }}/inventory/data/card/get_stock_card", // ajax source
                                                                "data": { "filter": {"id_warehouse" : id_warehouse,"id_item" :<?php echo $inventory->id_item; ?>} },
                                                                
                                                        },
                                                        "order": [
                                                                [1, "asc"],
                                                        ], // set first column as a default sort by asc
                                                        
                                                        "columns": [
                                                                { data: 'no', className: "alignCenter" },
                                                                { data: 'trx_date',},
                                                                { data: 'trx_type',},
                                                                { data: 'trx_no',},
                                                                { data: 'capital_price',className: "alignRight"},
                                                                { data: 'stock_in',className: "alignRight"},
                                                                { data: 'stock_out',className: "alignRight"},
                                                                { data: 'remaining_stock',className: "alignRight"},
                                                                { data: 'balance',className: "alignRight"}
                                                        ]
                                                }
                                        });
                        /* END DATA TABLE */
                      

                
                }
                
                //show data tambahan
                function showdataTambahan(id_warehouse){
                    $.ajax({
                            url :"{{ URL::to('/') }}/inventory/data/get_data_tambahan",
                            type:'POST',
                            data:{'id_warehouse':id_warehouse,'id_item':'<?php echo $inventory->id_item; ?>'},
                            dataType:'JSON',
                            success:function(data){
                                     console.log(data);
                                 if(typeof data.stock_initial !== 'undefined'){    
                                     $('#initial_stock').html(data.stock_initial);
                                 }else{
                                     $('#initial_stock').html("0");
                                 }    
                                 
                                 if(data.stock_in !== null){
                                      $('#stock_in').html(data.stock_in);
                                 }else{
                                     $('#stock_in').html("0");
                                 }    
                                     
                                 if(data.stock_out !== null){
                                      $('#stock_out').html(data.stock_out);
                                 }else{
                                     $('#stock_out').html("0");
                                 }  
                                 if(data.remaining_stock !== null){
                                      $('#remaining_stock').html(data.remaining_stock);
                                 }else{
                                     $('#remaining_stock').html("0");
                                 } 
                                     
                                 if(data.balance !== null){    
                                     $('#balance').html(data.balance);
                                 }else{
                                     $('#balance').html("0");
                                 }
                            }
                       });
                    
                }
               
                /* BEGIN EVENT HANDLER */
	         
                 //Filter id_warehouse
                 $('#filter').on('click',function(){
                     if ( $.fn.dataTable.isDataTable( '#datatable' ) ) {
                          
                          
                          showDataStock($('#id_warehouse').val());
                           var id_warehouse = $('#id_warehouse').val();
                              showdataTambahan(id_warehouse);
                              $('#post_id_warehouse').val(id_warehouse);
                       }     
                 });
                 
                 //on load
                 $('#stock_card').on('load',function(){
                     showDataStock('');
                 });
                 
                 /* END EVENT HANDLER */
                
		
		/* BEGIN JS FUNCTION */
		
		
		function clearError()
		{
			$('.form-group').removeClass('has-error'); 
			$('.form-control').attr('data-original-title','');
		}
                
                   //--------JQUERY perubahan status active - inactive       
                $(".bootstrap-switch").on("click",function(){
                    
                    if($(".bootstrap-switch").hasClass('bootstrap-switch-on') === true){
                        
                        $("#status").val(1);
                    }else{
                        $("#status").val(0);
                    }
                });
  

		/* END JS FUNCTION */

	};
        
        function showDataStockUtama(id_warehouse){
                
                /*
                 * fungsi untuk mendestroy #datatable supaya dapat re initialize datatable
                 */
                 if ( $.fn.dataTable.isDataTable( '#datatable' ) ) {
                             $('#datatable').DataTable().destroy();   
                         }
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
                                                                "url": "{{ URL::to('/') }}/inventory/data/card/get_stock_card", // ajax source
                                                                "data": { "filter": {"id_warehouse" : id_warehouse,"id_item" :<?php echo $inventory->id_item; ?>} },
                                                                
                                                        },
                                                        "order": [
                                                                [1, "asc"],
                                                        ], // set first column as a default sort by asc
                                                        
                                                        "columns": [
                                                                { data: 'no', className: "alignCenter" },
                                                                { data: 'trx_date',},
                                                                { data: 'trx_type',},
                                                                { data: 'trx_no',},
                                                                { data: 'capital_price',className: "alignRight"},
                                                                { data: 'stock_in',className: "alignRight"},
                                                                { data: 'stock_out',className: "alignRight"},
                                                                { data: 'remaining_stock',className: "alignRight"},
                                                                { data: 'balance',className: "alignRight"}
                                                        ]
                                                }
                                        });
                        /* END DATA TABLE */
         }           
         
         function showdataTambahanUtama(id_warehouse){
                    $.ajax({
                            url :"{{ URL::to('/') }}/inventory/data/get_data_tambahan",
                            type:'POST',
                            data:{'id_warehouse':id_warehouse,'id_item':'<?php echo $inventory->id_item; ?>'},
                            dataType:'JSON',
                            success:function(data){
                                     console.log(data);
                                 if(typeof data.stock_initial !== 'undefined'){    
                                     $('#initial_stock').html(data.stock_initial);
                                 }else{
                                     $('#initial_stock').html("0");
                                 }    
                                 
                                 if(data.stock_in !== null){
                                      $('#stock_in').html(data.stock_in);
                                 }else{
                                     $('#stock_in').html("0");
                                 }    
                                     
                                 if(data.stock_out !== null){
                                      $('#stock_out').html(data.stock_out);
                                 }else{
                                     $('#stock_out').html("0");
                                 }  
                                 if(data.remaining_stock !== null){
                                      $('#remaining_stock').html(data.remaining_stock);
                                 }else{
                                     $('#remaining_stock').html("0");
                                 } 
                                     
                                 if(data.balance !== null){    
                                     $('#balance').html(data.balance);
                                 }else{
                                     $('#balance').html("0");
                                 }
                            }
                       });
                    
                }
            
        
	function credit()
	{
		$(".credit").slideUp( "slow" );
		if( $('#rTop').val() == 2)
		{
			$(".credit").slideDown( "slow" );
		}
	}

	jQuery(document).ready(function() {     
		Metronic.init(); // init metronic core components
		Layout.init(); // init current layout
		construct();
                showDataStockUtama('');
                showdataTambahanUtama('');
		credit();
	});
</script>
@stop
@section('content')
<!-- BEGIN PAGE HEADER-->
	<div id="stock_card" class="row">
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
					<a href="#">Stock Card</a>
				</li>
			
			</ul>
			<!-- END PAGE TITLE & BREADCRUMB-->
		</div>
	</div>
	<!-- END PAGE HEADER-->
        <!-- BEGIN STOCK -->
         {{ Form::open(array('action' => 'InventoryController@showInventoryCardPost','id'=>'iniForm','method'=>'POST')) }}
        <div class="row">
		<div class="col-md-12">
                    <div class="row">
                        <div class="form-group">
                                        <label class="col-md-3 control-label">Item Code:</label>
                                        <div class="col-md-3">
                                                <label  class="control-label"><?php echo $inventory->item_code; ?></label>
                                        </div>
                        </div>
                        
                    </div>
                    <div class="row">
                        <div class="form-group">
                                        <label class="col-md-3 control-label">Item Name:</label>
                                        <div class="col-md-3">
                                                <label  class="control-label"><?php echo $inventory->item_name; ?></label>
                                        </div>
                        </div>
                        
                    </div>
                    <!-- Begin: life time stats -->
                      <div class="form-body">
                              <div class="col-md-8">
                                      <div class="form-group">  
                                            <div class="btn-group">
                                                    <label class="col-md-3 control-label"> Warehouse:</label>
                                                    <div class="col-md-5">
                                                        <input type="hidden" value="<?php echo $inventory->id_stock; ?>" name='id_stock'>
                                                        <input type="hidden" value="<?php echo  $inventory->id_item; ?>" name='id_item'>
                                                        <input id='post_id_warehouse' type="hidden" value="" name='id_warehouse'>
                                                       
                                                            {{ Form::select('filter[id_warehouse]',$listWarehouse,null,array('id'=>'id_warehouse','class'=>'form-control form-filter select2 input-sm')) }}

                                                         </div>
                                                         <div class="col-md-4">
                                                             <input value="Process" type="button" id='filter' class="btn fa fa-search  blue  tooltips" />
                                                            </div> 
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
    {{ Form::close() }}                 
    
    <div class="form-body">    
    <div class="row">
     
                 <div class="form-group">
                                        <label class="col-md-3 control-label">Initial Stock:</label>
                                        <div class="col-md-3">
                                                <label id="initial_stock" class="control-label"></label>
                                        </div>
                </div>
    </div>   
     <div class="row">    
                <div class="form-group">
                                        <label class="col-md-3 control-label">Stock In:</label>
                                        <div class="col-md-3">
                                                <label id="stock_in" class="control-label"></label>
                                        </div>
                </div>
     </div>    
      <div class="row">   
                <div class="form-group">
                                        <label class="col-md-3 control-label">Stock Out:</label>
                                        <div class="col-md-3">
                                                <label id="stock_out" class="control-label"></label>
                                        </div>
                </div>
      </div>
       <div class="row">  
                <div class="form-group">
                                        <label class="col-md-3 control-label">Remaining Stock:</label>
                                        <div class="col-md-3">
                                                <label id="remaining_stock" class="control-label"></label>
                                        </div>
                </div>
       </div>
        <div class="row"> 
               <div class="form-group">
                                        <label class="col-md-3 control-label">Balance:</label>
                                        <div class="col-md-3">
                                                <label id="balance" class="control-label"></label>
                                        </div>
                </div>
        </div>    
        


    
 </div>    
        
<!-- END STOCK -->
@stop        