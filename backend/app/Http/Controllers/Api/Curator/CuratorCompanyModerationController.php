<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Curator;

use App\Enums\CompanyVerificationStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Curator\ModerationStatusRequest;
use App\Http\Resources\CompanyResource;
use App\Models\Company;
use App\Services\Curator\CreateAuditLogService;
use App\Services\Curator\EnsureCuratorUserService;
use Illuminate\Http\JsonResponse;

final class CuratorCompanyModerationController extends Controller
{
    public function __invoke(
        ModerationStatusRequest $request,
        Company $company,
        EnsureCuratorUserService $ensureCuratorUserService,
        CreateAuditLogService $createAuditLogService,
    ): JsonResponse {
        $actor = $ensureCuratorUserService->handle($request->user());

        $oldStatus = $company->verification_status?->value;

        [$newStatus, $action] = match ($request->string('action')->toString()) {
            'approve' => [CompanyVerificationStatus::Verified, 'approved'],
            'reject' => [CompanyVerificationStatus::Rejected, 'rejected'],
            'needs_revision' => [CompanyVerificationStatus::NeedsRevision, 'needs_revision'],
        };

        $company->verification_status = $newStatus;
        $company->verification_comment = $request->input('comment');

        if ($newStatus === CompanyVerificationStatus::Verified) {
            $company->verified_at = now();
        }

        $company->save();
        $company->refresh();

        $createAuditLogService->handle(
            actor: $actor,
            entityType: 'company',
            entityId: $company->id,
            action: $action,
            oldStatus: $oldStatus,
            newStatus: $company->verification_status?->value,
            comment: $request->input('comment'),
        );

        return response()->json([
            'data' => CompanyResource::make($company)->resolve(),
        ]);
    }
}
