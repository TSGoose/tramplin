<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Curator;

use App\Enums\OpportunityStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\OpportunityResource;
use App\Models\Opportunity;
use App\Services\Curator\EnsureCuratorUserService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Request;

final class CuratorOpportunityIndexController extends Controller
{
    public function __invoke(
        Request $request,
        EnsureCuratorUserService $ensureCuratorUserService,
    ): AnonymousResourceCollection {
        $ensureCuratorUserService->handle($request->user());

        return OpportunityResource::collection(
            Opportunity::query()
                ->with(['company', 'tags'])
                ->latest()
                ->whereIn('status', [
                    OpportunityStatus::PendingModeration,
                    OpportunityStatus::NeedsRevision,
                ])
                ->get()
        );
    }
}
