@extends('layouts.admin.default')

@section('css')
<!-- BEGIN PAGE LEVEL STYLES -->
<link href="{{ URL::asset('/assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ URL::asset('/assets/global/plugins/bootstrap-modal/css/bootstrap-modal.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ URL::asset('/assets/global/plugins/bootstrap-datepicker/css/datepicker3.css') }}" rel="stylesheet" type="text/css"/>
<!-- END PAGE LEVEL SCRIPTS -->
@stop

@section('js')
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script type="text/javascript" src="{{ URL::asset('/assets/global/plugins/datatables/media/js/jquery.dataTables.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('/assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('/assets/global/scripts/datatable.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('/assets/global/plugins/bootstrap-modal/js/bootstrap-modal.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('/assets/global/plugins/bootstrap-modal/js/bootstrap-modalmanager.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('/assets/global/plugins/bootbox/bootbox.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') }}"></script>
<!-- END PAGE LEVEL PLUGINS -->
<script>

function construct() {

    $(document).on('click', ".call-modal", function(e) {
        e.preventDefault();
        nama_modal = '#modal-' + $(this).data('modal');
        $(nama_modal).modal('show');
    });

    /* BEGIN DATA TABLE */
    var grid = new Datatable();

    grid.init({
        src: $("#datatable"),
        onSuccess: function(grid) {
            // execute some code after table records loaded
        },
        onError: function(grid) {
            // execute some code on network or other general error  
        },
        dataTable: {// here you can define a typical datatable settings from http://datatables.net/usage/options 
            "ajax": {
                "url": "{{ URL::to('/') }}/purchase/order/get_data", // ajax source
            },
            "order": [
                [3, "desc"]
            ], // set first column as a default sort by asc
            "columns": [
                {data: 'no', className: "alignCenter"},
                {data: 'po_no'},
                {data: 'po_date'},
                {data: 'vendor_name', className: "alignLeft"},
                {data: 'total'},
                {data: 'action', bSortable: false, className: "alignCenter"}
            ]
        }
    });
    /* END DATA TABLE */

    /* DEFINE ELEMENT & OPTIONS HERE */

    if (jQuery().datepicker) {
        $('.date-picker').datepicker({
            rtl: Metronic.isRTL(),
            orientation: "left",
            autoclose: true
        });
        //$('body').removeClass("modal-open"); // fix bug when inline picker is used in modal
    }

    /* END DEFINE ELEMENT  */

    /* BEGIN EVENT HANDLER */

    // tombol delete
    $("#datatable tbody").on('click', "a.do_delete", function() {

        var oTable = grid.getDataTable();
        saveDelete(oTable.row($(this).parents('tr')).data().id);
    });

    /* END EVENT HANDLER */

    /* BEGIN JS FUNCTION */

    function saveDelete(id)
    {
        bootbox.confirm("Yakin ingin membatalkan faktur ini?", function(result) {
            if (result)
            {
                $.ajax({
                    url: "{{ URL::to('/') }}/sales/invoice/cancel",
                    type: "post",
                    data: 'id=' + id,
                    beforeSend: function() {
                        Metronic.blockUI();
                    },
                    success: function(result) {
                        var result = eval('(' + result + ')');
                        Metronic.unblockUI();
                        // alert(result);
                        if (result.status)
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
                    error: function(x, h, r)
                    {
                        alert(r);
                    }
                })
            }
        });
    }

    /* END JS FUNCTION */

}
;

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
                <a href="#">Procurement</a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <a href="#">Purchase Request</a>
            </li>
        </ul>
        <!-- END PAGE TITLE & BREADCRUMB-->
    </div>
</div>
<!-- END PAGE HEADER-->
<!-- BEGIN PAGE CONTENT-->
<div class="row">
    <div class="col-md-12">
        <!-- Begin: life time stats -->
        <div class="portlet">
            <div class="portlet-title">
                <div class="btn-group">
                    <a href="#" class="call-modal" data-modal="purpose"><button class="btn btn green filter-submit tooltips" data-original-title="Create New" id="add"><i class="fa fa-plus"></i></button></a>
                </div>
            </div>
            <div class="portlet-body">
                <div class="table-container">
                    <table class="table table-striped table-bordered table-hover" id="datatable">
                        <thead>
                            <tr role="row" class="heading">
                                <th width="2%">#</th>
                                <th width="10%">PO No</th>
                                <th width="20%">PO Date</th>
                                <th width="10%">Vendor</th>
                                <th width="10%">Total</th>
                                <th width="10%">Action</th>
                            </tr>
                            <tr role="row" class="filter">
                                <td>
                                </td>
                                <td>
                                    {{ Form::text('filter[po_no]','',array('class'=>'form-control form-filter input-sm', 'placeholder' => 'PO No')) }}
                                </td>
                                <td>
                                    <div class="input-group date date-picker margin-bottom-5" data-date-format="{{ Config::get('format.date.default') }}">
                                        {{ Form::text('filter[po_date]','',array('class'=>'form-control form-filter input-sm','placeholder'=>'PO Date','readonly'=>true)) }}
                                        <span class="input-group-btn">
                                            <button class="btn btn-sm default" type="button"><i class="fa fa-calendar"></i></button>
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    {{ Form::select('filter[vendor_name]', $listVendors,null,array('class'=>'form-control form-filter select2 input-sm')) }}
                                </td>
                                <td>
                                    <div class="margin-bottom-5">
                                        {{ Form::text('filter[total]','',array('class'=>'form-control form-filter input-sm currency','placeholder'=>'Total')) }}
                                    </div>
                                </td>
                                <td>
                                    <center>
                                        <button class="btn btn-sm yellow filter-submit tooltips" data-original-title="Cari"><i class="fa fa-search"></i> </button>
                                        <button class="btn btn-sm red filter-cancel tooltips" data-original-title="Reset"><i class="fa fa-times"></i> </button>
                                    </center>
                                </td>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- End: life time stats -->
    </div>
</div>

<div id="modal-purpose" class="modal fade" tabindex="-1" data-focus-on="input:first" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Choose Purchase Request</h4>
            </div>
            <div class="modal-body">
                <div class="scroller" style="height:170px" data-always-visible="1" data-rail-visible1="1">
                    <form role="form" id="form-purpose" method="get" class="form-horizontal" action="{{ URL::to('/') }}/purchase/order/new">
                        <div class="form-body">
                            <div id="pr_no" class="form-group">
                                <label class="col-md-4 control-label">Purchase Request No</label>
                                <div class="col-md-5">
                                    {{ Form::select('purchase_request', $listPurchases,'', array('class' => 'form-control')) }}
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="pull-right">
                            <button type="submit" id="save" class="btn blue">Process</button>
                            <button type="button" data-dismiss="modal" class="btn default">Close</button>
                        </div>
                    </form>
                </div>
            </div>


        </div>
    </div>
</div>

<!-- END PAGE CONTENT-->
@stop