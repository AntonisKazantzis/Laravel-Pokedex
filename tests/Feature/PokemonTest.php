<?php

namespace Tests\Feature;

use App\Models\Pokemon;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class PokemonTest extends TestCase
{
    public function test_index_page()
    {
        // Find a user
        $user = User::inRandomOrder()->first();

        // Acting as the user
        $response = $this->actingAs($user)->get(route('pokemons.index'));

        // Assert that the response is successful
        $response->assertStatus(200);

        // Assert that the Inertia page was returned
        $response->assertInertia(fn (Assert $inertia) => $inertia
            ->url('/pokemons')
        );
    }

    public function test_show_page()
    {
        // Find a user
        $user = User::inRandomOrder()->first();

        // Find a pokemon
        $pokemon = Pokemon::inRandomOrder()->first();

        // Acting as the user
        $response = $this->actingAs($user)->get(route('pokemons.show', $pokemon->name));

        // Assert that the response is successful
        $response->assertStatus(200);

        // Assert that the Inertia page was returned
        $response->assertInertia(fn (Assert $inertia) => $inertia
            ->url("/pokemons/$pokemon->name")
        );
    }

    public function test_like_unlike_action()
    {
        // Find a user
        $user = User::inRandomOrder()->first();

        // Find a pokemon
        $pokemon = Pokemon::inRandomOrder()->first();

        // Acting as the user, perform the like action
        $response = $this->actingAs($user)->post(route('pokemons.like', $pokemon));

        // Assert that the response is a redirect
        $response->assertStatus(302);

        // Follow the redirect
        $response->assertRedirect();

        // Get the redirected URL
        $redirectedUrl = $response->headers->get('Location');

        // Assert that the redirected URL matches the expected URL
        $this->assertEquals(route('welcome'), $redirectedUrl);
    }

    public function test_liked_page()
    {
        // Find a user
        $user = User::inRandomOrder()->first();

        // Acting as the user
        $response = $this->actingAs($user)->get(route('pokemons.liked'));

        // Assert that the response is successful
        $response->assertStatus(200);

        // Assert that the Inertia page was returned
        $response->assertInertia(fn (Assert $inertia) => $inertia
            ->url('/user/pokemons')
        );
    }
}
