<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class History extends Controller
{
    public function __invoke($companyCode = null)
    {
        return collect()->put('histories', \App\Company::where('code', $companyCode)->first()->history()->select(['amount', 'closing_price', 'date', 'no_of_transaction', 'traded_shares', 'max_price', 'min_price'])->get());
    }
}
