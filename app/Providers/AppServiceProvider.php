<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use DB;
use Collection;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
       \DB::listen(function($sql){
           logger($sql->sql);
       });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
