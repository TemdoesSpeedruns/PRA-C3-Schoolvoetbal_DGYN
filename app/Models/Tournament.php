<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Tournament extends Model
{
    protected $fillable = ['name', 'type', 'year', 'results', 'winner', 'status'];
    
    

    protected $casts = [
        'results' => 'array',
        'archived_at' => 'datetime',
    ];

    public function matches(): HasMany
    {
        return $this->hasMany(GameMatch::class);
    }

    public function pools(): HasMany
    {
        return $this->hasMany(Pool::class);
    }

    /**
     * Scope a query to only archived tournaments.
     */
    public function scopeArchived(Builder $query)
    {
        return $query->whereNotNull('archived_at');
    }

    /**
     * Scope a query to only active (not archived) tournaments.
     */
    public function scopeActive(Builder $query)
    {
        return $query->whereNull('archived_at');
    }

    public function isArchived(): bool
    {
        return !is_null($this->archived_at);
    }
}
