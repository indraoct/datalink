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
		/* BEGIN EVENT HANDLER */
		// tombol save
//		$("#button").on('click', "#save", function(){
//			save();
//		});
                
                        // tombol proses
		$("#button").on('click', "#process", function(){
			
			validate();
		});
                
                
                $("#button").on('click',"#goToEdit",function(){
                    
                    
                    $(location).attr('href',"{{ URL::to('/') }}/item/edit/{{encode($item->id_item)}}");
                    
                });
		
		/* END EVENT HANDLER */
                
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
                                                                "url": "{{ URL::to('/') }}/item/get_data_stock", // ajax source
                                                                "data": { "filter": {"id_warehouse" : id_warehouse,"id_item" :<?php echo $item->id_item; ?>} },
                                                                
                                                        },
                                                        "order": [
                                                                [1, "asc"],
                                                        ], // set first column as a default sort by asc
                                                        
                                                        "columns": [
                                                                { data: 'no', className: "alignCenter" },
                                                                { data: 'trx_date',},
                                                                { data: 'warehouse_name',},
                                                                { data: 'trx_type',},
                                                                { data: 'trx_no',},
                                                                { data: 'capital_price', className: "alignRight"},
                                                                { data: 'stock_in', className: "alignRight"},
                                                                { data: 'stock_out', className: "alignRight"},
                                                                { data: 'remaining_stock', className: "alignRight"},
                                                                { data: 'balance', className: "alignRight"}
                                                        ]
                                                }
                                        });
                        /* END DATA TABLE */
                      

                
                }
                
                //show data tambahan
                function showdataTambahan(id_warehouse){
                    $.ajax({
                            url :"{{ URL::to('/') }}/item/get_data_tambahan",
                            type:'POST',
                            data:{'id_warehouse':id_warehouse,'id_item':'<?php echo $item->id_item; ?>'},
                            dataType:'JSON',
                            success:function(data){
                                     console.log(data);
                                 if(data.stock_initial !== null){    
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
               
                $('#stockViewClick').on('click',function(){
                            
                              showDataStock($('#id_warehouse').val());
                              
                              var id_warehouse = $('#id_warehouse').val();
                              showdataTambahan(id_warehouse);
                         });
                         
                 //Filter id_warehouse
                 $('#filter').on('click',function(){
                     if ( $.fn.dataTable.isDataTable( '#datatable' ) ) {
                          
                          
                          showDataStock($('#id_warehouse').val());
                           var id_warehouse = $('#id_warehouse').val();
                              showdataTambahan(id_warehouse);
                              $('#post_id_warehouse').val(id_warehouse);
                       }     
                 });
                
		
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
                
                
                //create item unit dapat di add
                $("#itemUnit").select2({
			placeholder: "-- Pilih --",
			maximumInputLength: 100,
			formatNoMatches: function(term) {
				return '<input class="form-control" id="new_itemUnit" value="'+term+'">'+
						   '<button class="btn btn-sm green filter-submit" id="add_itemUnit">Add New</button>'
			}
		})
		.parent().find('.select2-with-searchbox').on('click','#add_itemUnit',function(){
		
			var newTerm = $('#new_itemUnit').val();
			
			var id = newItemUnit(newTerm);
			
			if(id!='')
			{
				$('<option value="'+id+'">'+newTerm+'</option>').appendTo('#itemUnit');
				$('#itemUnit').select2('val',id); // select the new term
				$("#itemUnit").select2('close');		// close the dropdown
			}
		});
                
                function newItemUnit(unit_name)
		{			
			var id = '';
			$.ajax({
				url: "{{ URL::to('/') }}/item/addUnit",
				async: false,
				type:"post",
				data: 'unit_name='+unit_name+'&from=dropdown',
				beforeSend:function(){
					Metronic.blockUI();
				},
				success:function(result){
					var result = eval('('+result+')');
					Metronic.unblockUI();
					// alert(result);
					
					id = result.id;
					
					if(result.status)
					{
						toastr['success'](result.alert_msg);
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
			return id;
		}
                
        
                
                function validate(){
                    
                     $.ajax({
                        url: "{{ URL::to('/') }}/item/validate",
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
                                                         $("textarea").each(function(){
                                                     $(this).val($(this).val().replace(/\r\n|\r|\n/g,"<br />"));
                                                     });
                                                        $("#form").submit();
                            }
                                                else
                                                {
                                                        toastr['error'](result.alert_msg);
                                                        var errors = result.error_msg;
                                                        Object.keys(errors).forEach(function(key) {
                                                                if(errors[key])
                                                                {
                                                                        $('#'+key).closest('.form-group').addClass('has-error'); 
                                                                        $('#'+key).attr("data-original-title", errors[key]).tooltip();
                                                                        $('#s2id_'+key).attr("data-original-title", errors[key]).tooltip();
                                                                }
                                                        });
                            }
                        },
                        error:function(x,h,r)
                                        {
                            alert(r);
                        }
                    });
                    
                    
                }

		function save()
		{
			clearError();
            $.ajax({
                url: "{{ URL::to('/') }}/item/{{$act}}",
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
						window.location.href ="{{ URL::to('/') }}/item";
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

	
	function renderData()
	{
		$('#idItem').val( '{{$item->id_item}}' ); 
                $('#itemCode').val( '{{$item->item_code}}' );
		$('#itemName').val( '{{$item->item_name}}' );
                $('#barcode').val( '{{$item->barcode}}' );
		$('#itemUnit').select2('val', '{{$item->id_unit}}');
                $('#itemCategory').select2('val', '{{$item->id_category}}');
                $('#itemType').select2('val', '{{$item->id_type}}');
                $('#itemBrand').select2('val', '{{$item->id_brand}}');
                $('#description').val( '{{$item->description}}' );
		$('#tax').select2('val', '{{$item->tax_type}}');
                $('#status').val('{{$item->status}}');
                
                $("textarea").each(function(){
				$(this).val($(this).val().replace(/<br\s?\/?>/g,"\n"));
		});
                
                
	}
        
        <?php
		
	      if($segment == 'detail'){	
	?>
          
          function showDataDetail(){
              
                $('#idItem').attr('disabled','disabled');
                $('#itemCode').attr('disabled','disabled');
		$('#itemName').attr('disabled','disabled');
                $('#barcode').attr('disabled','disabled');
		$('#itemUnit').attr('disabled','disabled');
                $('#itemCategory').attr('disabled','disabled');
                $('#itemType').attr('disabled','disabled');
                $('#itemBrand').attr('disabled','disabled');
                $('#description').attr('disabled','disabled');
		$('#tax').attr('disabled','disabled');
              
                $('#inventory_select').attr('disabled','disabled');
                $('#cogs').attr('disabled','disabled');
                $('#sales_select').attr('disabled','disabled');
                
                $('#minimumStock').attr('disabled','disabled');
                $('#maximumStock').attr('disabled','disabled');
              
          }
          
        <?php
              }else{
        
        ?>
            function showDataDetail(){
             
              
          }
         <?php
              }
        
        ?>    
            
        <?php 
            if($item->is_stockable == 0 || $item->is_stockable == "" ){
       
        ?>    
            $('#stockViewClick').attr('style','display:none');
        <?php
              }
        
        ?>    
	
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
		renderData();
                showDataDetail();
		credit();
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
					<a href="{{ URL::to('/') }}/item">Item</a>
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
        <form method="post" role="form" id="form" class="form-horizontal" action="{{ URL::to('/') }}/item/Edit">
							<div class="form-body">
									<ul class="nav nav-tabs">
										<li class="active">
											<a href="#general" data-toggle="tab">
											General </a>
										</li>
                                                                                
                                                                                <li id="stockView">
											<a id='stockViewClick' href="#stock" data-toggle="tab">
											Stock</a>
										</li>
									
										<li>
											<a href="#account" data-toggle="tab">
											Accounting</a>
										</li>
									</ul>
									<div class="tab-content">
									
										<div class="tab-pane active" id="general">
											<!-- BEGIN GENERAL-->
											<div class="form-body">
												<div class="row">
													<div class="col-md-6">
														<div class="form-group">
															<label class="col-md-4 control-label">Item Code</label>
															<div class="col-md-6">
																{{ Form::hidden('id','',array('id'=>'idItem')) }}
																{{ Form::text('itemCode','',array('id'=>'itemCode','class'=>'form-control')) }}
															</div>
														</div>
                                                                                                                <div class="form-group">
															<label class="col-md-4 control-label">Barcode</label>
															<div class="col-md-6">
																{{ Form::text('barcode','',array('id'=>'barcode','class'=>'form-control')) }}
															</div>
														</div>
                                                                                                                <div class="form-group">
															<label class="col-md-4 control-label">Item Name</label>
															<div class="col-md-6">
																{{ Form::text('itemName','',array('id'=>'itemName','class'=>'form-control')) }}
															</div>
														</div>
                                                                                                                <div class="form-group">
															<label class="col-md-4 control-label">Item Unit</label>
															<div class="col-md-6">
																{{Form::select('itemUnit',$listUnit,0,array('id'=>'itemUnit','class'=>'form-control select2')) }}
                                                                                                                                <span class="help-block help-block-error" for="itemUnit"></span>
															</div>
														</div>
														<div class="form-group">
															<label class="col-md-4 control-label">Item Category</label>
															<div class="col-md-6">
																{{Form::select('itemCategory',$listCategory,0,array('id'=>'itemCategory','class'=>'form-control select2')) }}
															</div>
														</div>
                                                                                                                <div class="form-group">
															<label class="col-md-4 control-label">Item Type</label>
															<div class="col-md-6">
																{{Form::select('itemType',$listType,0,array('id'=>'itemType','class'=>'form-control select2')) }}
															</div>
														</div>
                                                                                                            
                                                                                                                <div class="form-group">
															<label class="col-md-4 control-label">Item Brand</label>
															<div class="col-md-6">
																{{Form::select('itemBrand',$listBrand,0,array('id'=>'itemBrand','class'=>'form-control select2')) }}
															</div>
														</div>
                                                                                                                <div class="form-group">
															<label class="col-md-4 control-label">Description</label>
															<div class="col-md-6">
																{{ Form::textarea('description','',array('id'=>'description','class'=>'form-control','size'=>'10x3')) }}
															</div>
														</div>
                                                                                                                <div class="form-group">
															<label class="col-md-4 control-label">Tax</label>
															<div class="col-md-6">
																{{Form::select('tax',$listTax,0,array('id'=>'tax','class'=>'form-control select2')) }}
															</div>
														</div>
                                                                                                                <div class="form-group">
                                                                                                                    <label class="col-md-4 control-label">Status</label>
                                                                                                                    <div class="col-md-6">
															
                                                                                                                        <input <?php echo ($segment == "detail")?'disabled':''; ?> <?php echo ($item->status == 1)?'checked':''; ?>   id="status" type="radio" name="status"  class="make-switch switch-radio1"  >
                                                                                                                  
                                                                                                                    </div>
                                                                                                                </div> 
                                                                                                                
													</div>
													<!--/span-->
													
												</div>
												<!--/row-->
												
											</div>
											<!-- END GENERAL-->
										</div>
                                                                                
                                                                                
                                                                                <div class="tab-pane fade" id="stock">
                                                                                <!-- BEGIN STOCK -->
                                                                                    <div class="row">
                                                                                            <div class="col-md-12">
                                                                                                <div class="row">
                                                                                                    <div class="form-group">
                                                                                                                    <label class="col-md-2 control-label">Item Code:</label>
                                                                                                                    <div class="col-md-3">
                                                                                                                            <label  class="control-label"><?php echo $item->item_code; ?></label>
                                                                                                                    </div>
                                                                                                    </div>

                                                                                                </div>
                                                                                                <div class="row">
                                                                                                    <div class="form-group">
                                                                                                                    <label class="col-md-2 control-label">Item Name:</label>
                                                                                                                    <div class="col-md-3">
                                                                                                                            <label  class="control-label"><?php echo $item->item_name; ?></label>
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
                                                                                                                                    <input type="hidden" value="<?php echo  $item->id_item; ?>" name='id_item'>
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
                                                                                                                    var $form = $('#form');
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
                                                                                                        
                                                                                                    <div class="portlet-body">
                                                                                                                    <div class="table-container">
                                                                                                                            <table class="table table-striped table-bordered table-hover" id="datatable">
                                                                                                                             
                                                                                                                              
  
                                                                                                                              
                                                                                                                            <thead>
                                                                                                                            <tr role="row" class="heading">
                                                                                                                                    <th width="5%">
                                                                                                                                            #
                                                                                                                                    </th>
                                                                                                                                    <th width="20%">
                                                                                                                                             Date
                                                                                                                                    </th>
                                                                                                                                    <th width="10%">
                                                                                                                                             Warehouse
                                                                                                                                    </th>
                                                                                                                                    <th width="10%">
                                                                                                                                             Transaction Type
                                                                                                                                    </th>
                                                                                                                                    <th width="10%">
                                                                                                                                             Transaction No
                                                                                                                                    </th>
                                                                                                                                    <th width="10%">
                                                                                                                                             Capital Price
                                                                                                                                    </th>
                                                                                                                                    <th width="5%">
                                                                                                                                             Stock In
                                                                                                                                    </th>
                                                                                                                                    <th width="5%">
                                                                                                                                             Stock Out
                                                                                                                                    </th>
                                                                                                                                    <th width="10%">
                                                                                                                                             Remaining Stock
                                                                                                                                    </th>
                                                                                                                                    
                                                                                                                                    <th width="10%">
                                                                                                                                             Balance 
                                                                                                                                    </th>
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
                                                                                   
                                                                               
                                                                                    
                                                                                    <div class="row">
                                                                                        <div class="col-md-6">
                                                                                                 <div class="form-group">
															<label class="col-md-3 control-label">Initial Stock:</label>
															<div class="col-md-3">
                                                                                                                            <label style="text-align: right;" id="initial_stock" class="control-label"></label>
															</div>
                                                                                                </div>
                                                                                                <div class="form-group">
															<label style="text-align: right;" class="col-md-3 control-label">Stock In:</label>
															<div class="col-md-3">
																<label id="stock_in" class="control-label"></label>
															</div>
                                                                                                </div>
                                                                                                <div class="form-group">
															<label style="text-align: right;" class="col-md-3 control-label">Stock Out:</label>
															<div class="col-md-3">
																<label id="stock_out" class="control-label"></label>
															</div>
                                                                                                </div>
                                                                                                <div class="form-group">
															<label style="text-align: right;" class="col-md-3 control-label">Remaining Stock:</label>
															<div class="col-md-3">
																<label id="remaining_stock" class="control-label"></label>
															</div>
                                                                                                </div>
                                                                                               <div class="form-group">
															<label style="text-align: right;" class="col-md-3 control-label">Balance:</label>
															<div class="col-md-3">
																<label id="balance" class="control-label"></label>
															</div>
                                                                                                </div>
                                                                                        </div>
                                                                                        
                                                                                        <div class="col-md-6">
                                                                                            <div class="form-group">
															<label  style="text-align: right;" class="col-md-3 control-label">Minimum Stock</label>
															<div class="col-md-5">
																{{ Form::text('minimumStock',$item->min_stock,array('id'=>'minimumStock','class'=>'form-control currency')) }}
															</div>
                                                                                        </div>
                                                                                        <div class="form-group">
															<label style="text-align: right;" class="col-md-3 control-label">Maximum Stock</label>
															<div class="col-md-5">
																{{ Form::text('maximumStock',$item->max_stock,array('id'=>'maximumStock','class'=>'form-control currency')) }}
															</div>
                                                                                        </div>    
                                                                                    </div>
                                                                                        
                                                                                    </div>
                                                                             </div>   
                                                                                <!-- END STOCK -->
                                                                                
										<div class="tab-pane fade" id="account">
											<!-- BEGIN ACCOUNTING-->
											<div class="form-body">
												<div class="row">
													<div class="col-md-6">
														<div class="portlet box blue">
															<div class="portlet-title">
																<div class="caption">
																	<i class="fa fa-gift"></i> Acount
																</div>
															</div>
														</div>
														<div class="form-group">
															<label class="col-md-4 control-label">Inventory</label>
															<div class="col-md-6">
																{{Form::select('inventory',$listInventory,0,array('id'=>'inventory_select','class'=>'form-control select2')) }}
															</div>
														</div>
														<div class="form-group">
															<label class="col-md-4 control-label">COGS</label>
															<div class="col-md-6">
																{{Form::select('cogs',$listCOGS,0,array('id'=>'cogs','class'=>'form-control select2')) }}
															</div>
														</div>
                                                                                                                <div class="form-group">
															<label class="col-md-4 control-label">Sales</label>
															<div class="col-md-6">
																{{Form::select('sales',$listSales,0,array('id'=>'sales_select','class'=>'form-control select2')) }}
															</div>
														</div>
														
                                                                                                                   
													</div>
													
													<div class="col-md-6">
														<div class="portlet box blue">
															<div class="portlet-title">
																<div class="caption">
																	<i class="fa fa-gift"></i> List Purchase
																</div>
															</div>
														</div>
														<div class="form-group">
															
															<div class="col-md-6">
																{{ Form::textarea('purchase','',array('id'=>'purchase','class'=>'form-control','size'=>'10x3')) }}
                                                                                                                                
                                                                                                                        </div>
														</div>
														
													</div>
												</div>
											</div>
											<!-- END CONTACT-->
										</div>
									</div>
							</div>
						</form>

				<div id="button" class="modal-footer">
					<a href="{{ URL::to('/') }}/item"><button type="button" class="btn default" >Back</button></a>
                                        
                                        <?php 
                                              if($segment == 'detail'){
                                        ?>
                                           <button type="button" id="goToEdit" class="btn blue">Edit</button>
                                        <?php
                                              }else{
                                        ?>    
					    <button type="button" id="process" class="btn blue">Process</button>
                                        <?php
                                              }
                                        ?>     
				</div>
	<!-- End Dialog Form -->
	
	<!-- END PAGE CONTENT-->
@stop