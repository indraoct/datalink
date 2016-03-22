<?php

class ProjectModel {

	protected $_table = 'm_project';
	protected $_id = 'id_project';

	public function getData($filter, $limit=null, $offset=null, $sortBy=null, $sortDir=null)
	{
		$data['id'] = $this->_id;

		$tableColumn = array($this->_id, 'a.project_code','a.project_name', 'b.customer_name', 'a.status', 'c.emp_firstname','d.category_name');

		$caseStatus = 'CASE a.status ';
		foreach(Config::get('globalvar.project_status') as $key=>$val)
			$caseStatus .= ' WHEN '.$key.' THEN "'.$val.'"';
		$caseStatus .= ' END AS status_desc';
		
		$others = array();
		if(isset($filter['id']))
		{
			$others = array(8=>DB::raw('TRUNCATE(CASE total_budget WHEN 0 THEN 0 ELSE total_cost/total_budget*100 END,2) as percentage') );
		}
		
		$query = DB::table($this->_table.' AS a')
						->select($tableColumn+array(7=>DB::raw($caseStatus))+$others )
						->leftJoin('m_customer AS b', 'a.id_customer', '=', 'b.id_customer')
						->leftJoin('m_project_category AS d', 'a.id_category', '=', 'd.id_category')
						->leftJoin('hs_hr_employee AS c', 'a.id_sales_person', '=', 'c.emp_number')
						->where('a.is_deleted',0)
						->where('a.owner', Crypt::decrypt(Session::get('owner')));
		
		$data['count'] = $data['filter_count'] = $query->count();
		
		if($filter)
		{
			if(isset($filter['id']))
				$query->where('a.id_project', 'LIKE', '%'.$filter['id'].'%');
			if(isset($filter['id_project']))
				$query->where('a.project_code', 'LIKE', '%'.$filter['id_project'].'%');
			if(isset($filter['project_name']))
				$query->where('a.project_name', 'LIKE', '%'.$filter['project_name'].'%');
			if(isset($filter['custId']))
				$query->where('b.id_customer', 'LIKE', '%'.$filter['custId'].'%');
			if(isset($filter['status']))
				$query->where('a.status', 'LIKE', '%'.$filter['status'].'%');
			if(isset($filter['sales_id']))
				$query->where('a.id_sales_person', 'LIKE', '%'.$filter['sales_id'].'%');
				
			$data['filter_count'] = $query->count();
		}
		
		if($limit)
		{
			$query->orderBy($tableColumn[$sortBy],$sortDir)->skip($offset)->take($limit);
		}
		
		$data['data'] = $query->get();
		
		return $data;
	}

