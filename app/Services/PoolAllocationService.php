<?php

namespace App\Services;

use App\Models\Tournament;
use App\Models\Pool;
use App\Models\School;
use App\Models\GameMatch;
use App\Models\Referee;
use Carbon\Carbon;

class PoolAllocationService
{
    /**
     * Indeling scholen in poules (max 4 per poule) en aanmaken van wedstrijden
     */
    public function allocateSchoolsToPoolsAndCreateMatches(Tournament $tournament): array
    {
        // Haal alle goedgekeurde scholen op
        $approvedSchools = School::where('status', 'approved')
            ->whereNull('pool_id')
            ->orderBy('category')
            ->orderBy('name')
            ->get();

        if ($approvedSchools->isEmpty()) {
            return [
                'success' => false,
                'message' => 'Geen goedgekeurde scholen beschikbaar.',
                'pools_created' => 0,
                'matches_created' => 0
            ];
        }

        // Groepeer scholen per categorie
        $schoolsByCategory = $approvedSchools->groupBy('category');
        
        $poolsCreated = 0;
        $matchesCreated = 0;
        $matchSlot = 0; // Teller voor match scheduling

        // Maak poules aan per categorie
        foreach ($schoolsByCategory as $category => $schoolsInCategory) {
            $schoolArray = $schoolsInCategory->toArray();
            $poolNumber = 1;
            
            // Verdeel scholen in groepen van 4
            $chunks = array_chunk($schoolArray, 4);
            
            foreach ($chunks as $chunk) {
                // Maak poule aan
                $pool = Pool::create([
                    'tournament_id' => $tournament->id,
                    'name' => "Categorie {$category} - Poule {$poolNumber}"
                ]);
                
                // Wijs scholen toe aan poule
                foreach ($chunk as $school) {
                    School::find($school['id'])->update(['pool_id' => $pool->id]);
                }
                
                $poolsCreated++;
                
                // Maak wedstrijden aan voor deze poule (round-robin) met datum/tijd
                $matchesCreated += $this->createRoundRobinMatches($tournament, $pool, collect($chunk), $matchSlot);
                
                $poolNumber++;
            }
        }

        return [
            'success' => true,
            'message' => "{$poolsCreated} poule(s) aangemaakt en {$matchesCreated} wedstrijd(en) ingepland.",
            'pools_created' => $poolsCreated,
            'matches_created' => $matchesCreated
        ];
    }

    /**
     * Maak round-robin wedstrijden voor een poule met automatische datum/tijd
     */
    private function createRoundRobinMatches(Tournament $tournament, Pool $pool, $schools, &$matchSlot): int
    {
        $schoolList = $schools->all();
        $matchesCreated = 0;

        // Bepaal startdatum: volgende maandag als het weekend is, anders morgen
        $startDate = $this->getStartDate();
        
        // Bepaal aantal velden (standaard 2)
        $fieldCount = 2;
        
        // Bepaal match duration in minuten (standaard 15 min + 5 min pauze = 20 min per match)
        $matchDuration = 20;

        // Haal alle actieve scheidsrechters op voor round-robin indeling
        $referees = Referee::where('is_active', true)->get();
        $refereeCounter = 0;

        // Maak wedstrijden waarbij elke school tegen elke andere school speelt
        for ($i = 0; $i < count($schoolList); $i++) {
            for ($j = $i + 1; $j < count($schoolList); $j++) {
                $homeSchool = School::find($schoolList[$i]['id']);
                $awaySchool = School::find($schoolList[$j]['id']);

                // Controleer of wedstrijd nog niet bestaat
                $existingMatch = GameMatch::where('tournament_id', $tournament->id)
                    ->where(function ($query) use ($homeSchool, $awaySchool) {
                        $query->where(function ($q) use ($homeSchool, $awaySchool) {
                            $q->where('home_school_id', $homeSchool->id)
                              ->where('away_school_id', $awaySchool->id);
                        })
                        ->orWhere(function ($q) use ($homeSchool, $awaySchool) {
                            $q->where('home_school_id', $awaySchool->id)
                              ->where('away_school_id', $homeSchool->id);
                        });
                    })
                    ->exists();

                if (!$existingMatch) {
                    // Bereken de datum en tijd voor deze match
                    $scheduledTime = $this->calculateMatchTime($startDate, $matchSlot, $matchDuration, $fieldCount);
                    
                    // Wijs scheidsrechter toe in round-robin systeem
                    $referee = null;
                    if ($referees->isNotEmpty()) {
                        $referee = $referees[$refereeCounter % $referees->count()];
                        $refereeCounter++;
                    }
                    
                    GameMatch::create([
                        'tournament_id' => $tournament->id,
                        'home_school_id' => $homeSchool->id,
                        'away_school_id' => $awaySchool->id,
                        'status' => 'scheduled',
                        'match_date' => $scheduledTime->clone()->toDateString(),
                        'scheduled_time' => $scheduledTime,
                        'duration_minutes' => 15, // Speelduur zonder pauze
                        'referee_id' => $referee?->id,
                    ]);
                    
                    $matchSlot++;
                    $matchesCreated++;
                }
            }
        }

        return $matchesCreated;
    }

