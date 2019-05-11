<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NewsReport extends Controller
{
    public function display($code)
    {
        return ['data'=> \App\News::where('code', $code)->get()];
    }
}
