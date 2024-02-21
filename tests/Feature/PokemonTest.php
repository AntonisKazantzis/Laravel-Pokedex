<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Pokemon;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PokemonTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_index()
    {
        $response = $this->get('/pokemons');

        $response->assertStatus(200)
            ->assertViewIs('Pokemon/Index')
            ->assertViewHas('pokemons');
    }

    public function test_show()
    {
        $pokemon = Pokemon::inRandomOrder()->first();

        $response = $this->get("/pokemons/{$pokemon->id}");

        $response->assertStatus(200)
            ->assertViewIs('Pokemon/Show')
            ->assertViewHas('pokemon', $pokemon);
    }

    public function test_like_or_unlike()
    {
        $user = User::inRandomOrder()->first();
        $pokemon = Pokemon::inRandomOrder()->first();

        $response = $this->actingAs($user)->post("/pokemons/{$pokemon->id}/like");

        $response->assertStatus(200);

        $this->assertDatabaseHas('liked_pokemons', [
            'user_id' => $user->id,
            'pokemon_id' => $pokemon->id,
        ]);

        $response = $this->actingAs($user)->post("/pokemons/{$pokemon->id}/like");

        $response->assertStatus(200);

        $this->assertDatabaseMissing('liked_pokemons', [
            'user_id' => $user->id,
            'pokemon_id' => $pokemon->id,
        ]);
    }

    public function test_liked()
    {
        $user = User::inRandomOrder()->first();
        $user->pokemons()->attach(Pokemon::inRandomOrder()->first());

        $response = $this->actingAs($user)->get('/pokemons/liked');

        $response->assertStatus(200)
            ->assertViewIs('Pokemon/Liked')
            ->assertViewHas('pokemons');
    }
}
