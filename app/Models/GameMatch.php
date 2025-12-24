<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GameMatch extends Model
{
    protected $table = 'matches';
    
    protected $fillable = [
        'tournament_id',
        'home_school_id',
        'away_school_id',
        'home_goals',
        'away_goals',
        'status',
        'match_date',
        'scheduled_time',
        'duration_minutes',
        'field_id',
        'referee_id',
        'notes'
    ];

    protected $casts = [
        'match_date' => 'datetime',
        'scheduled_time' => 'datetime',
    ];

    public function tournament(): BelongsTo
    {
        return $this->belongsTo(Tournament::class);
    }

    public function homeSchool(): BelongsTo
    {
        return $this->belongsTo(School::class, 'home_school_id');
    }

    public function awaySchool(): BelongsTo
    {
        return $this->belongsTo(School::class, 'away_school_id');
    }

    public function field(): BelongsTo
    {
        return $this->belongsTo(Field::class);
    }

    public function referee(): BelongsTo
    {
        return $this->belongsTo(Referee::class);
    }
}