<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin AuditLog */
final class AuditLogResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'entity_type' => $this->resource->entity_type,
            'entity_id' => $this->resource->entity_id,
            'action' => $this->resource->action,
            'old_status' => $this->resource->old_status,
            'new_status' => $this->resource->new_status,
            'comment' => $this->resource->comment,
            'created_at' => $this->resource->created_at?->toISOString(),
            'actor' => $this->resource->actor
                ? [
                    'id' => $this->resource->actor->id,
                    'display_name' => $this->resource->actor->display_name,
                    'email' => $this->resource->actor->email,
                  ]
                : null,
        ];
    }
}
