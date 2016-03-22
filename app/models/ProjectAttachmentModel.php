<?php

class ProjectAttachmentModel {

	protected $_table = 't_project_attachment';
	protected $_id = 'id_attachment';

	public function getData($filter, $limit=null, $offset=null, $sortBy=null, $sortDir=null)
	{
		$data['id'] = $this->_id;

		$tableColumn = array($this->_id, 'a.attachment_name','a.description', 'a.filename');
		
		$query = DB::table($this->_table.' AS a')
						->where('a.is_deleted',0)
						->where('a.owner', Crypt::decrypt(Session::get('owner')));
		if(isset($filter['id_project']))
			$query->where('a.id_project',$filter['id_project']);

		$data['count'] = $data['filter_count'] = $query->count();
		
		if($filter)
		{
			if(isset($filter['attachment_name']))
				$query->where('a.attachment_name','like','%'.$filter['attachment_name'].'%');
			if(isset($filter['description']))
				$query->where('a.description','like','%'.$filter['description'].'%');
			if(isset($filter['attachment_filename']))
				$query->where('a.filename','like','%'.$filter['attachment_filename'].'%');
			if(isset($filter['id_attachment']))
				$query->where('a.id_attachment',$filter['id_attachment']);
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
		// INSERT MASTER DATA
			$insertMaster = array(
				'owner' 		=> Crypt::decrypt(Session::get('owner')),
				'id_project'	=> $data['idProject'],
				'attachment_name'=> (isset($data['attachment_name'])) ? $data['attachment_name'] : '',
				'description'			=> (isset($data['description'])) ? $data['description'] : '',
				'filename'			=> (isset($data['attachment_filename'])) ? $data['attachment_filename'] : '',

				'is_deleted'=> 0,
				'created_by' => Session::get('user_name'),
				'created_at'=> DB::raw('now()')
			);
		   $id = DB::table($this->_table)->insertGetId($insertMaster);
		// INSERT GROUP DATA
		
		// INSERT ITEM DATA

		   DB::commit();
		}catch(Exception $e)
		{
			DB::rollback();
			return $e->getMessage();
		}
        return true;
	}

	public function deleteData($id,$idProject)
	{
		try
		{
			$updateArr = array(
				'is_deleted'	=> 1,
				'updated_by' => Session::get('user_name'),
				'updated_at'=> DB::raw('now()'),
				'deleted_at'=> DB::raw('now()')
			);
			$filter = array('id'=>$idProject);
			$model 	= new ProjectModel();
			$data 		= $model->getData($filter);
			$getFile 	= self::getData(array('id_attachment'=>$id));

			if(isset($data['data'][0]) && isset($getFile['data'][0]))
			{
				$file = public_path().'\assets\document\project\\'.$data['data'][0]->project_code.'\attachment\\'.$getFile['data'][0]->filename;
				if (File::exists($file)) {
					File::delete($file);
				}
				DB::table($this->_table)
									->where($this->_id,$id)
									->update($updateArr);
				$return = true;
			}
			else{
				$return = false;
			}
		}catch(Exception $e)
		{
			$return = $e->getMessage();
			var_dump($return);
		}
        return $return;
	}

}
