<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Goutte;
use App\News;
use App\Company;
use Carbon\Carbon;

class FetchNews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:news';

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
        $urls = [
            [
                'url' => 'https://www.sharesansar.com/ajaxcompanyannouncement?page=1&companyid=',
                'type' => 'announcements'
            ],
            [
                'url' => 'https://www.sharesansar.com/ajaxcompanycategorynews/financial-analysis?page=1&companyid=',
                'type' => 'financials'
            ],
            [
                'url' => 'https://www.sharesansar.com/ajaxcompanynews?companyid=',
                'type' => 'news'
            ],
            [
                'url' => 'https://www.sharesansar.com/ajaxcompanyevents?companyid=',
                'type' => 'events'
            ]
        ];

        $urls = collect($urls);
        Company::all()->map(function ($company) use ($urls) {
            $urls->map(function ($e) use ($company) {
                $data = Goutte::setHeader('X-Requested-With', 'XMLHttpRequest')->request('GET', $e['url'] . $company->share_sansar_id);
                $data->filter('tbody tr')->each(function ($node) use ($e, $company) {
                    $arr = ['type' => $e['type'], 'code' => $company->code];
                    $node->filter('td')->each(function ($node, $index) use (&$arr) {
                        if ($index == 0) {
                            $arr['date'] = strip_tags($node->html());
                        }
                        if ($index == 1) {
                            $arr['href'] = $node->filter('a')->extract('href')[0];
                            $arr['text'] = $node->filter('a')->html();
                        }
                    });
                    if ($arr['date'] != ' No Record Found.' && !News::where($arr)->exists()) {
                        $news = new News();
                        $news->forceFill($arr);
                        $news->save();
                    }
                });
            });
        });
    }
}
