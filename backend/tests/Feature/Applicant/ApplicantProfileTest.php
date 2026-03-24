<?php

declare(strict_types=1);

namespace Tests\Feature\Applicant;

use App\Enums\ProfilePrivacyLevel;
use App\Enums\UserRole;
use App\Models\ApplicantProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class ApplicantProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_applicant_can_fetch_own_profile(): void
    {
        $user = User::factory()->create([
            'role' => UserRole::Applicant,
        ]);

        ApplicantProfile::factory()->create([
            'user_id' => $user->id,
            'full_name' => 'Иван Петров',
        ]);

        $response = $this
            ->actingAs($user, 'sanctum')
            ->getJson('/api/applicant/profile');

        $response
            ->assertOk()
            ->assertJsonPath('data.full_name', 'Иван Петров');
    }

    public function test_profile_is_created_automatically_for_applicant_if_missing(): void
    {
        $user = User::factory()->create([
            'role' => UserRole::Applicant,
            'display_name' => 'Новый студент',
        ]);

        $response = $this
            ->actingAs($user, 'sanctum')
            ->getJson('/api/applicant/profile');

        $response
            ->assertOk()
            ->assertJsonPath('data.full_name', 'Новый студент')
            ->assertJsonPath('data.privacy_level', 'platform_visible');

        $this->assertDatabaseHas('applicant_profiles', [
            'user_id' => $user->id,
            'full_name' => 'Новый студент',
        ]);
    }

    public function test_non_applicant_cannot_access_applicant_profile(): void
    {
        $user = User::factory()->create([
            'role' => UserRole::Employer,
        ]);

        $response = $this
            ->actingAs($user, 'sanctum')
            ->getJson('/api/applicant/profile');

        $response->assertForbidden();
    }

    public function test_applicant_can_update_profile(): void
    {
        $user = User::factory()->create([
            'role' => UserRole::Applicant,
        ]);

        ApplicantProfile::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this
            ->actingAs($user, 'sanctum')
            ->putJson('/api/applicant/profile', [
                'full_name' => 'Иван Петров',
                'university_name' => 'МГТУ',
                'course' => 3,
                'graduation_year' => 2027,
                'about' => 'Frontend developer',
                'portfolio_url' => 'https://portfolio.example.com',
                'github_url' => 'https://github.com/ivan',
                'privacy_level' => ProfilePrivacyLevel::ContactsOnly->value,
                'preferred_work_formats' => ['remote', 'hybrid'],
                'preferred_cities' => ['Москва', 'Казань'],
            ]);

        $response
            ->assertOk()
            ->assertJsonPath('data.full_name', 'Иван Петров')
            ->assertJsonPath('data.privacy_level', 'contacts_only')
            ->assertJsonPath('data.preferred_cities.0', 'Москва');

        $this->assertDatabaseHas('applicant_profiles', [
            'user_id' => $user->id,
            'full_name' => 'Иван Петров',
            'privacy_level' => ProfilePrivacyLevel::ContactsOnly->value,
        ]);
    }
}
