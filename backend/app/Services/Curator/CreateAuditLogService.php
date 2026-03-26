<?php

declare(strict_types=1);

namespace App\Services\Curator;

use App\Models\AuditLog;
use App\Models\User;

final readonly class CreateAuditLogService
{
    public function handle(
        User $actor,
        string $entityType,
        int $entityId,
        string $action,
        ?string $oldStatus = null,
        ?string $newStatus = null,
        ?string $comment = null,
    ): AuditLog {
        return AuditLog::query()->create([
            'actor_user_id' => $actor->id,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'action' => $action,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'comment' => $comment,
        ]);
    }
}
