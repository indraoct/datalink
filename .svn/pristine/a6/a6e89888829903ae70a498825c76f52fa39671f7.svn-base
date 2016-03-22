@extends('modules.project.detail')

@section('additionCss')
	<link href="{{ URL::asset('assets/dhtmlxGantt/dhtmlxgantt.css') }}" rel="stylesheet" type="text/css"/>
@stop
@section('additionJs')
	<script type="text/javascript" src="{{ URL::asset('/assets/dhtmlxGantt/dhtmlxgantt.js') }}"></script>
@stop

@section('readyFunction')
		//gantt.config.readonly = true;
		gantt.config.date_grid = "%d-%m-%Y";
		gantt.init("gantt_here");

		$.get( "{{ URL::to('/') }}/project/timeline/load/{{$id}}", function( data ) {
			gantt.parse(data);
		});

        var dp = new dataProcessor("{{ URL::to('/') }}/project/timeline/save?id_project={{$id}}");
        dp.setTransactionMode("POST");
        dp.init(gantt);
		gantt.attachEvent("onAfterTaskAdd", function (obj, index){
			setTimeout(function(){			
				gantt.clearAll();
				$.get( "{{ URL::to('/') }}/project/timeline/load/{{$id}}", function( data ) {
					gantt.parse(data);
				});
			}
			,100);
		});
@stop

@section('timelines')
<!-- BEGIN Content-->
	<div id="gantt_here" style='width:100%; height:400px;'></div>
	<script type="text/javascript">
	</script>
	<!-- END Content-->
@stop