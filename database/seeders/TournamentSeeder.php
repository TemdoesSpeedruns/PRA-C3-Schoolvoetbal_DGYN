<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tournament;

class TournamentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Update of maak standaard toernooien aan
        Tournament::updateOrCreate(
            ['name' => 'Voetbal', 'year' => 2025],
            [
                'type' => 'voetbal',
                'status' => 'active',
            ]
        );

        Tournament::updateOrCreate(
            ['name' => 'Lijnbal', 'year' => 2025],
            [
                'type' => 'lijnbal',
                'status' => 'active',
            ]
        );
    }
}
