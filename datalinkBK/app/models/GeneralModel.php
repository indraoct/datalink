<?php

class GeneralModel {

    public static function getSelectionList($table, $idCol, $nameCol, $whereArr = null) {
        $query = DB::table($table)
                ->select($idCol, $nameCol)
                ->where('is_deleted', 0)
                ->where('owner', Crypt::decrypt(Session::get('owner')));

        if ($whereArr) {
            foreach ($whereArr as $col => $val) {
                $opval = explode(' ', $val);
                $query->where($col, $opval[0], $opval[1]);
            }
        }
        $data = $query->orderBy($nameCol)->get();

        return listArray($data, $idCol, $nameCol);
    }

    /*
     * no need is_deleted & owner
     * @author : Indra Octama
     * @date : 24 november 2014
     */

    public static function getSelectionList2($table, $idCol, $nameCol, $whereArr = null) {
        $query = DB::table($table)
                ->select($idCol, $nameCol);

        if ($whereArr) {
            foreach ($whereArr as $col => $val) {
                $opval = explode(' ', $val);
                $query->where($col, $opval[0], $opval[1]);
            }
        }
        $data = $query->orderBy($nameCol)->get();

        return listArray($data, $idCol, $nameCol);
    }

    public static function customGetSelectionList($table, $idCol, $nameCol, $txt, $whereArr = null) {
        $query = DB::table($table)
                ->select($idCol, $nameCol);

        if ($whereArr) {
            foreach ($whereArr as $col => $val) {
                $opval = explode(' ', $val);
                $query->where($col, $opval[0], $opval[1]);
            }
        }
        $data = $query->orderBy($nameCol)->get();

        return customListArray($data, $idCol, $nameCol, $txt);
    }

    public static function getCcy() {
        $query = DB::table('hs_hr_currency_type')
                ->select('code', 'currency_id');
        $data = $query->orderBy('currency_id')->get();

        return listArray($data, 'code', 'currency_id');
    }

    public static function getSelection($table, $idCol, $nameCol, $whereArr = null) {
        $query = DB::table($table)
                ->select($idCol, $nameCol);

        if ($whereArr) {
            foreach ($whereArr as $col => $val) {
                $opval = explode(' ', $val);
                $query->where($col, $opval[0], $opval[1]);
            }
        }
        $data = $query->orderBy($nameCol)->get();

        return listArray($data, $idCol, $nameCol);
    }

    public static function getPRNo() {
        $query = DB::table('t_purchase_request')
                ->select(DB::raw('MAX(pr_no) AS no'))
                ->where('is_deleted', 0);

        $no = $query->first()->no;

        if (!$no || substr($no, 0, 2) != date('y')){
            return date('y') . str_pad(1, 4, "0", STR_PAD_LEFT);
        }elseif($no){
            return $no + 1;
        }
    }
    
    public static function getPONo() {
        $query = DB::table('t_purchase_order')
                ->select(DB::raw('MAX(po_no) AS no'))
                ->where('is_deleted', 0);
        $no = $query->first()->no;
        if (!$no || substr($no, 0, 2) != date('y')) {
            return date('y') . str_pad(1, 4, 0, STR_PAD_LEFT);
        }
        return ++$no;
    }

    public static function getTrxNo($table,$col) {
        $query = DB::table($table)
                ->select(DB::raw('MAX('.$col.') AS no'))
                ->where('is_deleted', 0)
				->where('owner', Crypt::decrypt(Session::get('owner')));

        $no = $query->first()->no;

        if (!$no || substr($no, 0, 2) != date('y'))
            return date('y') . str_pad(1, 4, "0", STR_PAD_LEFT);
        else if ($no)
            return $no + 1;
    }
    
    public static function getTable($table, $where){
        $query = DB::table($table);
        if ($where) {
            foreach ($where as $col => $val) {
                $opval = explode(' ', $val);
                $query->where($col, $opval[0], $opval[1]);
            }
        }

        return $query;
    }

    // M - Ambil 1 baris data dari table
    public static function singleRow($table, $select, $where) {
        $query = DB::table($table)
                ->select($select);

        if ($where) {
            foreach ($where as $col => $val) {
                $opval = explode(' ', $val);
                $query->where($col, $opval[0], $opval[1]);
            }
        }

        return $query->first();
    }

    public static function insertData($table, $data) {
        try {
            DB::beginTransaction();
            $query = DB::table($table)->insert($data);
            DB::commit();

            return $query;
        } catch (Exception $e) {
            DB::rollback();
            return $e->getMessage();
        }
        return false;
    }

}
