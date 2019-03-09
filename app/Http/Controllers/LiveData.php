<?php

namespace App\Http\Controllers;
use App\LiveStock;

class LiveData extends Controller
{
    public function __invoke()
    {
        return collect()->put('data', LiveStock::where('time', LiveStock::latest()->select('time')->first()->time ?? '')->get()->unique(function ($stock) {
            return $stock->time . $stock->symbol;
        }));
    }
}
