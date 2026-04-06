<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OnlineId extends Model
{
    private const ID_PREFIX = 'GNT-27-';

    private const SEQUENCE_LENGTH = 6;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'user_id',
        'id_number',
        'issued_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'issued_at' => 'datetime',
        ];
    }

    /**
     * Get the user that owns the online ID.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Generate a new ID number in the format GNT-27-YYYY-000001.
     */
    public static function generateIdNumber(): string
    {
        $year = now()->format('Y');
        $prefix = static::ID_PREFIX . $year . '-';

        $lastIdNumber = static::query()
            ->where('id_number', 'like', $prefix . '%')
            ->orderByDesc('id_number')
            ->value('id_number');

        $nextSequence = 1;

        if (!empty($lastIdNumber)) {
            $lastSequence = (int) substr($lastIdNumber, strrpos($lastIdNumber, '-') + 1);
            $nextSequence = $lastSequence + 1;
        }

        $formattedCount = str_pad((string) $nextSequence, static::SEQUENCE_LENGTH, '0', STR_PAD_LEFT);

        return $prefix . $formattedCount;
    }
}
