<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class History extends Controller
{
    public function __invoke($companyCode = null)
    {
        $json = cache()->remember('history-'.$companyCode, 36000, function() use ($companyCode) {
            return collect()->put('histories', \App\Company::where('code', $companyCode)->first()->history()->select(['amount', 'closing_price', 'date', 'no_of_transaction', 'traded_shares', 'max_price', 'min_price'])->get());
        });
        return response()
            ->json($json)
            ->header("pragma", "private")
            ->header("Cache-Control", " private, max-age=86400");
    }
}
