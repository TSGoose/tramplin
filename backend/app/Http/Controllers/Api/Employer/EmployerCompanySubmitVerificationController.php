<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Employer;

use App\Enums\CompanyVerificationStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\CompanyResource;
use App\Services\Employer\EnsureEmployerCompanyService;
use App\Services\Employer\EnsureEmployerUserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class EmployerCompanySubmitVerificationController extends Controller
{
    public function __invoke(
        Request $request,
        EnsureEmployerUserService $ensureEmployerUserService,
        EnsureEmployerCompanyService $ensureEmployerCompanyService,
    ): JsonResponse {
        $user = $ensureEmployerUserService->handle($request->user());
        $company = $ensureEmployerCompanyService->handle($user);

        if (blank($company->name) || blank($company->description) || blank($company->website_url)) {
            throw new HttpException(422, 'Для отправки на верификацию заполните название, описание и сайт компании.');
        }

        if ($company->verification_status === CompanyVerificationStatus::Verified) {
            throw new HttpException(422, 'Компания уже верифицирована.');
        }

        $company->verification_status = CompanyVerificationStatus::PendingVerification;
        $company->verification_comment = null;
        $company->save();

        return response()->json([
            'data' => CompanyResource::make($company->fresh())->resolve(),
        ]);
    }
}
