<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminUserApprovalTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_assign_a_role_while_approving_a_user(): void
    {
        $admin = User::create([
            'first_name' => 'Maria',
            'surname' => 'Santos',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'status' => 'approved',
        ]);

        $pendingUser = User::create([
            'first_name' => 'Juan',
            'surname' => 'Dela Cruz',
            'email' => 'juan@example.com',
            'password' => Hash::make('password123'),
            'role' => 'resident',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($admin)->post(route('admin.users.approve', $pendingUser), [
            'assigned_role' => 'official',
        ]);

        $response->assertRedirect();

        $pendingUser->refresh();

        $this->assertSame('approved', $pendingUser->status);
        $this->assertSame('official', $pendingUser->role);
        $this->assertSame($admin->id, $pendingUser->approved_by);
        $this->assertSame('admin', $pendingUser->approver_role);
        $this->assertNotNull($pendingUser->approved_at);
    }
}