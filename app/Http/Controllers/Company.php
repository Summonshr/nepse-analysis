<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Company extends Controller
{
    public function __invoke($companyCode = null)
    {
        return collect()->put('companies', $companyCode ? \App\Company::where('code', $companyCode)->first() : cache()->remember('companies-all-' . request('minimal', 5), 10, function () {
            return \App\Company::with(['dividends','report'])->get()->map->toApi();
        }));
    }
}
