<?php

namespace App\Services;

use App\Models\GameMatch;
use App\Models\Field;
use App\Models\Referee;
use Carbon\Carbon;

class SchedulePlanningService
{
    /**
     * Toernooi specs met spelregels
     */
    protected array $specs = [
        'voetbal_3_4' => ['duration' => 15, 'pause' => 5],
        'voetbal_5_6' => ['duration' => 15, 'pause' => 5],
        'voetbal_7_8' => ['duration' => 15, 'pause' => 5],
        'voetbal_vo_meisjes' => ['duration' => 15, 'pause' => 5],
        'voetbal_vo_jongens' => ['duration' => 15, 'pause' => 5],
        'lijnbal_basis' => ['duration' => 10, 'pause' => 2],
        'lijnbal_vo' => ['duration' => 12, 'pause' => 0],
    ];

    /**
     * Plan een wedstrijd in
     * @return array ['success' => bool, 'message' => string, 'match' => GameMatch|null]
     */
    public function scheduleMatch(
        GameMatch $match,
        Field $field,
        ?Referee $referee,
        Carbon $startTime
    ): array {
        // Haal specs op (genees hier wat te vereenvoudigen)
        $tournamentName = str_replace(' ', '_', strtolower($match->tournament->name));
        $specs = $this->specs[$tournamentName] ?? ['duration' => 15, 'pause' => 5];
        $durationMinutes = $specs['duration'];

        // Validatie 1: Veld beschikbaar?
        if (!$field->isAvailable($startTime, $durationMinutes)) {
            return [
                'success' => false,
                'message' => "Veld '{$field->name}' is niet beschikbaar op dit moment.",
                'match' => null
            ];
        }

        // Validatie 2: Scheidsrechter beschikbaar?
        if ($referee && !$referee->isAvailable($startTime, $durationMinutes)) {
            return [
                'success' => false,
                'message' => "Scheidsrechter '{$referee->name}' is niet beschikbaar op dit moment.",
                'match' => null
            ];
        }

        // Validatie 3: Teams niet dubbel geboekt?
        $homeTeamConflict = GameMatch::where('home_school_id', $match->home_school_id)
            ->orWhere('away_school_id', $match->home_school_id)
            ->where('status', '!=', 'cancelled')
            ->where('id', '!=', $match->id)
            ->where(function ($query) use ($startTime, $durationMinutes) {
                $endTime = (clone $startTime)->modify("+{$durationMinutes} minutes");
                $query->whereRaw('TIMESTAMPADD(MINUTE, duration_minutes, scheduled_time) > ?', [$startTime])
                      ->whereRaw('scheduled_time < ?', [$endTime]);
            })
            ->exists();

        if ($homeTeamConflict) {
            return [
                'success' => false,
                'message' => "Team '{$match->homeSchool->name}' is al geboekt op dit moment.",
                'match' => null
            ];
        }

        $awayTeamConflict = GameMatch::where('home_school_id', $match->away_school_id)
            ->orWhere('away_school_id', $match->away_school_id)
            ->where('status', '!=', 'cancelled')
            ->where('id', '!=', $match->id)
            ->where(function ($query) use ($startTime, $durationMinutes) {
                $endTime = (clone $startTime)->modify("+{$durationMinutes} minutes");
                $query->whereRaw('TIMESTAMPADD(MINUTE, duration_minutes, scheduled_time) > ?', [$startTime])
                      ->whereRaw('scheduled_time < ?', [$endTime]);
            })
            ->exists();

        if ($awayTeamConflict) {
            return [
                'success' => false,
                'message' => "Team '{$match->awaySchool->name}' is al geboekt op dit moment.",
                'match' => null
            ];
        }

        // Update de wedstrijd
        $match->update([
            'scheduled_time' => $startTime,
            'duration_minutes' => $durationMinutes,
            'field_id' => $field->id,
            'referee_id' => $referee?->id,
            'status' => 'scheduled',
        ]);

        return [
            'success' => true,
            'message' => "Wedstrijd ingepland op {$startTime->format('d-m-Y H:i')} op {$field->name}",
            'match' => $match
        ];
    }

    /**
     * Haal alle volgende beschikbare slots op
     */
    public function getAvailableSlots(
        Field $field,
        ?Referee $referee,
        int $durationMinutes,
        Carbon $startFrom,
        int $numSlots = 5
    ): array {
        $slots = [];
        $currentTime = $startFrom->copy();
        $searchDays = 7; // Zoek tot 7 dagen vooruit

        while (count($slots) < $numSlots && $currentTime->diffInDays($startFrom) <= $searchDays) {
            if ($field->isAvailable($currentTime, $durationMinutes) &&
                ($referee === null || $referee->isAvailable($currentTime, $durationMinutes))) {
                $slots[] = $currentTime->copy();
            }
            $currentTime->addMinutes(5); // Check elke 5 minuten
        }

        return $slots;
    }

    /**
     * Get specs voor een toernooi
     */
    public function getSpecs(string $tournamentName): array
    {
        $key = str_replace(' ', '_', strtolower($tournamentName));
        return $this->specs[$key] ?? ['duration' => 15, 'pause' => 5];
    }
}
