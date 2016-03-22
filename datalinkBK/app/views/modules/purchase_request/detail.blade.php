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



jQuery(document).ready(function () {
    Metronic.init(); // init metronic core components
    Layout.init(); // init current layout

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
                <a href="{{ URL::to('/') }}/purchase/request">Purchase Request</a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <a href="#">Detail</a>
            </li>
        </ul>
        <!-- END PAGE TITLE & BREADCRUMB-->
    </div>
</div>
<!-- END PAGE HEADER-->
<!-- BEGIN PAGE CONTENT-->
<div class="row">
    <div class="col-md-12">
        <div class="portlet blue-hoki box">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-cog"></i> PR Detail
                </div>
            </div>
            <div class="portlet-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-body">
                            <div class="form-group">
                                <label class="col-md-4 control-label">PR No </label>
                                <div class="col-md-8">
                                    <label class="control-label">: {{ $pr_no }}</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label">Date </label>
                                <div class="col-md-8">
                                    <label class="control-label">: {{ $pr_tgl }}</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label">Notes </label>
                                <div class="col-md-8">
                                    <label class="control-label">: {{ $notes }}</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-body">
                            <div class="form-group">
                                <label class="col-md-4 control-label">Requestor </label>
                                <div class="col-md-8">
                                    <label class="control-label">: {{ $lreq[$requestor] }}</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label">Purpose </label>
                                <div class="col-md-8">
                                    <label class="control-label">: {{ $lpurpose[$purpose] }}</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label">Project Name </label>
                                <div class="col-md-8">
                                    <label class="control-label">: {{ $project_name }}</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label">No Ref BoM </label>
                                <div class="col-md-8">
                                    <label class="control-label">: {{ $bom }}</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="portlet blue-hoki box">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-cog"></i> Items
                </div>
            </div>
            <div class="portlet-body form">
                <div class="form-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div id="data_id"></div>
                            <div class="data_nama"></div>
                            <div class="table-container">
                                <table class="table table-striped table-bordered table-hover" id='purchase'>
                                    <thead>
                                        <tr role="row" class="heading">
                                            <th width="5%">#</th>
                                            <th width="50%">Item Name</th>
                                            <th width="15%">Stock Available</th>
                                            <?php if ($purpose == 1) { ?>
                                                <th width="15%">Minimum Stock</th>
                                            <?php } ?>
                                            <th colspan="2" width="20%">Qty Request</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 1;
                                        foreach ($detail as $d) {
                                            $id1 = $d->id_item;
                                            $item = GeneralModel::getTable('m_item', array('id_item' => "= $id1"))->first();
                                            $stock = DB::table('t_item_stock')
                                                    ->select(DB::raw('SUM( stock_initial + stock_in - stock_out ) as total'))
                                                    ->where('id_item', '=', $id1)
                                                    ->first();
                                            ?>
                                            <tr>
                                                <td><?php echo $no; ?></td>
                                                <td><?php echo $d->item_name; ?></td>
                                                <td class="currency"><?php echo displayNumeric($stock->total) . ' ' . $d->item_unit ?></td>
                                                <td class="currency"><?php echo displayNumeric($item->min_stock) . ' ' . $d->item_unit ?></td>
                                                <td class="currency"><?php echo $d->qty_request . ' ' . $d->item_unit ?></td>
                                            </tr>
                                            <?php
                                            $no++;
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="form-actions fluid">
                        <div class="col-md-offset-5 col-md-7">
                            <button class="btn default" type="button" onclick="history.back();">Back</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@stop