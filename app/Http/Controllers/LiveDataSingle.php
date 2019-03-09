<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LiveDataSingle extends Controller
{
    public function __invoke()
    {
        $store = new \App\LiveStock();
        return collect()->put('single', \App\LiveStock::where('time', 'like', "%" . request('time') . "%")->where('symbol', request('symbol'))->get()->reject(function ($stock) use (&$store) {
            if ($stock->ltp != $store->ltp) {
                $store = $stock;
                return false;
            }
            return true;
        })->values());
    }
}
