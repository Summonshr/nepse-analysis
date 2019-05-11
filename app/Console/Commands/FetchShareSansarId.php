<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Goutte;

class FetchShareSansarId extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrape:sharesansar-id';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrape share sansar site to get their repective IDs';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
 
    }
}
