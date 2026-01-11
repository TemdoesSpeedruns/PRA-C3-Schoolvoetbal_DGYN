<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Referee;

class RefereeSeeder extends Seeder
{
    public function run()
    {
        $referees = [
            ['name' => 'Jan de Vries', 'email' => 'jan.devries@example.com', 'type' => 'professional', 'is_active' => true, 'status' => 'approved'],
            ['name' => 'Maria García', 'email' => 'maria.garcia@example.com', 'type' => 'professional', 'is_active' => true, 'status' => 'approved'],
            ['name' => 'Peter Müller', 'email' => 'peter.muller@example.com', 'type' => 'senior', 'is_active' => true, 'status' => 'approved'],
            ['name' => 'Anna Kowalski', 'email' => 'anna.kowalski@example.com', 'type' => 'senior', 'is_active' => true, 'status' => 'approved'],
            ['name' => 'Robert Dubois', 'email' => 'robert.dubois@example.com', 'type' => 'junior', 'is_active' => true, 'status' => 'approved'],
            ['name' => 'Sophie Lefebvre', 'email' => 'sophie.lefebvre@example.com', 'type' => 'junior', 'is_active' => true, 'status' => 'approved'],
        ];

        foreach ($referees as $referee) {
            Referee::firstOrCreate(
                ['email' => $referee['email']],
                $referee
            );
        }
    }
}
