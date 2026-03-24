<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_applicant_can_login_via_public_login(): void
    {
        User::factory()->create([
            'email' => 'student@example.com',
            'password' => 'password123',
            'role' => UserRole::Applicant,
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'student@example.com',
            'password' => 'password123',
        ]);

        $response
            ->assertOk()
            ->assertJsonPath('data.role', 'applicant')
            ->assertJsonStructure([
                'token',
                'data' => ['id', 'display_name', 'email', 'role'],
            ]);
    }

    public function test_employer_can_login_via_public_login(): void
    {
        User::factory()->create([
            'email' => 'employer@example.com',
            'password' => 'password123',
            'role' => UserRole::Employer,
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'employer@example.com',
            'password' => 'password123',
        ]);

        $response
            ->assertOk()
            ->assertJsonPath('data.role', 'employer');
    }

    public function test_curator_cannot_login_via_public_login(): void
    {
        User::factory()->create([
            'email' => 'curator@example.com',
            'password' => 'password123',
            'role' => UserRole::Curator,
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'curator@example.com',
            'password' => 'password123',
        ]);

        $response->assertForbidden();
    }

    public function test_curator_can_login_via_curator_login(): void
    {
        User::factory()->create([
            'email' => 'curator@example.com',
            'password' => 'password123',
            'role' => UserRole::Curator,
        ]);

        $response = $this->postJson('/api/auth/login-curator', [
            'email' => 'curator@example.com',
            'password' => 'password123',
        ]);

        $response
            ->assertOk()
            ->assertJsonPath('data.role', 'curator');
    }

    public function test_admin_can_login_via_curator_login(): void
    {
        User::factory()->create([
            'email' => 'admin@example.com',
            'password' => 'password123',
            'role' => UserRole::Admin,
        ]);

        $response = $this->postJson('/api/auth/login-curator', [
            'email' => 'admin@example.com',
            'password' => 'password123',
        ]);

        $response
            ->assertOk()
            ->assertJsonPath('data.role', 'admin');
    }

    public function test_invalid_credentials_are_rejected(): void
    {
        User::factory()->create([
            'email' => 'student@example.com',
            'password' => 'password123',
            'role' => UserRole::Applicant,
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'student@example.com',
            'password' => 'wrong-password',
        ]);

        $response->assertUnprocessable();
    }
}
