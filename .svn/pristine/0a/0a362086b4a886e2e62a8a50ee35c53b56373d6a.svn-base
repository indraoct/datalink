<?php

Class Accounting
{    
	protected $_owner;

	public function __construct($owner)
	{
		$this->_owner	= $owner;
	}
	
	public function createTrx($param,$paramDetail)
	{
        $insertTrx = array(
		
            'owner' 		=> $this->_owner,
            'trx_code'	=> $param['trx_code'],
            'trx_date'		=> $param['trx_date'],
            'id_ref'			=> $param['id_ref'],
            'no_ref'		=> $param['no_ref'],
            'notes' 		=> $param['notes'],
            'created_at'	=> DB::raw('now()')

        );
		
		$id_trx = DB::table('t_trx_journal')->insertGetId($insertTrx);
		
		$insertTrxDetail = array();
		foreach($paramDetail as $row)
		{
			$id_warehouse = $row['id_warehouse'];
			$id_acct = $row['id_acct'];
			$total_debit = isset($row['debit']) ? $row['debit'] : 0;
			$total_kredit = isset($row['kredit']) ? $row['kredit'] : 0;
		
			$remaining_stock = $this->updateAcctBalance($id_warehouse,$id_acct,$total_debit,$total_kredit);
		
			$insertTrxDetail[] = array(
			
				'id_trx'						=> $id_trx,
				'id_warehouse'		=> $id_warehouse,
				'id_acct'					=> $id_acct,
				'total_debit'				=> $total_debit,
				'total_kredit'			=> $total_kredit,
				'remaining_stock'	=> $remaining_stock,
				
			);
		}
		
		DB::table('t_trx_journal')->insert($insertTrxDetail);
	}
	
	function updateAcctBalance($id_warehouse,$id_acct,$total_debit,$total_kredit)
	{
		$dataAcct = DB::table('t_account_balance')
								->where('owner',$this->_owner)
								->where('id_warehouse',$id_warehouse)
								->where('id_acct',$id_acct)
								->first();
		if($dataAcct)
		{
			$initial_balance = $dataAcct->initial_balance;
			$total_debit += $dataAcct->total_debit;
			$total_kredit += $dataAcct->total_kredit;
			$updateArr = array(
				'total_debit'	=> $total_debit,
				'total_kredit'	=> $total_kredit
			);
		   
			DB::table('t_account_balance')
					->where('id',$dataAcct->id)
					->update($updateArr);
		}
		else
		{
			$initial_balance = 0;
			$insertArr = array(
							'owner' 				=> $this->_owner,
							'id_warehouse' 	=> $id_warehouse,
							'id_acct'				=> $id_acct,
							'initial_balance'	=> $initial_balance,
							'total_debit' 		=> $total_debit,
							'total_kredit' 		=> $total_kredit,
						);
						
			DB::table('t_account_balance')->insert($insertArr);
		}
		
		return ($initial_balance + $total_debit - $total_kredit);
	}    
}
