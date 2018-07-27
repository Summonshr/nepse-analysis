<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class Scraper extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testLoginFeature()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('https://meroshare.cdsc.com.np')
                ->click('#btn_companyprofile_dividend')
                ->see('Bonus Share');
        });
    }
}
