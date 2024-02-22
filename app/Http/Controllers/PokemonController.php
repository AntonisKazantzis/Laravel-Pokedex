<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;
use App\Models\Pokemon;

class PokemonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Retrieve query parameters
        $search = Request::input('search');
        $ability = Request::input('ability');
        $type = Request::input('type');
        $growth_rate = Request::input('growth_rate');
        $egg_group = Request::input('egg_group');
        $sort = Request::input('sort');

        // Query Pokemons based on filters
        $pokemons = Pokemon::query()->when($search, function ($q, $search) {
                $q->where('name', 'like', '%' . $search . '%');
            })
            ->when($ability, function ($q, $ability) {
                $q->where('abilities', 'like', '%' . $ability . '%');
            })
            ->when($type, function ($q, $type) {
                $q->where('types', 'like', '%' . $type . '%');
            })
            ->when($growth_rate, function ($q, $growth_rate) {
                $q->where('growth_rate', $growth_rate);
            })
            ->when($egg_group, function ($q, $egg_group) {
                $q->where('egg_groups', 'like', '%' . $egg_group . '%');
            })
            ->when($sort, function ($q, $sort) {
                [$column, $order] = explode(':', $sort);
                $q->orderBy($column, $order);
            })->orderBy('created_at', 'asc')->paginate(10)->withQueryString();

        // Transform Pokemons for better readability
        $this->transformPokemons($pokemons);

        // Retrieve distinct values for filters
        $types = Pokemon::distinct()->pluck('types')->toArray();
        $abilities = Pokemon::distinct()->pluck('abilities')->toArray();
        $egg_groups = Pokemon::distinct()->pluck('egg_groups')->toArray();
        $growth_rates = Pokemon::distinct()->pluck('growth_rate')->toArray();
        $pivot = DB::table('liked_pokemons')->where('user_id', auth()->id())->get();

        // Decode JSON arrays
        $types = array_map('json_decode', $types);
        $abilities = array_map('json_decode', $abilities);
        $egg_groups = array_map('json_decode', $egg_groups);

        // Render view with data
        return Inertia::render('Pokemon/Index', compact('pokemons', 'types', 'search', 'abilities', 'egg_groups', 'growth_rates', 'sort', 'pivot'));
    }

    /**
     * Display the specified resource.
     */
    public function show($name)
    {
        // Retrieve Pokemon by name
        $pokemon = Pokemon::where('name', $name)->firstOrFail();
        $pokemon->increment('views');

        // Transform Pokemon for better readability
        $this->transformPokemon($pokemon);

        // Retrieve evolution chain
        $evolution_chain = Pokemon::whereIn('name', $pokemon->evolution_chain)->pluck('name', 'id')->toArray();

        // Retrieve likes count and user's liked pokemons
        $likes = DB::table('liked_pokemons')->count();
        $pivot = DB::table('liked_pokemons')->where('user_id', auth()->id())->get();

        // Render view with data
        return Inertia::render('Pokemon/Show', compact('pokemon', 'likes', 'pivot', 'evolution_chain'));
    }

    /**
     * Like or unlike the specified resource.
     */
    public function like(Pokemon $pokemon)
    {
        // Retrieve authenticated user
        $user = auth()->user();

        // Check if the user has liked or unliked the pokemon
        if ($pokemon->users->contains($user)) {
            $pokemon->users()->detach($user);
            $message = "You have unliked $pokemon->name.";
            $type = 'flash-error';
        } else {
            $pokemon->users()->attach($user);
            $message = "You have liked $pokemon->name.";
            $type = 'flash-success';
        }

        // Redirect back with flash message
        return redirect()->back()->with(['message' => $message, 'type' => $type]);
    }

    /**
     * Display the specified resource.
     */
    public function liked()
    {
        // Retrieve authenticated user
        $user = auth()->user();

        // Retrieve liked pokemons of the user
        $pokemons = $user->pokemons()->orderBy('created_at', 'asc')->paginate(10)->withQueryString();
        $pivot = DB::table('liked_pokemons')->where('user_id', auth()->id())->get();

        // Transform pokemons for better readability
        $this->transformPokemons($pokemons);

        // Render view with data
        return Inertia::render('Pokemon/Liked', compact('pokemons', 'pivot'));
    }

    /**
     * Transform the given collection of pokemons.
     */
    private function transformPokemons($pokemons)
    {
        $pokemons->getCollection()->transform(function ($pokemon) {
            // Transform each pokemon
            $this->transformPokemon($pokemon);
            return $pokemon;
        });
    }

    /**
     * Transform the given pokemon.
     */
    private function transformPokemon($pokemon)
    {
        // Decode JSON attributes for better readability
        $pokemon->types = json_decode($pokemon->types, true);
        $pokemon->abilities = json_decode($pokemon->abilities, true);
        $pokemon->egg_groups = json_decode($pokemon->egg_groups, true);
        $pokemon->stats = json_decode($pokemon->stats, true);
        $pokemon->evolution_chain = json_decode($pokemon->evolution_chain, true);
    }
}
