<?php 

class ProjectAttachmentController extends BaseController 
{
	protected $_defaultModel = 'ProjectAttachmentModel';
	protected $_pageTitle = 'Project';
	protected $_menuId = 'project';
    
	public function __construct()
	{
        
    }
    
    public function index($idProject)
	{
		$idProject = decode($idProject);
		$filter = array('id'=>$idProject);
		$model = new ProjectModel();
		$data 	= $model->getData($filter);
		$return = '';

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

		if (Request::isMethod('post'))
		{
			$allParams = Input::all();
			$allParams['idProject'] = decode($allParams['idProject']);
			
			if($idProject != $allParams['idProject'])
			{
				$param = array(
									'title'			=> 'Page 404',
									'menu_id'		=> 'missing'
								);
				return Response::view('errors.missing_admin', $param, 404);
			}

			if(empty($allParams['attachment_name']))
			{
				$return = 'Please Input File Attachment';
			}
			else if(!Input::hasFile('attachment_filename'))
			{
				$return = 'Please Attach File';
			}
			else{
				$insertData = $allParams;
				$insertData['attachment_filename'] = time().'_'.Input::file('attachment_filename')->getClientOriginalName();
				$model 		= new $this->_defaultModel;
				$insert = $model->insertData($insertData);
				if($insert === true)
				{
					$path = public_path().'\assets\document\project\\'.$data['data'][0]->project_code.'\attachment';
					if(!File::exists($path)) {
						$parentPath = public_path().'\assets\document\project\\'.$data['data'][0]->project_code;
						if(!File::exists($parentPath)) {
							File::makeDirectory($parentPath);
						}
						File::makeDirectory($path);
					}

					Input::file('attachment_filename')->move($path,Input::file('attachment_filename')->getClientOriginalName());
				}
				$return = 'success';
			}
		}

		$param = array(
							'title'			=> $this->_pageTitle,
							'menu_id'		=> $this->_menuId,
							'title_desc'	=> 'Project Attachment',
							'page'		=> 'attachment',
							'data'		=>$data['data'][0],
							'id'			=>encode($idProject),
							'return'				=>$return,
						);

        return View::make('modules.project.attachment',$param);
	}

	public function getData($idProject)
	{
		$idProject = decode($idProject);
		if(Request::ajax()){
			$privi['delete'] = true;//hasPrivilege($this->_menuId,'delete');
		
			$param = Input::all();
			
			$iDisplayLength = intval($param['length']);
			$limit = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
			$offset = intval($param['start']);
			$draw = intval($param['draw']);
			$sortBy = $param['order'][0]['column'];
			$sortDir = $param['order'][0]['dir'];
			$filter = isset($param['filter']) ? $param['filter'] : array();
			$filter['id_project'] = $idProject;
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
				if($privi['delete'])
					$hapus = '<a href="javascript:;" class="btn btn-xs red tooltips do_delete" data-original-title="Hapus"><i class="fa fa-trash-o"></i></a> ';
				$row->action = '<div>'.$hapus.'</div>';
				$row->no = $n;
				
				$data[$key] = $row;
				
				$n++;
			}
			
			$records["data"] = $get['data'];
			$records["draw"] = $draw;
			$records["recordsTotal"] = $get['count'];
			$records["recordsFiltered"] = $get['filter_count'];
			
			echo json_encode($records);
		
		}
	}

	public function doDelete($idProject)
	{
        $id = intval(Input::get('id'));
		$model = new $this->_defaultModel;
		$del = $model->deleteData($id,decode($idProject));
		$ret = array(
					'status'		=> $del,
					'alert_msg'	=> $del ? 'Successfully deleted.' : 'Deletion failed. Please contact your administrator.',
					'error_msg'	=> array()
				);
		echo json_encode($ret);
	}

}