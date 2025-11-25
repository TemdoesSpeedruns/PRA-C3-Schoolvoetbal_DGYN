<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tournament extends Model
{
    protected $fillable = ['name', 'year', 'results', 'winner', 'status'];
    
    // Optional: cast results JSON automatically
    protected $casts = [
        'results' => 'array',
    ];
}
