@extends('modules.project.detail')

@section('additionCss')
	<link href="{{ URL::asset('/assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css') }}" rel="stylesheet" type="text/css"/>
	<link href="{{ URL::asset('/assets/global/plugins/bootstrap-modal/css/bootstrap-modal.css') }}" rel="stylesheet" type="text/css"/>
	<link href="{{ URL::asset('/assets/global/plugins/bootstrap-datepicker/css/datepicker3.css') }}" rel="stylesheet" type="text/css"/>
	<link href="{{ URL::asset('/assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css') }}" rel="stylesheet" type="text/css"/>
@stop

@section('additionJs')
	<script type="text/javascript" src="{{ URL::asset('/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') }}"></script>
	<script type="text/javascript" src="{{ URL::asset('/assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js') }}"></script>
@stop

@section('construct')
	if (jQuery().datepicker) {
            $('.date-picker').datepicker({
                rtl: Metronic.isRTL(),
                orientation: "left",
                autoclose: true
            });
	}
@stop

@section('readyFunction')
@stop

<script>
	var arrayGroup = [{index:0,array:[]}];
	
	function arraySearch(arr,val) 
	{
		for (var i=0; i<arr.length; i++)
			if (arr[i] === val)                    
				return i;
		return false;
	}
	
	function addGroup()
	{
		var lastIndex = arrayGroup[arrayGroup.length-1].index + 1;
		arrayGroup.push({index:lastIndex,array:[]});

		$( "#addGroup" ).find( ".groupName" ).attr( "name", "groupName["+lastIndex+"]" );
		$( "#addGroup" ).find( ".groupName" ).attr( "id", "group-"+lastIndex );
		$( "#addGroup" ).find( ".addGroup" ).attr( "onClick", "addSubGroup(this,"+lastIndex+")" );
		$($('#addGroup').html()).appendTo('#tableContent');
	}

	function removeGroup(dom,level)
	{
		var groupID = $(dom).closest( 'tbody').find('.groupName').attr('id');
		var globalID = groupID.substr(groupID.indexOf("-") + 1);
		
		if(level==1)
		{
			var id = globalID;
			arrayGroup = $.grep(arrayGroup,
					   function(o,i) { return o.index == id; },
					   true);
			console.log(arrayGroup);
		}
		else if(level==2)
		{
			var parentID = globalID.substring(0, globalID.indexOf('-'));
			var id = globalID.substr(globalID.indexOf("-") + 1);
			console.log(parentID);
			console.log(id);
			
			var getArray = $.grep(arrayGroup,
					   function(o,i) { return o.index != parentID; },
					   true);
			var removeID = $.grep(Object.keys(arrayGroup), function (k) { return arrayGroup[k].index == parentID; });
			var arraySubGroup = getArray[0].array;
			arraySubGroup = $.grep(arraySubGroup,
					   function(o,i) { return o.index == id; },
					   true);
			arrayGroup[removeID].array = arraySubGroup;
		}
		else if(level==3)
		{
			var parentID = globalID.substring(0, globalID.indexOf('-'));
			var id = globalID.substr(globalID.indexOf("-") + 1);
			
			var getArray = $.grep(arrayGroup,
					   function(o,i) { return o.index != parentID; },
					   true);
			var removeID = $.grep(Object.keys(arrayGroup), function (k) { return arrayGroup[k].index == parentID; });
			var arraySubGroup = getArray[0].array;
			arraySubGroup = $.grep(arraySubGroup,
					   function(o,i) { return o.index == id; },
					   true);
			arrayGroup[removeID].array = arraySubGroup;
		}
		
		
		$(dom).closest( 'tbody').remove();
		removeParent(groupID);
	}

	function addSubGroup(dom,parentID)
	{
		var getArray = $.grep(arrayGroup,
                   function(o,i) { return o.index != parentID; },
                   true);
		console.log(getArray);
	   var arraySubGroup = getArray[0].array;
		var lastIndex = 0;
		if(arraySubGroup.length)
		{
			var lastIndex = arraySubGroup[arraySubGroup.length-1].index + 1;
		}
			
		arraySubGroup.push({index:lastIndex,array:[]});
		arrayGroup = $.grep(arrayGroup,
                   function(o,i) { return o.index == parentID; },
                   true);
		arrayGroup.push({index:parentID,array:arraySubGroup});
		
		var groupName = 'groupName['+parentID+']';
		var margin = 20;
		
		$( "#addSubGroup tbody" ).find( ".groupName" ).parent().attr("style","margin-left:"+margin+"px");
		$( "#addSubGroup tbody" ).find( ".groupName" ).attr( "name", groupName+"["+lastIndex+"]" );
		$( "#addSubGroup tbody" ).find( ".groupName" ).attr( "id", "group-"+parentID+"-"+lastIndex );
		$( "#addSubGroup tbody" ).find( ".addSubGroup" ).show();
		$( "#addSubGroup tbody" ).find( ".addSubGroup" ).attr( "onClick", "addSubSubGroup(this,'"+parentID+"-"+lastIndex+"')" );
		$( "#addSubGroup tbody" ).addClass( "group-"+parentID );
		$( "#addSubGroup tbody tr #remove" ).attr( "onClick", "removeGroup(this,2)" );
		var html = $("#addSubGroup").html();

		$( "#addSubGroup tbody" ).removeAttr( "class" );
		
		$( html ).insertAfter( $(dom).closest( $('tbody') ));
	}

	function addSubSubGroup(dom,globalID)
	{
		var parentID = globalID.substring(0, globalID.indexOf('-'));
		var subParentID = globalID.substr(globalID.indexOf("-") + 1);

		var getArrayGroup = $.grep(arrayGroup,
                   function(o,i) { return o.index != parentID; },
                   true);

		var arraySubGroup = getArrayGroup[0].array;
		var getArraySubGroup = $.grep(arraySubGroup,
                   function(o,i) { return o.index != subParentID; },
                   true);
	
	   var arraySubSubGroup = getArraySubGroup[0].array;

		var lastIndex = 0;
		if(arraySubSubGroup.length)
		{
			var lastIndex = arraySubSubGroup[arraySubSubGroup.length-1].index + 1;
		}
			
		arraySubSubGroup.push({index:lastIndex,array:[]});

		arraySubGroup = $.grep(arraySubGroup,
                   function(o,i) { return o.index == subParentID; },
                   true);
		arrayGroup = $.grep(arrayGroup,
                   function(o,i) { return o.index == parentID; },
                   true);
		arraySubGroup.push({index:subParentID,array:arraySubSubGroup});
		arrayGroup.push({index:parentID,array:arraySubGroup});
		
		var groupName = 'groupName['+parentID+']['+subParentID+']';
		var margin = 40;
		
		$( "#addSubGroup tbody" ).find( ".groupName" ).parent().attr("style","margin-left:"+margin+"px");
		$( "#addSubGroup tbody" ).find( ".groupName" ).attr( "name", groupName+"["+lastIndex+"]" );
		$( "#addSubGroup tbody" ).find( ".groupName" ).attr( "id", "group-"+globalID+"-"+lastIndex );
		$( "#addSubGroup tbody" ).find( ".addSubGroup" ).hide();
		$( "#addSubGroup tbody" ).addClass( "group-"+parentID );
		$( "#addSubGroup tbody" ).addClass( "group-"+globalID );
		$( "#addSubGroup tbody tr #remove" ).attr( "onClick", "removeGroup(this,3)" );
		var html = $("#addSubGroup").html();

		$( "#addSubGroup tbody" ).removeAttr( "class" );
		
		$( html ).insertAfter( $(dom).closest( $('tbody') ));
	}

	function removeParent(rClass)
	{
		$('.'+rClass).closest( $('tbody') ).remove();
	}
	
