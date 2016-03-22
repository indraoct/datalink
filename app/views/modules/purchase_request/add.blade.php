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
	<!-- END PAGE LEVEL PLUGINS -->
	<script>
	
	function construct() {
		$("#cari_produk").select2({
			placeholder: "Ketik kode/barcode atau nama",
			allowClear: true,
			minimumInputLength: 2,
			ajax: {
				url: '{{ URL::to('/') }}/purchase/get_data_select',
				dataType: 'json',
				type: "GET",
				quietMillis: 200,
				data: function (term) {
					return {
						term: term
					};
				},
				results: function (data) {
					return {results: data};
				}
			}
		});
		
		
		
		
		
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
						<a href="{{ URL::to('/') }}/sales">Penjualan</a>
						<i class="fa fa-angle-right"></i>
					</li>
					<li>
						<a href="#">Transaksi Kasir (POS)</a>
					</li>
					<li class="btn-group">
						<button id="full_screen" class="btn green" type="button">Layar Penuh</button>
					</li>
				</ul>
				<!-- END PAGE TITLE & BREADCRUMB-->
			</div>
		</div>
		<!-- END PAGE HEADER-->
	
	<!-- BEGIN PAGE CONTENT-->
	<div class="row">
		<div class="col-md-12">
			<div class="portlet box red">
				<div class="portlet-title">
						<div class="caption">
							{{{ $title or '' }}}
						</div>
				</div>
				<div class="portlet-body form">
				<!-- BEGIN FORM-->
				<form id="form" method="post" action="{{ URL::to('/') }}/sales/pos" class="form-horizontal">
					<div class="form-body">
						<div class="row">
							<div class="col-md-4">
							</div>
							<!--/span-->
							<div class="col-md-4">
								
							</div>
							<!--/span-->
							<div class="col-md-4">
								
							</div>
							<!--/span-->
						</div>
						<!--/row-->
						<div class="row">
							<div class="col-md-8">
								<div class="form-group">
									<label class="control-label col-md-2">Cari Produk</label>
									<div class="col-md-10">
										{{ Form::text('cari_produk','',array('id'=>'cari_produk','class'=>'form-control')) }}
									</div>
								</div>
							</div>
							<!--/span-->
							<div class="col-md-4">
								<div class="form-group">
									<label class="control-label col-md-4">Scan Barcode</label>
									<div class="col-md-6">
										{{ Form::text('scan_barcode','',array('id'=>'scan_barcode','class'=>'form-control')) }}
									</div>
								</div>
							</div>
							<!--/span-->
						</div>
						<!--/row-->
						</div>
				</form>
				<!-- END FORM-->
				</div>
			</div>
		</div>
	</div>
	
	<!-- Begin Dialog Form Produk -->
	<!-- End Dialog Form Produk -->
	
	<!-- END PAGE CONTENT-->
@stop