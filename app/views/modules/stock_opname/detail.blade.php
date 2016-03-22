@extends('layouts.admin.default')

@section('css')
@stop

@section('js')
	<script>
	jQuery(document).ready(function() {     
		Metronic.init(); // init metronic core components
		Layout.init(); // init current layout
		
		@if(Session::get('alert')=='success')
			toastr['success']('{{ Session::get("alert_msg") }}');
		@endif
		
		
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
						<a href="{{ URL::to('/') }}/inventory">Inventory</a>
						<i class="fa fa-angle-right"></i>
					</li>
					<li>
						<a href="{{ URL::to('/') }}/inventory/opname">Stock Opname</a>
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
	<div class="row">
		<div class="col-md-12">
                    <div class="row">
                        <div class="form-body">
                                  <div class="col-md-6">
                                      <label class="col-md-3">Date :</label>
                                          <div class="form-group col-md-4">  
                                               <div class="input-group date date-picker margin-bottom-5" data-date-format="{{ Config::get('format.date.default') }}">
                                                                            
                                                                            {{$trx_date}}
                                                                           
                                                </div>
                                          </div>
                                  </div>
                          </div>
                    </div>
                    <div class="row">
                      <div class="form-body">
                              <div class="col-md-6">
                                  <label class="col-md-3">Ref No :</label>
                                      <div class="form-group col-md-4">  
                                           {{ $no_ref}}
                                      </div>
                              </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="form-body">
                              <div class="col-md-6">
                                  <label class="col-md-3">Note :</label>
                                      <div class="form-group col-md-4">  
                                           {{ preg_replace( '#\r\n|\n|\r#', '<br />',$note) }}
                                      </div>
                              </div>
                      </div>
                    </div>
                    
                    <div class="row">
					<div class="col-md-12">
						<div class="portlet">
                                                    <div class="portlet-title">
               
                                                            <div class="portlet-body">

                                                                            <div class="table-container">
                                                                                    <table class="table table-striped table-bordered table-hover" id="datatable">
                                                                                    <thead>
                                                                                    <tr role="row" class="heading">
                                                                                                                    <?php
                                                                                                                            foreach($tableHeader as $content)
                                                                                                                            {
                                                                                                                                    echo "<th>{$content['label']}</th>";
                                                                                                                            }
                                                                                                                    ?>
                                                                                    </tr>
                                                                                    
                                                                                    </thead>
                                                                                    <tbody>
                                                                                            <tr role="row" class="item_row"></tr>
                                                                                            <?php $i=1; ?>
                                                                                            @foreach($item as $p)
                                                                                                    <tr role="row" class="item_row">
                                                                                                            <td><center>{{ $i }}</center></td>
                                                                                                            <td>{{ $p['item_code'] }}</td>
                                                                                                            <td>{{ $p['item_name'] }}</td>
                                                                                                            <td class="alignRight">{{ $p['qty'] }}</td>
                                                                                                            <td>{{ $p['warehouse_name'] }}</td>
                                                                                                    </tr>
                                                                                                    <?php $i++; ?>
                                                                                            @endforeach
                                                                                    </tbody>
                                                                                    </table>
                                                                            </div>
                                                                
                                                                

                                                        </div>
                                        </div>
                                                             
                             </div>
                         </div>
			<!-- End: life time stats -->
		</div>
                <div class="row">

                </div>    
	</div>
   </div>
@stop