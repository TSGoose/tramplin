<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Employer;

use App\Enums\OpportunityStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Employer\UpsertEmployerOpportunityRequest;
use App\Http\Resources\OpportunityResource;
use App\Models\Opportunity;
use App\Services\Employer\EnsureEmployerCompanyService;
use App\Services\Employer\EnsureEmployerUserService;
use Illuminate\Http\JsonResponse;

final class EmployerOpportunityStoreController extends Controller
{
    public function __invoke(
        UpsertEmployerOpportunityRequest $request,
        EnsureEmployerUserService $ensureEmployerUserService,
        EnsureEmployerCompanyService $ensureEmployerCompanyService,
    ): JsonResponse {
        $user = $ensureEmployerUserService->handle($request->user());
        $company = $ensureEmployerCompanyService->handle($user);

        $payload = $request->validated();
        $tagIds = $payload['tag_ids'] ?? [];
        unset($payload['tag_ids']);

        $opportunity = new Opportunity([
            ...$payload,
            'status' => OpportunityStatus::Draft,
        ]);

        $opportunity->company()->associate($company);
        $opportunity->save();
        $opportunity->refresh();
        $opportunity->tags()->sync($tagIds);
        $opportunity->load(['company', 'tags']);

        return response()->json([
            'data' => OpportunityResource::make($opportunity)->resolve(),
        ], 201);
    }
}
