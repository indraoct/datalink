<?php 

class OpenProjectController extends BaseController 
{
	protected $_defaultModel = 'OpenProjectModel';
	protected $_pageTitle = 'Open Project';
	protected $_menuId = 'open_project';
    
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
						);
        return View::make('modules.open_project.index',$param);
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
				
				
				$row->detail = '<a href="'.URL::to('/').'/project/overview/'.encode($row->id).'">'.$row->project_name.'</a>';
				
				$ubah = $hapus = '';
				if($privi['edit']) 
					$ubah = '<a href="javascript:;" class="btn btn-xs purple tooltips show_edit" data-original-title="Edit" ><i class="fa fa-pencil"></i></a> ';
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

}