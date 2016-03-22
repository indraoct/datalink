<?php

class PurchaseInvoiceController extends BaseController {

    protected $_defaultModel = 'PurchaseInvoiceModel';
    protected $_pageTitle = 'Purchase Invoice';
    protected $_menuId = 'purchase_invoice';
    protected $_currency = array(1 => 'IDR', 2 => 'USD');
    protected $_vendor_l;
    protected $_term = array(1 => 'Cash', 2 => 'Kredit');
    protected $_tax = array(1 => TRUE, 0 => FALSE);
    protected $_disc = array(''=> '', 0 => '%', 1 => 'Nom');

    public function __construct() {
        $this->_vendor_l = GeneralModel::getSelection('m_vendor', 'id_vendor', 'vendor_name') + array('' => '-');
    }

    public function index() {
        Session::forget('purchase');

        $param = array(
            'title' => $this->_pageTitle,
            'menu_id' => $this->_menuId,
            'title_desc' => 'Purchase',
            'listPo' => GeneralModel::customGetSelectionList('t_purchase_order', 'id_po', 'po_no', 'PO-'),
            'new' => hasPrivilege($this->_menuId, 'new'),
        );
        return View::make('modules.purchase_invoice.index', $param);
    }

    public function getData() {
        if (Request::ajax()) {

            $privi['edit'] = hasPrivilege($this->_menuId, 'edit');
            $privi['delete'] = hasPrivilege($this->_menuId, 'delete');

            $param = Input::all();
            $ven_l = $this->_vendor_l;
            $status_l = array(0 => 'Unpaid', 1 => 'Paid', 2 => 'Partially Paid');

            $iDisplayLength = intval($param['length']);
            $limit = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength;
            $offset = intval($param['start']);
            $draw = intval($param['draw']);
            $sortBy = $param['order'][0]['column'];
            $sortDir = $param['order'][0]['dir'];
            $filter = isset($param['filter']) ? $param['filter'] : array();

            $model = new $this->_defaultModel;
            $get = $model->getData($filter, $limit, $offset, $sortBy, $sortDir);

            $data = array();
            $n = $offset + 1;
            foreach ($get['data'] as $key => $r) {
                $row = $r;

                $row->id = $r->$get['id'];
                unset($row->$get['id']);


                $row->no = $n;
                $row->inv_date = displayDate($r->pi_date);
                $row->due_date = displayDate($r->due_date);
                $row->pi_nums = "<a href='" . URL::to('/') . '/purchase/invoice/detail/' . $r->pi_no . "'>PI-$r->pi_no</a>";
                $row->vendor = $ven_l[$r->id_vendor];
                $row->total = displayNumeric($r->total);
                $row->status = $status_l[$r->status];

                $data[$key] = $row;
                $n++;
            }

            $records["data"] = $get['data'];
            $records["draw"] = $draw;
            $records["recordsTotal"] = $get['count'];
            $records["recordsFiltered"] = $get['filter_count'];


            echo json_encode($records);
        }
    }

