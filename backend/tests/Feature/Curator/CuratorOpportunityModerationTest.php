<?php

declare(strict_types=1);

namespace Tests\Feature\Curator;

use App\Enums\OpportunityStatus;
use App\Enums\UserRole;
use App\Models\Opportunity;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class CuratorOpportunityModerationTest extends TestCase
{
    use RefreshDatabase;

    public function test_curator_can_list_opportunities_for_moderation(): void
    {
        $curator = User::factory()->create([
            'role' => UserRole::Curator,
        ]);

        Opportunity::factory()->create([
            'status' => OpportunityStatus::PendingModeration,
            'title' => 'Pending Opportunity',
        ]);

        Opportunity::factory()->create([
            'status' => OpportunityStatus::Published,
            'title' => 'Published Opportunity',
        ]);

        $response = $this
            ->actingAs($curator, 'sanctum')
            ->getJson('/api/curator/opportunities');

        $response
            ->assertOk()
            ->assertJsonFragment(['title' => 'Pending Opportunity'])
            ->assertJsonMissing(['title' => 'Published Opportunity']);
    }

    public function test_curator_can_approve_opportunity(): void
    {
        $curator = User::factory()->create([
            'role' => UserRole::Curator,
        ]);

        $opportunity = Opportunity::factory()->create([
            'status' => OpportunityStatus::PendingModeration,
        ]);

        $response = $this
            ->actingAs($curator, 'sanctum')
            ->patchJson("/api/curator/opportunities/{$opportunity->id}/status", [
                'action' => 'approve',
                'comment' => 'Карточка проверена и допущена.',
            ]);

        $response
            ->assertOk()
            ->assertJsonPath('data.status', OpportunityStatus::Published->value);

        $this->assertDatabaseHas('audit_logs', [
            'entity_type' => 'opportunity',
            'entity_id' => $opportunity->id,
            'action' => 'approved',
        ]);
    }
}
