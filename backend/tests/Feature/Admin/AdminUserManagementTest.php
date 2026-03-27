<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class AdminUserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_list_users(): void
    {
        $admin = User::factory()->create([
            'role' => UserRole::Admin,
        ]);

        User::factory()->create([
            'display_name' => 'Curator User',
            'role' => UserRole::Curator,
        ]);

        $response = $this
            ->actingAs($admin, 'sanctum')
            ->getJson('/api/admin/users');

        $response
            ->assertOk()
            ->assertJsonFragment([
                'display_name' => 'Curator User',
            ]);
    }

    public function test_admin_can_filter_users_by_role(): void
    {
        $admin = User::factory()->create([
            'role' => UserRole::Admin,
        ]);

        User::factory()->create([
            'display_name' => 'Curator User',
            'role' => UserRole::Curator,
        ]);

        User::factory()->create([
            'display_name' => 'Employer User',
            'role' => UserRole::Employer,
        ]);

        $response = $this
            ->actingAs($admin, 'sanctum')
            ->getJson('/api/admin/users?role=curator');

        $response
            ->assertOk()
            ->assertJsonFragment(['display_name' => 'Curator User'])
            ->assertJsonMissing(['display_name' => 'Employer User']);
    }

    public function test_admin_can_create_curator(): void
    {
        $admin = User::factory()->create([
            'role' => UserRole::Admin,
        ]);

        $response = $this
            ->actingAs($admin, 'sanctum')
            ->postJson('/api/admin/users/curator', [
                'display_name' => 'Новый куратор',
                'email' => 'new-curator@example.com',
                'password' => 'password123',
                'password_confirmation' => 'password123',
            ]);

        $response
            ->assertCreated()
            ->assertJsonPath('data.role', UserRole::Curator->value);

        $this->assertDatabaseHas('users', [
            'email' => 'new-curator@example.com',
            'role' => UserRole::Curator->value,
        ]);
    }

    public function test_non_admin_cannot_create_curator(): void
    {
        $curator = User::factory()->create([
            'role' => UserRole::Curator,
        ]);

        $response = $this
            ->actingAs($curator, 'sanctum')
            ->postJson('/api/admin/users/curator', [
                'display_name' => 'Новый куратор',
                'email' => 'new-curator@example.com',
                'password' => 'password123',
                'password_confirmation' => 'password123',
            ]);

        $response->assertForbidden();
    }
}
