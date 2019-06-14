<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Goutte;
use App\News;

class FetchPhotos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:photos';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        News::whereNull('description')->get()->map(function($e){
            $data = Goutte::request('GET', $e['href']);
            $node =$data->filter('#announcement-image');
            if($node->extract('src')[0] ?? false) {
                $e->description = $node->extract('src')[0];
                $e->save();
            }
        });
       
    }
}
