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
                url: "{{ URL::to('/') }}/vendor2/{{$act}}",
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
						window.location.href ="{{ URL::to('/') }}/vendor2";
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
		$checkboxTax = 0;
		$pkpDate = '';
		if($act == 'Edit')
		{
			$checkboxTax = $vendor->tax;
			$pkpDate = ($vendor->pkpDate != '0000-00-00') ? displayDate($vendor->pkpDate): '';

	?>
	function renderData()
	{
		$('#vendId').val( '{{$vendor->id_vendor}}' ); 
		$('#vendName').val( '{{$vendor->vendor_name}}' ); 
		$('#vendCategory').select2('val', '{{$vendor->id_category}}');
		$('#salesPerson').select2('val', '{{$vendor->id_sales_person}}');
		$('#desc').val( '{{$vendor->description}}' ); 
		$('#addr').val( '{{$vendor->address}}' ); 
		$('#phone').val( '{{$vendor->telephone}}' ); 
		$('#fax').val( '{{$vendor->fax}}' ); 
		
		$('#bank').val( '{{$vendor->bank_name}}' ); 
		$('#bankAcct').val( '{{$vendor->bank_acct_no}}' ); 
		$('#acctName').val( '{{$vendor->bank_acct_name}}' ); 
		$('#npwp').val( '{{$vendor->npwp}}' ); 
		$('#pkp').val( '{{$vendor->pkp}}' ); 
		// $('#pkpDate').val( '{{$vendor->pkp_date}}' ); 
		// $('#tax').prop( 'checked',{{$vendor->tax}} ); 
		
		$('#rCcy').select2('val', '{{$vendor->currency}}');
		$('#rTop').select2('val', '{{$vendor->term_of_payment}}');
		$('#rInterval').val( '{{$vendor->credit_interval}}' ); 
		$('#rPlafond').val( '{{ ($vendor->credit_plafond) ? displayNumeric($vendor->credit_plafond) : ''}}' ); 
		$('#rPayable').select2('val', '{{$vendor->account_payable}}');
		
		$('#contTitle').select2('val', '{{$contact->contact_title}}');
		$('#contName').val( '{{$contact->contact_name}}' ); 
		$('#contPosition').val( '{{$contact->position}}' ); 
		$('#contAddr').val( '{{$contact->address}}' ); 
		$('#contMobilePhone').val( '{{$contact->phone_mobile}}' ); 
		$('#contPhone').val( '{{$contact->phone_work}}' ); 
		$('#contEmail').val( '{{$contact->email}}' ); 

		$("textarea").each(function(){
				$(this).val($(this).val().replace(/<br\s?\/?>/g,"\n"));
		});
	}
	<?php
		}
		else{
			echo "function renderData(){ $('#rCcy').select2('val', '".$defaultCcy."'); }";
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
					<a href="#">Procurement</a>
					<i class="fa fa-angle-right"></i>
				</li>
				<li>
					<a href="#">Master</a>
					<i class="fa fa-angle-right"></i>
				</li>
				<li>
					<a href="{{ URL::to('/') }}/vendor2">Vendor</a>
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
							<div class="form-body">
									<ul class="nav nav-tabs">
										<li class="active">
											<a href="#profile" data-toggle="tab">
											Profile </a>
										</li>
										<li>
											<a href="#contact" data-toggle="tab">
											Contact Person</a>
										</li>
										<li>
											<a href="#payment" data-toggle="tab">
											Payment</a>
										</li>
									</ul>
									<div class="tab-content">
									
										<div class="tab-pane active" id="profile">
											<!-- BEGIN PROFILE-->
											<div class="form-body">
												<div class="row">
													<div class="col-md-6">
														<div class="form-group">
															<label class="col-md-4 control-label">Vendor Name</label>
															<div class="col-md-6">
																{{ Form::hidden('id','',array('id'=>'vendId')) }}
																{{ Form::text('vendName','',array('id'=>'vendName','class'=>'form-control')) }}
															</div>
														</div>
														<div class="form-group">
															<label class="col-md-4 control-label">Vendor Category</label>
															<div class="col-md-6">
																{{Form::select('vendCategory',$listCategory,0,array('id'=>'vendCategory','class'=>'form-control select2')) }}
															</div>
														</div>
													</div>
													<!--/span-->
													<div class="col-md-6">
														<div class="form-group">
															<label class="col-md-4 control-label">Address</label>
															<div class="col-md-6">
																{{ Form::textarea('addr','',array('id'=>'addr','class'=>'form-control','size'=>'10x3')) }}
															</div>
														</div>
													</div>
													<!--/span-->
												</div>
												<div class="row">
													<div class="col-md-6">
														<div class="form-group">
															<label class="col-md-4 control-label">Description</label>
															<div class="col-md-6">
																{{ Form::textarea('desc','',array('id'=>'desc','class'=>'form-control','size'=>'10x3')) }}
															</div>
														</div>
													</div>
													<!--/span-->
													<div class="col-md-6">
														<div class="form-group">
															<label class="col-md-4 control-label">Telephone</label>
															<div class="col-md-6">
																{{ Form::text('phone','',array('id'=>'phone','class'=>'form-control','onkeypress'=>'return Digits(event)')) }}
															</div>
														</div>
														<div class="form-group">
															<label class="col-md-4 control-label">Fax</label>
															<div class="col-md-6">
																{{ Form::text('fax','',array('id'=>'fax','class'=>'form-control','onkeypress'=>'return Digits(event)')) }}
															</div>
														</div>
													</div>
													<!--/span-->
												</div>
												<!--/row-->
											</div>
											<!-- END PROFILE-->
										</div>
											
										<div class="tab-pane fade" id="contact">
											<!-- BEGIN CONTACT-->
											<div class="form-body">
												<div class="row">
													<div class="col-md-6">
														<div class="form-group">
															<label class="col-md-4 control-label">Name</label>
															<div class="col-md-3">
																{{Form::select('contTitle',$listTitle,0,array('id'=>'contTitle','class'=>'form-control select2')) }}
															</div>
															<div class="col-md-4">
																{{ Form::text('contName','',array('id'=>'contName','class'=>'form-control')) }}
															</div>
														</div>
													</div>
													<!--/span-->
													<div class="col-md-6">
														<div class="form-group">
															<label class="col-md-4 control-label">Mobile Phone</label>
															<div class="col-md-6">
																{{ Form::text('contMobilePhone','',array('id'=>'contMobilePhone','class'=>'form-control','onkeypress'=>'return Digits(event)')) }}
															</div>
														</div>
													</div>
													<!--/span-->
												</div>
												<!--/row-->
												<div class="row">
													<div class="col-md-6">
														<div class="form-group">
															<label class="col-md-4 control-label">Position</label>
															<div class="col-md-7">
																{{ Form::text('contPosition','',array('id'=>'contPosition','class'=>'form-control')) }}
															</div>
														</div>
													</div>
													<!--/span-->
													<div class="col-md-6">
														<div class="form-group">
															<label class="col-md-4 control-label">Phone (Work)</label>
															<div class="col-md-6">
																{{ Form::text('contPhone','',array('id'=>'contPhone','class'=>'form-control','onkeypress'=>'return Digits(event)')) }}
															</div>
														</div>
													</div>
													<!--/span-->
												</div>
												<!--/row-->
												<div class="row">
													<div class="col-md-6">
														<div class="form-group">
															<label class="col-md-4 control-label">Address</label>
															<div class="col-md-7">
																{{ Form::textarea('contAddr','',array('id'=>'contAddr','class'=>'form-control','size'=>'10x3')) }}
															</div>
														</div>
													</div>
													<!--/span-->
													<div class="col-md-6">
														<div class="form-group">
															<label class="col-md-4 control-label">Email</label>
															<div class="col-md-6">
																{{ Form::text('contEmail','',array('id'=>'contEmail','class'=>'form-control')) }}
															</div>
														</div>
													</div>
													<!--/span-->
												</div>
												<!--/row-->
											</div>
											<!-- END CONTACT-->
										</div>
										<div class="tab-pane fade" id="payment">
											<!-- BEGIN CONTACT-->
											<div class="form-body">
												<div class="row">
													<div class="col-md-6">
														<div class="portlet box blue">
															<div class="portlet-title">
																<div class="caption">
																	<i class="fa fa-gift"></i> Bank & Tax
																</div>
															</div>
														</div>
														<div class="form-group">
															<label class="col-md-4 control-label">Bank</label>
															<div class="col-md-6">
																{{ Form::text('bank','',array('id'=>'bank','class'=>'form-control')) }}
															</div>
														</div>
														<div class="form-group">
															<label class="col-md-4 control-label">Bank Account</label>
															<div class="col-md-6">
																{{ Form::text('bankAcct','',array('id'=>'bankAcct','class'=>'form-control','onkeypress'=>'return Digits(event)')) }}
															</div>
														</div>
														<div class="form-group">
															<label class="col-md-4 control-label">Account Name</label>
															<div class="col-md-6">
																{{ Form::text('acctName','',array('id'=>'acctName','class'=>'form-control')) }}
															</div>
														</div>
														<div class="form-group">
															<label class="col-md-4 control-label">N.P.W.P</label>
															<div class="col-md-6">
																{{ Form::text('npwp','',array('id'=>'npwp','class'=>'form-control')) }}
															</div>
														</div>
														<div class="form-group">
															<label class="col-md-4 control-label">P.K.P</label>
															<div class="col-md-6">
																{{ Form::text('pkp','',array('id'=>'pkp','class'=>'form-control')) }}
															</div>
														</div>
														<div class="form-group">
															<label class="col-md-4 control-label">P.K.P Date</label>
															<div class="col-md-6">
																<div class="input-group  date date-picker margin-bottom-5" data-date-format="{{ Config::get('format.date.default') }}">
																	{{ Form::text('pkpDate',$pkpDate,array('class'=>'form-control form-filter input-sm fix-var','readonly'=>true)) }}
																	<span class="input-group-btn">
																	<button class="btn btn-sm default" type="button"><i class="fa fa-calendar"></i></button>
																	</span>
																</div>
															</div>
														</div>
														<div class="form-group">
															<label class="col-md-4 control-label">Tax</label>
															<div class="col-md-6">
																{{ Form::checkbox('tax', 'Tax',$checkboxTax) }}
															</div>
														</div>
													</div>
													
													<div class="col-md-6">
														<div class="portlet box blue">
															<div class="portlet-title">
																<div class="caption">
																	<i class="fa fa-gift"></i> Receiveable
																</div>
															</div>
														</div>
														<div class="form-group">
															<label class="col-md-4 control-label">Currency</label>
															<div class="col-md-6">
																{{Form::select('rCcy',$listCcy,0,array('id'=>'rCcy','class'=>'form-control select2')) }}
															</div>
														</div>
														<div class="form-group">
															<label class="col-md-4 control-label">Term of Payment</label>
															<div class="col-md-6">
																{{Form::select('rTop',$listTop,0,array('id'=>'rTop','class'=>'form-control select2','onChange'=>'credit()')) }}
															</div>
														</div>
														<div class="form-group credit">
															<label class="col-md-4 control-label"></label>
															<div class="col-md-4">
																{{ Form::text('rInterval','',array('id'=>'rInterval','class'=>'form-control','placeholder'=>'Interval','onkeypress'=>'return Digits(event)')) }}
															</div>
															<label class="col-md-2 control-label">days</label>
														</div>
														<div class="form-group credit">
															<label class="col-md-4 control-label">Credit Plafond</label>
															<div class="col-md-6">
																{{ Form::text('rPlafond','',array('id'=>'rPlafond','class'=>'form-control currency')) }}
															</div>
														</div>
														<div class="form-group">
															<label class="col-md-4 control-label">Account Payable</label>
															<div class="col-md-6">
																{{Form::select('rPayable',$listAcctPayable,0,array('id'=>'rPayable','class'=>'form-control select2')) }}
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
					<a href="{{ URL::to('/') }}/vendor2"><button type="button" class="btn default" >Back</button></a>
					<button type="button" id="save" class="btn blue">Save</button>
				</div>
	<!-- End Dialog Form -->
	
	<!-- END PAGE CONTENT-->
@stop