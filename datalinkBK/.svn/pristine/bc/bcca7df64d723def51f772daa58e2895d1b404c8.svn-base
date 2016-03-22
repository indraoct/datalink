	function construct() {

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
							"url": "{{ URL::to('/') }}/user_group/get_data", // ajax source
						},
						"order": [
							[1, "asc"]
						], // set first column as a default sort by asc
						"columns": [
							{ data: 'no', className: "alignCenter" },
							{ data: 'group_name_link' },
							{ data: 'description' },
							{ data: 'action', bSortable: false, className: "alignCenter" }
						]
					}
				});
		/* END DATA TABLE */
	
		/* BEGIN EVENT HANDLER */
		
		// tombol delete
		$("#datatable tbody").on('click', "a.do_delete", function(){
					
			var oTable = grid.getDataTable();
			saveDelete(oTable.row($(this).parents('tr')).data().id);
		});
		
		/* END EVENT HANDLER */
		
		/* BEGIN JS FUNCTION */
		
		function saveDelete(id)
		{			
			bootbox.confirm("Are you sure want to delete this data?", function(result) {
			   if(result)
			   {
					$.ajax({
						url: "{{ URL::to('/') }}/user_group/delete",
						type:"post",
						data: 'id='+id,
						beforeSend:function(){
							Metronic.blockUI();
						},
						success:function(result){
							var result = eval('('+result+')');
							Metronic.unblockUI();
							// alert(result);
							if(result.status)
							{
								toastr['success'](result.alert_msg);
								
								// Refresh table
								var oTable = grid.getDataTable();
								oTable.draw();
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
			   }
			}); 
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