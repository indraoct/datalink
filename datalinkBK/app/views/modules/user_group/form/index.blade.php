@extends('layouts.admin.default')

@section('css')
	<!-- BEGIN PAGE LEVEL STYLES -->
	<link href="{{ URL::asset('/assets/global/plugins/bootstrap-datepicker/css/datepicker3.css') }}" rel="stylesheet" type="text/css"/>
	<!-- END PAGE LEVEL SCRIPTS -->
@stop

@section('js')
	<!-- BEGIN PAGE LEVEL PLUGINS -->
	<script type="text/javascript" src="{{ URL::asset('/assets/global/plugins/bootbox/bootbox.min.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('/assets/global/plugins/moment.min.js') }}"></script>
	<!-- END PAGE LEVEL PLUGINS -->
	<script>
	
	function construct() {
	
		/* DEFINE ELEMENT & OPTIONS HERE */
	
		/* END DEFINE ELEMENT  */
	
		/* BEGIN EVENT HANDLER */
	
		$("#pm_sales,#pm_sales_master,#open_project,#pm_sales_sales,#procurement,#procurement_master,#inventory,#inventory_master,#form_request,#create_request,#accounting,#accounting_master,#accounting_treasury,#accounting_report,#user_management").click(function(event) 
		{
			var id = $(this).attr('id');
			if(this.checked)
			{
				$('input:checkbox.'+id).each(function () {
					$(this).prop('checked',true);
					$(this).closest('span').addClass('checked');
				});
			}
			else
			{
				$('input:checkbox.'+id).each(function () {
					$(this).prop('checked',false);
					$(this).closest('span').removeClass('checked');
				});
			}
		});    
				
		// tombol proses
		$(".form").on('click', "#process", function(){
			
			validate();
		});
		
		/* END EVENT HANDLER */
		
		/* BEGIN JS FUNCTION */
		
		function clearError()
		{
			$('.item_row').removeClass('has-error'); 
			$('.form-group').removeClass('has-error'); 
			$('.form-control').attr('data-original-title','');
		}
		
		function validate()
		{			
            $.ajax({
                url: "{{ URL::to('/') }}/user_group/validate",
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
            })
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
					<a href="#">User Management</a>
					<i class="fa fa-angle-right"></i>
				</li>
				<li>
					<a href="{{ URL::to('/') }}/user_group">User Group</a>
					<i class="fa fa-angle-right"></i>
				</li>
				<li>
					New
				</li>
			</ul>
			<!-- END PAGE TITLE & BREADCRUMB-->
		</div>
	</div>
	<!-- END PAGE HEADER-->
	
	<!-- BEGIN PAGE CONTENT-->
	<!-- BEGIN FORM-->
	<form id="form" method="post" action="{{ URL::to('/') }}/user_group/save" class="form-horizontal">
		<div class="row">
			<div class="col-md-12">
				<div class="portlet yellow-crusta box">
					<div class="portlet-title">
						<div class="caption">
							<i class="fa fa-cogs"></i>Group Detail
						</div>
						<div class="tools">
							<a href="javascript:;" class="collapse">
							</a>
						</div>
					</div>
					<div class="portlet-body form">
						<div class="form-body">
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label class="control-label col-md-2">Group Name</label>
										<div class="col-md-8">
											{{ Form::hidden('do',$do,array('id'=>'do')) }}
											{{ Form::hidden('id_group',$id_group,array('id'=>'id_group')) }}
											<?php $group_name = isset($data['group_name']) ? $data['group_name'] : null; ?>
											{{ Form::text('group_name',$group_name,array('id'=>'group_name','class'=>'form-control')) }}
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label class="control-label col-md-2">Description</label>
										<div class="col-md-8">
											<?php $description = isset($data['description']) ? $data['description'] : null; ?>
											{{ Form::textarea('description',$description,array('id'=>'description','class'=>'form-control','size'=>'10x2')) }}
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="portlet box red">
					<div class="portlet-title">
						<div class="caption">
							<i class="fa fa-cogs"></i>Privilege
						</div>
						<div class="tools">
							<a href="javascript:;" class="collapse">
							</a>
						</div>
					</div>
					<div class="portlet-body form">
						<div class="form-body">
							<div class="table-container">
								<table class="table table-striped table-bordered table-hover" id="datatable">
								<thead>
								<tr role="row" class="heading">
									<th width="50%">Module Name</th>
									<th width="10%">View</th>
									<th width="10%">New</th>
									<th width="10%">Edit</th>
									<th width="10%">Delete</th>
									<th width="10%">Other</th>
								</tr>
								</thead>
								<tbody>
									<?php $default_privi =array('view'=>'','new'=>'','edit'=>'','delete'=>'') ?>
									<!-- PM & SALES -->
									<tr role="row" class="item_row">
										<?php if($do=='edit') $privi = isset($data['privi']['pm_sales']) ? $data['privi']['pm_sales'] : $default_privi; else $privi = $default_privi; ?>
										<td>Project Management & Sales</td>
										<td class="alignCenter">{{ Form::checkbox('privi[pm_sales][view]','',$privi['view'],array('id'=>'pm_sales','class'=>'form-control pm_sales')) }}</td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
									</tr>
									<tr role="row" class="item_row">
										<?php if($do=='edit') $privi = isset($data['privi']['pm_sales_master']) ? $data['privi']['pm_sales_master'] : $default_privi; else $privi = $default_privi; ?>
										<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Master</td>
										<td class="alignCenter">{{ Form::checkbox('privi[pm_sales_master][view]','',$privi['view'],array('id'=>'pm_sales_master','class'=>'form-control pm_sales pm_sales_master')) }}</td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
									</tr>
									<tr role="row" class="item_row">
										<?php if($do=='edit') $privi = isset($data['privi']['customer_category']) ? $data['privi']['customer_category'] : $default_privi; else $privi = $default_privi; ?>
										<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Customer Category</td>
										<td class="alignCenter">{{ Form::checkbox('privi[customer_category][view]','',$privi['view'],array('class'=>'form-control pm_sales pm_sales_master')) }}</td>
										<td class="alignCenter">{{ Form::checkbox('privi[customer_category][new]','',$privi['new'],array('class'=>'form-control pm_sales pm_sales_master')) }}</td>
										<td class="alignCenter"></td>
										<td class="alignCenter">{{ Form::checkbox('privi[customer_category][delete]','',$privi['delete'],array('class'=>'form-control pm_sales pm_sales_master')) }}</td>
										<td class="alignCenter"></td>
									</tr>
									<tr role="row" class="item_row">
										<?php if($do=='edit') $privi = isset($data['privi']['customer']) ? $data['privi']['customer'] : $default_privi; else $privi = $default_privi; ?>
										<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Customer </td>
										<td class="alignCenter">{{ Form::checkbox('privi[customer][view]','',$privi['view'],array('class'=>'form-control pm_sales pm_sales_master')) }}</td>
										<td class="alignCenter">{{ Form::checkbox('privi[customer][new]','',$privi['new'],array('class'=>'form-control pm_sales pm_sales_master')) }}</td>
										<td class="alignCenter">{{ Form::checkbox('privi[customer][edit]','',$privi['edit'],array('class'=>'form-control pm_sales pm_sales_master')) }}</td>
										<td class="alignCenter">{{ Form::checkbox('privi[customer][delete]','',$privi['delete'],array('class'=>'form-control pm_sales pm_sales_master')) }}</td>
										<td class="alignCenter"></td>
									</tr>
									<tr role="row" class="item_row">
										<?php if($do=='edit') $privi = isset($data['privi']['project_category']) ? $data['privi']['project_category'] : $default_privi; else $privi = $default_privi; ?>
										<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Project Category</td>
										<td class="alignCenter">{{ Form::checkbox('privi[project_category][view]','',$privi['view'],array('class'=>'form-control pm_sales pm_sales_master')) }}</td>
										<td class="alignCenter">{{ Form::checkbox('privi[project_category][new]','',$privi['new'],array('class'=>'form-control pm_sales pm_sales_master')) }}</td>
										<td class="alignCenter"></td>
										<td class="alignCenter">{{ Form::checkbox('privi[project_category][delete]','',$privi['delete'],array('class'=>'form-control pm_sales pm_sales_master')) }}</td>
										<td class="alignCenter"></td>
									</tr>
									<tr role="row" class="item_row">
										<?php if($do=='edit') $privi = isset($data['privi']['project']) ? $data['privi']['project'] : $default_privi; else $privi = $default_privi; ?>
										<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Project </td>
										<td class="alignCenter">{{ Form::checkbox('privi[project][view]','',$privi['view'],array('class'=>'form-control pm_sales pm_sales_master')) }}</td>
										<td class="alignCenter">{{ Form::checkbox('privi[project][new]','',$privi['new'],array('class'=>'form-control pm_sales pm_sales_master')) }}</td>
										<td class="alignCenter">{{ Form::checkbox('privi[project][edit]','',$privi['edit'],array('class'=>'form-control pm_sales pm_sales_master')) }}</td>
										<td class="alignCenter">{{ Form::checkbox('privi[project][delete]','',$privi['delete'],array('class'=>'form-control pm_sales pm_sales_master')) }}</td>
										<td class="alignCenter"></td>
									</tr>
									<tr role="row" class="item_row">
										<?php if($do=='edit') $privi = isset($data['privi']['open_project']) ? $data['privi']['open_project'] : $default_privi; else $privi = $default_privi; ?>
										<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Open Project</td>
										<td class="alignCenter">{{ Form::checkbox('privi[open_project][view]','',$privi['view'],array('id'=>'open_project','class'=>'form-control pm_sales open_project')) }}</td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
									</tr>
									<tr role="row" class="item_row">
										<?php if($do=='edit') $privi = isset($data['privi']['project_timeline']) ? $data['privi']['project_timeline'] : $default_privi; else $privi = $default_privi; ?>
										<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Timeline</td>
										<td class="alignCenter">{{ Form::checkbox('privi[project_timeline][view]','',$privi['view'],array('class'=>'form-control pm_sales open_project')) }}</td>
										<td class="alignCenter"></td>
										<td class="alignCenter">{{ Form::checkbox('privi[project_timeline][edit]','',$privi['edit'],array('class'=>'form-control pm_sales open_project')) }}</td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
									</tr>
									<tr role="row" class="item_row">
										<?php if($do=='edit') $privi = isset($data['privi']['project_quotation']) ? $data['privi']['project_quotation'] : $default_privi; else $privi = $default_privi; ?>
										<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Quotation</td>
										<td class="alignCenter">{{ Form::checkbox('privi[project_quotation][view]','',$privi['view'],array('class'=>'form-control pm_sales open_project')) }}</td>
										<td class="alignCenter">{{ Form::checkbox('privi[project_quotation][new]','',$privi['new'],array('class'=>'form-control pm_sales open_project')) }}</td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
									</tr>
									<tr role="row" class="item_row">
										<?php if($do=='edit') $privi = isset($data['privi']['project_sales']) ? $data['privi']['project_sales'] : $default_privi; else $privi = $default_privi; ?>
										<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Sales</td>
										<td class="alignCenter">{{ Form::checkbox('privi[project_sales][view]','',$privi['view'],array('class'=>'form-control pm_sales open_project')) }}</td>
										<td class="alignCenter">{{ Form::checkbox('privi[project_sales][new]','',$privi['new'],array('class'=>'form-control pm_sales open_project')) }}</td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
									</tr>
									<tr role="row" class="item_row">
										<?php if($do=='edit') $privi = isset($data['privi']['project_bom']) ? $data['privi']['project_bom'] : $default_privi; else $privi = $default_privi; ?>
										<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Bill of Material</td>
										<td class="alignCenter">{{ Form::checkbox('privi[project_bom][view]','',$privi['view'],array('class'=>'form-control pm_sales open_project')) }}</td>
										<td class="alignCenter">{{ Form::checkbox('privi[project_bom][new]','',$privi['new'],array('class'=>'form-control pm_sales open_project')) }}</td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
									</tr>
									<tr role="row" class="item_row">
										<?php if($do=='edit') $privi = isset($data['privi']['sales']) ? $data['privi']['sales'] : $default_privi; else $privi = $default_privi; ?>
										<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Sales</td>
										<td class="alignCenter">{{ Form::checkbox('privi[sales][view]','',$privi['view'],array('id'=>'pm_sales_sales','class'=>'form-control pm_sales pm_sales_sales')) }}</td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
									</tr>
									<tr role="row" class="item_row">
										<?php if($do=='edit') $privi = isset($data['privi']['sales_invoice']) ? $data['privi']['sales_invoice'] : $default_privi; else $privi = $default_privi; ?>
										<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Invoice & Tax</td>
										<td class="alignCenter">{{ Form::checkbox('privi[sales_invoice][view]','',$privi['view'],array('class'=>'form-control pm_sales pm_sales_sales')) }}</td>
										<td class="alignCenter">{{ Form::checkbox('privi[sales_invoice][new]','',$privi['new'],array('class'=>'form-control pm_sales pm_sales_sales')) }}</td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
									</tr>
									<!-- END PM & SALES -->
									<!-- PROCUREMENT -->
									<tr role="row" class="item_row">
										<?php if($do=='edit') $privi = isset($data['privi']['procurement']) ? $data['privi']['procurement'] : $default_privi; else $privi = $default_privi; ?>
										<td>Procurement</td>
										<td class="alignCenter">{{ Form::checkbox('privi[procurement][view]','',$privi['view'],array('id'=>'procurement','class'=>'form-control procurement')) }}</td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
									</tr>
									<tr role="row" class="item_row">
										<?php if($do=='edit') $privi = isset($data['privi']['procurement_master']) ? $data['privi']['procurement_master'] : $default_privi; else $privi = $default_privi; ?>
										<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Master</td>
										<td class="alignCenter">{{ Form::checkbox('privi[procurement_master][view]','',$privi['view'],array('id'=>'procurement_master','class'=>'form-control procurement procurement_master')) }}</td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
									</tr>
									<tr role="row" class="item_row">
										<?php if($do=='edit') $privi = isset($data['privi']['vendor_category']) ? $data['privi']['vendor_category'] : $default_privi; else $privi = $default_privi; ?>
										<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Vendor Category</td>
										<td class="alignCenter">{{ Form::checkbox('privi[vendor_category][view]','',$privi['view'],array('class'=>'form-control procurement procurement_master')) }}</td>
										<td class="alignCenter">{{ Form::checkbox('privi[vendor_category][new]','',$privi['new'],array('class'=>'form-control procurement procurement_master')) }}</td>
										<td class="alignCenter"></td>
										<td class="alignCenter">{{ Form::checkbox('privi[vendor_category][delete]','',$privi['delete'],array('class'=>'form-control procurement procurement_master')) }}</td>
										<td class="alignCenter"></td>
									</tr>
									<tr role="row" class="item_row">
										<?php if($do=='edit') $privi = isset($data['privi']['vendor']) ? $data['privi']['vendor'] : $default_privi; else $privi = $default_privi; ?>
										<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Vendor </td>
										<td class="alignCenter">{{ Form::checkbox('privi[vendor][view]','',$privi['view'],array('class'=>'form-control procurement procurement_master')) }}</td>
										<td class="alignCenter">{{ Form::checkbox('privi[vendor][new]','',$privi['new'],array('class'=>'form-control procurement procurement_master')) }}</td>
										<td class="alignCenter">{{ Form::checkbox('privi[vendor][edit]','',$privi['edit'],array('class'=>'form-control procurement procurement_master')) }}</td>
										<td class="alignCenter">{{ Form::checkbox('privi[vendor][delete]','',$privi['delete'],array('class'=>'form-control procurement procurement_master')) }}</td>
										<td class="alignCenter"></td>
									</tr>
									<tr role="row" class="item_row">
										<?php if($do=='edit') $privi = isset($data['privi']['purchase_request']) ? $data['privi']['purchase_request'] : $default_privi; else $privi = $default_privi; ?>
										<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Purchase Request</td>
										<td class="alignCenter">{{ Form::checkbox('privi[purchase_request][view]','',$privi['view'],array('class'=>'form-control procurement')) }}</td>
										<td class="alignCenter">{{ Form::checkbox('privi[purchase_request][new]','',$privi['new'],array('class'=>'form-control procurement')) }}</td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
									</tr>
									<tr role="row" class="item_row">
										<?php if($do=='edit') $privi = isset($data['privi']['purchase_order']) ? $data['privi']['purchase_order'] : $default_privi; else $privi = $default_privi; ?>
										<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Purchase Order</td>
										<td class="alignCenter">{{ Form::checkbox('privi[purchase_order][view]','',$privi['view'],array('class'=>'form-control procurement')) }}</td>
										<td class="alignCenter">{{ Form::checkbox('privi[purchase_order][new]','',$privi['new'],array('class'=>'form-control procurement')) }}</td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
									</tr>
									<tr role="row" class="item_row">
										<?php if($do=='edit') $privi = isset($data['privi']['purchase_invoice']) ? $data['privi']['purchase_invoice'] : $default_privi; else $privi = $default_privi; ?>
										<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Purchase Invoice</td>
										<td class="alignCenter">{{ Form::checkbox('privi[purchase_invoice][view]','',$privi['view'],array('class'=>'form-control procurement')) }}</td>
										<td class="alignCenter">{{ Form::checkbox('privi[purchase_invoice][new]','',$privi['new'],array('class'=>'form-control procurement')) }}</td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
									</tr>
									<!-- END PROCUREMENT -->
									<!-- INVENTORY -->
									<tr role="row" class="item_row">
										<?php if($do=='edit') $privi = isset($data['privi']['inventory']) ? $data['privi']['inventory'] : $default_privi; else $privi = $default_privi; ?>
										<td>Inventory</td>
										<td class="alignCenter">{{ Form::checkbox('privi[inventory][view]','',$privi['view'],array('id'=>'inventory','class'=>'form-control inventory')) }}</td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
									</tr>
									<tr role="row" class="item_row">
										<?php if($do=='edit') $privi = isset($data['privi']['inventory_master']) ? $data['privi']['inventory_master'] : $default_privi; else $privi = $default_privi; ?>
										<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Master</td>
										<td class="alignCenter">{{ Form::checkbox('privi[inventory_master][view]','',$privi['view'],array('id'=>'inventory_master','class'=>'form-control inventory inventory_master')) }}</td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
									</tr>
									<tr role="row" class="item_row">
										<?php if($do=='edit') $privi = isset($data['privi']['warehouse']) ? $data['privi']['warehouse'] : $default_privi; else $privi = $default_privi; ?>
										<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Warehouse</td>
										<td class="alignCenter">{{ Form::checkbox('privi[warehouse][view]','',$privi['view'],array('class'=>'form-control inventory inventory_master')) }}</td>
										<td class="alignCenter">{{ Form::checkbox('privi[warehouse][new]','',$privi['new'],array('class'=>'form-control inventory inventory_master')) }}</td>
										<td class="alignCenter">{{ Form::checkbox('privi[warehouse][edit]','',$privi['edit'],array('class'=>'form-control inventory inventory_master')) }}</td>
										<td class="alignCenter">{{ Form::checkbox('privi[warehouse][delete]','',$privi['delete'],array('class'=>'form-control inventory inventory_master')) }}</td>
										<td class="alignCenter"></td>
									</tr>
									<tr role="row" class="item_row">
										<?php if($do=='edit') $privi = isset($data['privi']['item_category']) ? $data['privi']['item_category'] : $default_privi; else $privi = $default_privi; ?>
										<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Item Category</td>
										<td class="alignCenter">{{ Form::checkbox('privi[item_category][view]','',$privi['view'],array('class'=>'form-control inventory inventory_master')) }}</td>
										<td class="alignCenter">{{ Form::checkbox('privi[item_category][new]','',$privi['new'],array('class'=>'form-control inventory inventory_master')) }}</td>
										<td class="alignCenter"></td>
										<td class="alignCenter">{{ Form::checkbox('privi[item_category][delete]','',$privi['delete'],array('class'=>'form-control inventory inventory_master')) }}</td>
										<td class="alignCenter"></td>
									</tr>
									<tr role="row" class="item_row">
										<?php if($do=='edit') $privi = isset($data['privi']['item_brand']) ? $data['privi']['item_brand'] : $default_privi; else $privi = $default_privi; ?>
										<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Item Brand</td>
										<td class="alignCenter">{{ Form::checkbox('privi[item_brand][view]','',$privi['view'],array('class'=>'form-control inventory inventory_master')) }}</td>
										<td class="alignCenter">{{ Form::checkbox('privi[item_brand][new]','',$privi['new'],array('class'=>'form-control inventory inventory_master')) }}</td>
										<td class="alignCenter"></td>
										<td class="alignCenter">{{ Form::checkbox('privi[item_brand][delete]','',$privi['delete'],array('class'=>'form-control inventory inventory_master')) }}</td>
										<td class="alignCenter"></td>
									</tr>
									<tr role="row" class="item_row">
										<?php if($do=='edit') $privi = isset($data['privi']['item']) ? $data['privi']['item'] : $default_privi; else $privi = $default_privi; ?>
										<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Item </td>
										<td class="alignCenter">{{ Form::checkbox('privi[item][view]','',$privi['view'],array('class'=>'form-control inventory inventory_master')) }}</td>
										<td class="alignCenter">{{ Form::checkbox('privi[item][new]','',$privi['new'],array('class'=>'form-control inventory inventory_master')) }}</td>
										<td class="alignCenter">{{ Form::checkbox('privi[item][edit]','',$privi['edit'],array('class'=>'form-control inventory inventory_master')) }}</td>
										<td class="alignCenter">{{ Form::checkbox('privi[item][delete]','',$privi['delete'],array('class'=>'form-control inventory inventory_master')) }}</td>
										<td class="alignCenter"></td>
									</tr>
									<tr role="row" class="item_row">
										<?php if($do=='edit') $privi = isset($data['privi']['view_stock']) ? $data['privi']['view_stock'] : $default_privi; else $privi = $default_privi; ?>
										<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;View Stock</td>
										<td class="alignCenter">{{ Form::checkbox('privi[view_stock][view]','',$privi['view'],array('class'=>'form-control inventory')) }}</td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
									</tr>
									<tr role="row" class="item_row">
										<?php if($do=='edit') $privi = isset($data['privi']['stock_opname']) ? $data['privi']['stock_opname'] : $default_privi; else $privi = $default_privi; ?>
										<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Stock Opname</td>
										<td class="alignCenter">{{ Form::checkbox('privi[stock_opname][view]','',$privi['view'],array('class'=>'form-control inventory')) }}</td>
										<td class="alignCenter">{{ Form::checkbox('privi[stock_opname][new]','',$privi['new'],array('class'=>'form-control inventory')) }}</td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
									</tr>
									<tr role="row" class="item_row">
										<?php if($do=='edit') $privi = isset($data['privi']['stock_transfer']) ? $data['privi']['stock_transfer'] : $default_privi; else $privi = $default_privi; ?>
										<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Stock Transfer</td>
										<td class="alignCenter">{{ Form::checkbox('privi[stock_transfer][view]','',$privi['view'],array('class'=>'form-control inventory')) }}</td>
										<td class="alignCenter">{{ Form::checkbox('privi[stock_transfer][new]','',$privi['new'],array('class'=>'form-control inventory')) }}</td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
									</tr>
									<tr role="row" class="item_row">
										<?php if($do=='edit') $privi = isset($data['privi']['good_receipt']) ? $data['privi']['good_receipt'] : $default_privi; else $privi = $default_privi; ?>
										<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Good Receipt</td>
										<td class="alignCenter">{{ Form::checkbox('privi[good_receipt][view]','',$privi['view'],array('class'=>'form-control inventory')) }}</td>
										<td class="alignCenter">{{ Form::checkbox('privi[good_receipt][new]','',$privi['new'],array('class'=>'form-control inventory')) }}</td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
									</tr>
									<tr role="row" class="item_row">
										<?php if($do=='edit') $privi = isset($data['privi']['delivery_order']) ? $data['privi']['delivery_order'] : $default_privi; else $privi = $default_privi; ?>
										<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Delivery Order</td>
										<td class="alignCenter">{{ Form::checkbox('privi[delivery_order][view]','',$privi['view'],array('class'=>'form-control inventory')) }}</td>
										<td class="alignCenter">{{ Form::checkbox('privi[delivery_order][new]','',$privi['new'],array('class'=>'form-control inventory')) }}</td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
									</tr>
									<tr role="row" class="item_row">
										<?php if($do=='edit') $privi = isset($data['privi']['stock_return']) ? $data['privi']['stock_return'] : $default_privi; else $privi = $default_privi; ?>
										<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Stock Return</td>
										<td class="alignCenter">{{ Form::checkbox('privi[stock_return][view]','',$privi['view'],array('class'=>'form-control inventory')) }}</td>
										<td class="alignCenter">{{ Form::checkbox('privi[stock_return][new]','',$privi['new'],array('class'=>'form-control inventory')) }}</td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
									</tr>
									<!-- END INVENTORY -->
									<!-- FORM REQUEST -->
									<tr role="row" class="item_row">
										<?php if($do=='edit') $privi = isset($data['privi']['form_request']) ? $data['privi']['form_request'] : $default_privi; else $privi = $default_privi; ?>
										<td>Form Request</td>
										<td class="alignCenter">{{ Form::checkbox('privi[form_request][view]','',$privi['view'],array('id'=>'form_request','class'=>'form-control form_request')) }}</td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
									</tr>
									<tr role="row" class="item_row">
										<?php if($do=='edit') $privi = isset($data['privi']['create_request']) ? $data['privi']['create_request'] : $default_privi; else $privi = $default_privi; ?>
										<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Create</td>
										<td class="alignCenter">{{ Form::checkbox('privi[create_request][view]','',$privi['view'],array('id'=>'create_request','class'=>'form-control form_request create_request')) }}</td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
									</tr>
									<tr role="row" class="item_row">
										<?php if($do=='edit') $privi = isset($data['privi']['request_advance']) ? $data['privi']['request_advance'] : $default_privi; else $privi = $default_privi; ?>
										<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Advance</td>
										<td class="alignCenter"></td>
										<td class="alignCenter">{{ Form::checkbox('privi[request_advance][new]','',$privi['new'],array('class'=>'form-control form_request create_request')) }}</td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
									</tr>
									<tr role="row" class="item_row">
										<?php if($do=='edit') $privi = isset($data['privi']['request_expense']) ? $data['privi']['request_expense'] : $default_privi; else $privi = $default_privi; ?>
										<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Expense</td>
										<td class="alignCenter"></td>
										<td class="alignCenter">{{ Form::checkbox('privi[request_expense][new]','',$privi['new'],array('class'=>'form-control form_request create_request')) }}</td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
									</tr>
									<tr role="row" class="item_row">
										<?php if($do=='edit') $privi = isset($data['privi']['request_overtime']) ? $data['privi']['request_overtime'] : $default_privi; else $privi = $default_privi; ?>
										<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Overtime</td>
										<td class="alignCenter"></td>
										<td class="alignCenter">{{ Form::checkbox('privi[request_overtime][new]','',$privi['new'],array('class'=>'form-control form_request create_request')) }}</td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
									</tr>
									<tr role="row" class="item_row">
										<?php if($do=='edit') $privi = isset($data['privi']['request_approval']) ? $data['privi']['request_approval'] : $default_privi; else $privi = $default_privi; ?>
										<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Waiting for Approval</td>
										<td class="alignCenter">{{ Form::checkbox('privi[request_approval][view]','',$privi['view'],array('class'=>'form-control form_request')) }}</td>
										<td class="alignCenter">{{ Form::checkbox('privi[request_approval][new]','',$privi['new'],array('class'=>'form-control form_request')) }}</td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
									</tr>
									<tr role="row" class="item_row">
										<?php if($do=='edit') $privi = isset($data['privi']['request_release']) ? $data['privi']['request_release'] : $default_privi; else $privi = $default_privi; ?>
										<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Waiting for Release</td>
										<td class="alignCenter">{{ Form::checkbox('privi[request_release][view]','',$privi['view'],array('class'=>'form-control form_request')) }}</td>
										<td class="alignCenter">{{ Form::checkbox('privi[request_release][new]','',$privi['new'],array('class'=>'form-control form_request')) }}</td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
									</tr>
									<!-- END FORM REQUEST -->
									<!-- ACCOUNTING -->
									<tr role="row" class="item_row">
										<?php if($do=='edit') $privi = isset($data['privi']['accounting']) ? $data['privi']['accounting'] : $default_privi; else $privi = $default_privi; ?>
										<td>Accounting</td>
										<td class="alignCenter">{{ Form::checkbox('privi[accounting][view]','',$privi['view'],array('id'=>'accounting','class'=>'form-control accounting')) }}</td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
									</tr>
									<tr role="row" class="item_row">
										<?php if($do=='edit') $privi = isset($data['privi']['accounting_master']) ? $data['privi']['accounting_master'] : $default_privi; else $privi = $default_privi; ?>
										<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Master</td>
										<td class="alignCenter">{{ Form::checkbox('privi[accounting_master][view]','',$privi['view'],array('id'=>'accounting_master','class'=>'form-control accounting accounting_master')) }}</td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
									</tr>
									<tr role="row" class="item_row">
										<?php if($do=='edit') $privi = isset($data['privi']['coa']) ? $data['privi']['coa'] : $default_privi; else $privi = $default_privi; ?>
										<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Chart of Account</td>
										<td class="alignCenter">{{ Form::checkbox('privi[coa][view]','',$privi['view'],array('class'=>'form-control accounting accounting_master')) }}</td>
										<td class="alignCenter">{{ Form::checkbox('privi[coa][new]','',$privi['new'],array('class'=>'form-control accounting accounting_master')) }}</td>
										<td class="alignCenter">{{ Form::checkbox('privi[coa][edit]','',$privi['edit'],array('class'=>'form-control accounting accounting_master')) }}</td>
										<td class="alignCenter">{{ Form::checkbox('privi[coa][delete]','',$privi['delete'],array('class'=>'form-control accounting accounting_master')) }}</td>
										<td class="alignCenter"></td>
									</tr>
									<tr role="row" class="item_row">
										<?php if($do=='edit') $privi = isset($data['privi']['currency']) ? $data['privi']['currency'] : $default_privi; else $privi = $default_privi; ?>
										<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Currency </td>
										<td class="alignCenter">{{ Form::checkbox('privi[currency][view]','',$privi['view'],array('class'=>'form-control accounting accounting_master')) }}</td>
										<td class="alignCenter">{{ Form::checkbox('privi[currency][new]','',$privi['new'],array('class'=>'form-control accounting accounting_master')) }}</td>
										<td class="alignCenter">{{ Form::checkbox('privi[currency][edit]','',$privi['edit'],array('class'=>'form-control accounting accounting_master')) }}</td>
										<td class="alignCenter">{{ Form::checkbox('privi[currency][delete]','',$privi['delete'],array('class'=>'form-control accounting accounting_master')) }}</td>
										<td class="alignCenter"></td>
									</tr>
									<tr role="row" class="item_row">
										<?php if($do=='edit') $privi = isset($data['privi']['accounting_treasury']) ? $data['privi']['accounting_treasury'] : $default_privi; else $privi = $default_privi; ?>
										<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Treasury</td>
										<td class="alignCenter">{{ Form::checkbox('privi[accounting_treasury][view]','',$privi['view'],array('id'=>'accounting_treasury','class'=>'form-control accounting accounting_treasury')) }}</td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
									</tr>
									<!--<tr role="row" class="item_row">
										<?php if($do=='edit') $privi = isset($data['privi']['cash_adjustment']) ? $data['privi']['cash_adjustment'] : $default_privi; else $privi = $default_privi; ?>
										<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Cash Adjustment</td>
										<td class="alignCenter">{{ Form::checkbox('privi[cash_adjustment][view]','',$privi['view'],array('class'=>'form-control accounting accounting_treasury')) }}</td>
										<td class="alignCenter">{{ Form::checkbox('privi[cash_adjustment][new]','',$privi['new'],array('class'=>'form-control accounting accounting_treasury')) }}</td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
									</tr>-->
									<tr role="row" class="item_row">
										<?php if($do=='edit') $privi = isset($data['privi']['cashbank_transfer']) ? $data['privi']['cashbank_transfer'] : $default_privi; else $privi = $default_privi; ?>
										<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Cash/Bank Transfer</td>
										<td class="alignCenter">{{ Form::checkbox('privi[cashbank_transfer][view]','',$privi['view'],array('class'=>'form-control accounting accounting_treasury')) }}</td>
										<td class="alignCenter">{{ Form::checkbox('privi[cashbank_transfer][new]','',$privi['new'],array('class'=>'form-control accounting accounting_treasury')) }}</td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
									</tr>
									<!--<tr role="row" class="item_row">
										<?php if($do=='edit') $privi = isset($data['privi']['bank_recon']) ? $data['privi']['bank_recon'] : $default_privi; else $privi = $default_privi; ?>
										<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Bank Reconciliation</td>
										<td class="alignCenter">{{ Form::checkbox('privi[bank_recon][view]','',$privi['view'],array('class'=>'form-control accounting accounting_treasury')) }}</td>
										<td class="alignCenter">{{ Form::checkbox('privi[bank_recon][new]','',$privi['new'],array('class'=>'form-control accounting accounting_treasury')) }}</td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
									</tr>-->
									<tr role="row" class="item_row">
										<?php if($do=='edit') $privi = isset($data['privi']['deposits']) ? $data['privi']['deposits'] : $default_privi; else $privi = $default_privi; ?>
										<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Deposits</td>
										<td class="alignCenter">{{ Form::checkbox('privi[deposits][view]','',$privi['view'],array('class'=>'form-control accounting accounting_treasury')) }}</td>
										<td class="alignCenter">{{ Form::checkbox('privi[deposits][new]','',$privi['new'],array('class'=>'form-control accounting accounting_treasury')) }}</td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
									</tr>
									<tr role="row" class="item_row">
										<?php if($do=='edit') $privi = isset($data['privi']['payments']) ? $data['privi']['payments'] : $default_privi; else $privi = $default_privi; ?>
										<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Payments</td>
										<td class="alignCenter">{{ Form::checkbox('privi[payments][view]','',$privi['view'],array('class'=>'form-control accounting accounting_treasury')) }}</td>
										<td class="alignCenter">{{ Form::checkbox('privi[payments][new]','',$privi['new'],array('class'=>'form-control accounting accounting_treasury')) }}</td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
									</tr>
									<tr role="row" class="item_row">
										<?php if($do=='edit') $privi = isset($data['privi']['acct_receivable']) ? $data['privi']['acct_receivable'] : $default_privi; else $privi = $default_privi; ?>
										<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Account Receivables</td>
										<td class="alignCenter">{{ Form::checkbox('privi[acct_receivable][view]','',$privi['view'],array('class'=>'form-control accounting accounting_treasury')) }}</td>
										<td class="alignCenter">{{ Form::checkbox('privi[acct_receivable][new]','',$privi['new'],array('class'=>'form-control accounting accounting_treasury')) }}</td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
									</tr>
									<tr role="row" class="item_row">
										<?php if($do=='edit') $privi = isset($data['privi']['acct_payable']) ? $data['privi']['acct_payable'] : $default_privi; else $privi = $default_privi; ?>
										<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Account Payables</td>
										<td class="alignCenter">{{ Form::checkbox('privi[acct_payable][view]','',$privi['view'],array('class'=>'form-control accounting accounting_treasury')) }}</td>
										<td class="alignCenter">{{ Form::checkbox('privi[acct_payable][new]','',$privi['new'],array('class'=>'form-control accounting accounting_treasury')) }}</td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
									</tr>
									<tr role="row" class="item_row">
										<?php if($do=='edit') $privi = isset($data['privi']['memorial_journal']) ? $data['privi']['memorial_journal'] : $default_privi; else $privi = $default_privi; ?>
										<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Memorial Journal</td>
										<td class="alignCenter">{{ Form::checkbox('privi[memorial_journal][view]','',$privi['view'],array('class'=>'form-control accounting')) }}</td>
										<td class="alignCenter">{{ Form::checkbox('privi[memorial_journal][new]','',$privi['new'],array('class'=>'form-control accounting')) }}</td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
									</tr>
									<tr role="row" class="item_row">
										<?php if($do=='edit') $privi = isset($data['privi']['accounting_report']) ? $data['privi']['accounting_report'] : $default_privi; else $privi = $default_privi; ?>
										<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Report</td>
										<td class="alignCenter">{{ Form::checkbox('privi[accounting_report][view]','',$privi['view'],array('id'=>'accounting_report','class'=>'form-control accounting accounting_report')) }}</td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
									</tr>
									<tr role="row" class="item_row">
										<?php if($do=='edit') $privi = isset($data['privi']['general_journal']) ? $data['privi']['general_journal'] : $default_privi; else $privi = $default_privi; ?>
										<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;General Journal</td>
										<td class="alignCenter">{{ Form::checkbox('privi[general_journal][view]','',$privi['view'],array('class'=>'form-control accounting accounting_report')) }}</td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
									</tr>
									<tr role="row" class="item_row">
										<?php if($do=='edit') $privi = isset($data['privi']['balance_sheet']) ? $data['privi']['balance_sheet'] : $default_privi; else $privi = $default_privi; ?>
										<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Balance Sheet</td>
										<td class="alignCenter">{{ Form::checkbox('privi[balance_sheet][view]','',$privi['view'],array('class'=>'form-control accounting accounting_report')) }}</td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
									</tr>
									<tr role="row" class="item_row">
										<?php if($do=='edit') $privi = isset($data['privi']['trial_balance']) ? $data['privi']['trial_balance'] : $default_privi; else $privi = $default_privi; ?>
										<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Trial Balance</td>
										<td class="alignCenter">{{ Form::checkbox('privi[trial_balance][view]','',$privi['view'],array('class'=>'form-control accounting accounting_report')) }}</td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
									</tr>
									<tr role="row" class="item_row">
										<?php if($do=='edit') $privi = isset($data['privi']['profit_loss']) ? $data['privi']['profit_loss'] : $default_privi; else $privi = $default_privi; ?>
										<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Profit Loss</td>
										<td class="alignCenter">{{ Form::checkbox('privi[profit_loss][view]','',$privi['view'],array('class'=>'form-control accounting accounting_report')) }}</td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
									</tr>
									<tr role="row" class="item_row">
										<?php if($do=='edit') $privi = isset($data['privi']['general_ledger']) ? $data['privi']['general_ledger'] : $default_privi; else $privi = $default_privi; ?>
										<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;General Ledger</td>
										<td class="alignCenter">{{ Form::checkbox('privi[general_ledger][view]','',$privi['view'],array('class'=>'form-control accounting accounting_report')) }}</td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
									</tr>
									<!-- END INVENTORY -->
									<!-- USER MANAGEMENT -->
									<tr role="row" class="item_row">
										<?php if($do=='edit') $privi = isset($data['privi']['user_management']) ? $data['privi']['user_management'] : $default_privi; else $privi = $default_privi; ?>
										<td>User Management</td>
										<td class="alignCenter">{{ Form::checkbox('privi[user_management][view]','',$privi['view'],array('id'=>'user_management','class'=>'form-control user_management')) }}</td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
										<td class="alignCenter"></td>
									</tr>
									<tr role="row" class="item_row">
										<?php if($do=='edit') $privi = isset($data['privi']['user_group']) ? $data['privi']['user_group'] : $default_privi; else $privi = $default_privi; ?>
										<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;User Group</td>
										<td class="alignCenter">{{ Form::checkbox('privi[user_group][view]','',$privi['view'],array('class'=>'form-control user_management')) }}</td>
										<td class="alignCenter">{{ Form::checkbox('privi[user_group][new]','',$privi['new'],array('class'=>'form-control user_management')) }}</td>
										<td class="alignCenter">{{ Form::checkbox('privi[user_group][edit]','',$privi['edit'],array('class'=>'form-control user_management')) }}</td>
										<td class="alignCenter">{{ Form::checkbox('privi[user_group][delete]','',$privi['delete'],array('class'=>'form-control user_management')) }}</td>
										<td class="alignCenter"></td>
									</tr>
									<tr role="row" class="item_row">
										<?php if($do=='edit') $privi = isset($data['privi']['user_account']) ? $data['privi']['user_account'] : $default_privi; else $privi = $default_privi; ?>
										<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;User Account</td>
										<td class="alignCenter">{{ Form::checkbox('privi[user_account][view]','',$privi['view'],array('class'=>'form-control user_management')) }}</td>
										<td class="alignCenter">{{ Form::checkbox('privi[user_account][new]','',$privi['new'],array('class'=>'form-control user_management')) }}</td>
										<td class="alignCenter">{{ Form::checkbox('privi[user_account][edit]','',$privi['edit'],array('class'=>'form-control user_management')) }}</td>
										<td class="alignCenter">{{ Form::checkbox('privi[user_account][delete]','',$privi['delete'],array('class'=>'form-control user_management')) }}</td>
										<td class="alignCenter"></td>
									</tr>
									<!-- END USER MANAGEMENT -->
								</tbody>
								</table>
							</div>
						</div>
						<div class="form-actions fluid">
							<div class="col-md-offset-5 col-md-7">
									<a href="{{ URL::to('/') }}/user_group" class="btn default">Back</a>
								<button type="button" id="process" class="btn blue">Process</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>
	<!-- END FORM-->
	
	<!-- END PAGE CONTENT-->
@stop