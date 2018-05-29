<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Goutte;
use Artisan;
class BrokersList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrape:brokers-list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrape the brokers list';

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
        $crawler = Goutte::request('GET', 'http://www.nepalstock.com/brokers?_limit=200');
        $brokers = [];
        $crawler->filter('#home-contents .container .row .col-md-4 .content')->each(function($node) use (&$brokers){
            $details = [
                'name'=>$node->filter('h4')->html()
            ];
            $config = [
                'location',
                'broker_id',
                'contact_no',
                'email',
                'address',
                'person',
            ];
            $node->filter('div.row-div')->each(function($node ,$index) use (&$details,$config){
                $details[$config[$index]] = trim($node->filter('.row-content')->text());
            });
            array_push($brokers, $details);
        });
        collect($brokers)->each(function($broker){
            $subject = \App\Broker::where('broker_id', $broker['broker_id'])->firstOrNew([]);
            $subject->forceFill($broker);
            $subject->save();
        });
    }
}
