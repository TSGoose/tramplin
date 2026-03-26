<?php

declare(strict_types=1);

namespace Tests\Feature\Applicant;

use App\Enums\OpportunityStatus;
use App\Enums\UserRole;
use App\Models\Favorite;
use App\Models\Opportunity;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class FavoriteTest extends TestCase
{
    use RefreshDatabase;

    public function test_applicant_can_add_favorite(): void
    {
        $user = User::factory()->create([
            'role' => UserRole::Applicant,
        ]);

        $opportunity = Opportunity::factory()->create([
            'status' => OpportunityStatus::Published,
        ]);

        $response = $this
            ->actingAs($user, 'sanctum')
            ->postJson("/api/applicant/favorites/{$opportunity->id}");

        $response
            ->assertCreated()
            ->assertJsonPath('data.opportunity.id', $opportunity->id);

        $this->assertDatabaseHas('favorites', [
            'user_id' => $user->id,
            'opportunity_id' => $opportunity->id,
        ]);
    }

    public function test_applicant_can_list_favorites(): void
    {
        $user = User::factory()->create([
            'role' => UserRole::Applicant,
        ]);

        $opportunity = Opportunity::factory()->create([
            'status' => OpportunityStatus::Published,
        ]);

        Favorite::factory()->create([
            'user_id' => $user->id,
            'opportunity_id' => $opportunity->id,
        ]);

        $response = $this
            ->actingAs($user, 'sanctum')
            ->getJson('/api/applicant/favorites');

        $response
            ->assertOk()
            ->assertJsonFragment([
                'id' => $opportunity->id,
            ]);
    }

    public function test_applicant_can_remove_favorite(): void
    {
        $user = User::factory()->create([
            'role' => UserRole::Applicant,
        ]);

        $opportunity = Opportunity::factory()->create([
            'status' => OpportunityStatus::Published,
        ]);

        Favorite::factory()->create([
            'user_id' => $user->id,
            'opportunity_id' => $opportunity->id,
        ]);

        $response = $this
            ->actingAs($user, 'sanctum')
            ->deleteJson("/api/applicant/favorites/{$opportunity->id}");

        $response->assertNoContent();

        $this->assertDatabaseMissing('favorites', [
            'user_id' => $user->id,
            'opportunity_id' => $opportunity->id,
        ]);
    }
}
