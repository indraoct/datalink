<?php 

class DeliveryOrderNewController extends BaseController 
{
	protected $_defaultModel = 'DeliveryOrderModel';
	protected $_pageTitle = 'New Delivery Order';
	protected $_menuId = 'delivery_order';
    
	public function __construct()
	{
        
    }
    
    public function index($idBom)
	{
		$idBom = decode($idBom);
		$listCustomer 	= GeneralModel::getSelectionList('m_customer','id_customer','customer_name');
		$listProject 			= GeneralModel::getSelectionList('m_project','id_project','project_name');
		$listWarehouse 	= GeneralModel::getSelectionList('m_warehouse','id_warehouse','warehouse_name');
		$trx_no		 		= GeneralModel::getTrxNo('t_delivery_order','trx_no');
		
		$model 	= new $this->_defaultModel;
		$bomData 	= $model->getBOM($idBom);
		if(empty($bomData['master']))
		{
			$param = array(
								'title'			=> 'Page 404',
								'menu_id'		=> 'missing'
							);
			return Response::view('errors.missing_admin', $param, 404);
		}

		$bomData = array(
									'master'=>array(
																'trx_no'=>$trx_no,
																'id_customer'=>$bomData['master']->id_customer,
																'trx_date'=>'',
																'id_project'=>$bomData['master']->id_project,
																'note'=>'',
																'no_ref'=>$bomData['master']->bom_no,
															),
									'detail'=>$bomData['detail']
								);

		if(Session::get('action')=='back')
		{
			Session::forget('action');
			$data = Session::get('deliveryOrder');
			$bomData['master']['trx_date'] = $data['trx_date'];
			$bomData['master']['id_customer'] = $data['id_customer'];
			$bomData['master']['id_project'] = $data['id_project'];
			$bomData['master']['note'] = $data['note'];
			$bomData['item'] = $data['item'];
		}
		Session::forget('deliveryOrder');
		
		$param = array(
							'title'			=> $this->_pageTitle,
							'title_desc'		=> 'Delivery Order',
							'listCustomer'	=> array('' => '-- Any Value --') + $listCustomer,
							'listWarehouse'	=> array('' => '-- Any Value --') + $listWarehouse,
							'listProject'	=> array('' => '-- Please Select --') + $listProject,
							'menu_id'	=> $this->_menuId,
							'bomData'			=> $bomData,
							'idBom'			=> encode($idBom),
							'new'			=> hasPrivilege($this->_menuId,'new')
						);
        return View::make('modules.delivery_order.new',$param);
    }

	public function doValidate()
	{
        $input = Input::all();
		$validate = true;
		
		$errorArr = array();
		$alertMsg = 'An Error Occured. Please Try Again.';

        $rules = array(
								// 'idBom' 		=> 'required|unique:t_delivery_order,id_po',
								'trx_date' 		=> 'required',
								'id_customer' 		=> 'required',
								'id_project' 		=> 'required',
							  );
        $validator = Validator::make($input, $rules);

		if($validator->fails())
		{
			foreach($rules as $key=>$row)
			{
				$errorArr[$key] = $validator->messages()->first($key);
			}
			$validate = false;
		}
		else
		{
			if(isset($input['item']))
			{
				$i=0;
				foreach($input['item'] as $row)
				{
					$row['qty'] = defaultNumeric($row['qty']);

					$rulesItem = array(
										'qty' 		=> 'required|numeric',
										'id_warehouse' 		=> 'required'
									);
					$validatorP = Validator::make($row, $rulesItem);
					if($validatorP->fails())
					{
						foreach($rulesItem as $key=>$row)
						{
							$errorArr['item'][$i][$key] = $validatorP->messages()->first($key);
						}
						$validate = false;
					}
					$i++;
				}
			}
			else
			{
				$validate = false;
				$alertMsg = 'Please Check the Item';
			}
        }
        if(!$validate)
		{    
			$ret = array(
						'status'		=> false,
						'alert_msg'	=> $alertMsg,
						'error_msg'	=> $errorArr
					);
        }    
        else
		{                
			$ret = array(
						'status'		=> true,
						'alert_msg'	=> '',
						'error_msg'	=> array()
					);
        } //end of validator
		
		echo json_encode($ret);
    }
    
    public function doProcess()
	{
		if(Request::isMethod('post')){
			$input = Input::all();
		}
		else{
			$input = Session::get('deliveryOrder');
		}

		$listEmp 		= GeneralModel::getSelectionList2('hs_hr_employee','emp_number','emp_firstname');
		$listWarehouse 	= GeneralModel::getSelectionList('m_warehouse','id_warehouse','warehouse_name');
		$param = array(
							'title'				=> $this->_pageTitle.' - Confirm Page',
							'title_desc'		=> $this->_pageTitle,
							'menu_id'		=> $this->_menuId,
							'listWarehouse'=> $listWarehouse,
						);

		Session::put('deliveryOrder',$input);

		$projectModel = new ProjectModel;
		$projectData = $projectModel->getRow($input['id_project']);
		$input['project_name'] = $projectData->project_name;
		
		$customerModel = new CustomerModel;
		$customerData = $customerModel->getRow($input['id_customer']);
		$input['customer_name'] = $customerData->customer_name;
		
		$param = array_merge($param,$input);

		return View::make('modules.delivery_order.confirm',$param);
	}
    
    public function doSave()
	{
		$data = Session::get('deliveryOrder');
		$data['action'] = Input::get('action') ? Input::get('action') : $data['action'];

		$data['idBom'] = decode($data['idBom']);
        if($data['action']=='process')
		{
			$model = new $this->_defaultModel;
			$id_trx = $model->insertData($data);

			if($id_trx == true)
			{
				Session::forget('deliveryOrder');
				Session::forget('action');

				$alertArr = array(
									'alert' 			=> 'success',
									'alert_msg' 	=> 'Transaction has been processed',
								);
				return Redirect::to('inventory/delivery_order/detail/'.encode($id_trx))->with($alertArr);
			}
			else
			{
				$alertArr = array(
									'alert' 			=> 'error',
									'alert_msg' 	=> 'Error with message : '.$id_trx.'<br/> Please Contact The Administrator.',
								);
				return Redirect::to('inventory/delivery_order/new')->with($alertArr);
			}
		}
		else
			return Redirect::to('inventory/delivery_order/new/'.encode($data['idBom']))->with('action', 'back');
    }

}