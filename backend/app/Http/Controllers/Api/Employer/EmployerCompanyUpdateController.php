<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Employer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Employer\UpdateCompanyRequest;
use App\Http\Resources\CompanyResource;
use App\Services\Employer\EnsureEmployerCompanyService;
use App\Services\Employer\EnsureEmployerUserService;
use Illuminate\Http\JsonResponse;

final class EmployerCompanyUpdateController extends Controller
{
    public function __invoke(
        UpdateCompanyRequest $request,
        EnsureEmployerUserService $ensureEmployerUserService,
        EnsureEmployerCompanyService $ensureEmployerCompanyService,
    ): JsonResponse {
        $user = $ensureEmployerUserService->handle($request->user());
        $company = $ensureEmployerCompanyService->handle($user);

        $company->fill($request->validated());
        $company->save();

        return response()->json([
            'data' => CompanyResource::make($company->fresh())->resolve(),
        ]);
    }
}
