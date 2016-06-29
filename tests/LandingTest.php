<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LandingTest extends TestCase
{
    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testLanding()
    {
        $this->visit('/')
             ->see('Smart Bots')
             ->click('Go')
             ->seePageIs('/account/login');
    }
}
