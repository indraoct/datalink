<?php 

class ProjectController extends BaseController 
{
	protected $_defaultModel = 'ProjectModel';
	protected $_pageTitle = 'Project';
	protected $_menuId = 'project';
    
	public function __construct()
	{
        
    }
    
    public function index()
	{
		$listCustomer 	= GeneralModel::getSelectionList('m_customer','id_customer','customer_name');
		$listSales 			= GeneralModel::getSelection('hs_hr_employee','emp_number','emp_firstname');
		$listStatus = Config::get('globalvar.project_status');
		$param = array(
							'title'			=> $this->_pageTitle,
							'menu_id'		=> $this->_menuId,
							'title_desc'	=> 'Project',
							'listCustomer'	=> array('' => '-- Any Value --') + $listCustomer,
							'listStatus'	=> array('' => '-- Any Value --') + $listStatus,
							'listSales'	=> array('' => '-- Any Value --') + $listSales,
							'new'			=> hasPrivilege($this->_menuId,'new')
						);
        return View::make('modules.project.index',$param);
    }

	public function getData()
	{
		if(Request::ajax()){
		
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
			
			$data = array();
			$n = $offset + 1;
			foreach($get['data'] as $key=>$r)
			{
				$row = $r;
				
				$row->id = $r->$get['id'];
				unset($row->$get['id']);
				
				$ubah = $hapus = '';
				if($privi['edit']) 
					$ubah = '<a href="'.URL::to('/').'/project/edit/'.encode($row->id).'" class="btn btn-xs purple tooltips show_edit" data-original-title="Edit" ><i class="fa fa-pencil"></i></a> ';
				if($privi['delete'])
					$hapus = '<a href="javascript:;" class="btn btn-xs red tooltips do_delete" data-original-title="Hapus"><i class="fa fa-trash-o"></i></a> ';
				
				$row->action = '<div>'.$ubah.$hapus.'</div>';
				$row->no = $n;
				
				$data[$key] = $row;
				
				$n++;
			}
			
			$records["data"] = $get['data'];
			$records["draw"] = $draw;
			$records["recordsTotal"] = $get['count'];
			$records["recordsFiltered"] = $get['filter_count'];;
			
			echo json_encode($records);
		
		}
	}
    
	public function add()
	{
		$listCustomer 	= GeneralModel::getSelectionList('m_customer','id_customer','customer_name');
		$listCategory 	= GeneralModel::getSelectionList('m_project_category','id_category','category_name');
		$listArea 	= GeneralModel::getSelectionList('m_project_area','id_area','area_name');
		$listSales 		= GeneralModel::getSelection('hs_hr_employee','emp_number','emp_firstname');
		
		$param = array(
						'title'					=> $this->_pageTitle,
						'title_desc'		=> 'Project',
						'menu_id'		=> $this->_menuId,
						'act'					=> 'New',
						'listCustomer'	=> array('' => '-- Please Select --') + $listCustomer,
						'listCategory'	=> array('' => '-- Please Select --') + $listCategory,
						'listSales'			=> array('' => '-- Please Select --') + $listSales,
						'listArea'			=> array('' => '-- Please Select --') + $listArea,
					);
		return View::make('modules.project.add',$param);
	}

    public function doAdd()
	{
		$input = Input::all();

		$rules = $this->rules('add');
		$validator = Validator::make($input, $rules);
		if($validator->fails())
		{
			$errorArr = array();
			foreach($rules as $key=>$row)
			{
				$errorArr[$key] = $validator->messages()->first($key);
			}
			
			$ret = array(
						'status'		=> false,
						'alert_msg'	=> 'Error occured. Please check again.',
						'error_msg'	=> $errorArr
					);
		}    
		else
		{                                    
			$model = new $this->_defaultModel;
			$input['project_code'] = $model->generateCode($input['id_category']);
			$return = $model->insertData($input);

			if($return === true)
			{
				$ret = array(
							'status'		=> true,
							'alert_msg'	=> 'Successfully added.',
							'error_msg'	=> $return,
						);
			}
			else
			{
				$ret = array(
							'status'		=> false,
							'alert_msg'	=> 'Addition failed. Please contact your administrator.',
							'error_msg'	=> $return
						);
			}
		}//end of validator
		
		echo json_encode($ret);
    }
    
