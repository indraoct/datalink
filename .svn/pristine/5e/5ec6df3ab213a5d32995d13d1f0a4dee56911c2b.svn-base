<?php 

class DeliveryOrderController extends BaseController 
{
	protected $_defaultModel = 'DeliveryOrderModel';
	protected $_pageTitle = 'Delivery Order';
	protected $_menuId = 'delivery_order';
    
	public function __construct()
	{
        
    }

    public function index()
	{
		if (Request::isMethod('post'))
		{
			$allParams = Input::all();
			$export = isset($allParams['export']) ? $allParams['export'] : null;
			$exportHeader = array('Gr No','Gr Date','Vendor','No Ref PO');

			if( $export == 'xls' || $export == 'csv' )
			{
				$data[] = $exportHeader;
				$data 	= array_merge($data,$this->getData());

				Excel::create('PurchaseReport'.$allParams['report'], function($excel) use($data) {
					$excel->sheet('Sheetname', function($sheet) use($data) {
						$sheet->fromArray($data);
					});
				})->export($export);
			}
			else if( $export == 'pdf' )
			{
				$data 	= $this->getData();
				$exportHeader = array('Gr No','Gr Date','Vendor','No Ref PO');
				$paramPdf['tableHeader'] = $exportHeader;
				$paramPdf['data'] = $data;

				$destination = public_path().'/';
				$fileName = 'PurchaseReport'.$allParams['report'].'.pdf';
				$pdf = App::make('dompdf');
				$pdf->loadView('pdf.datatable',$paramPdf)
						->save($destination.$fileName);
				header('Content-Description: File Transfer');
				header('Content-Type: application/octet-stream');
				header('Content-Disposition: attachment; filename="'.$fileName.'"');
				header('Content-Transfer-Encoding: binary');
				header('Expires: 0');
				header('Cache-Control: must-revalidate');
				header('Pragma: public');
				header('Content-Length: ' . filesize($destination.$fileName));

				readfile($destination.$fileName);
	    		@unlink($destination.$fileName);
			}
		}
		$listCustomer 	= GeneralModel::getSelectionList('m_customer','id_customer','customer_name');
		$listProject 	= GeneralModel::getSelectionList('m_project','id_project','project_name');
		// $listBOM 	= GeneralModel::getSelectionList('t_purchase_order','id_po','po_no');
		// $listBOM = array_combine(
												// array_map(function($value) { return encode($value); }, array_keys($listBOM))
												// ,array_map(function($value) { return 'BOM-'.$value; }, $listBOM)
											// );
		$model = new $this->_defaultModel;
		$listBOM = $model->selectionBOM();

		$param = array(
							'title'			=> $this->_pageTitle,
							'title_desc'	=> 'Delivery Order',
							'listCustomer'	=> array('' => '-- Any Value --') + $listCustomer,
							'listProject'	=> array('' => '-- Any Value --') + $listProject,
							'listBOM'	=> array('' => '-- Please Select --') + $listBOM,
							'menu_id'		=> $this->_menuId,
						);
        return View::make('modules.delivery_order.index',$param);
    }

	public function getData()
	{
		$privi['edit'] = hasPrivilege($this->_menuId,'edit');
		$privi['delete'] = hasPrivilege($this->_menuId,'delete');
	
		$param = Input::all();

		$iDisplayLength = intval( (isset($param['length'])) ? $param['length'] : 0);
		$limit = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
		$offset = intval( (isset($param['start'])) ? $param['start'] : 0);
		$draw = intval( (isset($param['draw'])) ? $param['draw'] : 0);
		$sortBy = (isset($param['order'][0]['column'])) ? $param['order'][0]['column'] : 0;
		$sortDir = (isset($param['order'][0]['dir'])) ? $param['order'][0]['dir'] : 'asc';
		$filter = isset($param['filter']) ? $param['filter'] : array();
		$model = new $this->_defaultModel;

		$get = $model->getData($filter, $limit, $offset, $sortBy, $sortDir);
		$data = array();
		if(Request::ajax()){
			$n = $offset + 1;
			foreach($get['data'] as $key=>$r)
			{
				$row = $r;
				
				$row->id = $r->$get['id'];
				unset($row->$get['id']);
				$row->trx_no = '<a href="'.URL::to('/').'/inventory/delivery_order/detail/'.encode($row->id).'" class="show_detail">PO-'.$r->trx_no.'</a>';
				$ubah = $hapus = '';
				if($privi['edit']) 
					$ubah = '<a href="javascript:;" class="btn btn-xs purple tooltips show_edit" data-original-title="Edit" ><i class="fa fa-pencil"></i></a> ';
				if($privi['delete'])
					$hapus = '<a href="javascript:;" class="btn btn-xs red tooltips do_delete" data-original-title="Hapus"><i class="fa fa-trash-o"></i></a> ';
				
                $row->trx_date = displayDate($r->trx_date);
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
		else if (Request::isMethod('post')){
			foreach($get['data'] as $key=>$r)
			{
				unset($r->id_trx);
				$row = $r;
				/* $row['trx_no'] = $r->trx_no;
                $row['vendor_name'] = $r->vendor_name;
                $row['no_ref'] = $r->no_ref; */
                $row->trx_date = displayDate($r->trx_date);
				
				$data[$key] = (array) $row;
			}
			return $data;
		}
	}

	public function showDetail($id)
	{
		$id = decode($id);
		$model = new $this->_defaultModel;
		$data = $model->getDO($id);

		if(empty($data['master']))
		{
			$param = array(
								'title'			=> 'Page 404',
								'menu_id'		=> 'missing'
							);
			return Response::view('errors.missing_admin', $param, 404);
		}
		$listWarehouse 	= GeneralModel::getSelectionList('m_warehouse','id_warehouse','warehouse_name');

		$param = array(
							'title'				=> $this->_pageTitle,
							'title_desc'	=> 'Delivery Order Detail',
							'menu_id'	=> $this->_menuId,
							'data'			=> $data['master'],
							'item'			=> $data['detail'],
							'listWarehouse'			=> $listWarehouse,
						);
        return View::make('modules.delivery_order.detail',$param);
    }

}