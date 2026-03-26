<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Application */
final class ApplicationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'cover_letter' => $this->resource->cover_letter,
            'status' => $this->resource->status->value,
            'employer_comment' => $this->resource->employer_comment,
            'created_at' => $this->resource->created_at?->toISOString(),
            'opportunity' => OpportunityResource::make($this->resource->opportunity)->resolve(),
        ];
    }
}
