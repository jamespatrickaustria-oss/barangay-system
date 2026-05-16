<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CarouselSlide extends Model
{
    protected $fillable = [
        'slot',
        'image_path',
        'title',
        'description',
        'enabled',
        'link_url',
        'open_in_new_tab',
    ];

    protected function casts(): array
    {
        return [
            'slot' => 'integer',
            'enabled' => 'boolean',
            'open_in_new_tab' => 'boolean',
        ];
    }

    /**
     * Get all images associated with this carousel slide
     */
    public function images(): HasMany
    {
        return $this->hasMany(CarouselImage::class, 'carousel_slide_id')->orderBy('sort_order');
    }
}

