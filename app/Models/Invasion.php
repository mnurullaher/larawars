<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Invasion extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'planet_id'
    ];

    public function planet(): BelongsTo
    {
        return $this->belongsTo(Planet::class);
    }

    public function people(): BelongsToMany
    {
        return $this->belongsToMany(
            People::class,
            'invasion_people',
            'invasion_id',
            'people_id'
        );
    }
}