</script>

@section('quotation')
<!-- BEGIN Content-->
	<div class="row">
		<div class="col-md-12">
			<div class="tab-pane " id="tab_2">
				<div class="portlet box blue">
					<div class="portlet-title">
						<div class="caption">
							<i class="fa fa-gift"></i>Add Quotation
						</div>
						@if(isset($new))
						<div class="tools">
							Export Here
						</div>
						@endif
					</div>
					<div class="portlet-body form">
						<!-- BEGIN FORM-->
						{{ Form::open(array('method'=>'POST','class'=>'form-horizontal','files'=>true,)) }}
							<div class="form-body">
								<h3 class="form-section">Quotation Detail</h3>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label class="control-label col-md-3">QT - No</label>
											<div class="col-md-9">
												{{ Form::hidden('id',$id,array('id'=>'id')) }}
												{{ Form::text('qt_no','QT - '.$data->project_code,array('id'=>'qt_no','class'=>'form-control','readonly'=>true)) }}
											</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label class="control-label col-md-3">Total COGS</label>
											<div class="col-md-9">
												{{ Form::text('total_cogs',$quot['master']['total_cogs'],array('id'=>'total_cogs','class'=>'form-control currency')) }}
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label class="control-label col-md-3">Date</label>
											<div class="col-md-9">
												<div class="input-group  date date-picker margin-bottom-5" data-date-format="{{ Config::get('format.date.default') }}">
													{{ Form::text('qt_date',$quot['master']['qt_date'],array('id'=>'qt_date','class'=>'form-control form-filter input-sm fix-var','readonly'=>true)) }}
													<span class="input-group-btn">
													<button class="btn btn-sm default" type="button"><i class="fa fa-calendar"></i></button>
													</span>
												</div>
											</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label class="control-label col-md-3">Total Sales</label>
											<div class="col-md-9">
												{{ Form::text('total_sales',$quot['master']['total_sales'],array('id'=>'total_sales','class'=>'form-control currency')) }}
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label class="control-label col-md-3">Ref No</label>
											<div class="col-md-9">
												{{ Form::text('ref_no',$quot['master']['ref_no'],array('id'=>'ref_no','class'=>'form-control')) }}
											</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label class="control-label col-md-3">Margin (%)</label>
											<div class="col-md-9">
												{{ Form::text('percentage_margin',$quot['master']['percentage_margin'],array('id'=>'percentage_margin','class'=>'form-control currency','maxlength'=>"5")) }}
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label class="control-label col-md-3">Currency</label>
											<div class="col-md-9">
												{{Form::select('currency',$listCcy,$quot['master']['currency'],array('id'=>'currency','class'=>'form-control select2')) }}
											</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label class="control-label col-md-3">Total Margin</label>
											<div class="col-md-9">
												{{ Form::text('total_margin',$quot['master']['total_margin'],array('id'=>'total_margin','class'=>'form-control currency','maxlength'=>"5")) }}
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label class="control-label col-md-3">Notes</label>
											<div class="col-md-9">
												{{ Form::textarea('notes',$quot['master']['notes'],array('id'=>'notes','class'=>'form-control','size'=>'10x3')) }}
											</div>
										</div>
									</div>
									<div class="col-md-6">
									</div>
								</div>
								<h3 class="form-section">Item</h3>
									<div class="portlet-title">
										<div class="btn-group">
											<a href="javascript:void(0)" onClick="addGroup()"><input type="button" class="btn btn green filter-submit tooltips fa fa-plus" value="Add Group" data-original-title="Add Group" id="add"></a>
										</div>
									</div>
									<div class="table-container">
										<table class="table table-striped table-bordered table-hover" id="tableContent">
											<thead>
												<tr role="row" class="heading">
													<th width="3%" tabindex="0" rowspan="1" colspan="1">
														#
													</th>
													<th width="10%" tabindex="0" rowspan="1" colspan="1">
														Item Group
													</th>
													<th width="10%" tabindex="0" rowspan="1" colspan="1">
														Item Name
													</th>
													<th width="7%" tabindex="0" rowspan="1" colspan="1">
														Qty
													</th>
													<th width="10%" tabindex="0" rowspan="1" colspan="1">
														Price List Vendor
													</th>
													<th width="10%" tabindex="0" rowspan="1" colspan="1">
														Disc Vendor
													</th>
													<th width="10%" tabindex="0" rowspan="1" colspan="1">
														COGS
													</th>
													<th width="10%" tabindex="0" rowspan="1" colspan="1">
														Margin
													</th>
													<th width="10%" tabindex="0" rowspan="1" colspan="1">
														Sales Price
													</th>
													<th width="10%" tabindex="0" rowspan="1" colspan="1">
														Rounding Price
													</th>
													<th width="10%" tabindex="0" rowspan="1" colspan="1">
														@Price
													</th>
												</tr>
											</thead>
											<tbody id="subGroup">
												<tr role="row" class="itemGroup">
													<td>
													</td>
													<td colspan="2">
														{{ Form::text('groupName[0]','',array('id'=>'group-0','class'=>'form-control groupName','placeholder'=>'Group Name')) }}
													</td>
													<td colspan="8">
														<div class="portlet-title">
															<div class="btn-group">
																<a href="javascript:void(0)" onClick="addSubGroup(this,0)"><input type="button" class="btn btn green filter-submit tooltips fa fa-plus" value="Add Sub Group" data-original-title="Add Sub Group" id="add"></a>
																<a href="javascript:void(0)" onClick="addGroup()"><input type="button" class="btn btn green filter-submit tooltips fa fa-plus" value="Add Item" data-original-title="Add Item" id="add"></a>
															</div>
														</div>
													</td>
												</tr>
											</tbody>
										</table>
									</div>
									
								<h3 class="form-section">Wording</h3>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label class="control-label col-md-3">Foreword</label>
											<div class="col-md-9">
												{{ Form::textarea('foreword',$quot['master']['foreword'],array('id'=>'foreword','class'=>'form-control','size'=>'20x5')) }}
											</div>
										</div>
									</div>
									<div class="col-md-6">
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label class="control-label col-md-3">Afterword</label>
											<div class="col-md-9">
												{{ Form::textarea('afterword',$quot['master']['afterword'],array('id'=>'afterword','class'=>'form-control','size'=>'20x5')) }}
											</div>
										</div>
									</div>
									<div class="col-md-6">
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label class="control-label col-md-3">Attachment</label>
											<div class="col-md-9">
												<div class="fileinput fileinput-new" data-provides="fileinput">
													<span class="btn default btn-file">
													<span class="fileinput-new">
													Select file </span>
													<span class="fileinput-exists">
													Change </span>
													<input type="file" name="attachment_filename">
													</span>
													<span class="fileinput-filename">
													</span>
													&nbsp; <a href="#" class="close fileinput-exists" data-dismiss="fileinput">
													</a>
												</div>
											</div>
										</div>
									</div>
									<div class="col-md-6">
									</div>
								</div>
							</div>
							
							<div class="form-actions fluid">
								<div class="row">
									<div class="col-md-6">
										<div class="col-md-offset-3 col-md-9">
											<button type="submit" class="btn blue">Submit</button>
											<button type="button" class="btn default" onClick='window.location.href ="{{ URL::to('/') }}/project/quotation/{{$id}}"'>Cancel</button>
										</div>
									</div>
									<div class="col-md-6">
									</div>
								</div>
							</div>
						{{ Form::close() }}
						<!-- END FORM-->
					</div>
				</div>
			</div>
		</div>
	</div>
	<table id="addGroup" style="display:none">
		<tbody>
		<tr role="row" class="itemGroup">
			<td>
				<a href="javascript:void(0)" id="remove" onClick="removeGroup(this,1);"><span class="badge badge-danger">x</span></a>
			</td>
			<td colspan="2">
				{{ Form::text('groupName[0]','',array('id'=>'','class'=>'form-control groupName','placeholder'=>'Group Name')) }}
			</td>
			<td colspan="8">
				<div class="portlet-title">
					<div class="btn-group">
						<a href="javascript:void(0)" class="addGroup"><input type="button" class="btn btn green filter-submit tooltips fa fa-plus" value="Add Sub Group" data-original-title="Add Sub Group" id="add"></a>
						<a href="javascript:void(0)" onClick="addGroup()"><input type="button" class="btn btn green filter-submit tooltips fa fa-plus" value="Add Item" data-original-title="Add Item" id="add"></a>
					</div>
				</div>
			</td>
		</tr>
		</tbody>
	</table>
	<table id="addSubGroup" style="display:none">
		<tbody>
		<tr role="row">
			<td>
				<a href="javascript:void(0)" id="remove" ><span class="badge badge-danger">x</span></a>
			</td>
			<td colspan="10">
				<div class="input-group">
					{{ Form::text('groupName[0][0]','',array('class'=>'form-control groupName','placeholder'=>'Sub Group Name')) }}
					<span class="input-group-btn" >
						<a href="javascript:void(0)" class="addSubGroup"><input type="button" class="btn btn green filter-submit tooltips fa fa-plus" value="Add Sub Group" data-original-title="Add Sub Group" id="add"></a>
						<a href="javascript:void(0)" onClick="addGroup()"><input type="button" class="btn btn green filter-submit tooltips fa fa-plus" value="Add Item" data-original-title="Add Item" id="add"></a>
					</span>
				</div>
			</td>
		</tr>
		</tbody>
	</table>
<!-- END Content-->
@stop