	public function edit($idproject)
	{
		$idproject = decode($idproject);

		$model = new $this->_defaultModel;
		$getDetail = $model->getDetail($idproject);

		if(!$getDetail)
		{
			$param = array(
								'title'			=> 'Page 404',
								'menu_id'		=> 'missing'
							);
			return Response::view('errors.missing_admin', $param, 404);
		} 
		
		$listCustomer 	= GeneralModel::getSelectionList('m_customer','id_customer','customer_name');
		$listCategory 	= GeneralModel::getSelectionList('m_project_category','id_category','category_name');
		$listArea 	= GeneralModel::getSelectionList('m_project_area','id_area','area_name');
		$listSales 		= GeneralModel::getSelection('hs_hr_employee','emp_number','emp_firstname');
		
		$param = array(
						'title'					=> $this->_pageTitle,
						'title_desc'		=> 'Project',
						'menu_id'		=> $this->_menuId,
						'act'					=> 'Edit',
						'listCustomer'	=> array('' => '-- Please Select --') + $listCustomer,
						'listCategory'	=> array('' => '-- Please Select --') + $listCategory,
						'listSales'			=> array('' => '-- Please Select --') + $listSales,
						'listArea'			=> array('' => '-- Please Select --') + $listArea,

						'project'		=> $getDetail,
						'idProject'			=> $idproject,
					);
		return View::make('modules.project.add',$param);
	}

    public function doEdit()
	{
		$input = Input::all();

		$rules = $this->rules('edit');
		$validator = Validator::make($input, $rules);

		if($validator->fails())
		{
			$errorArr = array();
			foreach($rules as $key=>$row)
			{
				$errorArr[$key] = $validator->messages()->first($key);
			}
			
			$ret = array(
						'status'		=> false,
						'alert_msg'	=> 'Error occured. Please check again.',
						'error_msg'	=> $errorArr
					);
		}    
		else
		{                                    
			$model = new $this->_defaultModel;
			$return = $model->updateData($input);

			if($return === true)
			{
				$ret = array(
							'status'		=> true,
							'alert_msg'	=> 'Successfully updated.',
							'error_msg'	=> $return,
						);
			}
			else
			{
				$ret = array(
							'status'		=> false,
							'alert_msg'	=> 'Update failed. Please contact your administrator.',
							'error_msg'	=> $return
						);
			}
		}//end of validator
		
		echo json_encode($ret);
    }
    
	public function rules($type)
	{
		$rules = array(
									// 'project_code' => 'required|alpha_num|unique:m_project',
									'project_name' => 'required',
									'id_category' => 'required',
									'id_customer' => 'required',
									'id_sales_person' => 'required',
									'id_area' => 'required',
								);
		if($type =='edit')
		{
			unset($rules['project_code']);
		}
		return $rules;
	}
	
    public function doDelete()
	{
        $id = intval(Input::get('id'));
		$model = new $this->_defaultModel;
		if($model->projectStatus($id))
		{
			$ret = array(
						'status'		=> false,
						'alert_msg'	=> 'The selected category has customers, cannot be deleted.',
						'error_msg'	=> array()
					);
		}
		else
		{
			$del = $model->deleteData($id);
			$ret = array(
						'status'		=> $del,
						'alert_msg'	=> $del ? 'Successfully deleted.' : 'Deletion failed. Please contact your administrator.',
						'error_msg'	=> array()
					);
		}
		echo json_encode($ret);
	}
    
	public function showOverview($idProject)
	{
		$idProject = decode($idProject);
		$filter = array('id'=>$idProject);
		$model = new $this->_defaultModel;
		$data 	= $model->getData($filter);
		$percentTask 	= $model->getTaskPercentage($idProject);

		$timelineModel = new TimelineModel();
		$task = $timelineModel->getTask($idProject,array('progress'=>true));

		if($data['filter_count'] != 1)
		{
			$param = array(
								'title'			=> 'Page 404',
								'menu_id'		=> 'missing'
							);
			return Response::view('errors.missing_admin', $param, 404);
		}

		if($data['data'][0]->status != 4){
			$this->_menuId = 'open_project';
		}

		$param = array(
							'title'			=> $this->_pageTitle,
							'menu_id'		=> $this->_menuId,
							'title_desc'	=> 'Project Overview',
							'page'		=> 'overview',
							'data'		=>$data['data'][0],
							'task'		=>$task,
							'percentTask'		=>$percentTask,
							'id'			=>encode($idProject),
						);

        return View::make('modules.project.overview',$param);
	}

}