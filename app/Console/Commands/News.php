<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class News extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrape:news';

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
        // http://sharesansar.com/category/ipo-fpo-news
        // http://sharesansar.com/category/dividend-right-bonus
        // http://sharesansar.com/category/share-listed
        // http://sharesansar.com/category/expert-speak
        // http://sharesansar.com/category/financial-analysis
    }
}
