@extends('layouts.admin.default')

@section('css')
@stop

@section('js')
<!-- BEGIN PAGE LEVEL PLUGINS -->
<!--<script type="text/javascript" src="{{ URL::asset('/assets/global/plugins/qz-print/js/deployJava.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('/assets/global/scripts/qz-print.js') }}"></script>-->
<!-- END PAGE LEVEL PLUGINS -->
<script>
    jQuery(document).ready(function() {
        Metronic.init(); // init metronic core components
        Layout.init(); // init current layout

        @if (Session::get('alert') == 'success')
            toastr['success']('{{ Session::get("alert_msg") }}');
        @endif

//        $(document).on('click', ".print", function() {
//            if (notReady()) { 
//                return;
//            }
//            qz.append("{{ $print }}");
//            qz.print();
//            toastr['success']('Print berhasil.');
//        });
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
                <a href="#">Purchase Order</a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <a href="#">{{{ $title or '' }}}</a>
            </li>
        </ul>
        <!-- END PAGE TITLE & BREADCRUMB-->
    </div>
</div>
<!-- END PAGE HEADER-->

<!-- BEGIN PAGE CONTENT-->
<form id="form" method="post" action="{{ URL::to('/') }}/purchase/order/new/save" class="form-horizontal">
    <div class="row">
        <div class="col-md-12">
            <div class="portlet yellow-crusta box">
                <div class="portlet-title">
                    <div class="caption"><i class="fa fa-clipboard"></i>PO Detail</div>
                    <div class="tools">
                        <a href="javascript:;" class="collapse">
                        </a>
                    </div>
                </div>
                <div class="portlet-body form">
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-3">PO No</label>
                                    <div class="col-md-3">
                                        <p class="form-control-static bold">
                                            : {{ 'PO-' . $po_no }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-3">Vendor</label>
                                    <div class="col-md-3">
                                        <p class="form-control-static bold">
                                            : {{ $vendorData['vendor']->vendor_name }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-3">PO Date</label>
                                    <div class="col-md-3">
                                        <p class="form-control-static bold">
                                            : {{ $po_date }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-3">Address</label>
                                    <div class="col-md-3">
                                        <p class="form-control-static bold">
                                            : {{ '' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-3">Tax</label>
                                    <div class="col-md-3">
                                        <p class="form-control-static bold">
                                            : {{ (isset($tax) && $tax) ? 'Yes' : 'No' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-3">No Ref</label>
                                    <div class="col-md-3">
                                        <p class="form-control-static bold">
                                            : {{ 'PR-' . $pr_no }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-3">Currency</label>
                                    <div class="col-md-3">
                                        <p class="form-control-static bold">
                                            : {{ $currency }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-3">Total</label>
                                    <div class="col-md-3">
                                        <p class="form-control-static bold">
                                            : {{ $total }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-3">Notes</label>
                                    <div class="col-md-3">
                                        <p class="form-control-static bold">
                                            : {{ !empty($notes) ? $notes : '-' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Items -->
    <div class="row">
        <div class="col-md-12">
            <div class="portlet box red">
                <div class="portlet-title">
                    <div class="caption"><i class="fa fa-file"></i>Item</div>
                    <div class="tools">
                        <a href="javascript:;" class="collapse">
                        </a>
                    </div>
                </div>
                <div class="portlet-body form">
                    <div class="form-body">
                        <!--/row-->
                        <div class="table-container">
                            <table class="table table-striped table-bordered table-hover" id="datatable">
                                <thead>
                                    <tr role="row" class="heading">
                                        <th width="5%">#</th>
                                        <th width="35%">Item Name</th>
                                        <th colspan="2" width="15%">Qty</th>
                                        <th width="15%">Price @</th>
                                        <th colspan="2" width="15%">Disc</th>
                                        <th width="20%">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $itemSubtotal = 0;
                                    foreach ($items as $key => $item):
                                        ?>
                                        <tr>
                                            <td class="alignCenter">{{ ++$key }}</td>
                                            <td class="alignLeft">{{ $item['item_name'] }}</td>
                                            <td class="alignRight">{{ $item['qty'] }}</td>
                                            <td class="alignLeft">{{ $item['item_unit'] }}</td>
                                            <td class="alignRight">{{ $item['price'] }}</td>
                                            <td class="alignCenter" colspan="2">{{ ($item['discount_type'] == 'N') ? 'Rp ' . $item['discount'] : $item['discount'] . ' ' .  $item['discount_type'] }}</td>
                                            <td class="alignRight">{{ $item['subtotal'] }}</td>
                                        </tr>
                                        <?php
                                        $itemSubtotal += $item['subtotal'];
                                    endforeach;
                                    ?>
                                </tbody>
                                <tfoot>
                                    <tr role="row">
                                        <th colspan="4" rowspan="4"></th>
                                        <th class="alignRight">Subtotal</th>
                                        <td colspan="3" class="alignRight">
                                            <p class="form-control-static bold">
                                                {{ number_format($itemSubtotal, 2, '.', ',') }}
                                            </p>
                                        </td>
                                    </tr>
                                    <tr role="row">
                                        <th class="alignRight">Global Diskon</th>
                                        <td colspan="2" class="alignCenter">
                                            <p class="form-control-static bold">
                                                {{ ($discount_type == 'N') ? 'Rp ' . $discount : $discount . ' ' .  $discount_type }}
                                            </p>
                                        </td>
                                        <td class="alignRight">
                                            <p class="form-control-static bold">
                                                {{ $total_discount }}
                                            </p>
                                        </td>
                                    </tr>
                                    <tr role="row">
                                        <th class="alignRight">Tax</th>
                                        <td colspan="3" class="alignRight">
                                            <p class="form-control-static bold">
                                                {{ '' }}
                                            </p>
                                        </td>
                                    </tr>
                                    <tr role="row">
                                        <th class="alignRight">Total</th>
                                        <td colspan="3" class="alignRight">
                                            <p class="form-control-static bold">
                                                {{ $total }}
                                            </p>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-actions fluid">
            <div class="col-md-offset-5 col-md-7">
                <a href="{{ URL::to('/') }}/purchase/order/" class="btn red">Back</a>
            </div>
        </div>
    </div>
</form>
<!-- END PAGE CONTENT-->
@stop