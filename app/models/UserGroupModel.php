<?php

class UserGroupModel {

	protected $_table = 'm_user_group';
	protected $_id = 'id_group';

	public function getData($filter, $limit, $offset, $sortBy, $sortDir)
	{
		$data['id'] = $this->_id;
	
		$tableColumn = array($this->_id, 'group_name', 'description');
		
		$query = DB::table($this->_table)
						->select($tableColumn)
						->where('deleted',0)
						->where('owner', Crypt::decrypt(Session::get('owner')));
		
		$data['count'] = $data['filter_count'] = $query->count();
		
		if($filter)
		{
			if($filter['group_name'])
				$query->where('group_name', 'LIKE', '%'.$filter['group_name'].'%');
			if($filter['description'])
				$query->where('description', 'LIKE', '%'.$filter['description'].'%');
				
			$data['filter_count'] = $query->count();
		}
		
		$data['data'] = $query->orderBy($tableColumn[$sortBy],$sortDir)->skip($offset)->take($limit)->get();
		
		return $data;
	}

	public function getDataRow($id_group)
	{
		$id_group = decode($id_group);
		$data['id'] = $this->_id;
		$tableColumn = array($this->_id, 'group_name', 'description');
		
		$data = DB::table($this->_table)
						->select($tableColumn)
						->where('id_group',$id_group)
						->where('owner', Crypt::decrypt(Session::get('owner')))
						->first();
	
		$priviData = DB::table('m_user_privilege')
								->where('id_group',$id_group)
								->get();
		if($data)
		{		
			foreach($priviData as $row)
			{
				$privi[$row->privi_code]['view'] =  $row->view;
				$privi[$row->privi_code]['new'] =  $row->new;
				$privi[$row->privi_code]['edit'] =  $row->edit;
				$privi[$row->privi_code]['delete'] =  $row->delete;
			}
			$data->privi = $privi;
		}
		
		return $data;
	}

	public function insertData($data)
	{
        $insertArr = array(
            'owner' => Crypt::decrypt(Session::get('owner')),
            'group_name'	=> $data['group_name'],
            'description'	=> $data['description'],
            'created_by' => Session::get('user_name'),
            'created_at'=> DB::raw('now()')
        );
       
        $id_group = DB::table($this->_table)->insertGetId($insertArr);
		
		$priviArr = array();
		foreach($data['privi'] as $privi_code=>$row)
		{
			$privi = array(
							'privi_code' => $privi_code,
							'id_group' => $id_group,
							'view' => isset($row['view']) ? 1 : 0,
							'new' => isset($row['new']) ? 1 : 0,
							'edit' => isset($row['edit']) ? 1 : 0,
							'delete' => isset($row['delete']) ? 1 : 0
			);
			array_push($priviArr,$privi);
		}
	
		DB::table('m_user_privilege')->insert($priviArr);
		
		return $id_group;
	}

	public function updateData($data)
	{
		$id_group = decode($data['id_group']);
        $updateArr = array(
            'group_name'	=> $data['group_name'],
            'description'	=> $data['description'],
            'updated_by' 	=> Session::get('user_name'),
            'updated_at'	=> DB::raw('now()')
        );
       
	   
        DB::table($this->_table)
			->where($this->_id,$id_group)
			->update($updateArr);
		
		// delete privi
		DB::table('m_user_privilege')->where('id_group', $id_group)->delete();
		
		$priviArr = array();
		foreach($data['privi'] as $privi_code=>$row)
		{
			$privi = array(
							'privi_code' => $privi_code,
							'id_group' => $id_group,
							'view' => isset($row['view']) ? 1 : 0,
							'new' => isset($row['new']) ? 1 : 0,
							'edit' => isset($row['edit']) ? 1 : 0,
							'delete' => isset($row['delete']) ? 1 : 0
			);
			array_push($priviArr,$privi);
		}
		
		DB::table('m_user_privilege')->insert($priviArr);
		
		return $id_group;
	}

	public function deleteData($id)
	{
        $updateArr = array(

            'deleted'	=> 1,
            'updated_by' => Session::get('user_name'),
            'updated_at'=> DB::raw('now()')
        );
       
        return DB::table($this->_table)
							->where($this->_id,$id)
							->update($updateArr);
	}

	public function hasUserAcct($id)
	{
		$data = DB::table('m_user')
						->select('id_user')
						->where('id_group',$id)
						->where('owner', Crypt::decrypt(Session::get('owner')))
						->where('deleted',0)
						->get();
       
        return $data;
	}
}
