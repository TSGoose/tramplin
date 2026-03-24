<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Opportunity;

use App\Enums\OpportunityStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Opportunity\IndexOpportunityRequest;
use App\Http\Resources\OpportunityResource;
use App\Models\Opportunity;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

final class OpportunityIndexController extends Controller
{
    public function __invoke(IndexOpportunityRequest $request): AnonymousResourceCollection
    {
        $query = Opportunity::query()
            ->with(['company', 'tags'])
            ->where('status', OpportunityStatus::Published);

        $validated = $request->validated();

        if (($validated['search'] ?? null) !== null) {
            $search = trim((string) $validated['search']);

            $query->where(static function ($builder) use ($search): void {
                $builder
                    ->where('title', 'ilike', "%{$search}%")
                    ->orWhere('short_description', 'ilike', "%{$search}%")
                    ->orWhereHas('company', static function ($companyQuery) use ($search): void {
                        $companyQuery->where('name', 'ilike', "%{$search}%");
                    });
            });
        }

        if (($validated['type'] ?? null) !== null) {
            $query->where('type', $validated['type']);
        }

        if (($validated['work_format'] ?? null) !== null) {
            $query->where('work_format', $validated['work_format']);
        }

        if (($validated['city'] ?? null) !== null) {
            $query->where('city', $validated['city']);
        }

        if (($validated['tag'] ?? null) !== null) {
            $tag = trim((string) $validated['tag']);

            $query->whereHas('tags', static function ($tagQuery) use ($tag): void {
                $tagQuery
                    ->where('slug', $tag)
                    ->orWhere('name', 'ilike', "%{$tag}%");
            });
        }

        return OpportunityResource::collection(
            $query
                ->latest('published_at')
                ->paginate(12)
        );
    }
}
