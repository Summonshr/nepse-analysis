<?php

namespace App\Http\Controllers;

class QuarterlyReport extends Controller
{
    public function display(){
        return \App\Company::with(['report','history'=>function($query){
            $query->orderByDesc('id')->take(1);
        }])->get()->map->toReport();
    }
}
