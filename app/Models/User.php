<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MstVerifyEmail;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\ChatThread;
use App\Models\ChatMessage;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'middle_name',
        'surname',
        'email',
        'password',
        'role',
        'status',
        'profile_photo',
        'resident_id_number',
        'phone',
        'emergency_contact_name',
        'emergency_contact_relationship',
        'emergency_contact_number',
        'father_name',
        'mother_name',
        'house_no',
        'barangay',
        'municipality_city',
        'nationality',
        'address',
        'birthdate',
        'gender',
        'marital_status',
        'email_verification_token',
        'approved_by',
        'approved_at',
        'approver_role',
        'account_number',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'birthdate' => 'date',
            'approved_at' => 'datetime',
        ];
    }

    /**
     * Check if user is an admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is an official.
     */
    public function isOfficial(): bool
    {
        return $this->role === 'official';
    }

    /**
     * Check if user is a resident.
     */
    public function isResident(): bool
    {
        return $this->role === 'resident';
    }

    /**
     * Check if user can access the given Filament panel.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        // Only allow approved users to access panels
        if ($this->status !== 'approved') {
            return false;
        }

        // Match panel ID with user role
        return match ($panel->getId()) {
            // Admin can access admin panel
            // Officials can also access admin panel to use all barangay official features
            'admin' => in_array($this->role, ['admin', 'official']),
            // Officials can access their own official panel for backward compatibility
            'official' => $this->role === 'official',
            // Residents can access resident panel
            'resident' => $this->role === 'resident',
            default => false,
        };
    }

    /**
     * Check if user status is pending.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if user status is approved.
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Get resident account expiry timestamp (1 year after approval).
     */
    public function residentAccountExpiresAt(): ?Carbon
    {
        if (!$this->isResident() || !$this->isApproved() || !$this->approved_at) {
            return null;
        }

        return $this->approved_at->copy()->addYear();
    }

    /**
     * Determine if a resident account is expired.
     */
    public function isResidentAccountExpired(): bool
    {
        $expiresAt = $this->residentAccountExpiresAt();

        if (!$expiresAt) {
            return false;
        }

        return now()->greaterThanOrEqualTo($expiresAt);
    }

    /**
     * Get the full name of the user.
     */
    public function getFullName(): string
    {
        $parts = array_filter([
            $this->first_name,
            $this->middle_name,
            $this->surname,
        ]);
        return implode(' ', $parts) ?: 'Unknown User';
    }

    /**
     * Resolve the public URL for the user's profile photo.
     */
    public function getProfilePhotoUrlAttribute(): ?string
    {
        if (empty($this->profile_photo)) {
            return null;
        }

        if (str_starts_with($this->profile_photo, 'http://') || str_starts_with($this->profile_photo, 'https://')) {
            return $this->profile_photo;
        }

        return route('profile-photos.show', [
            'user' => $this,
            'v' => $this->updated_at?->timestamp,
        ]);
    }

    /**
     * Resolve the stored path for a user's profile photo across legacy and current formats.
     */
    public function getProfilePhotoStoragePath(): ?string
    {
        if (empty($this->profile_photo)) {
            return null;
        }

        if (str_starts_with($this->profile_photo, 'http://') || str_starts_with($this->profile_photo, 'https://')) {
            return null;
        }

        $photoPath = ltrim($this->profile_photo, '/');

        if (str_starts_with($photoPath, 'storage/')) {
            $photoPath = substr($photoPath, 8);
        }

        if (str_starts_with($photoPath, 'public/')) {
            $photoPath = substr($photoPath, 7);
        }

        $candidates = array_values(array_unique(array_filter([
            $photoPath,
            !str_contains($photoPath, '/') ? 'photos/' . $photoPath : null,
            !str_contains($photoPath, '/') ? 'uploads/profile_photos/' . $photoPath : null,
        ])));

        foreach ($candidates as $candidate) {
            if (Storage::disk('public')->exists($candidate)) {
                return $candidate;
            }
        }

        return $candidates[0] ?? null;
    }

    /**
     * Get all notifications for this user.
     */
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Get the online ID for this user.
     */
    public function onlineId(): HasOne
    {
        return $this->hasOne(OnlineId::class);
    }

    /**
     * Get resident chat thread.
     */
    public function chatThread(): HasOne
    {
        return $this->hasOne(ChatThread::class, 'resident_id');
    }

    /**
     * Get messages sent by this user.
     */
    public function sentChatMessages(): HasMany
    {
        return $this->hasMany(ChatMessage::class, 'sender_id');
    }

    /**
     * Generate a unique account number in the format GNT-27-YYYY-000001.
     */
    public static function generateAccountNumber(string $firstName, ?string $middleName, string $surname, ?string $birthdate): string
    {
        $year = now()->format('Y');
        $prefix = 'GNT-27-' . $year . '-';

        $lastAccountNumber = static::withTrashed()
            ->where('account_number', 'like', $prefix . '%')
            ->orderByDesc('account_number')
            ->value('account_number');

        $nextSequence = 1;

        if (!empty($lastAccountNumber)) {
            $lastSequence = (int) substr($lastAccountNumber, strrpos($lastAccountNumber, '-') + 1);
            $nextSequence = $lastSequence + 1;
        }

        return $prefix . str_pad((string) $nextSequence, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Ensure resident has a unique account number and return it.
     */
    public function ensureResidentAccountNumber(): ?string
    {
        if (!$this->isResident()) {
            return $this->account_number;
        }

        if (!empty($this->account_number)) {
            return $this->account_number;
        }

        $accountNumber = static::generateAccountNumber(
            $this->first_name ?? 'R',
            $this->middle_name,
            $this->surname ?? 'Resident',
            $this->birthdate?->format('Y-m-d')
        );

        $this->account_number = $accountNumber;
        $this->save();

        return $accountNumber;
    }
}
