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
                'password' => Hash::make('password123'),
                'role' => 'official',
                'status' => 'approved',
            ]
        );

        User::updateOrCreate(
            ['email' => 'res@barangay.gov'],
            [
                'first_name' => 'User',
                'surname' => 'Resident',
                'password' => Hash::make('123'),
                'role' => 'resident',
                'status' => 'approved',
            ]
        );
    }
}
