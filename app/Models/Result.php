<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    protected $fillable = [
        'tournament_name',
        'winner_name',
        'runner_up',
        'date'
    ];
}
