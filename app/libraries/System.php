<?php

/*Additional library...
 * Add anythonh you want
 * Call everywhere with System::method($params)
 * */

Class System{
    
    public static function setting($param){
        
       return DB::table('t_setting')->pluck($param);
       
       // return "Academic System";
    }
    

    
    
}
