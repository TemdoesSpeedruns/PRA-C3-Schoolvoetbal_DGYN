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
            ['name' => 'School Den Haag', 'contact_person' => 'Lisa Hendrikx', 'email' => 'denhaag@test.nl'],
            ['name' => 'School Eindhoven', 'contact_person' => 'Tom Bergman', 'email' => 'eindhoven@test.nl'],
            ['name' => 'School Almere', 'contact_person' => 'Sandra Willemsen', 'email' => 'almere@test.nl'],
            ['name' => 'School Breda', 'contact_person' => 'Robert de Vries', 'email' => 'breda@test.nl'],
            ['name' => 'School Arnhem', 'contact_person' => 'Renée Goossens', 'email' => 'arnhem@test.nl'],
            ['name' => 'School Leiden', 'contact_person' => 'Dirk Janssen', 'email' => 'leiden@test.nl'],
            ['name' => 'School Haarlem', 'contact_person' => 'Paulien Cools', 'email' => 'haarlem@test.nl'],
            ['name' => 'School Delft', 'contact_person' => 'Martijn Pieters', 'email' => 'delft@test.nl'],
            ['name' => 'School Gouda', 'contact_person' => 'Evelien Manders', 'email' => 'gouda@test.nl'],
            ['name' => 'School Tilburg', 'contact_person' => 'Henrik Sørensen', 'email' => 'tilburg@test.nl'],
            ['name' => 'School Zwolle', 'contact_person' => 'Claire Dubois', 'email' => 'zwolle@test.nl'],
        ];

        foreach ($schools as $schoolData) {
            School::updateOrCreate(
                ['email' => $schoolData['email']],
                array_merge($schoolData, ['status' => 'approved', 'category' => '3/4'])
            );
        }
    }
}
