<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class People extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'height',
        'mass',
        'hair_color',
        'skin_color',
        'eye_color',
        'birth_year',
        'gender',
        'sense_force'
    ];

    public function starships(): HasMany
    {
        return $this->hasMany(Starship::class, 'owner_id');
    }

    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class, 'owner_id');
    }

    public function immigratedPlanet(): BelongsTo
    {
        return $this->belongsTo(Planet::class, 'immigrated_planet_id');
    }
}