    /**
     * Bepaal startdatum (volgende ochtend om 9:00 uur, liefst maandag)
     */
    private function getStartDate(): Carbon
    {
        $now = Carbon::now();
        $tomorrow = $now->clone()->addDay()->setHour(9)->setMinute(0)->setSecond(0);
        
        // Als het volgende werkdag is (ma-vr), gebruik die
        // Anders wacht tot volgende maandag
        if ($tomorrow->isWeekday()) {
            return $tomorrow;
        }
        
        // Anders volgende maandag 9:00
        return $tomorrow->next('Monday')->setHour(9)->setMinute(0)->setSecond(0);
    }

    /**
     * Bereken match tijd op basis van slot nummer met 5 minuten pauze
     * Aanname: 2 velden, 15 minuten match + 5 minuten pauze = 20 minuten per match
     */
    private function calculateMatchTime(Carbon $startDate, int $matchSlot, int $duration, int $fieldCount): Carbon
    {
        // Match duration inclusief pauze: 15 minuten match + 5 minuten pauze = 20 minuten
        $totalMinutesPerMatch = 20;
        
        // Bereken hoeveel matches er per dag kunnen (max 3 per veld per dag)
        $matchesPerDay = $fieldCount * 3; // 3 time slots per veld per dag
        
        // Bepaal dag (elke $matchesPerDay matches = volgende dag)
        $daysOffset = intdiv($matchSlot, $matchesPerDay);
        $slotInDay = $matchSlot % $matchesPerDay;
        
        // Bepaal veld en time slot
        $timeSlot = intdiv($slotInDay, $fieldCount);
        
        // Bereken de startdatum
        $matchDate = $startDate->clone()->addDays($daysOffset);
        
        // Starttijd: 09:00, dan voeg minuten toe per time slot
        // TimeSlot 0: 09:00 - 09:20 (match) - 09:20 - 09:25 (pauze)
        // TimeSlot 1: 09:25 - 09:45 (match) - 09:45 - 09:50 (pauze)
        // TimeSlot 2: 09:50 - 10:10 (match) - 10:10 - 10:15 (pauze)
        
        $minutesOffset = $timeSlot * $totalMinutesPerMatch;
        $matchDate->setHour(9)->setMinute(0)->setSecond(0);
        $matchDate->addMinutes($minutesOffset);
        
        return $matchDate;
    }

    /**
     * Controleer of alle poules vol zijn (4 scholen) en maak wedstrijden aan
     */
    public function checkAndCreateMatches(Tournament $tournament): array
    {
        $pools = $tournament->pools()->get();
        
        if ($pools->isEmpty()) {
            return [
                'success' => false,
                'message' => 'Geen poules gevonden.',
                'matches_created' => 0
            ];
        }

        $matchesCreated = 0;
        $matchSlot = 0;

        foreach ($pools as $pool) {
            $schoolCount = $pool->schools()->count();
            
            if ($schoolCount > 0) {
                $schools = $pool->schools()->get();
                $matchesCreated += $this->createRoundRobinMatches($tournament, $pool, $schools, $matchSlot);
            }
        }

        return [
            'success' => true,
            'message' => $matchesCreated > 0 ? "{$matchesCreated} wedstrijd(en) aangemaakt." : 'Geen nieuwe wedstrijden nodig.',
            'matches_created' => $matchesCreated
        ];
    }
}
