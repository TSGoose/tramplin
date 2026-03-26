<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Employer;

use App\Http\Controllers\Controller;
use App\Http\Resources\OpportunityResource;
use App\Services\Employer\EnsureEmployerCompanyService;
use App\Services\Employer\EnsureEmployerUserService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Request;

final class EmployerOpportunityIndexController extends Controller
{
    public function __invoke(
        Request $request,
        EnsureEmployerUserService $ensureEmployerUserService,
        EnsureEmployerCompanyService $ensureEmployerCompanyService,
    ): AnonymousResourceCollection {
        $user = $ensureEmployerUserService->handle($request->user());
        $company = $ensureEmployerCompanyService->handle($user);

        return OpportunityResource::collection(
            $company->opportunities()
                ->with(['company', 'tags'])
                ->latest()
                ->get()
        );
    }
}
