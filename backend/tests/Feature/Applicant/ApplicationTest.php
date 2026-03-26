<?php

declare(strict_types=1);

namespace Tests\Feature\Applicant;

use App\Enums\ApplicationStatus;
use App\Enums\OpportunityStatus;
use App\Enums\UserRole;
use App\Models\ApplicantProfile;
use App\Models\Application;
use App\Models\Opportunity;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class ApplicationTest extends TestCase
{
    use RefreshDatabase;

    public function test_applicant_can_apply_to_published_opportunity(): void
    {
        $user = User::factory()->create([
            'role' => UserRole::Applicant,
        ]);

        $profile = ApplicantProfile::factory()->create([
            'user_id' => $user->id,
        ]);

        $opportunity = Opportunity::factory()->create([
            'status' => OpportunityStatus::Published,
        ]);

        $response = $this
            ->actingAs($user, 'sanctum')
            ->postJson('/api/applicant/applications', [
                'opportunity_id' => $opportunity->id,
                'cover_letter' => 'Очень заинтересован в этой возможности.',
            ]);

        $response
            ->assertCreated()
            ->assertJsonPath('data.status', ApplicationStatus::New->value)
            ->assertJsonPath('data.opportunity.id', $opportunity->id);

        $this->assertDatabaseHas('applications', [
            'opportunity_id' => $opportunity->id,
            'applicant_profile_id' => $profile->id,
            'status' => ApplicationStatus::New->value,
        ]);
    }

    public function test_applicant_can_list_applications(): void
    {
        $user = User::factory()->create([
            'role' => UserRole::Applicant,
        ]);

        $profile = ApplicantProfile::factory()->create([
            'user_id' => $user->id,
        ]);

        $opportunity = Opportunity::factory()->create([
            'status' => OpportunityStatus::Published,
        ]);

        Application::factory()->create([
            'opportunity_id' => $opportunity->id,
            'applicant_profile_id' => $profile->id,
            'status' => ApplicationStatus::Reviewing,
        ]);

        $response = $this
            ->actingAs($user, 'sanctum')
            ->getJson('/api/applicant/applications');

        $response
            ->assertOk()
            ->assertJsonFragment([
                'status' => ApplicationStatus::Reviewing->value,
            ]);
    }

    public function test_non_applicant_cannot_apply(): void
    {
        $user = User::factory()->create([
            'role' => UserRole::Employer,
        ]);

        $opportunity = Opportunity::factory()->create([
            'status' => OpportunityStatus::Published,
        ]);

        $response = $this
            ->actingAs($user, 'sanctum')
            ->postJson('/api/applicant/applications', [
                'opportunity_id' => $opportunity->id,
                'cover_letter' => 'test',
            ]);

        $response->assertForbidden();
    }
}
