@extends('layouts.global')

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
	<div class="row">
		<div class="col-md-12 page-404">
			<div class="number">
				 404
			</div>
			<div class="details">
				<h3>Oops! You're lost.</h3>
				<p>
					We can not find the page you're looking for.<br/>
					Return <a href="{{ URL::to('/') }}/home">home</a>
				</p>
			</div>
		</div>
	</div>
@stop