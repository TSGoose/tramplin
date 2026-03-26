<?php

declare(strict_types=1);

namespace Tests\Feature\Employer;

use App\Enums\CompanyVerificationStatus;
use App\Enums\OpportunityStatus;
use App\Enums\UserRole;
use App\Models\Company;
use App\Models\Opportunity;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class EmployerRevisionFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_employer_can_resubmit_company_after_needs_revision(): void
    {
        $user = User::factory()->create([
            'role' => UserRole::Employer,
        ]);

        Company::factory()->create([
            'owner_user_id' => $user->id,
            'name' => 'Digital Horizon',
            'description' => 'Описание компании',
            'website_url' => 'https://example.com',
            'verification_status' => CompanyVerificationStatus::NeedsRevision,
            'verification_comment' => 'Уточните данные о компании.',
        ]);

        $response = $this
            ->actingAs($user, 'sanctum')
            ->postJson('/api/employer/company/verification-submit');

        $response
            ->assertOk()
            ->assertJsonPath('data.verification_status', CompanyVerificationStatus::PendingVerification->value)
            ->assertJsonPath('data.verification_comment', null);
    }

    public function test_employer_can_resubmit_opportunity_after_needs_revision(): void
    {
        $user = User::factory()->create([
            'role' => UserRole::Employer,
        ]);

        $company = Company::factory()->create([
            'owner_user_id' => $user->id,
            'verification_status' => CompanyVerificationStatus::Verified,
        ]);

        $opportunity = Opportunity::factory()->create([
            'company_id' => $company->id,
            'status' => OpportunityStatus::NeedsRevision,
            'moderation_comment' => 'Добавьте более подробное описание.',
        ]);

        $response = $this
            ->actingAs($user, 'sanctum')
            ->postJson("/api/employer/opportunities/{$opportunity->id}/submit");

        $response
            ->assertOk()
            ->assertJsonPath('data.status', OpportunityStatus::PendingModeration->value)
            ->assertJsonPath('data.moderation_comment', null);
    }
}
