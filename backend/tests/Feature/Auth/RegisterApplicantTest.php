<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class RegisterApplicantTest extends TestCase
{
    use RefreshDatabase;

    public function test_applicant_can_register(): void
    {
        $response = $this->postJson('/api/auth/register/applicant', [
            'display_name' => 'Иван Петров',
            'email' => 'ivan@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response
            ->assertCreated()
            ->assertJsonPath('data.email', 'ivan@example.com')
            ->assertJsonPath('data.role', 'applicant')
            ->assertJsonStructure([
                'token',
                'data' => ['id', 'display_name', 'email', 'role'],
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'ivan@example.com',
            'role' => UserRole::Applicant->value,
        ]);
    }

    public function test_registration_requires_unique_email(): void
    {
        User::factory()->create([
            'email' => 'ivan@example.com',
            'role' => UserRole::Applicant,
        ]);

        $response = $this->postJson('/api/auth/register/applicant', [
            'display_name' => 'Иван Петров',
            'email' => 'ivan@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertUnprocessable();
    }
}
