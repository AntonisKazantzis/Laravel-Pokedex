<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Pokemon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Illuminate\Http\Request as HttpRequest;

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

        $pokemons = Pokemon::query();

        $pokemons->when($search, function ($q, $search) {
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
            });

        $pokemons = $pokemons->orderBy('created_at', 'asc')->paginate(10)->withQueryString();

        $pokemons->getCollection()->transform(function ($pokemon) {
            $pokemon->types = json_decode($pokemon->types, true);
            $pokemon->abilities = json_decode($pokemon->abilities, true);
            $pokemon->egg_groups = json_decode($pokemon->egg_groups, true);
            $pokemon->stats = json_decode($pokemon->stats, true);
            return $pokemon;
        });

        $types = Pokemon::distinct()->pluck('types')->toArray();
        $abilities = Pokemon::distinct()->pluck('abilities')->toArray();
        $egg_groups = Pokemon::distinct()->pluck('egg_groups')->toArray();
        $growth_rates = Pokemon::distinct()->pluck('growth_rate')->toArray();
        $pivot = DB::table('liked_pokemons')->where('user_id', auth()->id())->get();

        $types = array_map('json_decode', $types);
        $abilities = array_map('json_decode', $abilities);
        $egg_groups = array_map('json_decode', $egg_groups);

        return Inertia::render('Pokemon/Index', compact('pokemons', 'types', 'search', 'abilities', 'egg_groups', 'growth_rates', 'sort', 'pivot'));
    }

    /**
     * Display the specified resource.
     */
    public function show($name)
    {
        $pokemon = Pokemon::where('name', $name)->firstOrFail();
        $pokemon->increment('views');

        $pokemon->types = json_decode($pokemon->types, true);
        $pokemon->abilities = json_decode($pokemon->abilities, true);
        $pokemon->egg_groups = json_decode($pokemon->egg_groups, true);
        $pokemon->stats = json_decode($pokemon->stats, true);
        $pokemon->evolution_chain = json_decode($pokemon->evolution_chain, true);

        $likes = DB::table('liked_pokemons')->count();
        $pivot = DB::table('liked_pokemons')->where('user_id', auth()->id())->get();

        return Inertia::render('Pokemon/Show', compact('pokemon', 'likes', 'pivot'));
    }

    /**
     * Like or unlike the specified resource.
     */
    public function like(Pokemon $pokemon)
    {
        $user = auth()->user();

        if ($pokemon->users->contains($user)) {
            $pokemon->users()->detach($user);
        } else {
            $pokemon->users()->attach($user);
        }
    }

    /**
     * Display the specified resource.
     */
    public function liked()
    {
        $user = auth()->user();

        $pokemons = $user->pokemons()->orderBy('created_at', 'asc')->paginate(10)->withQueryString();
        $pivot = DB::table('liked_pokemons')->where('user_id', auth()->id())->get();

        $pokemons->getCollection()->transform(function ($pokemon) {
            $pokemon->types = json_decode($pokemon->types, true);
            $pokemon->abilities = json_decode($pokemon->abilities, true);
            $pokemon->egg_groups = json_decode($pokemon->egg_groups, true);
            $pokemon->stats = json_decode($pokemon->stats, true);
            return $pokemon;
        });

        return Inertia::render('Pokemon/Liked', compact('pokemons', 'pivot'));
    }
}
