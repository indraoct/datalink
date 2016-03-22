<?php 

class VendorController extends BaseController 
{
	protected $_defaultModel = 'VendorModel';
	protected $_pageTitle = 'Vendor';
	protected $_menuId = 'vendor';
    
	public function __construct()
	{
        
    }
    
    public function index()
	{
		$param = array(
							'title'			=> $this->_pageTitle,
							'title_desc'	=> 'Vendor',
							'menu_id'		=> $this->_menuId,
						);
        return View::make('modules.vendor.index',$param);
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
					$ubah = '<a href="'.URL::to('/').'/vendor2/edit/'.encode($row->id).'" class="btn btn-xs purple tooltips show_edit" data-original-title="Edit" ><i class="fa fa-pencil"></i></a> ';
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
		$listCategory 	= GeneralModel::getSelectionList('m_vendor_category','id_category','category_name');
		$listTitle 			= array(1=>'Mr',2=>'Mrs',3=>'Ms');
		$listTop 			= array(1=>'Cash',2=>'Credit');
		$listAcctPayable 			= array();
		$listCcy 			= GeneralModel::getSelection('m_currency','ccy_code','ccy_name');
		$defaultCcy = 'IDR';

		$param = array(
						'title'					=> $this->_pageTitle,
						'title_desc'		=> 'Client',
						'menu_id'		=> $this->_menuId,
						'act'					=> 'New',
						'listCategory'	=> array('' => '-- Please Select --') + $listCategory,
						'listTop'			=> $listTop,
						'listTitle'			=> $listTitle,
						'listCcy'			=> $listCcy,
						'listAcctPayable'			=> array('' => '-- Please Select --') + $listAcctPayable,
						'defaultCcy'			=> $defaultCcy,
					);
		return View::make('modules.vendor.add',$param);
	}

    public function doAdd()
	{
		$input = Input::all();

		$rules = $this->rules($input);
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
    
	public function edit($idVendor)
	{
		$idVendor = decode($idVendor);

		$model = new $this->_defaultModel;
		$getDetail = $model->getDetail($idVendor);

		if(!$getDetail)
		{
			$param = array(
								'title'			=> 'Page 404',
								'menu_id'		=> 'missing'
							);
			return Response::view('errors.missing_admin', $param, 404);
		} 
		
		$listCategory 	= GeneralModel::getSelectionList('m_vendor_category','id_category','category_name');
		$listTitle 			= array(1=>'Mr',2=>'Mrs',3=>'Ms');
		$listTop 			= array(1=>'Cash',2=>'Credit');
		$listAcctPayable 			= array();
		$listCcy 			= GeneralModel::getSelection('m_currency','ccy_code','ccy_name');
		$defaultCcy = 'IDR';
		
		$param = array(
						'title'					=> $this->_pageTitle,
						'title_desc'		=> 'Client',
						'menu_id'		=> $this->_menuId,
						'act'					=> 'Edit',
						'listCategory'	=> array('' => '-- Please Select --') + $listCategory,
						'listTop'			=> array('' => '-- Please Select --') + $listTop,
						'listTitle'			=> $listTitle,
						'listCcy'			=> array('' => '-- Please Select --') + $listCcy,
						'listAcctPayable'			=> array('' => '-- Please Select --') + $listAcctPayable,
						
						'vendor'	=> $getDetail['vendor'],
						'contact'		=> $getDetail['contact'],
						'vendId'			=> $idVendor,
						'defaultCcy'			=> $defaultCcy,
					);
		return View::make('modules.vendor.add',$param);
	}

    public function doEdit()
	{
		$input = Input::all();

		$rules = $this->rules($input);
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
    
	public function rules($input)
	{
		$rules = array(
									'vendName' => 'required',
									'vendCategory' => 'required',
									'addr' => 'required',
									'phone' => 'required',
									// 'desc' => 'required',
									// 'fax' => 'required',
									
									'contTitle' => 'required',
									'contName' => 'required',
									// 'contMobilePhone' => 'required',
									// 'contPosition' => 'required',
									// 'contPhone' => 'required',
									// 'contAddr' => 'required',
									// 'contEmail' => 'required',
									
									// 'bank' => 'required',
									// 'bankAcct' => 'required',
									// 'acctName' => 'required',
									// 'npwp' => 'required',
									// 'pkp' => 'required',
									// 'pkpDate' => 'required',
									// 'tax' => 'required',
									
									'rCcy' => 'required',
									'rTop' => 'required',
									// 'rReceive' => 'required',
								);

		if($input['rTop'] == 2)
		{
			$rules+= array(
										'rInterval' => 'required',
										'rPlafond' => 'required',
									);
		}
		if(!empty($input['contEmail']))
		{
			$rules+= array(
										'contEmail' => 'email',
									);
		}

		return $rules;
	}
	
    public function doDelete()
	{
        $id = intval(Input::get('id'));
		$model = new $this->_defaultModel;
		if($model->hasInv($id))
		{
			$ret = array(
						'status'		=> false,
						'alert_msg'	=> 'The selected category has vendors, cannot be deleted.',
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
    
}