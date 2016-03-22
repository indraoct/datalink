@extends('layouts.admin.default')

@section('css')
<!-- BEGIN PAGE LEVEL STYLES -->
<link href="{{ URL::asset('/assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ URL::asset('/assets/global/plugins/bootstrap-modal/css/bootstrap-modal.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ URL::asset('/assets/global/plugins/bootstrap-datepicker/css/datepicker3.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ URL::asset('/assets/admin/layout/css/custom.css') }}" rel="stylesheet" type="text/css"/>
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
    var produkColumns = [
        {cTitle: "Kode", cName: "item_code", cClass: "", cSize: 3},
        {cTitle: "Item Name", cName: "item_name", cClass: "", cSize: 6}
    ];
}

function cekSelect() {
    $('.m_select').each(function () {
        v_sel = $(this).val();
        id_sel = $(this).attr('data-id');
        if (v_sel != '') {
            $('.m_sub_select[data-id="' + id_sel + '"]').attr('readonly', false);
        }
    });
}

function mNumeric(angka) {
    n_angka = angka.replace(/\./g, '');
    return n_angka;
}

function mFloat(angka) {
    n_angka = angka.replace(/\./g, '');
    n_angka = n_angka.replace(',', '.');
    return n_angka;
}

function mCurrency(angka) {
    return angka.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.") + ',00';
}

function mHitung() {
    var v_sub = j_sub = a_sub = j_tot = v_tax = v_disc = v_gen_disc = 0;

    v_tax = mNumeric($('[name="t_tax"]').val());
    v_gen_disc = mFloat($('[name="t_global_disc"]').val());
    v_disc = $('[name="t_disc_t"]').val();
    v_tax = mFloat($('[name="t_tax"]').val());

    $('.m_subtotal').each(function () {
        v_sub = parseInt(mNumeric($(this).val()));
        j_sub = j_sub + v_sub;
    });


    if (v_disc == 0) {
        a_sub = (j_sub * v_gen_disc) / 100;
        j_tot = (j_sub - a_sub) + (((j_sub - a_sub) * v_tax) / 100);
    } else if (v_disc == 1) {
        a_sub = v_gen_disc;
        j_tot = (j_sub - a_sub) + (((j_sub - a_sub) * v_tax) / 100);
    } else {
        a_sub = '0,00';
        j_tot = j_sub + ((j_sub * v_tax) / 100);
    }

    $('.t_subtotal').val(mCurrency(j_sub));
    $('[name="t_disc"]').val(mCurrency(a_sub));
    $('[name="t_total"]').val(mCurrency(j_tot));
    $('[name="total"]').val(mCurrency(j_tot));
}

function mGetRowHitung(v_id) {
    var v_qty = v_price = v_disc = v_subtotal = 0;

    v_qty = parseInt(mNumeric($('.m_qty[data-id="' + v_id + '"]').val()));
    v_price = parseInt(mNumeric($('.m_price[data-id="' + v_id + '"]').val()));
    v_disc = parseInt(mNumeric($('.m_nom_disc[data-id="' + v_id + '"]').val()));
    v_mDisc = $('.m_disc[data-id="' + v_id + '"]').val();
    v_subtotal = (v_qty * v_price);

    if (v_mDisc == 0) {
        j_subtotal = mCurrency(v_subtotal - ((v_subtotal * v_disc) / 100));
    } else if (v_mDisc == 1) {
        j_subtotal = mCurrency(v_subtotal - v_disc);
    } else {
        j_subtotal = mCurrency(v_subtotal);
    }

    return j_subtotal;
}
function mRowHitung(v_id) {

    j_subtotal = mGetRowHitung(v_id);
    $('.m_subtotal[data-id="' + v_id + '"]').attr('value', j_subtotal);
    mHitung();
}

