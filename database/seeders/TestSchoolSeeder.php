<?php

namespace Database\Seeders;

use App\Models\School;
use Illuminate\Database\Seeder;

class TestSchoolSeeder extends Seeder
{
    public function run(): void
    {
        $schools = [
            ['name' => 'School Amsterdam', 'contact_person' => 'Jan Jansen', 'email' => 'amsterdam@test.nl'],
            ['name' => 'School Rotterdam', 'contact_person' => 'Marie Pieterse', 'email' => 'rotterdam@test.nl'],
            ['name' => 'School Utrecht', 'contact_person' => 'Kees van der Linden', 'email' => 'utrecht@test.nl'],
            ['name' => 'School Groningen', 'contact_person' => 'Anna Bakker', 'email' => 'groningen@test.nl'],
            ['name' => 'School Maastricht', 'contact_person' => 'Peter Bonten', 'email' => 'maastricht@test.nl'],
        ];

        foreach ($schools as $schoolData) {
            School::updateOrCreate(
                ['email' => $schoolData['email']],
                array_merge($schoolData, ['status' => 'pending'])
            );
        }
    }
}
