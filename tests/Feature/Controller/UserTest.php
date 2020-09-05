<?php

namespace Tests\Feature\Controller;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    private function user_login()
    {
        return factory(User::class)->create([
            'email'     => 'username@example.net',
            'password'  => bcrypt('username')
        ]);
    }

    private function user_sample()
    {
        $data = [
                'name'      =>  'first',
                'email'     =>  'first@example.net',
                'password'  =>  bcrypt('first')
        ];

        return factory(User::class)->create($data);
    }

    /** @test */
    public function user_access_index_user()
    {
        $this->actingAs($this->user_login());

        $this->visit('/user');

        $this->seePageIs('/user');

        $this->seeText('User Index');
    }

    /** @test */
    public function user_access_create_user()
    {
        $this->actingAs($this->user_login());

        $this->visit('/user');

        $this->seePageIs('/user');

        $this->click('Create User');

        $this->seePageIs('/user/create');

        $this->seeText('User Create');
    }

    /** @test */
    public function user_access_edit_user()
    {
        $this->user_sample();

        $this->actingAs($this->user_login());

        $this->visit('/user');

        $this->seePageIs('/user');

        $this->visit('/user/2/edit');

        $this->seeRouteIs('user.edit', ['id' => 2]);

        $this->seeText('User Edit');
    }

    /** @test */
    public function user_action_store_user()
    {
        $this->actingAs($this->user_login());

        $this->visit('/user/create');

        $this->submitForm('Simpan', [
            'name'      => 'Tomo',
            'email'     => 'hanung@prihutomo.web.id',
            'password'  => 'remaster_aja',
        ]);

        $this->seeInDatabase('users', [
            'name'      => 'Tomo',
            'email'     => 'hanung@prihutomo.web.id',
        ]);

        $this->seePageIs('/user');
    }

    /** @test */
    public function user_action_update_user()
    {
        $this->user_sample();

        $this->actingAs($this->user_login());

        $this->visit('/user/2/edit');

        $this->submitForm('Simpan', [
            'name'      => 'Tomo',
            'email'     => 'hanung@prihutomo.web.id',
            'password'  => 'remaster_aja',
        ]);

        $this->seeInDatabase('users', [
            'name'      => 'Tomo',
            'email'     => 'hanung@prihutomo.web.id',
        ]);

        $this->seePageIs('/user');
    }

    /** @test */
    public function user_action_delete_user()
    {
        $this->user_sample();

        $this->actingAs($this->user_login());

        $this->visit('/user');

        $this->delete('user/2');

        $this->notSeeInDatabase('users', [
            'id' =>  2,
        ]);
    }
}