function updateSession(id) {
    qty         = $('.m_qty[data-id="' + id + '"]').val();
    n_disc      = $('.m_nom_disc[data-id="' + id + '"]').val();
    disc        = $('.m_disc[data-id="' + id + '"]').val();
    subtotal    = mGetRowHitung(id);
    $.ajax({
        url: '{{ URL::to('/') }}/purchase/invoice/update_row',
        dataType: 'json',
        type: 'POST',
        data: {id: id, qty: qty, n_disc: n_disc, disc: disc, subtotal: subtotal}
    });
}



jQuery(document).ready(function () {
    Metronic.init(); // init metronic core components
    Layout.init(); // init current layout
    construct();
    mHitung();
    cekSelect();


    $(".m_qty, .m_nom_disc, [name='t_tax'], [name='t_global_disc']").on("click", function () {
        $(this).select();
    });


    //select diskon
    $('.m_disc').change(function () {
        v_disc = $(this).val();
        d_disc = $(this).attr('data-id');
        if (v_disc === '') {
            $('.m_nom_disc[data-id="' + d_disc + '"]').attr('readonly', true);
            $('.m_nom_disc[data-id="' + d_disc + '"]').val('0,00');
        } else {
            $('.m_nom_disc[data-id="' + d_disc + '"]').attr('readonly', false);
        }
        updateSession(d_disc);
        mRowHitung(d_disc);
    });

    // select global diskon
    $('[name="t_disc_t"]').change(function () {
        v_disc_t = $(this).val();
        if (v_disc_t === '') {
            $('[name="t_global_disc"]').attr('readonly', true);
            $('[name="t_global_disc"]').val('0,00');
            
        } else {
            $('[name="t_global_disc"]').attr('readonly', false);
        }
        mHitung();
    });

    // update diskon dan qty
    $('.m_nom_disc, .m_qty').keyup(function () {
        id = $(this).attr('data-id');

        updateSession(id);
        mRowHitung(id);
    });

    $('[name="t_global_disc"], [name="t_tax"]').keyup(function () {
        mHitung();
    });

    $('.date-picker').datepicker({
        rtl: Metronic.isRTL(),
        orientation: "left",
        autoclose: true
    });

    $('.select2').select2();



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
                <a href="#">Purchase Invoice</a>
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
<form role="form" id="form-purpose" method="post" class="form-horizontal" action="{{ URL::to('/') }}/purchase/invoice/konfirmasi">
    <div class="row">
        <div class="col-md-12">
            <div class="portlet blue-hoki box">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-cog"></i> Invoice Detail
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="row">
                        <div class="col-md-6">
                            {{ Form::hidden('po_ref_no', $po_ref_no) }}
                            <div class="form-body">
                                <div class="form-group">
                                    <label class="col-md-3 control-label">PR No </label>
                                    <div class="col-md-5">
                                        {{ Form::text('trx_no', $trx_no, array('class' => 'form-control')) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Date </label>
                                    <div class="col-md-5">
                                        <div class="input-group date date-picker" id="div_tanggal" data-date="{{ $pi_tgl }}" data-date-format="{{ Config::get('format.date.default') }}">
                                            {{ Form::text('tanggal',$pi_tgl, array('id'=>'tanggal','class'=>'form-control','readonly'=>true)) }}
                                            <span class="input-group-btn">
                                                <button class="btn btn-sm default" type="button"><i class="fa fa-calendar"></i></button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Tax </label>
                                    <div class="col-md-5">
                                        <label class="control-label">
                                            {{ Form::checkbox(' TAX', 1, $tax) }} Tax
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Currency </label>
                                    <div class="col-md-5">
                                        {{ Form::select('currency', $currency,'',array('class' => 'form-control')) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Total </label>
                                    <div class="col-md-5">
                                        {{ Form::text('total', $total, array('class' => 'form-control currency', 'readonly')) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Notes </label>
                                    <div class="col-md-5">
                                        {{ Form::textarea('notes', $notes,array('class' => 'form-control','cols' => '40', 'rows' => '5')) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-body">
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Vendor </label>
                                    <div class="col-md-5">
                                        {{ Form::select('vendor', $vendor_l, $vendor, array('class' => 'select2 form-control')) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Term Payment </label>
                                    <div class="col-md-5">
                                        {{ Form::select('term', $term,'', array('class' => 'form-control')) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Due Date </label>
                                    <div class="col-md-5">
                                        <div class="input-group date date-picker" id="div_tanggal" data-date="{{ $pi_tgl }}" data-date-format="{{ Config::get('format.date.default') }}">
                                            {{ Form::text('due_date',$pi_tgl, array('id'=>'tanggal','class'=>'form-control','readonly'=>true)) }}
                                            <span class="input-group-btn">
                                                <button class="btn btn-sm default" type="button"><i class="fa fa-calendar"></i></button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Invoice Ref No </label>
                                    <div class="col-md-5">
                                        {{ Form::text('inv_ref_no', '', array('class' => 'form-control')) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">PO Ref No </label>
                                    <div class="col-md-5">
                                        <label class="control-label">{{ $po_ref_no }}</label>
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
                                                <th width="25%">Item Name</th>
                                                <th colspan="2" width="15%">Qty</th>
                                                <th width="20%">Price@</th>
                                                <th colspan="2" width="20%">Disc</th>
                                                <th width="15%">Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if (Session::has('purchase')) {
                                                $pre = Session::get('purchase');
                                                foreach ($pre as $p) {
                                                    ?>
                                                    <tr>
                                                        <td><a href="" class="btn btn-xs red tooltips do_delete" data-original-title="Hapus" data-id="<?php echo $p['d_id_item']; ?>"><i class="fa fa-trash-o"></i></a></td>
                                                        <td><?php echo $p['d_item_name'] ?></td>
                                                        <td>{{ Form::text('d_qty', $p['d_qty'], array('class' => 'form-control currency m_qty', 'data-id' => $p['d_id_detail'])) }}</td>
                                                        <td> <?php echo $p['d_item_unit'] ?></td>
                                                        <td>{{ Form::text('d_price', $p['d_price'], array('class' => 'form-control currency m_price', 'readonly', 'data-id' => $p['d_id_detail'])) }}</td>
                                                        <td>{{ Form::text('d_discount', $p['d_discount'], array('class' => 'form-control currency m_nom_disc m_sub_select', 'readonly', 'data-id' => $p['d_id_detail'])) }}</td>
                                                        <td>{{ Form::select('d_disc_t', $t_disc_t, $p['d_discount_type'], array('class' => 'm_disc m_select', 'data-id' => $p['d_id_detail'])) }}</td>
                                                        <td>{{ Form::text('d_subtotal', $p['d_subtotal'], array('class' => 'form-control currency m_subtotal', 'readonly',  'data-id' => $p['d_id_detail'])) }}</td>
                                                    </tr>
                                                    <?php
                                                }
                                            }
                                            ?>
                                            <tr class="item_row"></tr>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="4"></td>
                                                <td colspan="3" class="alignCenter"> Sub total</td>
                                                <td>{{ Form::text('t_subtotal', $subtotal, array('class' => 'form-control currency t_subtotal', 'readonly')) }}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="4"></td>
                                                <td class="alignCenter"> Global Disc</td>
                                                <td>{{ Form::text('t_global_disc', $discount, array('class' => 'form-control currency m_sub_select', 'readonly', 'data-id' => '-1')) }}</td>
                                                <td> {{ Form::select('t_disc_t', $t_disc_t, $t_disc, array('class' => 'm_select', 'data-id' => '-1')) }}</td>
                                                <td>{{ Form::text('t_disc', $total_discount, array('class' => 'form-control currency', 'readonly')) }}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="4"></td>
                                                <td colspan="3" class="alignCenter"> Tax</td>
                                                <td>{{ Form::text('t_tax', 0, array('class' => 'form-control currency')) }}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="4"></td>
                                                <td colspan="3" class="alignCenter"> Total</td>
                                                <td>{{ Form::text('t_total', $total, array('class' => 'form-control currency', 'readonly')) }}</td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions fluid">
                            <div class="col-md-offset-5 col-md-7">
                                <button type="submit" id="proses" data-sess="0" class="btn blue" disabled="<?php ?>true">Process</button>
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