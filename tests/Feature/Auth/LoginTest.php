<?php

namespace Tests\Feature\Auth;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function seeder()
    {
        $user = factory(User::class)->create([
            'email'     => 'username@example.net',
            'password'  => bcrypt('secret')
        ]);

        return $user;
    }
    /** @test */
    public function registered_user_can_login()
    {
        $this->seeder();

        $this->visit('/login');

        $this->submitForm('Login', [
            'email'     => 'username@example.net',
            'password'  => 'secret'
        ]);

        $this->seePageIs('/home');

        $this->seeText('Dashboard');
    }

    /** @test */
    public function logged_in_user_can_logout()
    {
        $user = $this->seeder();

        $this->actingAs($user);

        $this->visit('/home');

        $this->post('/logout');

        $this->visit('/home');

        $this->seePageIs('/login');
    }
}
