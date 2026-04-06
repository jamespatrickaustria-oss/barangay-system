<?php

namespace Tests\Unit;

use App\Models\OnlineId;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class OnlineIdTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_generates_the_first_id_for_the_current_year(): void
    {
        Carbon::setTestNow(Carbon::parse('2025-04-06 10:00:00'));

        try {
            $this->assertSame('GNT-27-2025-000001', OnlineId::generateIdNumber());
        } finally {
            Carbon::setTestNow();
        }
    }

    public function test_it_increments_the_sequence_within_the_same_year(): void
    {
        Carbon::setTestNow(Carbon::parse('2025-04-06 10:00:00'));

        try {
            $firstUser = User::create([
                'first_name' => 'Juan',
                'surname' => 'Cruz',
                'email' => 'juan.one@example.com',
                'password' => Hash::make('password123'),
                'role' => 'resident',
                'status' => 'approved',
            ]);

            $secondUser = User::create([
                'first_name' => 'Maria',
                'surname' => 'Santos',
                'email' => 'maria.two@example.com',
                'password' => Hash::make('password123'),
                'role' => 'resident',
                'status' => 'approved',
            ]);

            OnlineId::create([
                'user_id' => $firstUser->id,
                'id_number' => 'GNT-27-2025-000001',
                'issued_at' => now(),
            ]);

            OnlineId::create([
                'user_id' => $secondUser->id,
                'id_number' => 'GNT-27-2025-000002',
                'issued_at' => now(),
            ]);

            $this->assertSame('GNT-27-2025-000003', OnlineId::generateIdNumber());
        } finally {
            Carbon::setTestNow();
        }
    }
}