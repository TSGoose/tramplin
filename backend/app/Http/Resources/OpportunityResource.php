<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Opportunity;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Opportunity */
final class OpportunityResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'title' => $this->resource->title,
            'short_description' => $this->resource->short_description,
            'full_description' => $this->resource->full_description,
            'type' => $this->resource->type?->value,
            'work_format' => $this->resource->work_format?->value,
            'employment_type' => $this->resource->employment_type?->value,
            'level' => $this->resource->level?->value,
            'city' => $this->resource->city,
            'address' => $this->resource->address,
            'latitude' => $this->resource->latitude,
            'longitude' => $this->resource->longitude,
            'is_remote' => $this->resource->is_remote,
            'published_at' => $this->resource->published_at?->toISOString(),
            'expires_at' => $this->resource->expires_at?->toISOString(),
            'event_date' => $this->resource->event_date?->toISOString(),
            'salary_from' => $this->resource->salary_from,
            'salary_to' => $this->resource->salary_to,
            'contacts_text' => $this->resource->contacts_text,
            'external_url' => $this->resource->external_url,
            'company' => [
                'id' => $this->resource->company->id,
                'name' => $this->resource->company->name,
                'city' => $this->resource->company->city,
            ],
            'tags' => TagResource::collection($this->resource->tags)->resolve(),
        ];
    }
}
