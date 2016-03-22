<?php

class StockOpnameModel{
    
    protected $_table = 't_stock_opname';
    protected $_id = 'id_trx';
    
    protected $_table_detail = 't_stock_opname_detail';
    protected $_id_detail = 'id_detail';
    
    protected $_table_stock = 't_item_stock';
    protected $_id_stock = 'id_stock';
    
    protected $_table_item = 'm_item';
    protected $_id_item  = 'id_item';
    
    protected $_table_warehouse = 'm_warehouse';
    protected $_id_warehouse = 'id_warehouse';




    public function getData($filter, $limit, $offset, $sortBy, $sortDir){
        
        $tableColumn = array($this->_table.'.'.$this->_id, 'trx_no','trx_date','note');
                     $query = DB::table($this->_table)
                                                        ->select(array($this->_table.'.'.$this->_id." as id", 'trx_no','trx_date','note'))
                                                        ->where($this->_table.'.is_deleted',0)
                                                        ->where($this->_table.'.owner', Crypt::decrypt(Session::get('owner')));
                    
                
                       

		$data['count'] = $data['filter_count'] = $query->count();
		
		if($filter)
		{
			if($filter['trx_no'] != "")
				$query->where('trx_no', 'LIKE', '%'.$filter['trx_no'].'%');
			if($filter['date_from'] != "")
                                $query->where('trx_date', '>=',  defaultDate($filter['date_from']));
                        if($filter['date_to'] != "")
                                $query->where('trx_date', '<=',defaultDate($filter['date_to']));
                        
			$data['filter_count'] = $query->count();
		}
		
		$data['data'] = $query->orderBy($tableColumn[$sortBy],$sortDir)->skip($offset)->take($limit)->get();
		
		return $data;
        
        
     }
     
     public function getDetailOpname($id_trx)
	{		
		$data = DB::table($this->_table)
                                                        ->select(array($this->_table.'.'.$this->_id." as id", 'trx_no','no_ref','trx_date','note'))
                                                        ->where($this->_table.'.is_deleted',0)
                                                        ->where($this->_table.'.id_trx',$id_trx)
                                                        ->where($this->_table.'.owner', Crypt::decrypt(Session::get('owner')))
								->first();
		
		if($data)
			$data->item = DB::table($this->_table_detail." as a")
                                                        ->select(array('b.item_code as item_code','b.item_name as item_name','a.qty as qty','c.warehouse_name as warehouse_name'))
                                                        ->leftJoin($this->_table_item." as b",'a.id_item','=','b.id_item')
                                                        ->leftJoin('m_warehouse as c','a.id_warehouse','=','c.id_warehouse')
                                                        ->where('a.id_trx',$id_trx)
                                                        ->get();
		
		return toArray($data);
	}
     
     
     public function getDataSelect($term,$item_code)
	{		
		$query = DB::table($this->_table_item)
						->select(array($this->_id_item.' as id','item_code','item_name','id_unit'))
						->where('is_deleted',0)
                                                ->where('is_stockable',1)
						->where('owner', Crypt::decrypt(Session::get('owner')))
                                                ->where('item_name', 'LIKE', '%'.$term.'%');
						//->where('item_code',$item_code);
		
		$data['data'] = $query->orderBy('id_item')->take(100)->get();
		
		return $data;
	}
        
     
     public function getWarehouseSelect($term,$id_warehouse)
	{		
		$query = DB::table($this->_table_warehouse)
						->select(array($this->_id_warehouse.' as id','id_warehouse','warehouse_name'))
						->where('is_deleted',0)
						->where('owner', Crypt::decrypt(Session::get('owner')))
                                                ->where('warehouse_name', 'LIKE', '%'.$term.'%');
		
		$data['data'] = $query->orderBy('id_warehouse')->take(100)->get();
		
		return $data;
	}
        
      public function getQty($id_warehouse,$id_item){
          
          $query = DB::table($this->_table_stock." as a")
                                                               ->select(array($this->_id_stock, 
                                                                              DB::raw('SUM(a.stock_initial + a.stock_in - a.stock_out) AS qty'),))
                                                               ->leftJoin('m_item as b', 'a.id_item', '=', 'b.id_item')
                                                               ->leftJoin('m_warehouse as c', 'a.id_warehouse', '=', 'c.id_warehouse')
                                                               ->where('a.id_item',$id_item)
                                                               ->where('a.id_warehouse',$id_warehouse)
                                                               ->where('a.is_deleted',0)
                                                               ->where('a.owner', Crypt::decrypt(Session::get('owner')))
                                                               ->groupBy('a.id_item');
          $data['data'] = $query->get();
          
          return $data;
      }  
     
