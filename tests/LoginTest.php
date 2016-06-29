<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LoginTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testLogin()
    {

        $user = factory(SmartBots\User::class)->make()->save();

        $this->visit('/account/login')
             ->see('Login')
             ->type('demodemo','username')
             ->type('demodemo','password')
             ->check('remember')
             ->press('Login')
             ->seeJson([
                 'success' => true,
             ]);
    }
}
