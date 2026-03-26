<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Employer;

use App\Enums\CompanyVerificationStatus;
use App\Enums\OpportunityStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\OpportunityResource;
use App\Models\Opportunity;
use App\Services\Employer\EnsureEmployerCompanyService;
use App\Services\Employer\EnsureEmployerUserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class EmployerOpportunitySubmitController extends Controller
{
    public function __invoke(
        Request $request,
        Opportunity $opportunity,
        EnsureEmployerUserService $ensureEmployerUserService,
        EnsureEmployerCompanyService $ensureEmployerCompanyService,
    ): JsonResponse {
        $user = $ensureEmployerUserService->handle($request->user());
        $company = $ensureEmployerCompanyService->handle($user);

        if ($opportunity->company_id !== $company->id) {
            throw new HttpException(404);
        }

        if ($company->verification_status !== CompanyVerificationStatus::Verified) {
            throw new HttpException(422, 'Опубликовать возможность можно только после верификации компании.');
        }

        if (!in_array($opportunity->status, [OpportunityStatus::Draft, OpportunityStatus::NeedsRevision], true)) {
            throw new HttpException(422, 'Эту возможность нельзя повторно отправить на модерацию из текущего статуса.');
        }

        $opportunity->status = OpportunityStatus::PendingModeration;
        $opportunity->moderation_comment = null;
        $opportunity->save();
        $opportunity->refresh();
        $opportunity->load(['company', 'tags']);

        return response()->json([
            'data' => OpportunityResource::make($opportunity)->resolve(),
        ]);
    }
}