    function add() {
        $po = Input::get('no_po');
        $t = GeneralModel::getTable('t_purchase_order', array('id_po' => "= $po"))->first();

        $param = array(
            'title'         => 'New ' . $this->_pageTitle,
            'menu_id'       => $this->_menuId,
            'new'           => hasPrivilege($this->_menuId, 'new'),
            'title_desc'    => 'Purchase',
            'listPo'        => GeneralModel::customGetSelectionList('t_purchase_order', 'id_po', 'po_no', 'PO-'),
            'trx_no'        => GeneralModel::getTrxNo('t_purchase_invoice', 'pi_no'),
            'pi_tgl'        => date("d-m-Y"),
            'currency'      => $this->_currency,
            'vendor_l'      => $this->_vendor_l,
            'vendor'        => $t->id_vendor,
            'term'          => $this->_term,
            'po_ref_no'     => $t->ref_no,
            't_disc_t'      => $this->_disc,
            't_disc'        => $t->discount_type,
            'total'         => displayNumeric($t->total),
            'subtotal'      => displayNumeric($t->subtotal),
            'discount'      => $t->discount,
            'total_discount' => $t->total_discount,
            'tax'           => $this->_tax[$t->tax],
            'notes'         => $t->notes
            
        );

        if (!Session::has('purchase')) {
            $table = GeneralModel::getTable('t_purchase_order_detail', array('id_po' => "= $po"))->get();
            foreach ($table as $pod) {
                $item_arr = array(
                    'd_id_detail' => $pod->id_detail,
                    'd_id_po' => $pod->id_po,
                    'd_id_detail_pr' => $pod->id_detail_pr,
                    'd_id_item' => $pod->id_item,
                    'd_item_name' => $pod->item_name,
                    'd_item_desc' => $pod->item_desc,
                    'd_qty' => $pod->qty,
                    'd_qty_received' => $pod->qty_received,
                    'd_item_unit' => $pod->item_unit,
                    'd_price' => $pod->price,
                    'd_discount' => $pod->discount,
                    'd_discount_type' => $pod->discount_type,
                    'd_subtotal' => $pod->subtotal
                );
                Session::push('purchase', $item_arr);
            }
        }

        return View::make('modules.purchase_invoice.add', $param);
    }

    public function getDataSelect() {

        $term = Input::get('term');

        $query = DB::table('m_item')
                ->where('item_code', 'LIKE', '%' . $term . '%')
                ->orWhere('item_name', 'LIKE', '%' . $term . '%')
                ->where('is_stockable', '1');

        $get['data'] = $query->orderBy('item_name')->take(100)->get();

        $data = array();

        foreach ($get['data'] as $key => $r) {
            $row = $r;
            $data[] = $row;
        }

        echo json_encode($data);
    }
    
    function updateRow(){
        $id = Input::get('id');
        $qty = defaultNumeric(Input::get('qty'));
        $n_disc = defaultNumeric(Input::get('n_disc'));
        $disc = Input::get('disc');
        $stotal = defaultNumeric(Input::get('subtotal'));
        
        $item_arr = array();
        $pre = Session::get('purchase');
        Session::forget('purchase');

        foreach ($pre as $pr) {
            if ($pr['d_id_detail'] == $id) {
                $item_arr = array(
                    'd_id_detail'   => $pr['d_id_detail'],
                    'd_id_po'       => $pr['d_id_po'],
                    'd_id_detail_pr' => $pr['d_id_detail_pr'],
                    'd_id_item'     => $pr['d_id_item'],
                    'd_item_name'   => $pr['d_item_name'],
                    'd_item_desc'   => $pr['d_item_desc'],
                    'd_qty'         => $qty,
                    'd_qty_received' => $pr['d_qty_received'],
                    'd_item_unit'   => $pr['d_item_unit'],
                    'd_price'       => $pr['d_price'],
                    'd_discount'    => $n_disc,
                    'd_discount_type' => $disc,
                    'd_subtotal'    => $stotal
                );
                Session::push('purchase', $item_arr);
            } else {
                $item_arr = array(
                    'd_id_detail'   => $pr['d_id_detail'],
                    'd_id_po'       => $pr['d_id_po'],
                    'd_id_detail_pr' => $pr['d_id_detail_pr'],
                    'd_id_item'     => $pr['d_id_item'],
                    'd_item_name'   => $pr['d_item_name'],
                    'd_item_desc'   => $pr['d_item_desc'],
                    'd_qty'         => $pr['d_qty'],
                    'd_qty_received' => $pr['d_qty_received'],
                    'd_item_unit'   => $pr['d_item_unit'],
                    'd_price'       => $pr['d_price'],
                    'd_discount'    => $pr['d_discount'],
                    'd_discount_type' => $pr['d_discount_type'],
                    'd_subtotal'    => $pr['d_subtotal']
                );
                Session::push('purchase', $item_arr);
            }
        }
    }

}
