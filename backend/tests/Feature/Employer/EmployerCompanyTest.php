<?php

declare(strict_types=1);

namespace Tests\Feature\Employer;

use App\Enums\CompanyVerificationStatus;
use App\Enums\UserRole;
use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class EmployerCompanyTest extends TestCase
{
    use RefreshDatabase;

    public function test_employer_can_fetch_company_and_it_is_created_if_missing(): void
    {
        $user = User::factory()->create([
            'role' => UserRole::Employer,
            'display_name' => 'Employer Owner',
        ]);

        $response = $this
            ->actingAs($user, 'sanctum')
            ->getJson('/api/employer/company');

        $response
            ->assertOk()
            ->assertJsonPath('data.owner_user_id', $user->id);

        $this->assertDatabaseHas('companies', [
            'owner_user_id' => $user->id,
        ]);
    }

    public function test_employer_can_update_company(): void
    {
        $user = User::factory()->create([
            'role' => UserRole::Employer,
        ]);

        Company::factory()->create([
            'owner_user_id' => $user->id,
        ]);

        $response = $this
            ->actingAs($user, 'sanctum')
            ->putJson('/api/employer/company', [
                'name' => 'Digital Horizon',
                'description' => 'Мы нанимаем студентов.',
                'industry' => 'IT',
                'website_url' => 'https://example.com',
                'social_url' => 'https://t.me/example',
                'inn' => '7701234567',
                'city' => 'Москва',
                'address' => 'Тверская 1',
            ]);

        $response
            ->assertOk()
            ->assertJsonPath('data.name', 'Digital Horizon');

        $this->assertDatabaseHas('companies', [
            'owner_user_id' => $user->id,
            'name' => 'Digital Horizon',
        ]);
    }

    public function test_employer_can_submit_company_for_verification(): void
    {
        $user = User::factory()->create([
            'role' => UserRole::Employer,
        ]);

        Company::factory()->create([
            'owner_user_id' => $user->id,
            'name' => 'Digital Horizon',
            'description' => 'Описание',
            'website_url' => 'https://example.com',
            'verification_status' => CompanyVerificationStatus::Draft,
        ]);

        $response = $this
            ->actingAs($user, 'sanctum')
            ->postJson('/api/employer/company/verification-submit');

        $response
            ->assertOk()
            ->assertJsonPath('data.verification_status', CompanyVerificationStatus::PendingVerification->value);
    }
}
