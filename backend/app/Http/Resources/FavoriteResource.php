<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Favorite */
final class FavoriteResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'planned_apply_at' => $this->resource->planned_apply_at?->toISOString(),
            'opportunity' => OpportunityResource::make($this->resource->opportunity)->resolve(),
        ];
    }
}
