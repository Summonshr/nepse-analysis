<?php

namespace App\Http\Controllers;

class QuarterlyReport extends Controller
{
    public function display(){
        return \App\Company::with('report')->get()->map->toReport();
    }
}
