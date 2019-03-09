<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Dividend extends Controller
{
    public function __invoke($code = null)
    {
        if ($code) {
            return \App\Dividend::where('code', $code)->get();
        }
        return \App\Dividend::all();
    }
}
