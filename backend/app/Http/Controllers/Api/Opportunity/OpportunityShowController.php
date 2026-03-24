<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Opportunity;

use App\Enums\OpportunityStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\OpportunityResource;
use App\Models\Opportunity;
use Illuminate\Http\JsonResponse;

final class OpportunityShowController extends Controller
{
    public function __invoke(Opportunity $opportunity): JsonResponse
    {
        abort_if($opportunity->status !== OpportunityStatus::Published, 404);

        $opportunity->load(['company', 'tags']);

        return response()->json([
            'data' => OpportunityResource::make($opportunity)->resolve(),
        ]);
    }
}
