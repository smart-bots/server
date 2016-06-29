<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RegisterTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testRegister()
    {
        $this->visit('/account/register')
             ->see('Register')
             ->type('tester','username')
             ->type('Tờ ét tờ','name')
             ->type('test@te.st','email')
             ->type('testtest','password')
             ->type('testtest','password_confirmation')
             ->check('agree_with_terms')
             ->press('Register')
             ->seeJson([
                 'success' => true,
             ])
             ->seeInDatabase('users', ['username' => 'tester']);
    }
}
