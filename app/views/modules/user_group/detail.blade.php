@extends('layouts.admin.default')

@section('css')
@stop

@section('js')
	<!-- BEGIN PAGE LEVEL PLUGINS -->
	<script type="text/javascript" src="{{ URL::asset('/assets/global/plugins/qz-print/js/deployJava.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('/assets/global/scripts/qz-print.js') }}"></script>
	<!-- END PAGE LEVEL PLUGINS -->
	<script>
	jQuery(document).ready(function() {     
		Metronic.init(); // init metronic core components
		Layout.init(); // init current layout
		
		@if(Session::get('alert')=='success')
			toastr['success']('{{ Session::get("alert_msg") }}');
		@endif
		
		$(document).on('click', ".print", function()
		{			
			if (notReady()) { return; }
			
			qz.append("{{ $print }}");                
					
			qz.print();
			
			toastr['success']('Print success.');
			
		});
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
					<a href="{{ URL::to('/') }}/sales/invoice">Data Faktur</a>
					<i class="fa fa-angle-right"></i>
				</li>
				<li>
					<a href="javascript:;">Detail</a>
				</li>
			</ul>
			<!-- END PAGE TITLE & BREADCRUMB-->
		</div>
	</div>
	<!-- END PAGE HEADER-->
	
	<!-- BEGIN PAGE CONTENT-->
	@if($id_customer>0)	
		<div class="row">
			<div class="col-md-12">
				<div class="portlet">
					<div class="portlet-title">
						<div class="caption">
							<i class="fa fa-shopping-cart"></i>{{ $status ? 'Faktur #'.$no_faktur : 'Draft #'.$no_draft }} <span class="hidden-480">
							| {{ displayDateTime($created_at) }} </span>
						</div>
						<div class="actions">
							<a href="{{ URL::to('/') }}/sales/invoice" class="btn default yellow-stripe">
							<i class="fa fa-angle-left"></i>
							<span class="hidden-480">
							Kembali </span>
							</a>
							<div class="btn-group">
								<a class="btn default yellow-stripe" href="javascript:;" data-toggle="dropdown" data-hover="dropdown" data-delay="1000" data-close-others="true">
								<i class="fa fa-print"></i>
								<span class="hidden-480">
								Cetak </span>
								<i class="fa fa-angle-down"></i>
								</a>
								<ul class="dropdown-menu pull-right">
									<li>
										<a href="javascript:;">
										Cetak ke PDF </a>
									</li>
									<li>
										<a href="javascript:;" class="print">
										Cetak ke Printer </a>
									</li>
								</ul>
							</div>
						</div>
					</div>
					<div class="portlet-body form">
						<!-- BEGIN FORM-->
						<form id="form" method="post" action="{{ URL::to('/') }}/sales/invoice/process_draft" class="form-horizontal">
							<div class="row">
								<div class="col-md-6 col-sm-12">
									<div class="portlet yellow-crusta box">
										<div class="portlet-title">
											<div class="caption">
												<i class="fa fa-cogs"></i>Detail {{ $status ? 'Faktur' : 'Draft' }}
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
															<label class="control-label col-md-3">No {{ $status ? 'Faktur' : 'Draft' }}</label>
															<div class="col-md-3">
																<p class="form-control-static">
																	<strong>{{ $status ? $no_faktur : $no_draft }}</strong>
																</p>
															</div>
															<label class="control-label col-md-3">Tanggal</label>
															<div class="col-md-3">
																<p class="form-control-static">
																	{{ displayDate($tanggal) }}
																</p>
															</div>
														</div>
													</div>
												</div>
												<div class="row">
													<div class="col-md-12">
														<div class="form-group">
															<label class="control-label col-md-3">Salesman</label>
															<div class="col-md-3">
																<p class="form-control-static">
																	{{ $nama_sales ? $nama_sales : '-' }}
																</p>
															</div>
															<label class="control-label col-md-3">Diskon Global</label>
															<div class="col-md-3">
																<p class="form-control-static">
																	@if($diskon_global>0)
																		@if($tipe_diskon_global == '%')
																			{{ displayNumeric($diskon_global).' %' }}
																		@else
																			{{ 'Rp '.displayNumeric($diskon_global) }}
																		@endif
																	@else
																		-
																	@endif
																</p>
															</div>
														</div>
													</div>
												</div>
												<div class="row">
													<div class="col-md-12">
														<div class="form-group">
															<label class="control-label col-md-3">Biaya </label>
															<div class="col-md-4">
																<p class="form-control-static">
																	{{{ displayNumeric($biaya) }}}
																	<span class="help-block"><i>(Pengiriman, dsb)</i></span>
																</p>
															</div>
															<label class="control-label col-md-2">Total</label>
															<div class="col-md-3">
																<p class="form-control-static">
																	<strong> {{ displayNumeric($total) }} </strong>
																</p>
															</div>
														</div>
													</div>
												</div>
												<div class="row">
													<div class="col-md-12">
														<div class="form-group">
															<label class="control-label col-md-3">Status</label>
															<div class="col-md-5">
																<p class="form-control-static">
																	<span class="label label-{{ $status_label }}">{{ $status_desc }}</span> 
																</p>
															</div>
															<div class="col-md-3">
																<p class="form-control-static">
																	@if($status==0)
																		{{ Form::hidden('id_trx',$id_trx,array('id'=>'id_trx')) }}
																		<button type="submit" class="btn green">Proses Draft</button>
																	@elseif($status==1 || $status==2)
																		<a href="#" class="btn green">Tagih</a>
																	@endif
																</p>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-6 col-sm-12">
									<div class="portlet blue-hoki box">
										<div class="portlet-title">
											<div class="caption">
												<i class="fa fa-cogs"></i>Info Pelanggan
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
															<label class="control-label col-md-3">Pelanggan</label>
															<div class="col-md-8">
																<p class="form-control-static">
																	{{ $nama_customer }}
																</p>
															</div>
														</div>
													</div>
												</div>
												<div class="row">
													<div class="col-md-12">
														<div class="form-group">
															<label class="control-label col-md-3">Termin Bayar</label>
															<div class="col-md-4">
																<p class="form-control-static">
																	{{ $termin_bayar>0 ? $termin_bayar.' Hari' : 'Saat Menerima Faktur' }}
																</p>
															</div>
														</div>
													</div>
												</div>
												<div class="row">
													<div class="col-md-12">
														<div class="form-group">
															<label class="control-label col-md-3">Jatuh Tempo</label>
															<div class="col-md-4">
																<p class="form-control-static">
																	{{ displayDate($jatuh_tempo) }}
																</p>
															</div>
														</div>
													</div>
												</div>
												<div class="row">
													<div class="col-md-12">
														<div class="form-group">
															<label class="control-label col-md-3">Alamat Penagihan</label>
															<div class="col-md-8">
																<p class="form-control-static">
																	{{ preg_replace( '#\r\n|\n|\r#', '<br />', $alamat_penagihan) }}
																</p>
															</div>
														</div>
													</div>
												</div>
												<div class="row">
													<div class="col-md-12">
														<div class="form-group">
															<label class="control-label col-md-3">Alamat Pengiriman</label>
															<div class="col-md-8">
																<p class="form-control-static">
																	{{ preg_replace( '#\r\n|\n|\r#', '<br />', $alamat_pengiriman) }}
																</p>
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
												<i class="fa fa-cogs"></i>Produk
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
														<th width="5%">#</th>
														<th width="25%">Nama Produk</th>
														<th colspan="2" width="15%">Qty</th>
														<th width="15%">Harga Satuan</th>
														<th width="20%">Diskon</th>
														<th width="15%">Total</th>
													</tr>
													</thead>
													<tbody>
														<?php $i=1; ?>
														@foreach($produk as $p)
															<tr role="row" class="item_row">
																<td><center>{{ $i }}</center></td>
																<td>{{ $p['nama_produk'] }}</td>
																<td class="alignRight">{{ displayNumeric($p['qty']) }}</td>
																<td>{{ $p['satuan'] }}</td>
																<td class="alignRight">{{ displayNumeric($p['harga_jual']) }}</td>
																<td class="alignRight">
																	@if($p['diskon']>0)
																		@if($p['tipe_diskon'] == '%')
																			{{ displayNumeric($p['diskon']).' %' }}
																		@else
																			{{ 'Rp '.displayNumeric($p['diskon']) }}
																		@endif
																	@else
																		-
																	@endif
																</td>
																<td class="alignRight">{{ displayNumeric($p['total_item']) }}</td>
															</tr>
															<?php $i++; ?>
														@endforeach
													</tbody>
													<tfoot>
														<tr role="row">
															<th colspan="5" rowspan="4">
															</th>
															<th class="alignRight">Sub Total</th>
															<td class="alignRight">
																{{ displayNumeric($sub_total) }}
															</td>
														</tr>
														<tr role="row">
															<th class="alignRight">Total Diskon</th>
															<td class="alignRight">
																{{ displayNumeric($total_diskon_global) }}
															</td>
														</tr>
														<tr role="row">
															<th class="alignRight">Biaya</th>
															<td class="alignRight">
																{{ displayNumeric($biaya) }}
															</td>
														</tr>
														<tr role="row">
															<th class="alignRight">Total</th>
															<td class="alignRight">
																<strong> {{ displayNumeric($total) }} </strong>
															</td>
														</tr>
													</tfoot>
													</table>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</form>
						<!-- END FORM-->
					</div>
				</div>
			</div>
		</div>
	@else 
		<!-- POS -->
		<div class="row">
			<div class="col-md-12">
				<div class="portlet box red">
					<div class="portlet-title">
						<div class="caption">
							<i class="fa fa-shopping-cart"></i>Faktur #{{ $no_faktur }} <span class="hidden-480">
							| {{ displayDateTime($created_at) }} </span>
						</div>
						<div class="actions">
							<a href="{{ URL::to('/') }}/sales/invoice" class="btn default yellow-stripe">
							<i class="fa fa-angle-left"></i>
							<span class="hidden-480">
							Kembali </span>
							</a>
							<div class="btn-group">
								<a class="btn default yellow-stripe" href="javascript:;" data-toggle="dropdown" data-hover="dropdown" data-delay="1000" data-close-others="true">
								<i class="fa fa-print"></i>
								<span class="hidden-480">
								Cetak </span>
								<i class="fa fa-angle-down"></i>
								</a>
								<ul class="dropdown-menu pull-right">
									<li>
										<a href="javascript:;" class="print">
										Cetak ke Printer </a>
									</li>
								</ul>
							</div>
						</div>
					</div>
					<div class="portlet-body form">
					<!-- BEGIN FORM-->
					<form id="form" class="form-horizontal">
						<div class="form-body">
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										<label class="control-label col-md-4">No Faktur</label>
										<div class="col-md-8">
											<p class="form-control-static">
												<strong>{{ $no_faktur }}</strong>
											</p>
										</div>
									</div>
								</div>
								<!--/span-->
								<div class="col-md-4">
									<div class="form-group">
										<label class="control-label col-md-3">Tanggal</label>
										<div class="col-md-6">
											<p class="form-control-static">
												{{ displayDate($tanggal) }}
											</p>
										</div>
									</div>
								</div>
								<!--/span-->
								<div class="col-md-4">
									<div class="form-group">
										<label class="control-label col-md-4">Diskon Global</label>
										<div class="col-md-6">
											<p class="form-control-static">
												@if($diskon_global>0)
													@if($tipe_diskon_global == '%')
														{{ displayNumeric($diskon_global).' %' }}
													@else
														{{ 'Rp '.displayNumeric($diskon_global) }}
													@endif
												@else
													-
												@endif
											</p>
										</div>
									</div>
								</div>
								<!--/span-->
							</div>
							<!--/row-->
							<div class="table-container">
								<table class="table table-striped table-bordered table-hover" id="datatable">
								<thead>
								<tr role="row" class="heading">
									<th width="5%">#</th>
									<th width="25%">Nama Produk</th>
									<th colspan="2" width="15%">Qty</th>
									<th width="15%">Harga Satuan</th>
									<th width="20%">Diskon</th>
									<th width="15%">Total</th>
								</tr>
								</thead>
								<tbody>
									<?php $i=1; ?>
									@foreach($produk as $p)
										<tr role="row" class="item_row">
											<td><center>{{ $i }}</center></td>
											<td>{{ $p['nama_produk'] }}</td>
											<td class="alignRight">{{ displayNumeric($p['qty']) }}</td>
											<td>{{ $p['satuan'] }}</td>
											<td class="alignRight">{{ displayNumeric($p['harga_jual']) }}</td>
											<td class="alignRight">
												@if($p['diskon']>0)
													@if($p['tipe_diskon'] == '%')
														{{ displayNumeric($p['diskon']).' %' }}
													@else
														{{ 'Rp '.displayNumeric($p['diskon']) }}
													@endif
												@else
													-
												@endif
											</td>
											<td class="alignRight">{{ displayNumeric($p['total_item']) }}</td>
										</tr>
										<?php $i++; ?>
									@endforeach
								</tbody>
								<tfoot>
									<tr role="row">
										<th colspan="2" rowspan="3">
										</th>
										<th colspan="4" class="alignRight">Sub Total</th>
										<td class="alignRight">{{ displayNumeric($sub_total) }}</td>
									</tr>
									<tr role="row">
										<th colspan="4" class="alignRight">Total Diskon</th>
										<td class="alignRight">{{ displayNumeric($total_diskon_global) }}</td>
									</tr>
									<tr role="row">
										<th colspan="4" class="alignRight">Total</th>
										<th class="alignRight"><strong>{{ displayNumeric($total_netto) }}</strong></th>
									</tr>
								</tfoot>
								</table>
							</div>
						</div>
					</form>
					<!-- END FORM-->
					</div>
				</div>
			</div>
		</div>
	@endif
	
	<div id="print_area" style="display:none;"></div>
	<canvas style="display:none;"></canvas>
	<!-- END PAGE CONTENT-->
@stop