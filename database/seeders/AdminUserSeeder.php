<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        // Zorg dat de standaard admin altijd bestaat
        $adminEmail = 'PaastoernooiAdmin@gmail.com';

        $admin = User::firstOrCreate(
            ['email' => $adminEmail], // zoekt op email
            [
                'name' => 'Super Admin',
                'password' => Hash::make('Paastoernooiwachtwoordadminaccount'), // pas dit aan
                'is_admin' => true,
            ]
        );
        $adminEmail = 'giovanni.vanderweegen@gmail.com';

        $admin = User::firstOrCreate(
            ['email' => $adminEmail], // zoekt op email
            [
                'name' => 'SuperAdmin',
                'password' => Hash::make('password123'), // pas dit aan
                'is_admin' => true,
            ]
        );

        // Zorg dat dit account altijd admin blijft
        if (!$admin->is_admin) {
            $admin->is_admin = true;
            $admin->save();
        }
    }
}
