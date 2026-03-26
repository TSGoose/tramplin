<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Company */
final class CompanyResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'owner_user_id' => $this->resource->owner_user_id,
            'name' => $this->resource->name,
            'description' => $this->resource->description,
            'industry' => $this->resource->industry,
            'website_url' => $this->resource->website_url,
            'social_url' => $this->resource->social_url,
            'inn' => $this->resource->inn,
            'city' => $this->resource->city,
            'address' => $this->resource->address,
            'verification_status' => $this->resource->verification_status?->value,
            'verification_comment' => $this->resource->verification_comment,
            'verified_at' => $this->resource->verified_at?->toISOString(),
        ];
    }
}
