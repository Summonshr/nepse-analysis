<?php


Route::domain('{domain}.scraper.test')->group(function(){

    Auth::routes();
    
    Route::group(['middleware'=>'auth'], function(){
        Route::get('/home', 'HomeController@index')->name('home');
        Route::get('companies', 'CompanyController@index');  
        Route::view('/','home');
        Route::redirect('home','/');
    });
    
});