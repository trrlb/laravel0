<?php

namespace Tests\Feature\Admin;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FilterUserTest extends TestCase
{
use RefreshDatabase;

    /** @test */
    public function filter_user_by_state_active()
    {

        $activeUser = factory(User::class)->create([
            'active' => true
        ]);
        $inactiveUser = factory(User::class)->create([
            'active' => false
        ]);


        $response  = $this->get('usuarios?state=active');

        $response->assertViewCollection('users')
            ->contains($activeUser)
            ->notcontains($inactiveUser);
    }


    /** @test */
    public function filter_user_by_state_inactive()
    {

        $activeUser = factory(User::class)->create([
            'active' => true
        ]);
        $inactiveUser = factory(User::class)->create([
            'active' => false
        ]);

        $response  = $this->get('usuarios?state=inactive');

        $response->assertViewCollection('users')
            ->contains($inactiveUser)
            ->notcontains($activeUser);
    }
}
