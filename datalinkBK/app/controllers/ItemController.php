<?php

class ItemController extends BaseController {

    protected $_defaultModel = 'ItemModel';
    protected $_pageTitle = 'Item';
    protected $_menuId = 'item';

    public function __construct() {
        
    }

    public function index() {
        $listCategory = GeneralModel::getSelectionList('m_item_category', 'id_category', 'category_name');
        $listBrand = GeneralModel::getSelectionList('m_item_brand', 'id_brand', 'brand_name');
        $listUnit = GeneralModel::getSelectionList('m_item_unit', 'id_unit', 'unit_name');


        $param = array(
            'title' => $this->_pageTitle,
            'title_desc' => 'Item',
            'menu_id' => $this->_menuId,
            'listCategory' => array('' => '-- Please Select --') + $listCategory,
            'listBrand' => array('' => '-- Please Select --') + $listBrand,
            'listUnit' => array('' => '-- Please Select --') + $listUnit,
        );



        return View::make('modules.item.index', $param);
    }

    public function getData() {
        if (Request::ajax()) {
            $privi['edit'] = hasPrivilege($this->_menuId, 'edit');
            $privi['delete'] = hasPrivilege($this->_menuId, 'delete');

            $param = Input::all();

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

                $row->item_name_link = '<a href="' . URL::to('/') . '/item/detail/' . encode($row->id) . '" class="show_detail">' . $r->item_name . '</a> ';

                $ubah = $hapus = '';
                if ($privi['edit'])
                    $ubah = '<a href="' . URL::to('/') . '/item/edit/' . encode($row->id) . '" class="btn btn-xs purple tooltips show_edit" data-original-title="Edit" ><i class="fa fa-pencil"></i></a> ';
                if ($privi['delete'])
                    $hapus = '<a href="javascript:;" class="btn btn-xs red tooltips do_delete" data-original-title="Hapus"><i class="fa fa-trash-o"></i></a> ';

                $row->action = '<div>' . $ubah . $hapus . '</div>';
                $row->no = $n;

                $data[$key] = $row;

                $n++;
            }

            $records["data"] = $get['data'];
            $records["draw"] = $draw;
            $records["recordsTotal"] = $get['count'];
            $records["recordsFiltered"] = $get['filter_count'];
            ;


            echo json_encode($records);
        }
    }

    public function getDataStock() {

        if (Request::ajax()) {
            $privi['edit'] = hasPrivilege($this->_menuId, 'edit');
            $privi['delete'] = hasPrivilege($this->_menuId, 'delete');

            $param = Input::all();

            $iDisplayLength = intval($param['length']);
            $limit = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength;
            $offset = intval($param['start']);
            $draw = intval($param['draw']);
            $sortBy = $param['order'][0]['column'];
            $sortDir = $param['order'][0]['dir'];
            $filter = isset($param['filter']) ? $param['filter'] : array();

            $model = new $this->_defaultModel;
            $get = $model->getDataStock($filter, $limit, $offset, $sortBy, $sortDir);

            $data = array();
            $n = $offset + 1;
            foreach ($get['data'] as $key => $r) {
                $row = $r;

                $row->id = $r->$get['id'];
                unset($row->$get['id']);


                $row->no = $n;
                $row->trx_date = displayDate($r->trx_date);
                $row->capital_price = displayNumeric($r->capital_price);
                $row->stock_in = displayNumeric($r->stock_in);
                $row->stock_out = displayNumeric($r->stock_out);
                $row->remaining_stock = displayNumeric($r->remaining_stock);
                $row->balance = displayNumeric($r->balance);

                $data[$key] = $row;

                $n++;
            }

            $records["data"] = $get['data'];
            $records["draw"] = $draw;
            $records["recordsTotal"] = $get['count'];
            $records["recordsFiltered"] = $get['filter_count'];
            ;


            echo json_encode($records);
        }
    }

    public function getDataStockPost($id_item) {

        $privi['edit'] = hasPrivilege($this->_menuId, 'edit');
        $privi['delete'] = hasPrivilege($this->_menuId, 'delete');

        $param = Input::all();

        $param['filter'] = array_merge($param['filter'], array('id_item' => $id_item));

        if (Input::has('export')) {
            $iDisplayLength = intval((isset($param['length'])) ? $param['length'] : $param['datatable_length']);
            $limit = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength;
            $offset = intval((isset($param['start'])) ? $param['start'] : 0);
            $draw = intval((isset($param['draw'])) ? $param['draw'] : 0);
            $sortBy = (isset($param['order'][0]['column'])) ? $param['order'][0]['column'] : 1;
            $sortDir = (isset($param['order'][0]['dir'])) ? $param['order'][0]['dir'] : "asc";
            $filter = isset($param['filter']) ? $param['filter'] : array();
        } else {
            $iDisplayLength = intval($param['length']);
            $limit = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength;
            $offset = intval($param['start']);
            $draw = intval($param['draw']);
            $sortBy = $param['order'][0]['column'];
            $sortDir = $param['order'][0]['dir'];
            $filter = isset($param['filter']) ? $param['filter'] : array();
        }

        $model = new $this->_defaultModel;
        $get = $model->getDataStock($filter, $limit, $offset, $sortBy, $sortDir);

        if (!Input::has('export')) {
            echo $this->parseDataStock($get, $offset, $draw);
        } else {
            $export = isset($param['export']) ? $param['export'] : null;
            return $this->parseDataStock($get, $offset, $draw, $export);
        }
    }

    public function getDataTambahan() {

        if (Request::ajax()) {
            $id_item = $_POST['id_item'];
            $id_warehouse = $_POST['id_warehouse'];
            $model = new $this->_defaultModel;

            $get = $model->getDataTambahan($id_item, $id_warehouse);
            $row = new stdClass();
            $row->stock_initial = $row->stock_in = $row->stock_out = $row->remaining_stock = $row->balance = 0;
            foreach ($get['tambahan'] as $r => $key) {

                $row->stock_initial += $key->stock_initial;
                $row->stock_in += $key->stock_in;
                $row->stock_out += $key->stock_out;
                $row->remaining_stock += ($key->stock_initial + $key->stock_in - $key->stock_out);
                $row->balance += (($key->stock_initial + $key->stock_in - $key->stock_out) * $key->cogs_price);
            }

            $row->stock_initial = displayNumeric($row->stock_initial);
            $row->stock_in = displayNumeric($row->stock_in);
            $row->stock_out = displayNumeric($row->stock_out);
            $row->remaining_stock = displayNumeric($row->remaining_stock);
            $row->balance = displayNumeric($row->balance);


            echo json_encode($row);
        }
    }

    public function getDataTambahanPost($id_item, $id_warehouse) {

        $id_item = $id_item;
        $id_warehouse = $id_warehouse;
        $model = new $this->_defaultModel;

        $get = $model->getDataTambahan($id_item, $id_warehouse);
        $row = new stdClass();
        $row->stock_initial = $row->stock_in = $row->stock_out = $row->remaining_stock = $row->balance = 0;
        if ($get != false) {
            foreach ($get['tambahan'] as $r => $key) {

                $row->stock_initial += $key->stock_initial;
                $row->stock_in += $key->stock_in;
                $row->stock_out += $key->stock_out;
                $row->remaining_stock += ($key->stock_initial + $key->stock_in - $key->stock_out);
                $row->balance += (($key->stock_initial + $key->stock_in - $key->stock_out) * $key->cogs_price);
            }

            $row->stock_initial = displayNumeric($row->stock_initial);
            $row->stock_in = displayNumeric($row->stock_in);
            $row->stock_out = displayNumeric($row->stock_out);
            $row->remaining_stock = displayNumeric($row->remaining_stock);
            $row->balance = displayNumeric($row->balance);
        }

        return json_encode($row);
    }

    public function add() {
        $listCategory = GeneralModel::getSelectionList('m_item_category', 'id_category', 'category_name');
        $listBrand = GeneralModel::getSelectionList('m_item_brand', 'id_brand', 'brand_name');
        $listUnit = GeneralModel::getSelectionList('m_item_unit', 'id_unit', 'unit_name');
        $listType = array("1" => "Main Material", "2" => "Support Material", "3" => "Packages", "4" => "Services", "5" => "Others");
        $listTax = array("PPN" => "PPN", "PPH 23" => "PPH 23");
        $listInventory = array("" => "");
        $listCOGS = array("" => "");
        $listSales = array("" => "");

        $param = array(
            'title' => $this->_pageTitle,
            'title_desc' => 'Client',
            'menu_id' => $this->_menuId,
            'act' => 'New',
            'listCategory' => array('' => '-- Please Select --') + $listCategory,
            'listBrand' => array('' => '-- Please Select --') + $listBrand,
            'listUnit' => array('' => '-- Please Select --') + $listUnit,
            'listType' => array('' => '-- Please Select --') + $listType,
            'listTax' => array('' => '-- Please Select --') + $listTax,
            'listInventory' => array('' => '-- Please Select --') + $listInventory,
            'listCOGS' => array('' => '-- Please Select --') + $listCOGS,
            'listSales' => array('' => '-- Please Select --') + $listSales,
        );
        return View::make('modules.item.add', $param);
    }

    public function doAdd() {
        $input = Input::all();

        $rules = $this->rules($input);
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            $errorArr = array();
            foreach ($rules as $key => $row) {
                $errorArr[$key] = $validator->messages()->first($key);
            }

            $ret = array(
                'status' => false,
                'alert_msg' => 'Error occured. Please check again.',
                'error_msg' => $errorArr
            );
        } else {
            $model = new $this->_defaultModel;
            $return = $model->insertData($input);

            if ($return === true) {
                $ret = array(
                    'status' => true,
                    'alert_msg' => 'Successfully added.',
                    'error_msg' => $return,
                );
            } else {
                $ret = array(
                    'status' => false,
                    'alert_msg' => 'Addition failed. Please contact your administrator.',
                    'error_msg' => $return
                );
            }
        }//end of validator


        $alertArr = array(
            'alert' => 'success',
            'alert_msg' => $ret['alert_msg'],
        );
        return Redirect::to('item')->with($alertArr);
    }

    public function edit($idItem) {
        $idItem = decode($idItem);

        $model = new $this->_defaultModel;
        $getDetail = $model->getDetail($idItem);

        $getDetail['item']->min_stock = displayNumeric($getDetail['item']->min_stock);
        $getDetail['item']->max_stock = displayNumeric($getDetail['item']->max_stock);


        if (!$getDetail) {
            $param = array(
                'title' => 'Page 404',
                'menu_id' => 'missing'
            );
            return Response::view('errors.missing_admin', $param, 404);
        }
        $listWarehouse = GeneralModel::getSelectionList('m_warehouse', 'id_warehouse', 'warehouse_name');
        $listCategory = GeneralModel::getSelectionList('m_item_category', 'id_category', 'category_name');
        $listBrand = GeneralModel::getSelectionList('m_item_brand', 'id_brand', 'brand_name');
        $listUnit = GeneralModel::getSelectionList('m_item_unit', 'id_unit', 'unit_name');
        $listType = array("1" => "Main Material", "2" => "Support Material", "3" => "Packages", "4" => "Services", "5" => "Others");
        $listTax = array("PPN" => "PPN", "PPH 23" => "PPH 23");
        $listInventory = array("" => "");
        $listCOGS = array("" => "");
        $listSales = array("" => "");
        $report = "Item Stock";


        $segment = Request::segment(2);

        $param = array(
            'title' => $this->_pageTitle,
            'title_desc' => '',
            'menu_id' => $this->_menuId,
            'act' => 'Edit',
            'listWarehouse' => array('' => '-- Please Select --') + $listWarehouse,
            'listCategory' => array('' => '-- Please Select --') + $listCategory,
            'listBrand' => array('' => '-- Please Select --') + $listBrand,
            'listUnit' => array('' => '-- Please Select --') + $listUnit,
            'listType' => array('' => '-- Please Select --') + $listType,
            'listTax' => array('' => '-- Please Select --') + $listTax,
            'listInventory' => array('' => '-- Please Select --') + $listInventory,
            'listCOGS' => array('' => '-- Please Select --') + $listCOGS,
            'listSales' => array('' => '-- Please Select --') + $listSales,
            'item' => $getDetail['item'],
            'segment' => $segment,
            'report' => $report,
        );


        return View::make('modules.item.edit', $param);
    }

    public function editPost() {
        $idItem = $_POST['id_item'];

        $model = new $this->_defaultModel;
        $getDetail = $model->getDetail($idItem);

        $getDetail['item']->min_stock = displayNumeric($getDetail['item']->min_stock);
        $getDetail['item']->max_stock = displayNumeric($getDetail['item']->max_stock);


        if (!$getDetail) {
            $param = array(
                'title' => 'Page 404',
                'menu_id' => 'missing'
            );
            return Response::view('errors.missing_admin', $param, 404);
        }
        $listWarehouse = GeneralModel::getSelectionList('m_warehouse', 'id_warehouse', 'warehouse_name');
        $listCategory = GeneralModel::getSelectionList('m_item_category', 'id_category', 'category_name');
        $listBrand = GeneralModel::getSelectionList('m_item_brand', 'id_brand', 'brand_name');
        $listUnit = GeneralModel::getSelectionList('m_item_unit', 'id_unit', 'unit_name');
        $listType = array("1" => "Main Material", "2" => "Support Material", "3" => "Packages", "4" => "Services", "5" => "Others");
        $listTax = array("PPN" => "PPN", "PPH 23" => "PPH 23");
        $listInventory = array("" => "");
        $listCOGS = array("" => "");
        $listSales = array("" => "");
        $report = "Item Stock";




        $tableHeader = array(
            array(
                'data' => 'no',
                'label' => '#',
                'width' => '5%',
            ),
            array(
                'data' => 'warehouse_name',
                'width' => '7%',
                'label' => 'Warehouse',
            ),
            array(
                'data' => 'trx_date',
                'width' => '7%',
                'label' => 'Date',
            ),
            array(
                'data' => 'trx_type',
                'width' => '10%',
                'label' => 'Transaction Type',
            ),
            array(
                'data' => 'trx_no',
                'label' => 'Transaction No',
                'width' => '10%',
            ),
            array(
                'data' => 'capital_price',
                'label' => 'Capital Price',
                'className' => 'alignRight',
            ),
            array(
                'data' => 'stock_in',
                'label' => 'Stock In',
                'className' => 'alignRight',
            ),
            array(
                'data' => 'stock_out',
                'label' => 'Stock Out',
                'className' => 'alignRight',
            ),
            array(
                'data' => 'remaining_stock',
                'label' => 'Remaining Stock',
                'className' => 'alignRight',
            ),
            array(
                'data' => 'balance',
                'label' => 'Balance',
                'className' => 'alignRight',
            ),
            array(
                'data' => '',
                'label' => 'Action',
                'className' => 'alignRight',
            ),);

        $param = array(
            'title' => $this->_pageTitle,
            'title_desc' => '',
            'menu_id' => $this->_menuId,
            'act' => 'Edit',
            'listWarehouse' => array('' => '-- Please Select --') + $listWarehouse,
            'listCategory' => array('' => '-- Please Select --') + $listCategory,
            'listBrand' => array('' => '-- Please Select --') + $listBrand,
            'listUnit' => array('' => '-- Please Select --') + $listUnit,
            'listType' => array('' => '-- Please Select --') + $listType,
            'listTax' => array('' => '-- Please Select --') + $listTax,
            'listInventory' => array('' => '-- Please Select --') + $listInventory,
            'listCOGS' => array('' => '-- Please Select --') + $listCOGS,
            'listSales' => array('' => '-- Please Select --') + $listSales,
            'item' => $getDetail['item'],
            'report' => $report,
            'tableHeader' => $tableHeader,
        );

        if (Request::isMethod('post')) {
            $allParams = Input::all();
            $export = isset($allParams['export']) ? $allParams['export'] : null;
            $exportHeader = simpleArray($tableHeader, 'label');
            array_pop($exportHeader);





            if ($export == 'xls' || $export == 'csv') {
                $data[] = $exportHeader;
                $data = array_merge($data, $this->getDataStockPost($idItem));

                Excel::create('Item' . $allParams['report'], function($excel) use($data) {
                    $excel->sheet('Sheetname', function($sheet) use($data) {
                        $sheet->fromArray($data);
                    });
                })->export($export);
            } else if ($export == 'pdf') {
                $data = $this->getDataStockPost($idItem);
                $exportHeader = simpleArray($tableHeader, 'label');
                array_pop($exportHeader);
                $paramPdf['tableHeader'] = $exportHeader;
                $paramPdf['data'] = $data;

                $destination = public_path() . '/';
                $fileName = 'Item' . $allParams['report'] . '.pdf';
                $pdf = App::make('dompdf');
                $pdf->loadView('pdf.datatable', $paramPdf)
                        ->save($destination . $fileName);
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename="' . $fileName . '"');
                header('Content-Transfer-Encoding: binary');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($destination . $fileName));

                readfile($destination . $fileName);
                @unlink($destination . $fileName);
            }
        }


        return View::make('modules.item.edit', $param);
    }

    public function doEdit() {
        $input = Input::all();

        $export = isset($input['export']) ? $input['export'] : null;

        if ($export != null) {

            $idItem = $_POST['id_item'];

            $model = new $this->_defaultModel;
            $getDetail = $model->getDetail($idItem);

            $getDetail['item']->min_stock = displayNumeric($getDetail['item']->min_stock);
            $getDetail['item']->max_stock = displayNumeric($getDetail['item']->max_stock);


            if (!$getDetail) {
                $param = array(
                    'title' => 'Page 404',
                    'menu_id' => 'missing'
                );
                return Response::view('errors.missing_admin', $param, 404);
            }
            $listWarehouse = GeneralModel::getSelectionList('m_warehouse', 'id_warehouse', 'warehouse_name');
            $listCategory = GeneralModel::getSelectionList('m_item_category', 'id_category', 'category_name');
            $listBrand = GeneralModel::getSelectionList('m_item_brand', 'id_brand', 'brand_name');
            $listUnit = GeneralModel::getSelectionList('m_item_unit', 'id_unit', 'unit_name');
            $listType = array("1" => "Main Material", "2" => "Support Material", "3" => "Packages", "4" => "Services", "5" => "Others");
            $listTax = array("PPN" => "PPN", "PPH 23" => "PPH 23");
            $listInventory = array("" => "");
            $listCOGS = array("" => "");
            $listSales = array("" => "");
            $report = "Item Stock";




            $tableHeader = array(
                array(
                    'data' => 'no',
                    'label' => '#',
                    'width' => '2%',
                ),
                array(
                    'data' => 'warehouse_name',
                    'width' => '7%',
                    'label' => 'Warehouse',
                ),
                array(
                    'data' => 'trx_date',
                    'width' => '7%',
                    'label' => 'Date',
                ),
                array(
                    'data' => 'trx_type',
                    'width' => '10%',
                    'label' => 'Transaction Type',
                ),
                array(
                    'data' => 'trx_no',
                    'label' => 'Transaction No',
                    'width' => '10%',
                ),
                array(
                    'data' => 'capital_price',
                    'label' => 'Capital Price',
                    'className' => 'alignRight',
                ),
                array(
                    'data' => 'stock_in',
                    'label' => 'Stock In',
                    'className' => 'alignRight',
                ),
                array(
                    'data' => 'stock_out',
                    'label' => 'Stock Out',
                    'className' => 'alignRight',
                ),
                array(
                    'data' => 'remaining_stock',
                    'label' => 'Remaining Stock',
                    'className' => 'alignRight',
                ),
                array(
                    'data' => 'balance',
                    'label' => 'Balance',
                    'className' => 'alignRight',
                ),
                array(
                    'data' => '',
                    'label' => 'Action',
                    'className' => 'alignRight',
                ),);

            $param = array(
                'title' => $this->_pageTitle,
                'title_desc' => '',
                'menu_id' => $this->_menuId,
                'act' => 'Edit',
                'listWarehouse' => array('' => '-- Please Select --') + $listWarehouse,
                'listCategory' => array('' => '-- Please Select --') + $listCategory,
                'listBrand' => array('' => '-- Please Select --') + $listBrand,
                'listUnit' => array('' => '-- Please Select --') + $listUnit,
                'listType' => array('' => '-- Please Select --') + $listType,
                'listTax' => array('' => '-- Please Select --') + $listTax,
                'listInventory' => array('' => '-- Please Select --') + $listInventory,
                'listCOGS' => array('' => '-- Please Select --') + $listCOGS,
                'listSales' => array('' => '-- Please Select --') + $listSales,
                'item' => $getDetail['item'],
                'report' => $report,
                'tableHeader' => $tableHeader,
            );

            if (Request::isMethod('post')) {
                $allParams = Input::all();
                $export = isset($allParams['export']) ? $allParams['export'] : null;
                $exportHeader = simpleArray($tableHeader, 'label');
                array_pop($exportHeader);

                //data atas
                $model_detail = new $this->_defaultModel;
                $data_detail = $model_detail->getDetail($idItem);


                $data_atas[] = array('Item Code', '', $data_detail['item']->item_code, '', '', '', '', '', '');
                $data_atas[] = array('Item Name', '', $data_detail['item']->item_name, '', '', '', '', '', '');


                if ($export == 'xls' || $export == 'csv') {
                    $data[] = $exportHeader;
                    $data = array_merge($data_atas, $data, $this->getDataStockPost($idItem));

                    Excel::create('Item' . $allParams['report'], function($excel) use($data) {
                        $excel->sheet('Sheetname', function($sheet) use($data) {
                            $sheet->fromArray($data);
                        });
                    })->export($export);
                } else if ($export == 'pdf') {
                    $data = $this->getDataStockPost($idItem);
                    $exportHeader = simpleArray($tableHeader, 'label');
                    array_pop($exportHeader);
                    $paramPdf['tableHeader'] = $exportHeader;
                    $paramPdf['data'] = $data;

                    $destination = public_path() . '/';
                    $fileName = 'Item' . $allParams['report'] . '.pdf';
                    $pdf = App::make('dompdf');
                    $pdf->loadView('pdf.datatable', $paramPdf)
                            ->save($destination . $fileName);
                    header('Content-Description: File Transfer');
                    header('Content-Type: application/octet-stream');
                    header('Content-Disposition: attachment; filename="' . $fileName . '"');
                    header('Content-Transfer-Encoding: binary');
                    header('Expires: 0');
                    header('Cache-Control: must-revalidate');
                    header('Pragma: public');
                    header('Content-Length: ' . filesize($destination . $fileName));

                    readfile($destination . $fileName);
                    @unlink($destination . $fileName);
                }
            }
        } else {
            $rules = $this->rules($input);
            $validator = Validator::make($input, $rules);
            if ($validator->fails()) {
                $errorArr = array();
                foreach ($rules as $key => $row) {
                    $errorArr[$key] = $validator->messages()->first($key);
                }

                $ret = array(
                    'status' => false,
                    'alert_msg' => 'Error occured. Please check again.',
                    'error_msg' => $errorArr
                );
            } else {
                $model = new $this->_defaultModel;
                $return = $model->updateData($input);

                if (is_integer($return) && $return != false) {
                    $ret = array(
                        'status' => true,
                        'alert_msg' => 'Successfully updated.',
                        'error_msg' => $return,
                    );
                } else {
                    $ret = array(
                        'status' => false,
                        'alert_msg' => 'Update failed. Please contact your administrator.',
                        'error_msg' => $return
                    );
                }
            }//end of validator


            $alertArr = array(
                'alert' => 'success',
                'alert_msg' => $ret['alert_msg'],
            );


            return Redirect::to('item')->with($alertArr);
        }
    }

    public function doAddUnit() {

        $input = Input::all();

        $rules = array('unit_name' => 'required');

        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            $errorArr = array();
            foreach ($rules as $key => $row) {
                $errorArr[$key] = $validator->messages()->first($key);
            }

            $ret = array(
                'status' => false,
                'alert_msg' => 'Error occured. Please check again..',
                'error_msg' => $errorArr
            );
        } else {
            $model = new $this->_defaultModel;
            $ins = $model->insertDataUnit($input);

            $alert = isset($input['from']) ? 'Item Unit' : 'Data';
            if ($ins) {
                $ret = array(
                    'status' => true,
                    'alert_msg' => $alert . ' has been successfully added.',
                    'error_msg' => array(),
                    'id' => $input['unit_name']
                );
            } else {
                $ret = array(
                    'status' => false,
                    'alert_msg' => 'Addition failed. Please contact your administrator.',
                    'error_msg' => array()
                );
            }
        }//end of validator

        echo json_encode($ret);
    }

    /*
     * do validate
     */

    public function doValidate() {
        $input = Input::all();
        $validate = true;

        $errorArr = array();
        $alertMsg = 'Error occured. Please check again.';

        //rules        
        $rules = $this->rules();


        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            foreach ($rules as $key => $row) {
                $errorArr[$key] = $validator->messages()->first($key);
            }
            $validate = false;
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

    public function rules() {
        $rules = array(
            'itemCode' => 'required',
            'itemName' => 'required',
            'itemType' => 'required',
            'itemUnit' => 'required',
            // 'itemBrand' => 'required',
            'itemCategory' => 'required',
                // 'tax'           => 'required',
        );



        return $rules;
    }

    public function doDelete() {
        $id = intval(Input::get('id'));
        $model = new $this->_defaultModel;

        $del = $model->deleteData($id);
        $ret = array(
            'status' => $del,
            'alert_msg' => $del ? 'Successfully deleted.' : 'Deletion failed. Please contact your administrator.',
            'error_msg' => array()
        );

        echo json_encode($ret);
    }

    function parseDataStock($content, $offset = 0, $draw = 0, $export = null) {
        $data = array();
        $n = $offset + 1;



        foreach ($content['data'] as $key => $r) {
            if (!$export) {
                $row = $r;

                $row->id = $r->$content['id'];
                unset($row->$content['id']);
                $row->action = '';
            } else {
                $row = (object) array();
            }
            $row->no = $n;
            $row->warehouse_name = $r->warehouse_name;
            $row->trx_date = displayDate($r->trx_date);
            $row->trx_type = $r->trx_type;
            $row->trx_no = $r->trx_no;
            $row->capital_price = displayNumeric($r->capital_price);
            $row->stock_in = displayNumeric($r->stock_in);
            $row->stock_out = displayNumeric($r->stock_out);
            $row->remaining_stock = displayNumeric($r->remaining_stock);
            $row->balance = displayNumeric($r->balance);

            if ($export) {
                $data[$key] = (array) $row;
            } else {
                $data[$key] = $row;
            }

            $n++;
        }



        if ($export) {
            $tambahan_json = $this->getDataTambahanPost($_POST['id_item'], $_POST['id_warehouse']);
            $tambahan = json_decode($tambahan_json);

            $data[] = array('Initial Stock', '', $tambahan->stock_initial, '', '', '', '', '', '');
            $data[] = array('Stock In', '', $tambahan->stock_in, '', '', '', '', '', '');
            $data[] = array('Stock Out', '', $tambahan->stock_out, '', '', '', '', '', '');
            $data[] = array('Remaining Stock', '', $tambahan->remaining_stock, '', '', '', '', '', '');
            $data[] = array('Balance', '', $tambahan->balance, '', '', '', '', '', '');
        }

        if ($export) {

            return (empty($data)) ? array() : $data;
        }

        $records["data"] = $content['data'];
        $records["export"] = $data;
        $records["draw"] = $draw;
        $records["recordsTotal"] = $content['count'];
        $records["recordsFiltered"] = $content['filter_count'];
        ;

        return json_encode($records);
    }
    
    public function getList() {
        $param = Input::all();
        $model = new $this->_defaultModel();
        return json_encode($model->getLike($param['term'], 5));
    }
    
    public function getRow() {
        $param = Input::all();
        $model = new $this->_defaultModel();
        return json_encode($model->getRow($param['id']));
    }

}
