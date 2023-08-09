<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'gender'
    ];

    public function starships() {
        return $this->hasMany(Starship::class, 'owner_id');
    }

    public function vehicles() {
        return $this->hasMany(Vehicle::class, 'owner_id');
    }
}
