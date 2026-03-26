<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Employer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Employer\UpsertEmployerOpportunityRequest;
use App\Http\Resources\OpportunityResource;
use App\Models\Opportunity;
use App\Services\Employer\EnsureEmployerCompanyService;
use App\Services\Employer\EnsureEmployerUserService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class EmployerOpportunityUpdateController extends Controller
{
    public function __invoke(
        UpsertEmployerOpportunityRequest $request,
        Opportunity $opportunity,
        EnsureEmployerUserService $ensureEmployerUserService,
        EnsureEmployerCompanyService $ensureEmployerCompanyService,
    ): JsonResponse {
        $user = $ensureEmployerUserService->handle($request->user());
        $company = $ensureEmployerCompanyService->handle($user);

        if ($opportunity->company_id !== $company->id) {
            throw new HttpException(404);
        }

        $payload = $request->validated();
        $tagIds = $payload['tag_ids'] ?? [];
        unset($payload['tag_ids']);

        $opportunity->fill($payload);
        $opportunity->save();
        $opportunity->tags()->sync($tagIds);
        $opportunity->load(['company', 'tags']);

        return response()->json([
            'data' => OpportunityResource::make($opportunity)->resolve(),
        ]);
    }
}
