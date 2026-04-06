<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserAccountNumberTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_generates_the_first_account_number_for_the_current_year(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-04-06 10:00:00'));

        try {
            $this->assertSame('GNT-27-2026-000001', User::generateAccountNumber('Juan', null, 'Cruz', null));
        } finally {
            Carbon::setTestNow();
        }
    }

    public function test_it_increments_the_account_number_sequence_within_the_same_year(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-04-06 10:00:00'));

        try {
            User::create([
                'first_name' => 'Juan',
                'surname' => 'Cruz',
                'email' => 'juan.account@example.com',
                'password' => Hash::make('password123'),
                'role' => 'resident',
                'status' => 'approved',
                'account_number' => 'GNT-27-2026-000001',
            ]);

            User::create([
                'first_name' => 'Maria',
                'surname' => 'Santos',
                'email' => 'maria.account@example.com',
                'password' => Hash::make('password123'),
                'role' => 'resident',
                'status' => 'approved',
                'account_number' => 'GNT-27-2026-000002',
            ]);

            $this->assertSame('GNT-27-2026-000003', User::generateAccountNumber('Ana', null, 'Lopez', null));
        } finally {
            Carbon::setTestNow();
        }
    }
}