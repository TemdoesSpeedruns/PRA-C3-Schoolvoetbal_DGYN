<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tournament extends Model
{
    protected $fillable = ['name', 'type', 'year', 'results', 'winner', 'status'];
    
    protected $casts = [
        'results' => 'array',
    ];

    public function matches(): HasMany
    {
        return $this->hasMany(GameMatch::class);
    }

    public function pools(): HasMany
    {
        return $this->hasMany(Pool::class);
    }
}
