<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Contact; 

class ContactSeeder extends Seeder
{
    public function run()
    {
        $contacts = [
            ['value' => '09123456789'],
            ['value' => '09987654321'],

        ];

        foreach ($contacts as $contact) {
            Contact::create($contact);
        }
    }
}