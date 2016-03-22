<?php

class QuotationModel {

	protected $_table = 't_project_qt';
	protected $_detailTable = 't_project_qt_detail';
	protected $_groupDetailTable = 't_project_qt_group_detail';
	protected $_id = 'id_qt';

	public function getLatestQuotation($idProject)
	{
		$getRevision = DB::table($this->_table)
																->select(DB::raw('max(revision) as revision'))
																->where('id_project',$idProject)
																->where('is_deleted',0)
																->where('owner',Crypt::decrypt(Session::get('owner')))
																->first();
		$revision = ($getRevision->revision) ? $getRevision->revision : 0;
																
		$query = DB::table($this->_table)
										->where('id_project',$idProject)
										->where('is_deleted',0)
										->where('owner',Crypt::decrypt(Session::get('owner')))
										->where('revision',$revision);

		return $query->first();
	}

	public function getData($filter, $limit=null, $offset=null, $sortBy=null, $sortDir=null)
	{
		$data['id'] = $this->_id;

		$tableColumn = array($this->_id, 'a.revision','a.created_by', 'a.created_at');
		
		$query = DB::table($this->_table.' AS a')
						->where('a.is_deleted',0)
						->where('a.owner', Crypt::decrypt(Session::get('owner')));
		if(isset($filter['id_project']))
			$query->where('a.id_project',$filter['id_project']);
		
		$data['count'] = $data['filter_count'] = $query->count();
		
		if($filter)
		{
			$data['filter_count'] = $query->count();
		}
		
		if($limit)
		{
			$query->orderBy($tableColumn[$sortBy],$sortDir)->skip($offset)->take($limit);
		}
		
		$data['data'] = $query->get();
		
		return $data;
	}

	public function insertNew($data)
	{
		try
		{
			DB::beginTransaction();
		// INSERT MASTER DATA
			$insertMaster = array(
				'owner' 		=> Crypt::decrypt(Session::get('owner')),
				'id_project'	=> $data['master']['idProject'],
				'qt_no'			=> (isset($data['master']['qt_no'])) ? $data['master']['qt_no'] : '',
				'revision'			=> (isset($data['master']['revision'])) ? $data['master']['revision'] : 0,
				'total_cogs'	=> (isset($data['master']['total_cogs'])) ? $data['master']['total_cogs'] : '',
				'qt_date'		=> (isset($data['master']['qt_date'])) ? defaultDate($data['master']['qt_date']) : '',
				'total_sales'	=> (isset($data['master']['total_sales'])) ? $data['master']['total_sales'] : '',
				'ref_no'		=> (isset($data['master']['ref_no'])) ? $data['master']['ref_no'] : '',
				'percentage_margin'	=> (isset($data['master']['percentage_margin'])) ? $data['master']['percentage_margin'] : '',
				'currency'				=> (isset($data['master']['currency'])) ? $data['master']['currency'] : '',
				'total_margin'=> (isset($data['master']['total_margin'])) ? $data['master']['total_margin'] : '',
				'notes'			=> (isset($data['master']['notes'])) ? $data['master']['notes'] : '',
				'foreword'	=> (isset($data['master']['foreword'])) ? $data['master']['foreword'] : '',
				'afterword'	=> (isset($data['master']['afterword'])) ? $data['master']['afterword'] : '',
				'attachment_filename'=> (isset($data['master']['attachment'])) ? $data['master']['attachment'] : '',
				'notes'			=> (isset($data['master']['notes'])) ? $data['master']['notes'] : '',

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

}
