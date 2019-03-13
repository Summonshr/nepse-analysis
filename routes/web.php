<?php
Route::get('/', function(){
    die('Nothing here');
});
Route::get('growth-graph', 'GrowthGraph');
Route::get('live-data', 'LiveData');
Route::get('companies/{company?}', 'Company');
Route::get('companies/{company}/history', 'History');
Route::get('live-data-single', 'LiveDataSingle');
Route::get('dividends/{code?}', 'Dividend');
Route::get('quarterly-report{s?}','ReportController');
Route::post('quarterly-report','ReportController@save');
Route::get('quarterly-report/{year}/{quarter}','ReportController@report')->middleware('cache');