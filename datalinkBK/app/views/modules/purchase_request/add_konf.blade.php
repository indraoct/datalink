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

    $("#form-purpose").submit(function (e) {
        e.preventDefault();
        
        $.ajax({
            url: "{{ URL::to('/') }}/purchase/request/new/save",
            type: "post",
            data: $(this).serialize(),
            beforeSend: function () {
                Metronic.blockUI();
            },
            success: function (result) {
                var result = eval('(' + result + ')');
                Metronic.unblockUI();
                // alert(result);
                var alert_msg = '';
                if (result.status){
                    toastr['success'](result.alert_msg);
                    setTimeout('', 500);
                    window.location.href = "{{ URL::to('/') }}/purchase/request";
                }else{
                    toastr['error'](result.alert_msg);
                }
            },
            error: function (x, h, r)
            {
                alert(r);
            }
        });
    });

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
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <a href="#">Konfirmasi</a>
            </li>
        </ul>
        <!-- END PAGE TITLE & BREADCRUMB-->
    </div>
</div>
<!-- END PAGE HEADER-->
<!-- BEGIN PAGE CONTENT-->
<form role="form" id="form-purpose" method="post" class="form-horizontal" action="{{ URL::to('/') }}/purchase/request/new/save">
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
                            {{ Form::hidden('bom', $bom) }}
                            {{ Form::hidden('purpose', $purpose) }}
                            {{ Form::hidden('pr_no', $pr_no) }}
                            {{ Form::hidden('tanggal', $pr_tgl) }}
                            {{ Form::hidden('notes', $notes) }}
                            {{ Form::hidden('requestor', $requestor) }}
                            {{ Form::hidden('id_project', $id_project) }}
                            {{ Form::hidden('project_name', $project_name) }}
                            <div class="form-body">
                                <div class="form-group">
                                    <label class="col-md-3 control-label">PR No </label>
                                    <div class="col-md-5">
                                        <label class="control-label">: {{ $pr_no }}</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Date </label>
                                    <div class="col-md-5">
                                        <label class="control-label">: {{ $pr_tgl }}</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Notes </label>
                                    <div class="col-md-5">
                                        <label class="control-label">: {{ $notes }}</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-body">
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Requestor </label>
                                    <div class="col-md-5">
                                        <label class="control-label">: {{ $lreq[$requestor] }}</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Purpose </label>
                                    <div class="col-md-5">
                                        <label class="control-label">: {{ $lpurpose[$purpose] }}</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Project Name </label>
                                    <div class="col-md-5">
                                        <label class="control-label">: {{ $project_name }}</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">No Ref BoM </label>
                                    <div class="col-md-5">
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
                                            <tr role="row" class="item_row"></tr>
                                            <?php
                                            if (Session::has('purchase_request')) {
                                                $pre = Session::get('purchase_request');
                                                $no = 1;
                                                foreach ($pre as $pr1) {
                                                    ?>
                                                    <tr id="list-data<?php echo $pr1['id_list']; ?>">
                                                        <td><?php echo $no; ?></td>
                                                        <td><?php echo $pr1['name_list']; ?></td>
                                                        <td class="currency"><?php echo $pr1['stock_list'] + 0; ?> Unit</td>
                                                        <?php if ($purpose == 1) { ?>
                                                            <td class="currency"><?php echo $pr1['min_list'] + 0; ?> Unit</td>
        <?php } ?>
                                                        <td class="currency"><?php echo $pr1['qty_list']; ?> Unit</td>
                                                    </tr>
                                                    <?php
                                                    $no++;
                                                }
                                                ?>
<?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions fluid">
                            <div class="col-md-offset-5 col-md-7">
                                <button type="submit" id="proses" data-sess="0" class="btn blue" >Proses</button>
                                <button class="btn red" type="button" onclick="history.back();">Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>


@stop