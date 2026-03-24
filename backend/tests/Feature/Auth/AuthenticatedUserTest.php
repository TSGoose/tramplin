<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class AuthenticatedUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_fetch_me(): void
    {
        $user = User::factory()->create([
            'role' => UserRole::Applicant,
        ]);

        $response = $this
            ->actingAs($user, 'sanctum')
            ->getJson('/api/auth/me');

        $response
            ->assertOk()
            ->assertJsonPath('data.id', $user->id)
            ->assertJsonPath('data.email', $user->email)
            ->assertJsonPath('data.role', 'applicant');
    }

    public function test_guest_cannot_fetch_me(): void
    {
        $response = $this->getJson('/api/auth/me');

        $response->assertUnauthorized();
    }

    public function test_authenticated_user_can_logout(): void
    {
        $user = User::factory()->create([
            'role' => UserRole::Applicant,
        ]);

        $token = $user->createToken('auth_token');

        $response = $this
            ->withHeader('Authorization', 'Bearer '.$token->plainTextToken)
            ->postJson('/api/auth/logout');

        $response->assertOk();

        $this->assertDatabaseCount('personal_access_tokens', 0);
    }
}
