<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Goutte, Artisan;

class Calculation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrape:calculation';

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
        $crawler = Goutte::request('GET', 'http://www.nepalstock.com/calculation?startDdate=2018-05-01&endDate=2018-05-30');
        dd($crawler);
    }
}
