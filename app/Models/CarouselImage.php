<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CarouselImage extends Model
{
    protected $table = 'carousel_images';

    protected $fillable = [
        'carousel_slide_id',
        'image_path',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    /**
     * Get the carousel slide this image belongs to
     */
    public function slide(): BelongsTo
    {
        return $this->belongsTo(CarouselSlide::class, 'carousel_slide_id');
    }
}
