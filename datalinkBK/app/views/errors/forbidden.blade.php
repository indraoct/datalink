@extends('layouts.admin.default')

@section('css')
	<!-- BEGIN PAGE LEVEL STYLES -->
	<link href="{{ URL::asset('/assets/admin/pages/css/error.css') }}" rel="stylesheet" type="text/css"/>
	<!-- END PAGE LEVEL SCRIPTS -->
@stop

@section('js')
	<script>
		jQuery(document).ready(function() {     
			Metronic.init(); // init metronic core components
			Layout.init(); // init current layout
		});
	</script>
@stop

@section('content')
	<!-- BEGIN PAGE CONTENT-->
	<div class="row">
		<div class="col-md-12 page-404">
			<div class="number">
				 405
			</div>
			<div class="details">
				<h3>Forbidden.</h3>
				<p>
					You are not allowed to access this page.<br/>
					Return <a href="{{ URL::to('/') }}/home">home</a>
				</p>
			</div>
		</div>
	</div>
	<!-- END PAGE CONTENT-->
@stop
