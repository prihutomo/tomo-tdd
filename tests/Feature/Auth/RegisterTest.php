<?php

namespace Tests\Feature\Auth;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    private function getRegisterFields($overrides = [])
    {
        return array_merge([
            'name'                  => 'Username',
            'email'                 => 'username@example.net',
            'password'              => 'secret',
            'password_confirmation' => 'secret',
        ], $overrides);
    }

    /** @test */
    public function user_can_register()
    {
        $this->visit('/register');

        $this->submitForm('Register', $this->getRegisterFields());

        $this->seePageIs('/home');

        $this->seeText('Dashboard');

        $this->seeInDatabase('users', [
            'name'  => 'Username',
            'email' => 'username@example.net'
        ]);

        $this->assertTrue(app('hash')->check('secret', User::first()->password));
    }

    /** @test */
    public function user_name_is_required()
    {
        $this->post('/register', $this->getRegisterFields(['name' => '']));

        $this->assertSessionHasErrors(['name']);
    }

    /** @test */
    public function user_name_maximum_is_255_characters()
    {
        $this->post('/register', $this->getRegisterFields([
            'name' => str_repeat('User-name', 30)
        ]));

        $this->assertSessionHasErrors(['name']);
    }

    /** @test */
    public function user_email_is_required()
    {
        $this->post('/register', $this->getRegisterFields(['email' => '' ]));

        $this->assertSessionHasErrors(['email']);
    }

    /** @test */
    public function user_email_must_be_a_valid_email()
    {
        $this->post('/register', $this->getRegisterFields([
            'email' => 'username.example.net'
        ]));

        $this->assertSessionHasErrors(['email']);
    }

    /** @test */
    public function user_email_maximum_is_255_characters()
    {
        $this->post('/register', $this->getRegisterFields([
            'email' => str_repeat('username@example.net', 15)
        ]));

        $this->assertSessionHasErrors(['email']);
    }

    /** @test */
    public function user_email_must_be_unique_on_users_table()
    {
        $user = factory(User::class)->create(['email' => 'email@example.com']);

        $this->post('/register', $this->getRegisterFields([
            'email' => 'email@example.com'
        ]));

        $this->assertSessionHasErrors(['email']);
    }

    /** @test */
    public function user_password_is_required()
    {
        $this->post('/register', $this->getRegisterFields(['password' => '']));

        $this->assertSessionHasErrors(['password']);
    }

    /** @test */
    public function user_password_minimum_is_6_characters()
    {
        $this->post('/register', $this->getRegisterFields(['password' => 'push']));

        $this->assertSessionHasErrors(['password']);
    }

    /** @test */
    public function user_password_must_be_same_with_password_confrimation_field()
    {
        $this->post('/register', $this->getRegisterFields([
            'password' => 'rahasia',
            'password_confirmation' => 'arhasia',
        ]));

        $this->assertSessionHasErrors(['password']);
    }
}
