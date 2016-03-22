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
    cek_session();
    var produkColumns = [
        {cTitle: "Kode", cName: "item_code", cClass: "", cSize: 3},
        {cTitle: "Item Name", cName: "item_name", cClass: "", cSize: 6}
    ];

    $("#cari_produk").select2({
        id: function (e) {
            return e.item_code;
        },
        placeholder: "Ketik kode/barcode atau nama",
        allowClear: true,
        minimumInputLength: 2,
        ajax: {
            url: '{{ URL::to('/') }}/purchase/get_data_select',
            dataType: 'json',
            type: "GET",
            quietMillis: 200,
            data: function (term) {
                return {
                    term: term
                };
            },
            results: function (data) {
                return {results: data};
            }
        },
        formatResult: function (item) {

            var text = '<div class="row">';

            if (item.key === 0) {
                $.each(produkColumns, function (i, col) {
                    text = text + '<div class="col-md-' + col.cSize + ' alignCenter"><strong>' + col.cTitle + '</strong></div>';
                });
            }

            $.each(produkColumns, function (i, col) {
                if (item[col.cName] === undefined)
                    item[col.cName] = '';
                text = text + '<div class="col-md-' + col.cSize + ' ' + col.cClass + '">' + item[col.cName] + '</div>';
            });

            return text + '</div>';
        },
        formatSelection: dataFormatSelection
    })
            .on("change", function (e) {
                $('#cari_produk').select2('val', '');

                getRowProduk(e.val);

            });

    function dataFormatSelection(items) {
        return items.item_name;
    }

    function getRowProduk(id) {
        $.ajax({
            url: '{{ URL::to('/') }}/purchase/get_row',
            dataType: 'json',
            type: 'POST',
            data: 'id=' + id,
            beforeSend: function () {
                Metronic.blockUI();
            },
            success: function (result) {
                Metronic.unblockUI();
                insertRowProduk(result);
            },
            error: function (x, h, r)
            {
                alert(r);
            }
        });
    }

    function insertRowProduk(data)
    {
		var $row = $(['<tr class="item-row" id="list-data' + data.id_item + '"><td><a href="" class="btn btn-xs red tooltips do_delete" data-id="' + data.id_item + '" data-original-title="Hapus"><i class="fa fa-trash-o"></i></a></td><td>' + data.nama_item + '</td><td class="alignRight">' + data.item_stock + '</td><?php if ($purpose == 1) { ?><td class="alignRight">' + data.item_min + '</td><?php } ?><td><input type="text" data-id="' + data.id_item + '" class="list-qty form-control currency" name="qty' + data.id_item + '" value="' + data.item_qty + '"></td></tr>'].join(''));
       
		$row.find('.currency').autoNumeric('init', {{ autoNumeric() }} );
		
        $('.item_row').before($row);
		
        cek_session();
        
    }
    
    function cek_session(){
        $.ajax({
            url: '{{ URL::to('/') }}/purchase/request/cek_session',
            type: 'post',
            dataType: 'json',
            success: function (data) {
                if (data.item_ada == 1) {
                    $('#proses').prop('disabled', false);
                }else{
                    $('#proses').prop('disabled', true);
                }
            }
        });
    }
    
    $(document).on('click', '.do_delete', function (e) {
        e.preventDefault();
        id = $(this).data('id');
        $.ajax({
            url: '{{ URL::to('/') }}/purchase/del_row',
            dataType: 'json',
            type: 'POST',
            data: 'id=' + id,
            success: function () {
                $('#list-data' + id).remove();
                cek_session();
            }
        });
    });

}


jQuery(document).ready(function () {
    Metronic.init(); // init metronic core components
    Layout.init(); // init current layout
    construct();
    

    $('.date-picker').datepicker({
        rtl: Metronic.isRTL(),
        orientation: "left",
        autoclose: true
    });

    $('#reqc').select2();

    

    $('.list-qty').keyup(function () {
        id = $(this).data('id');
        qty = $('[name="qty' + id + '"]').val();
        $.ajax({
            url: '{{ URL::to('/') }}/purchase/update_row',
            dataType: 'json',
            type: 'POST',
            data: {id: id, qty: qty}
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
                <a href="{{ URL::to('/') }}/purchase/request">Purchase Request</a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <a href="#">New</a>
            </li>
        </ul>
        <!-- END PAGE TITLE & BREADCRUMB-->
    </div>
</div>
<!-- END PAGE HEADER-->
<!-- BEGIN PAGE CONTENT-->
<form role="form" id="form-purpose" method="post" class="form-horizontal" action="{{ URL::to('/') }}/purchase/request/konfirmasi">
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
                            {{ Form::hidden('id_project', $id_project) }}
                            {{ Form::hidden('project_name', $project_name) }}
                            <div class="form-body">
                                <div class="form-group">
                                    <label class="col-md-3 control-label">PR No </label>
                                    <div class="col-md-5">
                                        {{ Form::text('pr_no', $pr_no, array('class' => 'form-control', 'readonly')) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Date </label>
                                    <div class="col-md-5">
                                        <div class="input-group date date-picker" id="div_tanggal" data-date="{{ $pr_tgl }}" data-date-format="{{ Config::get('format.date.default') }}">
                                            {{ Form::text('tanggal',$pr_tgl,array('id'=>'tanggal','class'=>'form-control','readonly'=>true)) }}
                                            <span class="input-group-btn">
                                                <button class="btn btn-sm default" type="button"><i class="fa fa-calendar"></i></button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Notes </label>
                                    <div class="col-md-5">
                                        {{ Form::textarea('notes','',array('class' => 'form-control','cols' => '40', 'rows' => '5')) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-body">
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Requestor </label>
                                    <div class="col-md-5">
                                        {{ Form::select('requestor', $requestor,'', array('id' => 'reqc','class' => 'form-control')) }}
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
                                <div class="form-group">
                                    <label class="control-label col-md-2">Search Items</label>
                                    <div class="col-md-8">
                                        {{ Form::text('cari_produk','',array('id'=>'cari_produk','class'=>'form-control')) }}
                                    </div>
                                </div>
                            </div>
                        </div>
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
                                            if (Session::has('purchase_request')) {
                                                $pre = Session::get('purchase_request');
                                                foreach ($pre as $pr1) {
                                                    ?>
                                                    <tr class="item-row" id="list-data<?php echo $pr1['id_list']; ?>">
                                                        <td><a href="" class="btn btn-xs red tooltips do_delete" data-original-title="Hapus" data-id="<?php echo $pr1['id_list']; ?>"><i class="fa fa-trash-o"></i></a></td>
                                                        <td><?php echo $pr1['name_list']; ?></td>
                                                        <td class="currency"><?php echo $pr1['stock_list']; ?> Unit</td>
                                                        <?php if ($purpose == 1) { ?>
                                                        <td class="currency"><?php echo $pr1['min_list']; ?> Unit</td>
                                                        <?php } ?>
                                                        <td><input type="text" data-id="<?php echo $pr1['id_list']; ?>" class="list-qty currency" name="qty<?php echo $pr1['id_list']; ?>" value="<?php echo $pr1['qty_list']; ?>"></td>
                                                    </tr>
                                                    <?php
                                                }
                                                ?>
                                            <?php } ?>
                                            <tr class="item_row"></tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions fluid">
                            <div class="col-md-offset-5 col-md-7">
                                <button type="submit" id="proses" data-sess="0" class="btn blue" disabled="<?php ?>true">Proses</button>
                                <a class="btn red" href="{{ URL::to('/') }}/purchase/request">Cancel</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>


@stop