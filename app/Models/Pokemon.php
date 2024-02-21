<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Pokemon extends Model
{
    protected $guarded = [];

    protected $casts = [
        'height' => 'decimal:2',
        'weight' => 'decimal:2',
        'types' => 'array',
        'stats' => 'array',
        'abilities' => 'array',
        'egg_groups' => 'array',
        'evolution_chain' => 'array',
        'views' => 'integer',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'liked_pokemons');
    }
}
