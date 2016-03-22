<?php 

class UserGroupController extends BaseController 
{
	protected $_defaultModel = 'UserGroupModel';
	protected $_pageTitle = 'User Group';
	protected $_menuId = 'user_group';
    
	public function __construct()
	{
        
    }
    
    public function index()
	{
		$param = array(
							'title'			=> $this->_pageTitle,
							'title_desc'	=> 'user group list',
							'menu_id'		=> $this->_menuId,
						);
        return View::make('modules.user_group.index',$param);
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
				
				$row->group_name_link = '<a href="'.URL::to('/').'/user_group/detail/'.encode($row->id).'" class="show_detail">'.$r->group_name.'</a> ';
				
				$ubah = $hapus = '';
				if($privi['edit']) 
					$ubah = '<a href="'.URL::to('/').'/user_group/edit/'.encode($row->id).'" class="btn btn-xs purple tooltips" data-original-title="Edit" ><i class="fa fa-pencil"></i></a> ';
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
    
    public function doDelete()
	{
        $id = intval(Input::get('id'));
		$model = new $this->_defaultModel;
		if($model->hasUserAcct($id))
		{
			$ret = array(
						'status'		=> false,
						'alert_msg'	=> 'The selected user group has user accounts, cannot be deleted.',
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
    
    public function showNewEdit($id_group=null)
	{	
		$do = Request::segment(2);
		$data = $user_privi = array();
		if($do == 'new')
		{
			$title = 'New User Group';
			$desc = 'create new user group';
		}
		else
		{
			$title = 'Edit User Group';
			$desc = 'edit user group';
			$model = new $this->_defaultModel;
			$data = toArray($model->getDataRow($id_group));
			if(!$data)
				return Redirect::to('missing');
		}
		
		$param = array(
							'title'					=> $title,
							'title_desc'			=> $desc,
							'menu_id'				=> $this->_menuId,
							'do'						=> $do,
							'id_group'				=> $id_group,
							'data'					=> $data
						);
		
        return View::make('modules.user_group.form.index')->with($param);
    }
    
    public function doValidate()
	{
        $input = Input::all();
		$validate = true;
		
		$errorArr = array();
		$alertMsg = 'Error occured. Please check again.';
                
        $rules = array(
						'group_name' 		=> 'required',
					  );
		
		if(isset($input['privi']))
		{
		
		}
		else
		{
			$validate = false;
			$alertMsg = 'Please select privileges';
		}
        
        $validator = Validator::make($input, $rules);
		if($validator->fails())
		{
			foreach($rules as $key=>$row)
			{
				$errorArr[$key] = $validator->messages()->first($key);
			}
			$validate = false;
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
    
    public function doSave()
	{
        $data = Input::all();
		
		$model = new $this->_defaultModel;
		if( $data['do']=='new')
		{
			$id_trx = $model->insertData($data);
			$alert_msg = 'Successfully added.';
		}
		else
		{
			$id_trx = $model->updateData($data);
			$alert_msg = 'Successfully updated.';
		}
		
		$alertArr = array(
							'alert' 			=> 'success',
							'alert_msg' 	=> $alert_msg,
						);
		return Redirect::to('user_group')->with($alertArr);
    }
}