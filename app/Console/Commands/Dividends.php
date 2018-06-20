<?php

namespace App\Console\Commands;

use App\Dividend;
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
        \App\Dividend::truncate();
        \App\Company::all()->map(function ($company, $index) {
            echo $index;
            $url = 'http://merolagani.com/CompanyDetail.aspx?symbol=' . $company->code;
            $crawler = Goutte::request('GET', $url);
            $map = array_filter($crawler->filter('#accordion tbody')->each(function ($node, $index) {
                if ($index < 12 || $index > 14) {
                    return;
                }
                return $node->filter('table')->each(function ($node) {
                    return array_filter($node->filter('tr')->each(function ($node, $index) {
                        if ($index < 1) {
                            return;
                        }
                        return $node->filter('td')->each(function ($node) {
                            return trim($node->text());
                        });

                    }));
                });
            }));
            collect($map)->collapse()->each(function ($map, $index) use ($company) {
                $type = [
                    'cash',
                    'bonus',
                    'right'
                ];
                $company->dividend($map, $type[$index]);
            });
        });
    }
}
