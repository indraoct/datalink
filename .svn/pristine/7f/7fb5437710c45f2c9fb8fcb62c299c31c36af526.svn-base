<!DOCTYPE html>
<head>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8">
    <title>Basic initialization</title>
</head>

	<script src="../../assets/dhtmlxGantt/dhtmlxgantt.js" type="text/javascript" charset="utf-8"></script>
	<link rel="stylesheet" href="../../assets/dhtmlxGantt/dhtmlxgantt.css" type="text/css" media="screen" title="no title" charset="utf-8">

<script src="{{ URL::asset('/assets/global/plugins/jquery.js') }}" type="text/javascript"></script>
<script src="{{ URL::asset('/assets/global/plugins/jquery-migrate-1.2.1.min.js') }}" type="text/javascript"></script>
<script src="{{ URL::asset('/assets/global/plugins/bootstrap/js/bootstrap.min.js') }}" type="text/javascript"></script>	

<style type="text/css" media="screen">
    html, body{
        margin:0px;
        padding:0px;
        height:100%;
        overflow:hidden;
    }
</style>

<script type="text/javascript" charset="utf-8">
    function init() {

		/* var tasks = {
  data:[
     {id:1, text:"Project #1", start_date:"01-04-2013", duration:18},
     {id:2, text:"Task #1", start_date:"02-04-2013", duration:8, parent:1},
     {id:3, text:"Task #2", start_date:"11-04-2013", duration:8, parent:1}
   ]
}; */
		// var tasks = {"data":[{"id":1,"owner":1,"id_project":2,"text":"Task #10","start_date":"0000-00-00 00:00:00","duration":8,"progress":0,"sortorder":0},{"id":2,"owner":1,"id_project":2,"text":"Task #10","start_date":"0000-00-00 00:00:00","duration":8,"progress":0,"sortorder":0,"parent":1},{"id":6,"owner":1,"id_project":2,"text":"Task #5","start_date":"0000-00-00 00:00:00","duration":28,"progress":0,"sortorder":0,"parent":0},{"id":9,"owner":1,"id_project":2,"text":"Task #5","start_date":"0000-00-00 00:00:00","duration":28,"progress":0,"sortorder":0,"parent":0},{"id":10,"owner":1,"id_project":2,"text":"Task #5","start_date":"0000-00-00 00:00:00","duration":28,"progress":0,"sortorder":0,"parent":0},{"id":11,"owner":1,"id_project":2,"text":"Task #5","start_date":"0000-00-00 00:00:00","duration":28,"progress":0,"sortorder":0,"parent":0},{"id":12,"owner":1,"id_project":2,"text":"test","start_date":"0000-00-00 00:00:00","duration":1,"progress":0,"sortorder":0,"parent":0},{"id":13,"owner":1,"id_project":2,"text":"test","start_date":"0000-00-00 00:00:00","duration":1,"progress":0,"sortorder":0,"parent":0},{"id":14,"owner":1,"id_project":2,"text":"test","start_date":"0000-00-00 00:00:00","duration":1,"progress":0.46808511018753,"sortorder":0,"parent":0},{"id":15,"owner":1,"id_project":2,"text":"test","start_date":"0000-00-00 00:00:00","duration":1,"progress":0.62006080150604,"sortorder":0,"parent":0},{"id":16,"owner":1,"id_project":2,"text":"Task #5","start_date":"0000-00-00 00:00:00","duration":28,"progress":0,"sortorder":0,"parent":0}],"links":[{"id":2,"source":1,"target":2,"type":"1"}]};

		gantt.init("gantt_here");
		// gantt.load(tasks);
		// gantt.parse(tasks);//works
		$.get( "{{ URL::to('/') }}/gantt/load/{{encode($id)}}", function( data ) {
			gantt.parse(data);
		});

        var dp = new dataProcessor("{{ URL::to('/') }}/gantt/save?id_project={{encode($id)}}");
        dp.setTransactionMode("POST");
        dp.init(gantt);
	}
</script>

<body onload="init();">
<div id="gantt_here" style='width:1020px; height:400px;'></div>
	<script type="text/javascript">


	</script>
</body>