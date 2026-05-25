<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ApiAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_login_and_view_dashboard_metrics(): void
    {
        Role::findOrCreate('Super Admin');

        $user = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => 'password',
        ]);
        $user->assignRole('Super Admin');

        $token = $this->postJson('/api/login', [
            'email' => 'admin@example.com',
            'password' => 'password',
        ])->assertOk()->json('data.token');

        $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson('/api/dashboard/admin')
            ->assertOk()
            ->assertJsonPath('success', true);
    }
}
