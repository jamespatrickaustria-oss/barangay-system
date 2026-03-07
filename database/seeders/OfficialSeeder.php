<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class OfficialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'official@barangay.gov'],
            [
                'first_name' => 'Juan',
                'surname' => 'Dela Cruz',
                'password' => Hash::make('passw0rd'),
                'role' => 'official',
                'status' => 'approved',
            ]
        );
    }
}
