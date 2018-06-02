<?php




Route::view('/', 'welcome');

Route::get('live-data', function () {
    return collect()->put('data', \App\LiveStock::where('time', \App\LiveStock::latest()->select('time')->first()->time)->get()->unique(function ($stock) {
        return $stock->time . $stock->symbol;
    }));
});

Route::get('companies/{company?}', function ($companyCode = null) {
    return collect()->put('companies', $companyCode ? \App\Company::where('code', $companyCode)->first() : cache()->remember('companies-all', 100, function () {
        return \App\Company::all()->map->toApi();
    }));
});

Route::get('companies/{company}/history', function ($companyCode = null) {
    return collect()->put('histories', \App\Company::where('code', $companyCode)->first()->history()->select(['amount', 'closing_price', 'date', 'no_of_transaction', 'traded_shares', 'max_price', 'min_price'])->get());
});

Route::get('live-data-single', function () {
    $store = new \App\LiveStock();
    return collect()->put('single', \App\LiveStock::where('time', 'like', "%" . request('time') . "%")->where('symbol', request('symbol'))->get()->reject(function ($stock) use (&$store) {
        if ($stock->ltp != $store->ltp) {
            $store = $stock;
            return false;
        }
        return true;
    })->values());
});

Route::view('live-stock', 'live');