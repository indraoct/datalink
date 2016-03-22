<?php

class BomModel {

	protected $_table = 't_project_bom';
	protected $_detailTable = 't_project_bom_detail';
	protected $_groupDetailTable = 't_project_bom_group_detail';
	protected $_id = 'id_sales';

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

	public function insert($data)
	{
		try
		{
			DB::beginTransaction();

			$idProject = $data['master']['idProject'];
		// INSERT MASTER DATA
			$insertMaster = array(
				'owner' 		=> Crypt::decrypt(Session::get('owner')),
				'id_project'	=> $idProject,
				'id_customer'	=> $data['master']['id_customer'],
				'id_sales_person'	=> $data['master']['id_sales_person'],
				'sales_no'		=> $data['master']['sales_no'],
				'sales_date'		=> $data['master']['sales_date'],
				'ref_no'			=> (isset($data['master']['ref_no'])) ? $data['master']['ref_no'] : '',
				'currency'		=> (isset($data['master']['currency'])) ? $data['master']['currency'] : '',
				'notes'				=> (isset($data['master']['notes'])) ? $data['master']['notes'] : '',
				'subtotal'			=> (isset($data['master']['subtotal'])) ? $data['master']['subtotal'] : '',
				'discount'			=> (isset($data['master']['discount'])) ? $data['master']['discount'] : '',
				'discount_type'=> (isset($data['master']['discount_type'])) ? $data['master']['discount_type'] : '',
				'total_discount'=> (isset($data['master']['total_discount'])) ? $data['master']['total_discount'] : '',
				'total'				=> (isset($data['master']['total'])) ? $data['master']['total'] : '',
				'status'			=> (isset($data['master']['total'])) ? $data['master']['status'] : '',
				'foreword'	=> (isset($data['master']['foreword'])) ? $data['master']['foreword'] : '',
				'afterword'	=> (isset($data['master']['afterword'])) ? $data['master']['afterword'] : '',
				'attachment_filename'=> (isset($data['master']['attachment'])) ? $data['master']['attachment'] : '',

				'is_deleted'=> 0,
				'created_by' => Session::get('user_name'),
				'created_at'=> DB::raw('now()')
			);
		   // $id = DB::table($this->_table)->insertGetId($insertMaster);

		// INSERT GROUP DATA
		
		// INSERT ITEM DATA
	    
		// UPDATE STATUS PROJECT
		   $updateArr = array(
				'status'	=> 3,
			);
		   
			DB::table($this->_table)
								->where('id_project',$idProject)
								->update($updateArr);

		   DB::commit();
		}catch(Exception $e)
		{
			DB::rollback();
			return $e->getMessage();
		}
        return true;
	}

}
