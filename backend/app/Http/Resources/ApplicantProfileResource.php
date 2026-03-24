<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\ApplicantProfile;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin ApplicantProfile */
final class ApplicantProfileResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'user_id' => $this->resource->user_id,
            'full_name' => $this->resource->full_name,
            'university_name' => $this->resource->university_name,
            'course' => $this->resource->course,
            'graduation_year' => $this->resource->graduation_year,
            'about' => $this->resource->about,
            'resume_file_path' => $this->resource->resume_file_path,
            'portfolio_url' => $this->resource->portfolio_url,
            'github_url' => $this->resource->github_url,
            'privacy_level' => $this->resource->privacy_level->value,
            'preferred_work_formats' => $this->resource->preferred_work_formats ?? [],
            'preferred_cities' => $this->resource->preferred_cities ?? [],
            'profile_views_count' => $this->resource->profile_views_count,
            'moderation_status' => $this->resource->moderation_status->value,
            'moderation_comment' => $this->resource->moderation_comment,
        ];
    }
}
