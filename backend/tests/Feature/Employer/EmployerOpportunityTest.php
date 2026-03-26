<?php

declare(strict_types=1);

namespace Tests\Feature\Employer;

use App\Enums\CompanyVerificationStatus;
use App\Enums\OpportunityStatus;
use App\Enums\UserRole;
use App\Models\Company;
use App\Models\Opportunity;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class EmployerOpportunityTest extends TestCase
{
    use RefreshDatabase;

    public function test_employer_can_create_own_opportunity(): void
    {
        $user = User::factory()->create([
            'role' => UserRole::Employer,
        ]);

        Company::factory()->create([
            'owner_user_id' => $user->id,
            'verification_status' => CompanyVerificationStatus::Verified,
        ]);

        $tag = Tag::factory()->create();

        $response = $this
            ->actingAs($user, 'sanctum')
            ->postJson('/api/employer/opportunities', [
                'title' => 'Junior Frontend Developer',
                'short_description' => 'Короткое описание',
                'full_description' => 'Полное описание возможности',
                'type' => 'vacancy',
                'work_format' => 'remote',
                'employment_type' => 'full_time',
                'level' => 'junior',
                'city' => 'Москва',
                'address' => 'Тверская 1',
                'latitude' => 55.7558,
                'longitude' => 37.6173,
                'is_remote' => true,
                'salary_from' => 80000,
                'salary_to' => 120000,
                'contacts_text' => 'hr@example.com',
                'external_url' => 'https://example.com/jobs/1',
                'tag_ids' => [$tag->id],
            ]);

        $response
            ->assertCreated()
            ->assertJsonPath('data.title', 'Junior Frontend Developer')
            ->assertJsonPath('data.status', OpportunityStatus::Draft->value);
    }

    public function test_employer_can_list_only_own_opportunities(): void
    {
        $firstEmployer = User::factory()->create([
            'role' => UserRole::Employer,
        ]);
        $secondEmployer = User::factory()->create([
            'role' => UserRole::Employer,
        ]);

        $firstCompany = Company::factory()->create([
            'owner_user_id' => $firstEmployer->id,
        ]);
        $secondCompany = Company::factory()->create([
            'owner_user_id' => $secondEmployer->id,
        ]);

        Opportunity::factory()->create([
            'company_id' => $firstCompany->id,
            'title' => 'My Opportunity',
        ]);

        Opportunity::factory()->create([
            'company_id' => $secondCompany->id,
            'title' => ' чужая opportunity',
        ]);

        $response = $this
            ->actingAs($firstEmployer, 'sanctum')
            ->getJson('/api/employer/opportunities');

        $response
            ->assertOk()
            ->assertJsonFragment(['title' => 'My Opportunity'])
            ->assertJsonMissing(['title' => ' чужая opportunity']);
    }

    public function test_employer_can_submit_opportunity_for_moderation_when_company_is_verified(): void
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
            'status' => OpportunityStatus::Draft,
        ]);

        $response = $this
            ->actingAs($user, 'sanctum')
            ->postJson("/api/employer/opportunities/{$opportunity->id}/submit");

        $response
            ->assertOk()
            ->assertJsonPath('data.status', OpportunityStatus::PendingModeration->value);
    }
}
