@extends('layouts.admin.default')

@section('css')
	@yield('additionCss')
@stop

@section('js')
	<!-- BEGIN PAGE LEVEL PLUGINS -->
	<!-- END PAGE LEVEL PLUGINS -->
@yield('additionJs')
	
	<script>
	function construct() {

		/* END JS FUNCTION */
		$('a[href$="#{{$page}}"]')
		  .closest( "li" )
		  .addClass( "active" );

		$('#{{$page}}')
		  .removeClass('fade')
		  .addClass( "active" );

		@yield('construct')
	};

	jQuery(document).ready(function() {     
		Metronic.init(); // init metronic core components
		Layout.init(); // init current layout
		construct();
		@yield('readyFunction')
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
					<a href="#">Project Management & Sales</a>
					<i class="fa fa-angle-right"></i>
				</li>
				<li>
					<a href="#">Master</a>
					<i class="fa fa-angle-right"></i>
				</li>
				<li>
				<?php
				$urlProject = 'open_project';
				if($data->status == 4){
					$urlProject = 'project';
				}
				?>
					<a href="{{ URL::to('/').'/'.$urlProject }}">Project</a>
					<i class="fa fa-angle-right"></i>
				</li>
				<li>
					<a href="#">Detail</a>
					<i class="fa fa-angle-right"></i>
				</li>
				<li>
					<a href="#">{{$data->project_name}}</a>
				</li>
			</ul>
			<!-- END PAGE TITLE & BREADCRUMB-->
		</div>
	</div>
	<!-- END PAGE HEADER-->
	<!-- BEGIN PAGE CONTENT-->
		<div class="form-body">
				<ul class="nav nav-tabs">
					<li>
						<a href="#overview" data-toggle="tab" onClick="window.location.href='{{URL::to('/') }}/project/overview/{{$id}}'">Overview</a>
					</li>
					<li>
						<a href="#timelines" data-toggle="tab" onClick="window.location.href='{{URL::to('/') }}/project/timeline/{{$id}}'">Timelines</a>
					</li>
					<li>
						<a href="#quotation" data-toggle="tab" onClick="window.location.href='{{URL::to('/') }}/project/quotation/{{$id}}'">Quotation</a>
					</li>
					<li>
						<a href="#salesPage" data-toggle="tab" onClick="window.location.href='{{URL::to('/') }}/project/sales/{{$id}}'">Sales</a>
					</li>
					<li>
						<a href="#bom" data-toggle="tab" onClick="window.location.href='{{URL::to('/') }}/project/bom/{{$id}}'">Bill of Material</a>
					</li>
					<li>
						<a href="#attachment" data-toggle="tab" onClick="window.location.href='{{URL::to('/') }}/project/attachment/{{$id}}'">Attachment</a>
					</li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane fade" id="overview">
						@yield('overview')
					</div>
					<div class="tab-pane fade" id="timelines">
						@yield('timelines')
					</div>
					<div class="tab-pane fade" id="quotation">
						@yield('quotation')
					</div>
					<div class="tab-pane fade" id="salesPage">
						@yield('sales')
					</div>
					<div class="tab-pane fade" id="bom">
						@yield('bom')
					</div>
					<div class="tab-pane fade" id="attachment">
						@yield('attachment')
					</div>
				</div>
		</div>
	<!-- END PAGE CONTENT-->
@stop
