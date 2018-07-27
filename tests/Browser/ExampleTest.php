<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ExampleTest extends DuskTestCase
{
    /**
     * A basic browser test example.
     *
     * @return void
     */
    public function testBasicExample()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('https://meroshare.cdsc.com.np')
                ->select('dp_id',10600)
                ->type('LoginID','1296023')
                ->type('Password','129cdsshR!1')
                ->click('[type=submit]')
                ->pause(1000)
                ->click('#Portfolio')
                ->pause(1000)
                ->screenshot('Portfolio');
        });
    }
}
