<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Goutte;
use Illuminate\Support\Facades\Artisan;

class ScrapeController extends Controller
{
    public function start(){
        Artisan::call('scrape:todays-share-price');   
        dd('here');
        $crawler = Goutte::request('GET', 'http://www.nepalstock.com/');
        return $crawler->filter('table')->each(function($node){
            if($node->attr('id')){
                Artisan::call('scrape:'.$node->attr('id'));   
                return; 
            }
        });
    }
}
