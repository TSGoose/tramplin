<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Employer;

use App\Http\Controllers\Controller;
use App\Http\Resources\CompanyResource;
use App\Services\Employer\EnsureEmployerCompanyService;
use App\Services\Employer\EnsureEmployerUserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class EmployerCompanyShowController extends Controller
{
    public function __invoke(
        Request $request,
        EnsureEmployerUserService $ensureEmployerUserService,
        EnsureEmployerCompanyService $ensureEmployerCompanyService,
    ): JsonResponse {
        $user = $ensureEmployerUserService->handle($request->user());
        $company = $ensureEmployerCompanyService->handle($user);

        return response()->json([
            'data' => CompanyResource::make($company)->resolve(),
        ]);
    }
}
