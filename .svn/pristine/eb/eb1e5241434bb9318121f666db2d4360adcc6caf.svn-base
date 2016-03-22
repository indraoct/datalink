<?php

class TimelineModel {

	protected $_table = 't_project_timeline_tasks';
	protected $_detailTable = 't_project_timeline_links';
	protected $_id = 'id';

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

	public function insertTask($data)
	{
		try
		{
			DB::beginTransaction();
			$insert = array(
				'owner' => Crypt::decrypt(Session::get('owner')),
				'id_project'=> 	decode($data['id_project']),
				'text'=> 			(isset($data[$data['ids'].'_text'])) ? $data[$data['ids'].'_text'] : '',
				'start_date'=> (isset($data[$data['ids'].'_start_date'])) ? defaultDateTime($data[$data['ids'].'_start_date'].':00') : '',
				'duration'=> 	(isset($data[$data['ids'].'_duration'])) ? $data[$data['ids'].'_duration'] : '',
				'progress'=> 	(isset($data[$data['ids'].'_progress'])) ? $data[$data['ids'].'_progress'] : '',
				'sortorder'=>	(isset($data[$data['ids'].'_sortorder'])) ? $data[$data['ids'].'_sortorder'] : '',
				'parent'=> 		(isset($data[$data['ids'].'_parent'])) ? $data[$data['ids'].'_parent'] : '',
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
	public function insertLink($data)
	{
		try
		{
			DB::beginTransaction();
			$insert = array(
				'source'=> 			(isset($data[$data['ids'].'_source'])) ? $data[$data['ids'].'_source'] : '',
				'target'=> (isset($data[$data['ids'].'_target'])) ? $data[$data['ids'].'_target'] : '',
				'type'=> 	(isset($data[$data['ids'].'_type'])) ? $data[$data['ids'].'_type'] : '',
			);
		   $id = DB::table($this->_detailTable)->insertGetId($insert);
		   DB::commit();
		}catch(Exception $e)
		{
			DB::rollback();
			return $e->getMessage();
		}
        return true;
	}

	public function updateTask($data)
	{
		try
		{
			DB::beginTransaction();
			$updateArr = array(
												'text'=> 			(isset($data[$data['ids'].'_text'])) ? $data[$data['ids'].'_text'] : '',
												'start_date'=> (isset($data[$data['ids'].'_start_date'])) ? defaultDateTime($data[$data['ids'].'_start_date'].':00') : '',
												'duration'=> 	(isset($data[$data['ids'].'_duration'])) ? $data[$data['ids'].'_duration'] : '',
												'progress'=> 	(isset($data[$data['ids'].'_progress'])) ? $data[$data['ids'].'_progress'] : '',
												'sortorder'=>	(isset($data[$data['ids'].'_sortorder'])) ? $data[$data['ids'].'_sortorder'] : '',
												'parent'=> 		(isset($data[$data['ids'].'_parent'])) ? $data[$data['ids'].'_parent'] : '',
											);
			DB::table($this->_table)
							->where($this->_id,[$data['ids'].'_id'])
							->update($updateArr);
		   DB::commit();
		}catch(Exception $e)
		{
			DB::rollback();
			return $e->getMessage();
		}
        return true;
	}
	public function updateLink($data)
	{
		try
		{
			DB::beginTransaction();
			$updateArr = array(
												'source'=>(isset($data[$data['ids'].'_source'])) ? $data[$data['ids'].'_source'] : '',
												'target'=> (isset($data[$data['ids'].'_target'])) ? $data[$data['ids'].'_target'] : '',
												'type'=> 	(isset($data[$data['ids'].'_type'])) ? $data[$data['ids'].'_type'] : '',
											);
			DB::table($this->_detailTable)
							->where($this->_id,[$data['ids'].'_id'])
							->update($updateArr);
		   DB::commit();
		}catch(Exception $e)
		{
			DB::rollback();
			return $e->getMessage();
		}
        return true;
	}

	public function deleteTask($id)
	{
		return DB::table($this->_table)->where('id', $id)->delete();
	}
	public function deleteLink($id)
	{
		return DB::table($this->_detailTable)->where('id', $id)->delete();
	}

	public function getTask($idProject,$filter=null)
	{
		$query = DB::table($this->_table)
						->select(array('id','text','duration','progress','parent',DB::Raw("DATE_FORMAT(start_date, '%d-%m-%Y') as start_date")))
						->where('id_project',$idProject)
						->where('owner', Crypt::decrypt(Session::get('owner')));

		if(isset($filter['progress']))
			$query->where('progress','<>',1);//not completed
		
		$data = $query->get();

		return $data;
	}
	public function getLink($id)
	{
		$query = DB::table($this->_detailTable)
						->whereIn('source', function($x) use ($id){
										$x->select('id')
											->from($this->_table)
											->where('id_project',$id)
											->where('owner', Crypt::decrypt(Session::get('owner')));
											});
						// ->where('source','in',DB::Raw('(Select id from '.$this->_table.' where id_project = \''.$id.'\' and owner = \''.Crypt::decrypt(Session::get('owner')).'\')'));
		// var_dump($query->toSql());die;
		$data = $query->get();

		return $data;
	}

}
