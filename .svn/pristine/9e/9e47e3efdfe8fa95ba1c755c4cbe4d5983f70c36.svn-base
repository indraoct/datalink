<?php

class StockOpnameController extends BaseController{
    
    protected $_defaultModel = 'StockOpnameModel';
    protected $_pageTitle = 'Stock Opname';
    protected $_menuId = 'stock_opname';
    
 
    public function __construct() {
        
    }
    
    public function index(){
        $tableHeader = array(
                                    array(
                                                             'data' => 'no',
                                                             'label' => '#',
                                                             'width' => '5%',
                                                     ),
                                     array(
                                                             'data' => 'trx_no',
                                                             'width' => '12%',
                                                             'label' => 'Trx No &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',
                                                     ),
                                     array(
                                                             'data' => 'trx_date',
                                                             'label' => 'Date',
                                                     ),
                                     array(
                                                             'data' => 'note',
                                                             'width' => '20%',
                                                             'label' => 'Note &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',
                                                     ),
                                    
                                     array(
                                                             'data' => 'action',
                                                             'label' => 'Action &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',
                                                             'width' => '20%',
                                                             'bSortable' => 'false',
                                                             'className' => 'alignCenter',
                                                     ));
        
                                $param = array(
                                    'title'             => $this->_pageTitle,
                                    'title_desc'	=> 'Stock Opname',
                                    'menu_id'		=> $this->_menuId,
                                    'tableHeader' => $tableHeader,
                                );
        
        return View::make('modules.stock_opname.index',$param);
    }
    
     public function showDetail($id_trx)
	{
        $id_trx = decode($id_trx);
        
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
		
		$model = new $this->_defaultModel;
		$data = $model->getDetailOpname($id_trx);
		
		if($data)
		{		
			
			$data['print'] = "";
		
			$param = array(
								'title'					=> 'Stock Opname Detail',
								'title_desc'			=> '',
								'menu_id'				=> $this->_menuId,
                                                                'tableHeader' => $tableHeader,
							);
			
			$param = array_merge($param,$data);	
			
			return View::make('modules.stock_opname.detail')->with($param);
		}
		else
		{
			$param = array(
								'title'			=> 'Page 404',
								'menu_id'		=> 'missing'
							);
			return Response::view('errors.missing_admin', $param, 404);
		}
	}
    
    public function getData(){
        
        
			$privi['edit'] = hasPrivilege($this->_menuId,'edit');
			$privi['delete'] = hasPrivilege($this->_menuId,'delete');
		
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

                        echo $this->parseData($get,$offset,$draw);
        
    }
    
    public function getDataSelect()
	{
		if(Request::ajax()){
		
			$term = Input::get('term');
			$item_code = Input::get('item_code');
			
                        
			$model = new $this->_defaultModel;
			$get = $model->getDataSelect($term,$item_code);
			
			$data = array();
			
			foreach($get['data'] as $key=>$r)
			{
				$row = $r;
				$row->id   = $r->id;
                                $row->id_item = $r->id;
				$row->item_code = $r->item_code;
				$row->item_name = $r->item_name;
                                $row->id_unit = $r->id_unit;
				
				$data[] = $row;
			}
			
			echo json_encode($data);
		
		}
	}
        
    public function getWarehouseSelect(){
        if(Request::ajax()){
		
			$term = Input::get('term');
			$id_warehouse = Input::get('id_warehouse');
			
                        
			$model = new $this->_defaultModel;
			$get = $model->getWarehouseSelect($term,$id_warehouse);
			
			$data = array();
			
			foreach($get['data'] as $key=>$r)
			{
				$row = $r;
				$row->id   = $r->id;
                                $row->id_warehouse = $r->id_warehouse;
				$row->warehouse_name = $r->warehouse_name;
				
				$data[] = $row;
			}
			echo json_encode($data[0]);
		
		}
        
        
    }
    
    public function getQtyValue(){
         if(Request::ajax()){
             
             $param = Input::all();
             
             $model = new $this->_defaultModel;
             
             $get = $model->getQty($param['id_warehouse'],$param['id_item']);
            $row = new stdClass();
                        
            $row->qty = 0;
            if($get != false){
                foreach ($get['data'] as $r=>$key){

                    $row->qty += $key->qty;

                }
                $row->qty = displayNumeric($row->qty);

            }   

            echo json_encode($row);
             
             
             
         }
       
    }
    
    public function getRow(){
                       if(Request::ajax()){
		
			$id = intval(Input::get('id'));
			
			$model = new $this->_defaultModel;
			$data = $model->getRow($id);
			
			echo json_encode($data);
		
		}
        
        
        
    }
    
    public function parseData($content,$offset = 0,$draw = 0,$export = null)
	{
           $privi['edit'] = hasPrivilege($this->_menuId,'edit');
	   $privi['delete'] = hasPrivilege($this->_menuId,'delete');
		$data = array();
		$n = $offset + 1;

	
			foreach($content['data'] as $key=>$r)
			{
				
                                $row = $r;
                                $row->id = $r->id;
                                
                                $ubah = $hapus = '';
				if($privi['edit']) 
					$ubah = '<a href="'.URL::to('/').'/inventory/opname/detail/'.encode($row->id).'" class="btn btn-xs purple tooltips" data-original-title="Edit" ><i class="fa fa-pencil"></i></a> ';
				if($privi['delete'])
					$hapus = '<a href="javascript:;" class="btn btn-xs red tooltips do_delete" data-original-title="Hapus"><i class="fa fa-trash-o"></i></a> ';
				
                                
                                $row->action = $ubah." ".$hapus;

				$row->no = $n;
                                $row->trx_no = '<a href="'.URL::to('/').'/inventory/opname/detail/'.encode($row->id).'" class="show_detail">STO-'.$r->trx_no.'</a> ';
                                $row->trx_date = displayDate($r->trx_date);
                                $row->note = $r->note;

				
				$data[$key] = $row;
                                        

				$n++;
			}
			
		
		$records["data"] = $content['data'];
		$records["export"] 	= 	$data;
		$records["draw"] = $draw;
		$records["recordsTotal"] = $content['count'];
		$records["recordsFiltered"] = $content['filter_count'];;
		
		return json_encode($records);
	}
        
        
        public function parseDataRow($content,$offset = 0,$draw = 0,$export = null)
	{
		$data = array();
		$n = $offset + 1;

	
			foreach($content['data'] as $key=>$r)
			{
				
                                $row = $r;

                                //$row->id = $r->$content['id'];
                                //unset($row->$content['id']);
                                $row->action = '';

				$row->no = $n;
                                $row->id_item = $r->id_item;
                                $row->item_name = $r->item_name;
                                $row->item_code = $r->item_code;

				$data[$key] = $row;
                                        

				$n++;
			}
			
		
		$records["data"] = $content['data'];
		$records["export"] 	= 	$data;
		$records["draw"] = $draw;
		$records["recordsTotal"] = $content['count'];
		$records["recordsFiltered"] = $content['filter_count'];;
		
		return json_encode($records);
	}
    
}