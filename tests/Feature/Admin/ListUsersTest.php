<?php

namespace Tests\Feature\Admin;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ListUsersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_shows_the_users_list()
    {
        factory(User::class)->create([
            'name' => 'Joel',
        ]);
        factory(User::class)->create([
            'name' => 'Ellie'
        ]);

        $this->get('usuarios')
            ->assertStatus(200)
            ->assertSee('Usuarios')
            ->assertSee('Joel')
            ->assertSee('Ellie');
    }

    /** @test */
    public function it_shows_a_default_message_if_the_users_list_is_empty()
    {
        $this->get('usuarios')
            ->assertStatus(200)
            ->assertSee('Usuarios')
            ->assertSee('No hay usuarios registrados');
    }

    /** @test */
    public function it_shows_the_deleted_users()
    {
        factory(User::class)->create([
            'name' => 'Joel',
            'deleted_at' => now(),
        ]);

        factory(User::class)->create([
            'name' => 'Ellie',
        ]);

        $this->get('usuarios/papelera')
            ->assertStatus(200)
            ->assertSee('Listado de usuarios en la papelera')
            ->assertSee('Joel')
            ->assertDontSee('Ellie');
    }

    /** @test */
    public function it_paginates_the_user()
    {
        factory(User::class)->create([
            'name' => 'Tercer usuario',
            'created_at' => now()->subDays(5),
        ]);
        factory(User::class)->times(12)->create([
            'created_at' => now()->subDays(4),
        ]);
        factory(User::class)->create([
            'name' => 'Decimoséptimo usuario',
            'created_at' => now()->subDays(2),
        ]);
        factory(User::class)->create([
            'name' => 'Segundo usuario',
            'created_at' => now()->subDays(6),
        ]);
        factory(User::class)->create([
            'name' => 'Primer usuario',
            'created_at' => now()->subWeek(),
        ]);
        factory(User::class)->create([
            'name' => 'Decimosexto usuario',
            'created_at' => now()->subDays(3),
        ]);


        $this->get('usuarios')
            ->assertStatus(200)
            ->assertSeeInOrder([
                'Decimoséptimo usuario',
                'Decimosexto usuario',
                'Tercer usuario'
            ])
            ->assertDontSee('Primer usuario')
            ->assertDontSee('Segundo usuario');

        $this->get('usuarios?page=2')
            ->assertSeeInOrder([
                'Segundo usuario',
                'Primer usuario'
            ])->assertDontSee('Tercer usuario');
    }
}
