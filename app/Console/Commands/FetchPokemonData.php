<?php

namespace App\Console\Commands;

use App\Models\Pokemon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class FetchPokemonData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-pokemon-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch data from the Pokemon API.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            // Ask user for the limit
            $limit = $this->ask('Enter the limit for the API (e.g., 10):');

            // Make API request using Laravel HTTP client with the provided limit
            $response = Http::get("https://pokeapi.co/api/v2/pokemon?limit={$limit}&offset=0");

            // Process and use the fetched data as needed
            $pokemonList = $response->json()['results'];

            foreach ($pokemonList as $pokemonData) {
                // Chain requests for each Pokemon
                $pokemonDetails = Http::get($pokemonData['url'])->json();
                $pokemonSpecies = Http::get($pokemonDetails['species']['url'])->json();
                $evolutionChain = Http::get($pokemonSpecies['evolution_chain']['url'])->json();

                // Process stats
                $filteredStats = array_map(function ($stat) {
                    return [
                        'base_stat' => $stat['base_stat'],
                        'name' => $stat['stat']['name'],
                    ];
                }, $pokemonDetails['stats']);

                $firstEvolution = $evolutionChain['chain']['species'] ?? null;
                $secondEvolution = $evolutionChain['chain']['evolves_to'][0]['evolves_to'][0]['species'] ?? null;
                $thirdEvolution = $evolutionChain['chain']['evolves_to'][0]['species'] ?? null;

                // Create an array with the values, filtering out null values
                $mergedEvolutions = array_filter([$firstEvolution, $thirdEvolution, $secondEvolution]);

                // Prepare data for creation or update
                $pokemonData = [
                    'name' => $pokemonDetails['name'],
                    'sprite_1_path' => $pokemonDetails['sprites']['front_default'],
                    'sprite_2_path' => $pokemonDetails['sprites']['other']['dream_world']['front_default'],
                    'height' => $pokemonDetails['height'],
                    'weight' => $pokemonDetails['weight'],
                    'types' => json_encode(collect($pokemonDetails['types'])->pluck('type.name')->toArray()),
                    'stats' => json_encode($filteredStats),
                    'abilities' => json_encode(collect($pokemonDetails['abilities'])->pluck('ability.name')->toArray()),
                    'egg_groups' => json_encode(collect($pokemonSpecies['egg_groups'])->pluck('name')->toArray()),
                    'genera' => $pokemonSpecies['genera'][7]['genus'] ?? null,
                    'growth_rate' => $pokemonSpecies['growth_rate']['name'],
                    'evolution_chain' => json_encode(collect($mergedEvolutions)->pluck('name')->toArray()),
                ];

                Pokemon::updateOrCreate(
                    ['pokemon_id' => $pokemonDetails['id']],
                    $pokemonData
                );
            }

            $this->info('Pokemon data fetched successfully.');
        } catch (\Exception $e) {
            $this->error('An error occurred: '.$e->getMessage());
        }
    }
}
