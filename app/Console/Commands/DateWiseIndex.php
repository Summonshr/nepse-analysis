<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Goutte;
use App\SubIndex;

class DateWiseIndex extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrape:datewise-index';

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
        $crawler = Goutte::request('GET','http://www.nepalstock.com/indices');
        $crawler->filter('.index')->each(function($node){
            $node->filter('option')->each(function($node){
                $crawler = Goutte::request('GET','http://www.nepalstock.com/indices?index='.$node->attr('value'));
                $indexes = [];
                $crawler->filter('#home-contents table tr')->each(function($node, $index) use (&$indexes){
                    if($index < 2){
                        return;
                    }
                    $config = [
                        'sn',
                        'date',
                        'sub_index',
                        'absolute_change',
                        'percentage_change',
                    ];
                    $td = [];
                    $node->filter('td')->each(function($node, $index) use (&$config, &$td){
                        $td[$config[$index]] = $node->html();
                    });
                    array_push($indexes, $td);
                });
                collect($indexes)->each(function($index) use ($node){
                    $ind = SubIndex::where('index_type', $node->html())->where($index)->firstOrNew([]);
                    $ind->index_type = $node->html();
                    $ind->forceFill($index);
                    $ind->save(); 
                });
            });
        });
    }
}