     public function getRow($id){
        
                     
                     
                 $query = DB::table($this->_table_item." as a")
                                                        ->select(array('a.'.$this->_id_item." as id",'a.item_name','a.item_code','a.id_item','a.id_unit'))
                                                        ->where('a.is_deleted',0)
                                                        ->where('a.is_stockable',1)
                                                        ->where('a.id_item',$id)
                                                        ->where('a.owner', Crypt::decrypt(Session::get('owner')))
                                                        ->groupBy('a.id_item');
                    
		
		return $query->first();
        
        
     }
     
     public function checkMinusPlus($id_item,$id_warehouse,$qty){
         
                        $query = DB::table($this->_table_stock)
                                                        ->select(array($this->_id_stock." as id",
                                                         DB::raw('SUM(stock_initial + stock_in - stock_out) AS qty')))
                                                        ->where('id_warehouse',$id_warehouse)
                                                        ->where('is_deleted',0)
                                                        ->where('id_item',$id_item)
                                                        ->where('owner', Crypt::decrypt(Session::get('owner')))
                                                        ->groupBy('id_item');
                    
		
		$data = $query->first();
                
                if(!isset($data->qty)){
                    $data = new stdClass();
                    $data->qty = 0;
                }
                
                if($data->qty > $qty){
                   $selisih = $data->qty - $qty; 
                 
                    return array("isi"=>"plus","stock_out"=>$selisih,"stock_in"=>0);
                }else{
                   $selisih = $qty - $data->qty;
                  
                   return array("isi"=>"plus","stock_in"=>$selisih,"stock_out"=>0);
                }
         
         
     }
     
     /*
      * Insert Data Opname
      */
     public function insertDataOpname($data)
	{
         
         try
            {
                DB::beginTransaction();
		$owner = Crypt::decrypt(Session::get('owner'));
		
		$trx_no = GeneralModel::getTrxNo('t_stock_opname', 'trx_no'); 	
		$trx_date = defaultDate($data['trx_date']);
                $no_ref  = $data['ref_no'];
                $note    = $data['note'];
		
	
        $insertTrx = array(

            'owner' 				=> $owner,
            'trx_no'				=> $trx_no,
            'trx_date'				=> $trx_date,
            'no_ref'				=> $no_ref,
            'note'				=> $note,
            'created_by' 			=> Session::get('user_name'),
            'created_at'			=> DB::raw('now()')

        );
       
        $id_trx = DB::table('t_stock_opname')->insertGetId($insertTrx);
	
            $insertTrxDetail = $paramStokDetail = array();
            foreach($data['item'] as $p)
            {
                    
                    $qty = defaultNumeric($p['qty']);
                    $flag = $this->checkMinusPlus($p['id_item'], $p['id_warehouse'], $p['qty']);

                    $insertTrxDetail[] = array(

                            'id_trx' => $id_trx,
                            'id_warehouse' => $p['id_warehouse'],
                            'id_item'     => $p['id_item'],
                            'item_code'   => $p['item_code'],
                            'item_name'   => $p['item_name'],
                            'qty'         => $qty,
                            'unit'        => $p['id_unit'],
                            'flag'        => $flag['isi'],
                    );
                    
                    $paramStokDetail[] = array(

                            'id_warehouse' => $p['id_warehouse'],
                            'id_item'     => $p['id_item'],
                            'stock_in'    => $flag['stock_in'],
                            'stock_out'    => $flag['stock_out'],
                    );


            }

            DB::table('t_stock_opname_detail')->insert($insertTrxDetail);
            
            $Stock = new Stock(Crypt::decrypt(Session::get('owner')));  
	
			 $paramStok = array(
				'owner' 		=> $owner,
				'trx_code'	=> 'STO',
				'trx_date'		=> $trx_date,
				'id_ref'			=> $id_trx,
				'no_ref'		=> $trx_no,
				'notes' 		=> 'Stock Opname',
				'created_at'	=> DB::raw('now()')
			);
			
            $Stock->createTrx($paramStok, $paramStokDetail);
            
            DB::commit();
            
            }catch(Exception $e){
                
                    DB::rollback();
                    return $e->getMessage();
                    
            }
            
	  return $id_trx;
	}
        
        
        public function getDetailWarehouse($id)
	{
          
		$query = DB::table($this->_table_warehouse)
						->select('*')
						->where('is_deleted',0)
						->where('id_warehouse',$id)
						->where('owner', Crypt::decrypt(Session::get('owner')));
		
               
		$data = $query->first();
		return $data;
	}

      
     
     
}