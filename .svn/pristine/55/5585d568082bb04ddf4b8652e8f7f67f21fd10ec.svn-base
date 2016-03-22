<?php

class PurchaseOrderController extends BaseController {

    protected $_defaultModel = 'PurchaseOrderModel';
    protected $_pageTitle = 'Purchase Order';
    protected $_menuId = 'purchase-order';

    public function __construct() {
        
    }

    public function index() {
        $getVendors = GeneralModel::getSelectionList('m_vendor', 'id_vendor', 'vendor_name');

        $param = array(
            'title' => $this->_pageTitle,
            'title_desc' => 'Purchase Order',
            'menu_id' => $this->_menuId,
            'list_status' => array('' => '-- All --') + Config::get('globalvar.invoice_status'),
            'listVendors' => array('' => '-- All --') + $getVendors,
            'listPurchases' => GeneralModel::customGetSelectionList('t_purchase_request', 'id_pr', 'pr_no', 'PR-'),
        );

        return View::make('modules.purchase_order.index')->with($param);
    }

    public function getData() {
        if (Request::ajax()) {
            $privi['edit'] = hasPrivilege($this->_menuId, 'edit');
            $privi['delete'] = hasPrivilege($this->_menuId, 'delete');

            $param = Input::all();

            $iDisplayLength = intval($param['length']);
            $limit = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength;
            $offset = intval($param['start']);
            $sortBy = $param['order'][0]['column'];
            $sortDir = $param['order'][0]['dir'];
            $filter = isset($param['filter']) ? $param['filter'] : array();

            $model = new $this->_defaultModel;
            $get = $model->getData($filter, $limit, $offset, $sortBy, $sortDir);
            
            foreach ($get['data'] as $key => $value) {
                $data = $value;
                $data->po_no = 'PR-' . $value->po_no;
                $data->no = ++$key;
                $data->action = null;
            }

            echo json_encode($get);
        }
    }

    public function showDetail($id) {
        $id = decode($id);

        $model = new $this->_defaultModel;
        $data = $model->getDetail($id);
        $param = array(
            'title' => 'Detail',
            'title_desc' => 'detail purchase order',
            'menu_id' => $this->_menuId,
        );

        if ($data) {
            $modelVendor = new VendorModel();
            $param['vendorData'] = $modelVendor->getDetail($data['id_vendor']);
            $param['unitData'] = GeneralModel::getSelectionList2('m_item_unit', 'id_unit', 'unit_name');
            $param['pr_no'] = $data['ref_no'];
            $param['print'] = '';
            $param = array_merge($param, (array)$data);

            return View::make('modules.purchase_order.detail')->with($param);
        } else {
            $param = array(
                'title' => 'Page 404',
                'menu_id' => 'missing'
            );
            return Response::view('errors.missing_admin', $param, 404);
        }
    }

}
