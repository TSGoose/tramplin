<?php

declare(strict_types=1);

namespace Tests\Feature\Curator;

use App\Enums\UserRole;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class CuratorAuditLogTest extends TestCase
{
    use RefreshDatabase;

    public function test_curator_can_view_audit_logs(): void
    {
        $curator = User::factory()->create([
            'role' => UserRole::Curator,
        ]);

        AuditLog::factory()->create([
            'actor_user_id' => $curator->id,
            'entity_type' => 'company',
            'action' => 'approved',
        ]);

        $response = $this
            ->actingAs($curator, 'sanctum')
            ->getJson('/api/curator/audit-logs');

        $response
            ->assertOk()
            ->assertJsonFragment([
                'entity_type' => 'company',
                'action' => 'approved',
            ]);
    }
}
