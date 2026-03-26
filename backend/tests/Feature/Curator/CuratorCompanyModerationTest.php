<?php

declare(strict_types=1);

namespace Tests\Feature\Curator;

use App\Enums\CompanyVerificationStatus;
use App\Enums\UserRole;
use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class CuratorCompanyModerationTest extends TestCase
{
    use RefreshDatabase;

    public function test_curator_can_list_companies_for_moderation(): void
    {
        $curator = User::factory()->create([
            'role' => UserRole::Curator,
        ]);

        Company::factory()->create([
            'verification_status' => CompanyVerificationStatus::PendingVerification,
            'name' => 'Pending Company',
        ]);

        Company::factory()->create([
            'verification_status' => CompanyVerificationStatus::Verified,
            'name' => 'Verified Company',
        ]);

        $response = $this
            ->actingAs($curator, 'sanctum')
            ->getJson('/api/curator/companies');

        $response
            ->assertOk()
            ->assertJsonFragment(['name' => 'Pending Company'])
            ->assertJsonMissing(['name' => 'Verified Company']);
    }

    public function test_curator_can_approve_company(): void
    {
        $curator = User::factory()->create([
            'role' => UserRole::Curator,
        ]);

        $company = Company::factory()->create([
            'verification_status' => CompanyVerificationStatus::PendingVerification,
        ]);

        $response = $this
            ->actingAs($curator, 'sanctum')
            ->patchJson("/api/curator/companies/{$company->id}/status", [
                'action' => 'approve',
                'comment' => 'Компания подтверждена.',
            ]);

        $response
            ->assertOk()
            ->assertJsonPath('data.verification_status', CompanyVerificationStatus::Verified->value);

        $this->assertDatabaseHas('audit_logs', [
            'entity_type' => 'company',
            'entity_id' => $company->id,
            'action' => 'approved',
        ]);
    }
}