	public function insertData($data)
	{
		try
		{
			DB::beginTransaction();
			$insert = array(
				'owner' => Crypt::decrypt(Session::get('owner')),
				'project_code'=> $data['project_code'],
				'id_category'=> $data['id_category'],
				'id_customer'=> $data['id_customer'],
				'id_sales_person'=> $data['id_sales_person'],
				'id_area'=> $data['id_area'],
				'project_name'=> $data['project_name'],
				'description'=> $data['description'],
				'status'=> 1,//pre sales
				
				'is_deleted'=> 0,
				'created_by' => Session::get('user_name'),
				'created_at'=> DB::raw('now()')
			);

		   $id = DB::table($this->_table)->insertGetId($insert);
		   DB::commit();
		}catch(Exception $e)
		{
			DB::rollback();
			return $e->getMessage();
		}
        return true;
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

	public function getDetail($id)
	{
		$query = DB::table($this->_table)
						->select('*')
						->where('is_deleted',0)
						->where('id_project',$id)
						->where('owner', Crypt::decrypt(Session::get('owner')));
		
		$data = $query->first();

		return $data;
	}
	
	public function updateData($data)
	{
		if(empty($data['id'])) return 'Data Not Found';
		try
		{
			DB::beginTransaction();
			$update = array(
				'id_category'=> $data['id_category'],
				'id_customer'=> $data['id_customer'],
				'id_sales_person'=> $data['id_sales_person'],
				'id_area'=> $data['id_area'],
				'project_name'=> $data['project_name'],
				'description'=> $data['description'],

				'updated_by' => Session::get('user_name'),
				'updated_at'=> DB::raw('now()')
			);

		   DB::table($this->_table)->where($this->_id,$data['id'])->update($update);

		   DB::commit();
		}catch(Exception $e)
		{
			DB::rollback();
			return $e->getMessage();
		}
        return true;
	}
	
	public function projectStatus($id)
	{
		$data = DB::table('m_project')
						->select('id_project')
						->where($this->_id,$id)
						->where('owner', Crypt::decrypt(Session::get('owner')))
						->where('is_deleted',0)
						->where('status', '>',1)
						->get();
       
        return $data;
	}

	public function getTaskPercentage($id)
	{
		$data = DB::table('t_project_timeline_tasks')
										->select(DB::raw('TRUNCATE(avg(progress)*100,2) AS average'))
										->where($this->_id,$id)
										->where('owner', Crypt::decrypt(Session::get('owner')))
										->first();
		return $data;
	}

	public function generateCode($idCategory)
	{
		$query = DB::table($this->_table)
										->select(DB::raw('CASE WHEN (max(project_code) IS NULL ) THEN concat(\''.date('y').'\',LPAD("1","4","0")) ELSE max(project_code)+1 END AS ID'))
										// ->where('id_category',$idCategory)
										->where('owner', Crypt::decrypt(Session::get('owner')))
										->where(DB::raw('year(created_at)'), date('Y'))
										->first();
		return $query->ID;
	}

	public function getItemData($filter, $limit, $offset, $sortBy, $sortDir)
	{
		$this->_table = 'm_item';
		$data['id'] = 'id_item';
	
		$tableColumn = array($data['id'],'item_code', 'item_name', 'id_category', 'id_brand','id_unit','price_list');
		
		$query = DB::table($this->_table." as a")
						->select(array($data['id'], 'a.item_code AS item_code', 'a.item_name AS item_name','b.category_name AS category_name','c.brand_name AS brand_name','d.unit_name AS unit_name','a.price_list AS price_list'))
						->leftJoin('m_item_category as b', 'a.id_category', '=', 'b.id_category')
                                                ->leftJoin('m_item_brand as c', 'a.id_brand', '=', 'c.id_brand')
                                                ->leftJoin('m_item_unit as d', 'a.id_unit', '=', 'd.id_unit')
                                                ->where('a.is_deleted',0)
						->where('a.owner', Crypt::decrypt(Session::get('owner')));
		
		$data['count'] = $data['filter_count'] = $query->count();
		
		if($filter)
		{
			if($filter['item_code'])
				$query->where('a.item_code', 'LIKE', '%'.$filter['item_code'].'%');
                        if($filter['item_name'])
				$query->where('a.item_name', 'LIKE', '%'.$filter['item_name'].'%');
			if($filter['id_category'])
				$query->where('a.id_category', 'LIKE', '%'.$filter['id_category'].'%');
			if($filter['id_brand'])
				$query->where('a.id_brand', 'LIKE', '%'.$filter['id_brand'].'%');
			if($filter['id_unit'])
				$query->where('a.id_unit', 'LIKE', '%'.$filter['id_unit'].'%');
			if($filter['price_list'])
				$query->where('a.price_list', 'LIKE', '%'.$filter['price_list'].'%');
				
			$data['filter_count'] = $query->count();
		}
		
		$data['data'] = $query->orderBy($tableColumn[$sortBy],$sortDir)->skip($offset)->take($limit)->get();
		
		return $data;
	}

	public function getRow($id)
	{		
		$query = DB::table($this->_table)
						->where('is_deleted',0)
						->where($this->_id,$id)
						->where('owner', Crypt::decrypt(Session::get('owner')));
		
		return $query->first();
	}

}
