<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Goutte;

class TodaysFloorSheet extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrape:todays-floor-sheet';

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
        $crawler = Goutte::request('GET','http://www.nepalstock.com/floorsheet?_limit=10000');
        $sheets = [];
        $crawler->filter('.my-table tr')->each(function($node, $index) use (&$sheets) {
            if($index < 2 || count($node->filter('td')) != 8){
                return;
            } 
            $config = [
                'sn',
                'contract_no',
                'stock_symbol',
                'buyer_broker',
                'seller_broker',
                'quantity',
                'rate',
                'amount',
            ];
            $td = [];

            $node->filter('td')->each(function($node, $index) use (&$td, $config){
              $td[$config[$index]] = $node->html();
            });  
            array_push($sheets, $td);
        });
        
        collect($sheets)->each(function($sheet){
            $new = \App\FloorSheet::where($sheet)->firstOrNew([]);
            $new->forceFill($sheet);
            $new->save();
        });
    }
}
