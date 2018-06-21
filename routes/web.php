<?php
Route::view('/', 'welcome');

Route::get('live-data', function () {
    return collect()->put('data', \App\LiveStock::where('time', \App\LiveStock::latest()->select('time')->first()->time)->get()->unique(function ($stock) {
        return $stock->time . $stock->symbol;
    }));
});

Route::get('companies/{company?}', function ($companyCode = null) {
    return collect()->put('companies', $companyCode ? \App\Company::where('code', $companyCode)->first() : cache()->remember('companies-all', 100, function () {
        return \App\Company::with('dividends')->get()->map->toApi();
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


Route::get('dividends/{code?}', function ($code = null) {
    if ($code) {
        return \App\Dividend::where('code', $code)->get();
    }
    return \App\Dividend::all();
});


Route::get('articles', function(){
    return [
        [
            'group'=>'beginner',
            'articles'=>[
                [
                    'name'=>'What is a "Stock"',
                    'url'=>'https://www.investopedia.com/terms/s/stock.asp'
                ],
                [
                    'name'=>'How to Invest in Stocks?',
                    'url'=>'https://www.nerdwallet.com/blog/investing/how-to-invest-in-stocks/'
                ],
                [
                    'name'=>'The Complete Beginner\'s Guide to Investing in Stock',
                    'url'=>'https://www.thebalance.com/the-complete-beginner-s-guide-to-investing-in-stock-358114'
                ]
            ]
        ],
        [
            'group'=>'Intermediate',
            'articles'=>[
                [
                    'name'=>'What is a "Stock"',
                    'url'=>'https://www.investopedia.com/terms/s/stock.asp'
                ],
                [
                    'name'=>'How to Invest in Stocks?',
                    'url'=>'https://www.nerdwallet.com/blog/investing/how-to-invest-in-stocks/'
                ],
                [
                    'name'=>'The Complete Beginner\'s Guide to Investing in Stock',
                    'url'=>'https://www.thebalance.com/the-complete-beginner-s-guide-to-investing-in-stock-358114'
                ]
            ]
        ]
    ];
});

Route::view('articles/what-is-stock','articles.what-is-stock');
