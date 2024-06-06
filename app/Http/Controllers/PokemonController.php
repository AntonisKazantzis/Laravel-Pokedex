<?php

namespace App\Http\Controllers;

use App\Models\Pokemon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

class PokemonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $search = Request::input('search');
        $ability = Request::input('ability');
        $type = Request::input('type');
        $growth_rate = Request::input('growth_rate');
        $egg_group = Request::input('egg_group');
        $sort = Request::input('sort');

        $query = Pokemon::query()
            ->when($search, fn($q) => $q->where('name', 'like', '%' . $search . '%'))
            ->when($ability, fn($q) => $q->where('abilities', 'like', '%' . $ability . '%'))
            ->when($type, fn($q) => $q->where('types', 'like', '%' . $type . '%'))
            ->when($growth_rate, fn($q) => $q->where('growth_rate', $growth_rate))
            ->when($egg_group, fn($q) => $q->where('egg_groups', 'like', '%' . $egg_group . '%'))
            ->when($sort, function ($q) use ($sort) {
                [$column, $order] = explode(':', $sort);
                $q->orderBy($column, $order);
            });

        $pokemons = $query->orderBy('created_at', 'asc')->paginate(10)->withQueryString();

        $this->transformPokemons($pokemons);

        $types = Pokemon::distinct()->pluck('types')->map('json_decode')->toArray();
        $abilities = Pokemon::distinct()->pluck('abilities')->map('json_decode')->toArray();
        $egg_groups = Pokemon::distinct()->pluck('egg_groups')->map('json_decode')->toArray();
        $growth_rates = Pokemon::distinct()->pluck('growth_rate')->toArray();

        $pivot = DB::table('liked_pokemons')->where('user_id', auth()->id())->get();

        return Inertia::render('Pokemon/Index', compact('pokemons', 'types', 'search', 'abilities', 'egg_groups', 'growth_rates', 'sort', 'pivot'));
    }

    /**
     * Display the specified resource.
     */
    public function show($name)
    {
        $pokemon = Pokemon::where('name', $name)->firstOrFail();
        $pokemon->increment('views');

        $this->transformPokemon($pokemon);

        $evolution_chain = Pokemon::whereIn('name', $pokemon->evolution_chain)->pluck('name', 'id')->toArray();

        $likes = DB::table('liked_pokemons')->count();
        $pivot = DB::table('liked_pokemons')->where('user_id', auth()->id())->get();

        return Inertia::render('Pokemon/Show', compact('pokemon', 'likes', 'pivot', 'evolution_chain'));
    }

    /**
     * Like or unlike the specified resource.
     */
    public function like(Pokemon $pokemon)
    {
        $user = auth()->user();

        if ($pokemon->users->contains($user)) {
            $pokemon->users()->detach($user);
            $message = "You have unliked $pokemon->name.";
            $type = 'flash-error';
        } else {
            $pokemon->users()->attach($user);
            $message = "You have liked $pokemon->name.";
            $type = 'flash-success';
        }

        return to_route('pokemons.index')->with(['message' => $message, 'type' => $type]);
    }

    /**
     * Display the specified resource.
     */
    public function liked()
    {
        $user = auth()->user();

        $pokemons = $user->pokemons()->orderBy('created_at', 'asc')->paginate(10)->withQueryString();
        $pivot = DB::table('liked_pokemons')->where('user_id', auth()->id())->get();

        $this->transformPokemons($pokemons);

        return Inertia::render('Pokemon/Liked', compact('pokemons', 'pivot'));
    }

    /**
     * Transform the given collection of pokemons.
     */
    private function transformPokemons($pokemons)
    {
        $pokemons->getCollection()->transform(function ($pokemon) {
            $this->transformPokemon($pokemon);

            return $pokemon;
        });
    }

    /**
     * Transform the given pokemon.
     */
    private function transformPokemon($pokemon)
    {
        $pokemon->types = json_decode($pokemon->types, true);
        $pokemon->abilities = json_decode($pokemon->abilities, true);
        $pokemon->egg_groups = json_decode($pokemon->egg_groups, true);
        $pokemon->stats = json_decode($pokemon->stats, true);
        $pokemon->evolution_chain = json_decode($pokemon->evolution_chain, true);
    }
}
