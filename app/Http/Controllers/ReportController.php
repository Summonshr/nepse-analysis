<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Report;

class ReportController extends Controller
{
    public function index()
    {
        return view('report.index');    
    }

    public function show(Request $request, $code)
    {
        $company = \App\Company::with('report')->where('code', $code)->first();

        if(!$company){
            return view('report.index');
        }

        return view('report.show',['company'=>$company]);    
    }

    public function update(Request $request, $code)
    {
        
        $report = Report::where('code', $code)->firstOrNew(['code'=>$code]);
        $report->{$request->get('type' )} = $request->get('value');
        $report->save();

        return ['status'=>'updated'];
    }
}
