<?php

class StockOpnameNewController extends BaseController{
 
    protected $_defaultModel = 'StockOpnameModel';
    protected $_warehouseModel = 'WarehouseModel';
    protected $_pageTitle = 'Stock Opname';
    protected $_menuId = 'stock_opname';
    
	public function __construct()
	{
        
        }
        
        public function index(){
            $listWarehouse 	= GeneralModel::getSelectionList('m_warehouse','id_warehouse','warehouse_name');
            $countdetail = 0;
            $tableHeader = array(
                                    array(
                                                             'data' => 'no',
                                                             'label' => '#',
                                                             'width' => '5%',
                                                     ),
                                    array(
                                                             'data' => 'item_code',
                                                             'width' => '15%',
                                                             'label' => 'Item Code',
                                                     ),
                                     array(
                                                             'data' => 'item_name',
                                                             'width' => '20%',
                                                             'label' => 'Item Name ',
                                                     ),
                                     
                                     array(
                                                             'data' => 'qty',
                                                             'width' => '20%',
                                                             'label' => 'Qty',
                                                     ),
                                     array(
                                                             'data' => 'warehouse_name',
                                                             'width' => '20%',
                                                             'label' => 'Warehouse',
                                                     ),);
            
            
            
            $data = Session::get('opname');
            if(Session::get('action')=='back')
		{
                        $countdetail = count($data['item']); 
			Session::forget('action');
                        $data['item'] = $data['item'];
		}
		Session::forget('opname');
        
            
            $param = array('title'      => "New",
                           'title_desc'	=> 'Stock Opname',
                           'menu_id'	=> $this->_menuId,
                           'is_fullscreen' => false,
                           'tableHeader'=> $tableHeader,
                           'listWarehouse'	=> $listWarehouse,
                           'data'	=> $data,
                           'countdetail' => $countdetail,
                );
            
            
            
            return View::make('modules.stock_opname.new.index',$param);
        }
        
        
        public function doValidate()
	{
                $input = Input::all();
                        $validate = true;

                        $errorArr = array();
                        $alertMsg = 'Terdapat error. Mohon dicek kembali.';

                $rules = array(
                                                        'trx_date' 		=> 'required',
                                                        'ref_no' 		=> 'required',
                                                  );

                $validator = Validator::make($input, $rules);
                        if($validator->fails())
                        {
                                foreach($rules as $key=>$row)
                                {
                                        $errorArr[$key] = $validator->messages()->first($key);
                                }
                                $validate = false;
                        }
                        
                        if(isset($input['item']))
                        {
                                $i=0;
                                foreach($input['item'] as $rowP)
                                {
                                        $rowP['qty'] = defaultNumeric($rowP['qty']);
                                        $rulesP = array(
                                                                                'qty' 		=> 'required|numeric',
                                                                                'id_warehouse' 		=> 'required'
                                                                        );
                                        $validatorP = Validator::make($rowP, $rulesP);
                                        if($validatorP->fails())
                                        {
                                                foreach($rulesP as $key=>$row)
                                                {
                                                        $errorArr['item'][$i][$key] = $validatorP->messages()->first($key);
                                                }
                                                $validate = false;
                                        }
                                        $i++;
                                }
                        }else{
                                $validate = false;
                                $alertMsg = 'Harap pilih item';
                        }

                if(!$validate)
                        {    
                                $ret = array(
                                                        'status'		=> false,
                                                        'alert_msg'	=> $alertMsg,
                                                        'error_msg'	=> $errorArr
                                                );
                }    
                else
                        {                
                                $ret = array(
                                                        'status'		=> true,
                                                        'alert_msg'	=> '',
                                                        'error_msg'	=> array()
                                                );
                } //end of validator

                        echo json_encode($ret);
    }
    
    /*
     * DO Process
     */
    
    public function doProcess()
	{
        
        
        if(Request::isMethod('post')){
			$input = Input::all();
	}else{
                $input = Session::get('opname');
        }
		
		$title = $this->_pageTitle;
		$title_desc = 'making new stock opname.';
                
                
                $tableHeader = array(
                                    array(
                                                             'data' => 'no',
                                                             'label' => '#',
                                                             'width' => '5%',
                                                     ),
                                    array(
                                                             'data' => 'item_code',
                                                             'width' => '15%',
                                                             'label' => 'Item Code',
                                                     ),
                                     array(
                                                             'data' => 'item_name',
                                                             'width' => '20%',
                                                             'label' => 'Item Name ',
                                                     ),
                                     
                                     array(
                                                             'data' => 'qty',
                                                             'width' => '20%',
                                                             'label' => 'Qty',
                                                     ),
                                     array(
                                                             'data' => 'warehouse_name',
                                                             'width' => '20%',
                                                             'label' => 'Warehouse',
                                                     ),);
        
		
		$param = array(
							'title'				=> 'New - Konfirmasi',
							'title_desc'		=> $title_desc,
							'menu_id'			=> $this->_menuId,
                                                        'tableHeader'    => $tableHeader,
						);
                Session::put('opname',$input);
                /*
                 * setting urutan data supaya urut 0..1..2..dst
                 */
                $arr_item_new =  array_values($input['item']);
                unset($input['item']);
                
                $input['item'] = $arr_item_new;
	
		
                
                
		if($input['action']=='process')
		{
                       $i=0;
			$WarehouseModel = new $this->_defaultModel;//new $this->_warehouseModel;
                        
                        
			foreach($input['item'] as $key=>$val){
                            
                            
                                    $wh = $WarehouseModel->getDetailWarehouse($val['id_warehouse']);

                                    $warehouse_name = $wh->warehouse_name;
                                    $input['item'][$i]['warehouse_name'] = $warehouse_name; 
                            
                            
                            $i++;
                        }
                        
//                echo "<pre>";
//		var_dump($input);
//                echo "<pre>"; die();
                        
                        Session::put('opname',$input);
			
			$param = array_merge($param,$input);
			
			$this->layout = View::make('layouts.admin.default')->with($param);
			if($input['is_fullscreen'])
				$this->layout = View::make('layouts.admin.popup')->with($param);
			$this->layout->content = View::make('modules.stock_opname.new.confirm')->with($param);
			return $this->layout;
		}
		else
		{
			$this->doSave();
			
			if($input['is_fullscreen'])
				$url = 'sales/invoice/new/fullscreen';
			else
				$url = 'sales/invoice/new';
			
			$alertArr = array(
								'alert' 			=> 'success',
								'alert_msg' 	=> 'Transaksi berhasil disimpan sebagai Draft.',
							);
			return Redirect::to($url)->with($alertArr);
		}
	}
        
        
     public function doSave()
	{
         
		$data = Session::get('opname');
		$data['action'] = Input::get('action') ? Input::get('action') : $data['action'];

        if($data['action']=='process'){
            
            
			$model = new $this->_defaultModel;
			$id_trx = $model->insertDataOpname($data);
			
			Session::forget('opname');
			Session::forget('action');
			
			$alertArr = array(
								'alert' 			=> 'success',
								'alert_msg' 	=> 'Transaksi berhasil diproses.',
							);
			return Redirect::to('inventory/opname/detail/'.encode($id_trx))->with($alertArr);
		}
		else
			return Redirect::to('inventory/opname/new')->with('action', 'back');
    }   
    
}
?>