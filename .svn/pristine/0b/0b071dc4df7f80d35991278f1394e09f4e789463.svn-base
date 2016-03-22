<?php

class CustomerModel {

	protected $_table = 'm_customer';
	protected $_id = 'id_customer';

	public function getData($filter, $limit, $offset, $sortBy, $sortDir)
	{
		$data['id'] = $this->_id;
	
		$tableColumn = array($this->_id, 'customer_name', 'address', 'telephone');
		
		$query = DB::table($this->_table)
						->select($tableColumn)
						->where('is_deleted',0)
						->where('owner', Crypt::decrypt(Session::get('owner')));
		
		$data['count'] = $data['filter_count'] = $query->count();
		
		if($filter)
		{
			if($filter['customer_name'])
				$query->where('customer_name', 'LIKE', '%'.$filter['customer_name'].'%');
			if($filter['address'])
				$query->where('address', 'LIKE', '%'.$filter['address'].'%');
			if($filter['telephone'])
				$query->where('telephone', 'LIKE', '%'.$filter['telephone'].'%');
				
			$data['filter_count'] = $query->count();
		}
		
		$data['data'] = $query->orderBy($tableColumn[$sortBy],$sortDir)->skip($offset)->take($limit)->get();
		
		return $data;
	}

	public function insertData($data)
	{
		try
		{
			DB::beginTransaction();
			$insertCustomer = array(
				'owner' => Crypt::decrypt(Session::get('owner')),
				'id_category'	=> $data['custCategory'],
				'id_sales_person'	=> $data['salesPerson'],
				'customer_name'	=> $data['custName'],
				'description'	=> $data['desc'],
				'address'	=> $data['addr'],
				'telephone'	=> $data['phone'],
				'fax'	=> $data['fax'],
				
				'bank_name'	=> $data['bank'],
				'bank_acct_no'	=> $data['bankAcct'],
				'bank_acct_name'	=> $data['acctName'],
				'npwp'	=> $data['npwp'],
				'pkp'	=> $data['pkp'],
				'pkp_date'	=> defaultDate($data['pkpDate']),
				'tax'	=> ($data['rInterval']) ? 1 : 0,
				'currency'	=> $data['rCcy'],
				'term_of_payment'	=> $data['rTop'],
				'credit_interval'	=> ($data['rInterval']) ? $data['rInterval'] : 0,
				'credit_plafond'	=> ($data['rPlafond']) ? (defaultNumeric($data['rPlafond'])) : 0,
				'account_receivable'	=> $data['rReceive'],

				'is_deleted'=> 0,
				'created_by' => Session::get('user_name'),
				'created_at'=> DB::raw('now()')
			);

		   $id = DB::table($this->_table)->insertGetId($insertCustomer);
		   $insertContact = array(
				'owner' => Crypt::decrypt(Session::get('owner')),
				'id_customer'	=> $id,
				'contact_title'	=> $data['contTitle'],
				'contact_name'	=> $data['contName'],
				'position'	=> $data['contPosition'],
				'address'	=> $data['contAddr'],
				'phone_mobile'	=> $data['contMobilePhone'],
				'phone_work'	=> $data['contPhone'],
				'email'	=> $data['contEmail'],
				
				'is_deleted'=> 0,
				'created_by' => Session::get('user_name'),
				'created_at'=> DB::raw('now()')
			);
		   DB::table('m_customer_contact')->insertGetId($insertContact);
		   DB::commit();
		}catch(Exception $e)
		{
			DB::rollback();
			return $e->getMessage();
		}
        return false;
	}

	public function deleteData($id)
	{
        $updateArr = array(

            'is_deleted'	=> 1,
            'updated_by' => Session::get('user_name'),
            'updated_at'=> DB::raw('now()'),
            'deleted_at'=> DB::raw('now()')
        );
       
		DB::table($this->_table)
							->where($this->_id,$id)
							->update($updateArr);
		DB::table('m_customer_contact')
							->where($this->_id,$id)
							->update($updateArr);
        return true;
	}

	public function getDetail($id)
	{
		$queryCustomer = DB::table($this->_table)
						->select('*',DB::raw('date(pkp_date) as pkpDate'))
						->where('is_deleted',0)
						->where('id_customer',$id)
						->where('owner', Crypt::decrypt(Session::get('owner')));

		$data['customer'] = $queryCustomer->first();

		if($data['customer'])
		{
			$queryCustContact = DB::table('m_customer_contact')
													->select('*')
													->where('is_deleted',0)
													->where('id_customer',$id)
													->where('owner', Crypt::decrypt(Session::get('owner')));
			
			$data['contact'] = $queryCustContact->first();
		}
		else
		{
			$data = false;
		}

		return $data;
	}
	
	public function updateData($data)
	{
		if(empty($data['id'])) return 'Data Not Found';
		try
		{
			DB::beginTransaction();
			$updateCustomer = array(
				'id_category'	=> $data['custCategory'],
				'id_sales_person'	=> $data['salesPerson'],
				'customer_name'	=> $data['custName'],
				'description'	=> $data['desc'],
				'address'	=> $data['addr'],
				'telephone'	=> $data['phone'],
				'fax'	=> $data['fax'],
				
				'bank_name'	=> $data['bank'],
				'bank_acct_no'	=> $data['bankAcct'],
				'bank_acct_name'	=> $data['acctName'],
				'npwp'	=> $data['npwp'],
				'pkp'	=> $data['pkp'],
				'pkp_date'	=> defaultDate($data['pkpDate']),
				'tax'	=> ($data['rInterval']) ? 1 : 0,
				'currency'	=> $data['rCcy'],
				'term_of_payment'	=> $data['rTop'],
				'credit_interval'	=> ($data['rInterval']) ? $data['rInterval'] : 0,
				'credit_plafond'	=> ($data['rPlafond']) ? (defaultNumeric($data['rPlafond'])) : 0,
				'account_receivable'	=> $data['rReceive'],

				'updated_by' => Session::get('user_name'),
				'updated_at'=> DB::raw('now()')
			);

		   DB::table($this->_table)->where($this->_id,$data['id'])->update($updateCustomer);

		   $updateContact = array(
				'owner' => Crypt::decrypt(Session::get('owner')),
				'contact_title'	=> $data['contTitle'],
				'contact_name'	=> $data['contName'],
				'position'	=> $data['contPosition'],
				'address'	=> $data['contAddr'],
				'phone_mobile'	=> $data['contMobilePhone'],
				'phone_work'	=> $data['contPhone'],
				'email'	=> $data['contEmail'],

				'updated_by' => Session::get('user_name'),
				'updated_at'=> DB::raw('now()')
			);
		   DB::table('m_customer_contact')->where($this->_id,$data['id'])->update($updateContact);
		   DB::commit();
		}catch(Exception $e)
		{
			DB::rollback();
			return $e->getMessage();
		}
        return true;
	}

	public function getRow($id)
	{		
		$query = DB::table($this->_table)
						->where('is_deleted',0)
						->where($this->_id,$id)
						->where('owner', Crypt::decrypt(Session::get('owner')));
		
		return $query->first();
	}

	public function hasProject($id)
	{
		$data = DB::table('m_project')
						->select('id_customer')
						->where($this->_id,$id)
						->where('owner', Crypt::decrypt(Session::get('owner')))
						->where('is_deleted',0)
						->get();
       
        return $data;
	}
}
