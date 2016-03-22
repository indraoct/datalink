<?php

class ItemBrandModel{
    
        protected $_table = 'm_item_brand';
	protected $_id = 'id_brand';
        
        
        public function getData($filter, $limit, $offset, $sortBy, $sortDir)
	{
		$data['id'] = $this->_id;
	
		$tableColumn = array($this->_id, 'brand_name');
		
		$query = DB::table($this->_table)
						->select($tableColumn)
						->where('is_deleted',0)
						->where('owner', Crypt::decrypt(Session::get('owner')));
		
		$data['count'] = $data['filter_count'] = $query->count();
		
		if($filter)
		{
			if($filter['brand_name'])
				$query->where('brand_name', 'LIKE', '%'.$filter['brand_name'].'%');
				
			$data['filter_count'] = $query->count();
		}
		
		$data['data'] = $query->orderBy($tableColumn[$sortBy],$sortDir)->skip($offset)->take($limit)->get();
		
		return $data;
	}

	public function insertData($data)
	{
        $insertArr = array(

            'owner' => Crypt::decrypt(Session::get('owner')),
            'brand_name'	=> $data['brand_name'],
            'created_by' => Session::get('user_name'),
            'created_at'=> DB::raw('now()')

        );
       
        return DB::table($this->_table)->insertGetId($insertArr);
	}

	public function deleteData($id)
	{
        $updateArr = array(

            'is_deleted'	=> 1,
            'updated_by' => Session::get('user_name'),
            'updated_at'=> DB::raw('now()'),
            'deleted_at'=> DB::raw('now()')
        );
       
        return DB::table($this->_table)
							->where($this->_id,$id)
							->update($updateArr);
	}

	public function hasItem($id)
	{
		$data = DB::table('m_item')
						->select('id_brand')
						->where($this->_id,$id)
						->where('owner', Crypt::decrypt(Session::get('owner')))
						->where('is_deleted',0)
						->get();
       
        return $data;
	}
    
}