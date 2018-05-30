<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Goutte;

class PromoterShare extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrape:promoter-share';

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
        $crawler = Goutte::request('GET','http://www.nepalstock.com/promoter-share?_limit=1000');
        $promoters = [];
        $crawler->filter('#company-list table tr')->each(function($node, $index) use (&$promoters){
            if($index < 2 || count($node->filter('td')) == 1){
                return;
            }
            $config = [
                'sn',
                'image',
                'name',
                'code',
                'company_link'
            ];
            $new = [];
            $node->filter('td')->each(function($node, $index) use (&$new, $config){
                if($index == 4){
                    $new[$config[$index]] = $node->filter('a')->attr('href');
                    return;
                }

                $new[$config[$index]] = trim($node->text());
            });
            array_push($promoters, $new);
        });
        collect($promoters)->each(function($promoterArray){
            $promoter = \App\Promoter::where($promoterArray)->firstOrNew([]);
            $promoter->forceFill($promoterArray);
            $promoter->save();
        });
    }
}
