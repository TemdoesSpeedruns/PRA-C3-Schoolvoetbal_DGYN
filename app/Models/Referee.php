<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Referee extends Model
{
    protected $fillable = ['name', 'email', 'type', 'school_id', 'is_active'];
    
    protected $attributes = [
        'is_active' => true,
    ];

    public function matches(): HasMany
    {
        return $this->hasMany(GameMatch::class);
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Controleer of scheidsrechter beschikbaar is in gegeven timeframe
     */
    public function isAvailable(\DateTime $startTime, int $durationMinutes): bool
    {
        $endTime = (clone $startTime)->modify("+{$durationMinutes} minutes");

        // Controleer of scheidsrechter al geboekt is
        $conflict = GameMatch::where('referee_id', $this->id)
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) use ($startTime, $endTime) {
                $query->whereRaw('TIMESTAMPADD(MINUTE, duration_minutes, scheduled_time) > ?', [$startTime])
                      ->whereRaw('scheduled_time < ?', [$endTime]);
            })
            ->exists();

        return !$conflict;
    }
}
