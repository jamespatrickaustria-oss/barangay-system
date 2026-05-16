<?php

namespace Database\Seeders;

use App\Models\CarouselSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CarouselSettingsSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CarouselSetting::firstOrCreate([], [
            'autoplay_enabled' => true,
            'autoplay_speed' => 4000,
            'pause_on_hover' => true,
            'loop' => true,
        ]);
    }
}
