<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Company extends Controller
{
    public function __invoke($companyCode = null)
    {
        return collect()->put('companies', $companyCode ? array_only(\App\Company::with('report')->where('code', $companyCode)->first()->toArray(), ['code','name','report','profile','type']) : cache()->remember('companies-all-' . request('minimal', 5), 10, function () {
            return \App\Company::with('dividends')->get()->map->toApi();
        }));
    }
}
