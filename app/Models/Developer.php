<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Developer extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'email', 'latitude', 'longitude', 'years_experience'];

    public function skills()
    {
        return $this->belongsToMany(Skill::class);
    }

    public function scopeNearby($query, $lat, $lng, $radius = 50)
    {
        return $query->selectRaw('*,
            ( 6371 * acos( cos( radians(?) ) *
              cos( radians( latitude ) ) *
              cos( radians( longitude ) - radians(?) ) +
              sin( radians(?) ) *
              sin( radians( latitude ) )
            )) AS distance', [$lat, $lng, $lat])
            ->having('distance', '<=', $radius)
            ->orderBy('distance');
    }
}
