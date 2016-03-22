<?php

class PurchaseOrderNewController extends BaseController {

    protected $_defaultModel = 'PurchaseOrderModel';
    protected $_pageTitle = 'New Purchase Order';
    protected $_menuId = 'purchase-order';

    public function index() {
        $pr_id = Input::get('purchase_request');
        $modelPR = new PurchaseRequestModel();

        $param = array(
            'title' => $this->_pageTitle,
            'title_desc' => 'create new purchase order',
            'menu_id' => $this->_menuId,
            'is_fullscreen' => false,
            'po_no' => 'PO-' . GeneralModel::getPONo(),
            'pr_id' => $pr_id,
            'pr_no' => 'PR-' . $modelPR->getPRNoByID($pr_id),
            'no_faktur' => $pr_id,
            'no_auto' => $pr_id,
            'listVendors' => array('' => '-- select --') + GeneralModel::getSelectionList('m_vendor', 'id_vendor', 'vendor_name'),
            'listCurrencies' => array('' => '-- select --') + GeneralModel::getSelectionList2('m_currency', 'ccy_code', 'ccy_code'),
        );

        return View::make('modules.purchase_order.new.index')->with($param);
    }

    public function doValidate() {
        $input = Input::all();
        $validate = true;

        $errorArr = array();
        $alertMsg = 'Terdapat error. Mohon dicek kembali.';

        $rules = array(
            'po_no' => 'required',
            'vendor' => 'required',
            'currency' => 'required',
            'address' => 'required',
        );

        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            foreach ($rules as $key => $row) {
                $errorArr[$key] = $validator->messages()->first($key);
            }
            $validate = false;
        }

        if (isset($input['items'])) {
            $i = 0;
            foreach ($input['items'] as $rowP) {
                $rowP['qty'] = defaultNumeric($rowP['qty']);
                $rulesP = array(
                    'qty' => 'required|numeric'
                );
                $validatorP = Validator::make($rowP, $rulesP);
                if ($validatorP->fails()) {
                    foreach ($rulesP as $key => $row) {
                        $errorArr['items'][$i][$key] = $validatorP->messages()->first($key);
                    }
                    $validate = false;
                }
                $i++;
            }
        } else {
            $validate = false;
            $alertMsg = 'Harap pilih item';
        }

        if (!$validate) {
            $ret = array(
                'status' => false,
                'alert_msg' => $alertMsg,
                'error_msg' => $errorArr
            );
        } else {
            $ret = array(
                'status' => true,
                'alert_msg' => '',
                'error_msg' => array()
            );
        } //end of validator

        echo json_encode($ret);
    }

    public function doProcess() {
        $input = Input::all();
        Session::put('purchaseOrder', $input);
        $title = $this->_pageTitle;
        $title_desc = 'create new purchase order';

        $param = array(
            'title' => $title . ' - Confirmation',
            'title_desc' => $title_desc,
            'menu_id' => $this->_menuId,
        );

        if ($input['action'] == 'process') {
            $modelVendor = new VendorModel();

            $param['vendorData'] = $modelVendor->getDetail($input['vendor']);
            $param['unitData'] = GeneralModel::getSelectionList2('m_item_unit', 'id_unit', 'unit_name');
            $param['pr_no'] = $input['pr_no'];
            $param = array_merge($param, $input);

            $this->layout = View::make('layouts.admin.default')->with($param);
            $this->layout->content = View::make('modules.purchase_order.new.confirm')->with($param);
            return $this->layout;
        } else {
            $this->doSave();
            $url = 'purchase/order/new';

            return Redirect::to($url);
        }
    }

    public function doSave() {
        $data = Session::get('purchaseOrder');
        $model = new $this->_defaultModel;
        $id = $model->insertData($data);
        $alertArr = array(
            'alert' => 'success',
            'alert_msg' => 'Purchase Order successfully added.',
        );
        
        return Redirect::to('purchase/order/detail/' . encode($id))->with($alertArr);
    }

}
