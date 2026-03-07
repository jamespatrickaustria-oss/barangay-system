<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@barangay.gov'],
            [
                'first_name' => 'System',
                'surname' => 'Administrator',
                'password' => Hash::make('passw0rd'),
                'role' => 'admin',
                'status' => 'approved',
            ]
        );
    }
}
