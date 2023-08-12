<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Planet extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'rotation_period',
        'orbital_period',
        'diameter',
        'climate',
        'gravity',
        'terrain',
        'surface_water',
        'population',
        'has_force'
    ];

    public function invasion(): HasOne
    {
        return $this->hasOne(Invasion::class);
    }

    public function immigrants(): HasMany
    {
        return $this->hasMany(People::class, 'immigrated_planet_id');
    }
}
