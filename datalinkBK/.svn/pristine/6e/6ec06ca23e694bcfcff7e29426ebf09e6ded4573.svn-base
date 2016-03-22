<?php


Route::group(array('prefix'=>'admin'),function(){


    Route::get('/',function(){

        //Session::put('back_page', 'dashboard');
        return View::make('back.dashboard',array('title'=>'Dashboard'));
    });

    Route::get('/login',function(){

        Session::put('back_page', 'login');
        return View::make('back.login',array('title'=>'Login'));
    });



    Route::post('/add-user',array('uses'=>'AuthController@doAddGroup'));
    Route::post('/add-group',array('uses'=>'AuthController@doAddUser'));

});
