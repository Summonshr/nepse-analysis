<?php

dd(Artisan::call('fetch:news'));

Route::group(['middleware'=>'cache'], function(){
    Route::get('/', function(){
        die('Nothing here');
    });
    Route::get('growth-graph', 'GrowthGraph');
    Route::get('live-data', 'LiveData');
    Route::get('company.json','Company');
    Route::get('company/{company}.json', 'Company');
    Route::get('history/{company}.json', 'History');
    Route::get('live-data-single', 'LiveDataSingle');
    Route::get('dividends/{code}.json', 'Dividend');
    Route::get('report.json','QuarterlyReport@display');
});
Route::get('report','ReportController@index');
Route::get('report/{code}','ReportController@show');
Route::put('report/{code}','ReportController@update');
Route::get('sample',function(){
    Artisan::call('scrape:todays-share-price');
    Artisan::call('cache:clear');
    return redirect("report.json");
});