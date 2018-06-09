<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Goutte;

class LiveStock extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrape:livestock';

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
        $crawler = Goutte::request('GET','http://www.nepalstock.com/stocklive');

        $config = [
            'sn',
            'symbol',
            'ltp',
            'ltv',
            'point_change',
            'percentage_change',
            'open',
            'high',
            'low',
            'volume',
            'previous_closing',
        ];
        
        $stocks = [];
        
        $date = trim($crawler->filter('#ticker #date')->html());

        $crawler->filter('#home-contents .col-sm-9 table tr')->each(function($node, $index) use (&$stocks,$date, $config){
            if($index < 1){
                return;
            }
            $td = [
                'time'=>$date,
            ];
            $node->filter('td')->each(function($node, $index) use ($config, &$td){
                $td[$config[$index]] = trim($node->html());
            });

            array_push($stocks, $td);
        });
        
        collect($stocks)->each(function($new){
            $stock = \App\LiveStock::where($new)->firstOrNew([]);
            $stock->forceFill($new);
            $stock->save();
        });
        
    }
}
