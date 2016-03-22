<?php 

class ProjectSalesController extends BaseController 
{
	protected $_defaultModel = 'SalesModel';
	protected $_pageTitle = 'Project';
	protected $_menuId = 'sales';
    
	public function __construct()
	{
        
    }
    
    public function index($idProject)
	{
		$listCcy 			= GeneralModel::getSelection('m_currency','ccy_code','ccy_name');
		$insertData['master']['qt_no'] = '';
		$insertData['master']['total_cogs'] = '';
		$insertData['master']['qt_date'] = '';
		$insertData['master']['total_sales'] = '';
		$insertData['master']['ref_no'] = '';
		$insertData['master']['percentage_margin'] = '';
		$insertData['master']['currency'] = 'IDR';
		$insertData['master']['total_margin'] = '';
		$insertData['master']['notes'] = '';
		
		$insertData['master']['foreword'] = '';
		$insertData['master']['afterword'] = '';
		
		$insertData['master']['attachment_filename'] = '';
		
		$detail = array();
		
		if (Request::isMethod('post'))
		{
			$allParams = Input::all();
			$idProject 	= $allParams['id'] = decode($allParams['id']);
			$insertData['master'] 	= array_intersect_key($allParams, $insertData['master'])+array('idProject'=>$idProject);
			$insertData['detail'] 	= $this->makeDetail( array_diff_key($allParams, $insertData['master']) );
				
			// if( !empty($insertData['master']) && !empty($insertData['detail']))
			{
				$filter = array('id'=>$idProject);
				$projectModel = new ProjectModel();
				$data 	= $projectModel->getData($filter);

				$qtModel = new QuotationModel();
				$getLatestQuotation = $qtModel->getLatestQuotation($idProject);

				if(Input::hasFile('attachment_filename'))
				{
					$insertData['master']['attachment_filename'] = Input::file('attachment_filename')->getClientOriginalName();

					$insert = $model->insertNew($insertData);
					if($insert === true)
					{
						$path = public_path().'\assets\document\project\\'.$data['data'][0]->project_code.'\qt';
						if(!File::exists($path)) {
							$parentPath = public_path().'\assets\document\project\\'.$data['data'][0]->project_code;
							if(!File::exists($parentPath)) {
								File::makeDirectory($parentPath);
							}
							File::makeDirectory($path);
						}

						Input::file('attachment_filename')->move($path,Input::file('attachment_filename')->getClientOriginalName());
					}
				}
			}
		}
		else
		{
			$idProject = decode($idProject);
			$qtModel = new QuotationModel();
			$getLatestQuotation = $qtModel->getLatestQuotation($idProject);
		}

		$filter = array('id'=>$idProject);
		$model = new ProjectModel();
		$data 	= $model->getData($filter);

		$param = array(
							'title'				=> $this->_pageTitle,
							'menu_id'	=> $this->_menuId,
							'title_desc'	=> 'Project Sales',
							'page'			=> 'salesPage',
							'listCcy'		=> $listCcy,
							'quot'			=> $insertData,
							'data'			=> $data['data'][0],
							'latestQuotation'		=> $getLatestQuotation,
							'id'			=> encode($idProject),
						);
		
        return View::make('modules.project.sales',$param);
    }

	public function getData($idProject)
	{
		$idProject = decode($idProject);
		if(Request::ajax()){
			$privi['view'] = hasPrivilege($this->_menuId,'view');
		
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
				$row->action = '<div>'.$ubah.$hapus.'</div>';
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

	public function makeDetail($param)
	{
		
	}

}