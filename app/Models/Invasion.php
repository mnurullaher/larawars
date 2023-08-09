<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invasion extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'planet_id'
    ];

    public function planet() {
        return $this->belongsTo(Planet::class);
    }

    public function people() {
        return $this->belongsToMany(
            People::class,
            'invasion_people',
            'invasion_id',
            'people_id'
        );
    }
}
