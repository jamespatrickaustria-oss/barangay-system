<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class Announcement extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'content',
        'photo_path',
        'published_at',
        'expires_at',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'expires_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::created(function (Announcement $announcement): void {
            // Fan-out announcement alerts to approved residents for the bell panel.
            if (!$announcement->is_active) {
                return;
            }

            $residentIds = User::query()
                ->where('role', 'resident')
                ->where('status', 'approved')
                ->pluck('id');

            if ($residentIds->isEmpty()) {
                return;
            }

            $now = Carbon::now();
            $rows = $residentIds->map(function (int $residentId) use ($announcement, $now): array {
                return [
                    'user_id' => $residentId,
                    'sender_id' => $announcement->user_id,
                    'title' => 'New Announcement: ' . $announcement->title,
                    'message' => $announcement->content,
                    'is_read' => false,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            })->all();

            Notification::insert($rows);
        });
    }

    /**
     * Get the user who created the announcement.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
