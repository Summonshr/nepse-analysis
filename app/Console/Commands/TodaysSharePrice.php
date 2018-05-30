<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Goutte;
use Illuminate\Support\Carbon;


class TodaysSharePrice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrape:todays-share-price';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $date = optional(\App\SharePrice::latest()->first())->getAttribute('date') ?? '2010-04-17';
        $date = \Carbon\Carbon::parse($date)->addDay(1)->format('Y-m-d');
        $maxDate = \Carbon\Carbon::parse($date)->addDays(300)->format('Y-m-d');
        $config= [
            'sn',
            'company',
            'no_of_transaction',
            'max_price',
            'min_price',
            'closing_price',
            'traded_shares',
            'amount',
            'previous_closing',
            'difference'
        ];
        while(strtotime($date) < strtotime($maxDate)){
            $date = \Carbon\Carbon::parse($date)->addDay()->format('Y-m-d');
            $crawler = Goutte::request('POST','http://www.nepalstock.com/todaysprice',[], [], ['HTTP_CONTENT_TYPE' => 'application/x-www-form-urlencoded'], 'startDate='.$date.'&_limit=1000&stock-symbol=');
            $prices = [];
            $crawler->filter('#home-contents table tr')->each(function($node, $index) use ($date,&$prices, $config){
                if( $index < 2 || count($node->filter('td')) < 10){
                    return;
                }
                $td = [
                    'date'=> $date
                ];
                $node->filter('td')->each(function($node, $index) use ($config, &$td){
                    $td[$config[$index]] = trim($node->text());
                });
                array_push($prices, $td);
            });
            collect($prices)->each(function($price){
                $new = \App\SharePrice::where($price)->firstOrNew([]);
                $new->forceFill($price);
                $new->save();
            });
        }
    }
}
