<?php 

class ProjectQTController extends BaseController 
{
	protected $_defaultModel = 'QuotationModel';
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
		if($data['filter_count'] != 1)
		{
			$param = array(
								'title'			=> 'Page 404',
								'menu_id'		=> 'missing'
							);
			return Response::view('errors.missing_admin', $param, 404);
		}
		
		$listCustomer 	= GeneralModel::getSelectionList('m_customer','id_customer','customer_name');
		$listSales 			= GeneralModel::getSelection('hs_hr_employee','emp_number','emp_firstname');
		$listStatus = Config::get('globalvar.project_status');
		
		if($data['data'][0]->status != 4){
			$this->_menuId = 'open_project';
		}
		
		$param = array(
							'title'			=> $this->_pageTitle,
							'menu_id'		=> $this->_menuId,
							'title_desc'	=> 'Project Quotation',
							'page'		=> 'quotation',
							'listCustomer'	=> array('' => '-- Any Value --') + $listCustomer,
							'listStatus'	=> array('' => '-- Any Value --') + $listStatus,
							'listSales'	=> array('' => '-- Any Value --') + $listSales,
							'data'		=> $data['data'][0],
							'id'			=> encode($idProject),
							'new'			=> hasPrivilege('project_quotation','new'),
						);
        return View::make('modules.project.quotation',$param);
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
				
				$view = '' ;
				if($privi['view']) 
					$view = '<a href="'.URL::to('/').'/project/quotation/view/'.encode($idProject).'/'.$row->revision.'" target="_blank" data-original-title="View Detail" ><i class="fa-file-text-o"></i></a> ';
				$row->action = '<div>'.$view.'</div>';
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

	public function add($idProject)
	{
		$listCategory 	= GeneralModel::getSelectionList('m_item_category','id_category','category_name');
		$listBrand 	        = GeneralModel::getSelectionList('m_item_brand','id_brand','brand_name');
		$listUnit 	        = GeneralModel::getSelectionList('m_item_unit','id_unit','unit_name');	

		$listCcy 			= GeneralModel::getSelection('m_currency','ccy_code','ccy_name');
		$listDiscType 	= $status = Config::get('globalvar.disc_type');
		$insertData['master']['qt_no'] = '';
		$insertData['master']['total_cogs'] = '';
		$insertData['master']['qt_date'] = '';
		$insertData['master']['total_sales'] = '';
		$insertData['master']['ref_no'] = '';
		$insertData['master']['percentage_margin'] = '';
		$insertData['master']['currency'] = 'IDR';
		$insertData['master']['total_margin'] = '';
		$insertData['master']['notes'] = '';
		
		$insertData['master']['subtotal'] = '';
		$insertData['master']['discount_type'] = '';
		$insertData['master']['discount'] = '';
		$insertData['master']['total_discount'] = '';
		$insertData['master']['total'] = '';

		$insertData['master']['foreword'] = '';
		$insertData['master']['afterword'] = '';
		
		$insertData['master']['attachment_filename'] = '';
		
		$detail = array();
		$idProject = decode($idProject);
		if (Request::isMethod('post'))
		{
			$allParams = Input::all();
			var_dump($allParams);
			$idProject 	= $allParams['id'] = decode($allParams['id']);
		}
		$filter = array('id'=>$idProject);
		$model = new ProjectModel();
		$data 	= $model->getData($filter);

		if($data['filter_count'] != 1)
		{
			$param = array(
								'title'			=> 'Page 404',
								'menu_id'		=> 'missing'
							);
			return Response::view('errors.missing_admin', $param, 404);
		}

		if (Request::isMethod('post'))
		{
			$insertData['master'] 	= array_intersect_key($allParams, $insertData['master'])+array('idProject'=>$idProject);
			$insertData['detail'] 	= $this->makeDetail( array_diff_key($allParams, $insertData['master']) );
				
			// if( !empty($insertData['master']) && !empty($insertData['detail']))
			{
				$filter = array('id'=>$idProject);
				$model = new ProjectModel();
				$data 	= $model->getData($filter);

				$model 		= new $this->_defaultModel;
				$latestData = $model->getLatestQuotation($idProject);
				$insertData['master']['revision'] = isset($latestData->revision) ? $latestData->revision+1 : 0;
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
				else{
					$insert = $model->insertNew($insertData);
				}
				$insertData += array('success'=>true);
				// var_dump($insertData);die;
				
			}
		}
		else
		{
			$model 		= new $this->_defaultModel;
			$latestData = $model->getLatestQuotation($idProject);
			
		}

		$param = array(
							'title'			=> $this->_pageTitle,
							'menu_id'		=> $this->_menuId,
							'title_desc'	=> 'Project Quotation',
							'page'		=> 'quotation',
							'listCcy'		=> $listCcy,
							'listDiscType'		=> $listDiscType,
							'quot'			=> $insertData,
							'data'		=> $data['data'][0],
							'id'			=> encode($idProject),
							'listCategory' => array('' => '-- Please Select --') + $listCategory,
							'listBrand' => array('' => '-- Please Select --') + $listBrand,
							'listUnit' => array('' => '-- Please Select --') + $listUnit,

						);

        return View::make('modules.project.quotationAdd',$param);
	}

	public function view($idProject,$rev)
	{
		$listCcy 			= GeneralModel::getSelection('m_currency','ccy_code','ccy_name');
		$listDiscType 	= $status = Config::get('globalvar.disc_type');
		$insertData['master']['qt_no'] = '';
		$insertData['master']['total_cogs'] = '';
		$insertData['master']['qt_date'] = '';
		$insertData['master']['total_sales'] = '';
		$insertData['master']['ref_no'] = '';
		$insertData['master']['percentage_margin'] = '';
		$insertData['master']['currency'] = 'IDR';
		$insertData['master']['total_margin'] = '';
		$insertData['master']['notes'] = '';
		
		$insertData['master']['subtotal'] = '';
		$insertData['master']['discount_type'] = '';
		$insertData['master']['discount'] = '';
		$insertData['master']['total'] = '';

		$insertData['master']['foreword'] = '';
		$insertData['master']['afterword'] = '';
		
		$insertData['master']['attachment_filename'] = '';

		$idProject 	= decode($idProject);

		$filter = array('id'=>$idProject);
		$model = new ProjectModel();
		$data 	= $model->getData($filter);

		if($data['filter_count'] != 1)
		{
			$param = array(
								'title'			=> 'Page 404',
								'menu_id'		=> 'missing'
							);
			return Response::view('errors.missing_admin', $param, 404);
		}
		
		$model 		= new $this->_defaultModel;
		$revData = $model->getRevision($idProject,$rev);

		// var_dump($revData);die;
		if(empty($revData))
		{
			$param = array(
								'title'			=> 'Page 404',
								'menu_id'		=> 'missing'
							);
			return Response::view('errors.missing_admin', $param, 404);
		}


		$param = array(
							'title'			=> $this->_pageTitle,
							'menu_id'		=> $this->_menuId,
							'title_desc'	=> 'Project Quotation',
							'page'		=> 'quotation',
							'listCcy'		=> $listCcy,
							'listDiscType'		=> $listDiscType,
							'quot'			=> $revData,
							'data'		=> $data['data'][0],
							'id'			=> encode($idProject),
							'view'		=> true
						);
		
        return View::make('modules.project.quotationAdd',$param);
	}

	public function makeDetail($param)
	{
		
	}

}