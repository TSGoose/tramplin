<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use App\Enums\UserRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class RegisterEmployerTest extends TestCase
{
    use RefreshDatabase;

    public function test_employer_can_register(): void
    {
        $response = $this->postJson('/api/auth/register/employer', [
            'display_name' => 'Анна Смирнова',
            'email' => 'anna@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response
            ->assertCreated()
            ->assertJsonPath('data.role', 'employer');

        $this->assertDatabaseHas('users', [
            'email' => 'anna@example.com',
            'role' => UserRole::Employer->value,
        ]);
    }
}
