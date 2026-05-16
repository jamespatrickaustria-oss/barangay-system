<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarouselSetting extends Model
{
    protected $table = 'carousel_settings';

    protected $fillable = [
        'autoplay_enabled',
        'autoplay_speed',
        'pause_on_hover',
        'loop',
    ];

    protected $casts = [
        'autoplay_enabled' => 'boolean',
        'pause_on_hover' => 'boolean',
        'loop' => 'boolean',
        'autoplay_speed' => 'integer',
    ];
}
