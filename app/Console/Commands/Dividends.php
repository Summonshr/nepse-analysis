<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Goutte;

class Dividends extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrape:dividend';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrape the dividends from sharesansar';

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
        \App\Company::with('dividends')->take(100)->get()->map(function ($company, $index) {
            if ($company->dividends->count() > 0) {
                return;
            }
            $url = 'http://www.nepalipaisa.com/CompanyDetail.aspx/' . $company->code . '/?quote=' . $company->code;
            $crawler = Goutte::request('GET', $url);
            $dividents = array_collapse($crawler->filter('.marketresources_history .overview_dividend tr')->each(function ($node) {
                $node = $node->filter('td')->each(function ($node) {
                    return $node->text();
                });

                return [str_slug($node[0], '_') => $node[1]];
            }));
            if ($dividents == []) {
                return $dividents;
            }
            $dividents['code'] = $company->code;
            return $dividents;
        })->filter()->map(function($dividend){
            return \App\Dividend::forceCreate($dividend);
        });
    }
}
