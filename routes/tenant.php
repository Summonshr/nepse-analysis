<?php


Route::domain('{domain}.scraper.test')->group(function(){
    Route::get('/','ProfileController@show');
    Route::get('companies', 'CompanyController@index');
});