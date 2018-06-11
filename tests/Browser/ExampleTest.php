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
            $browser->visit('http://eightythree.test/castings/selfie-women')
            ->click('.get-in-touch')
            ->pause(1000)
            ->type('email','summonshr@gmail.com')
            ->type('name','Suman Shrestha')
            ->type('address','Address of a user')
            ->click('.sw-btn-next')
            ->pause(1000)
            ->assertSee('This value is required.');

            $browser->visit('http://eightythree.test/castings/selfie-women')
            ->click('.get-in-touch')
            ->pause(1000)
            ->type('email','summonshr@gmail.com')
            ->type('name','Suman Shrestha')
            ->type('address','Address of a user')
            ->type('phone','9999999999')
            ->click('.sw-btn-next')
            ->click('.sw-btn-next')
            ->type('payment_value','summonshr@gmail.com')
            ->click('.sw-btn-next')
            ->assertSee('PayPal Email')
            ->click('.submit-button')
            ->pause(1000)
            ->assertSee('Request received');
        });
    }
}
