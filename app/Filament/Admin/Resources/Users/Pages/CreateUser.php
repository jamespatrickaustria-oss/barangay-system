<?php

namespace App\Filament\Admin\Resources\Users\Pages;

use App\Filament\Admin\Resources\Users\UserResource;
use App\Models\User;
use App\Services\MailService;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Set the role to 'official' for accounts created from admin panel
        $data['role'] = 'official';
        
        // Set status to pending until email is verified
        $data['status'] = 'pending';
        
        // Generate email verification token
        $data['email_verification_token'] = Str::random(64);
        
        // Hash the password if provided
        if (!empty($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }

        // Generate unique account number
        $data['account_number'] = User::generateAccountNumber(
            $data['first_name'],
            $data['middle_name'] ?? null,
            $data['surname'],
            $data['birthdate'] ?? null
        );
        
        return $data;
    }

    protected function afterCreate(): void
    {
        $user = $this->record;
        
        // Generate verification link
        $verificationLink = route('verify-official-email', [
            'token' => $user->email_verification_token,
            'email' => urlencode($user->email),
        ]);
        
        // Send verification email
        MailService::send(
            $user->email,
            $user->getFullName(),
            'Verify Your Barangay Official Account',
            MailService::officialEmailVerification($user->getFullName(), $verificationLink)
        );
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}

