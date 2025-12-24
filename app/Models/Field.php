<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Field extends Model
{
    protected $fillable = ['name', 'type', 'capacity', 'is_active'];
    
    protected $attributes = [
        'is_active' => true,
    ];

    public function matches(): HasMany
    {
        return $this->hasMany(GameMatch::class);
    }

    /**
     * Controleer of veld beschikbaar is in gegeven timeframe
     */
    public function isAvailable(\DateTime $startTime, int $durationMinutes): bool
    {
        $endTime = (clone $startTime)->modify("+{$durationMinutes} minutes");

        // Controleer of er geen conflicterende wedstrijden zijn
        $conflict = GameMatch::where('field_id', $this->id)
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) use ($startTime, $endTime) {
                // Bestaande wedstrijd start voor onze einde EN eindigt na onze start
                $query->whereRaw('TIMESTAMPADD(MINUTE, duration_minutes, scheduled_time) > ?', [$startTime])
                      ->whereRaw('scheduled_time < ?', [$endTime]);
            })
            ->exists();

        return !$conflict;
    }
}
