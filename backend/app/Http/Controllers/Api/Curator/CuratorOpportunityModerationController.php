<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Curator;

use App\Enums\OpportunityStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Curator\ModerationStatusRequest;
use App\Http\Resources\OpportunityResource;
use App\Models\Opportunity;
use App\Services\Curator\CreateAuditLogService;
use App\Services\Curator\EnsureCuratorUserService;
use Illuminate\Http\JsonResponse;

final class CuratorOpportunityModerationController extends Controller
{
    public function __invoke(
        ModerationStatusRequest $request,
        Opportunity $opportunity,
        EnsureCuratorUserService $ensureCuratorUserService,
        CreateAuditLogService $createAuditLogService,
    ): JsonResponse {
        $actor = $ensureCuratorUserService->handle($request->user());

        $oldStatus = $opportunity->status?->value;

        [$newStatus, $action] = match ($request->string('action')->toString()) {
            'approve' => [OpportunityStatus::Published, 'approved'],
            'reject' => [OpportunityStatus::Rejected, 'rejected'],
            'needs_revision' => [OpportunityStatus::NeedsRevision, 'needs_revision'],
        };

        $opportunity->status = $newStatus;
        $opportunity->moderation_comment = $request->input('comment');

        if ($newStatus === OpportunityStatus::Published && $opportunity->published_at === null) {
            $opportunity->published_at = now();
        }

        $opportunity->save();
        $opportunity->refresh();
        $opportunity->load(['company', 'tags']);

        $createAuditLogService->handle(
            actor: $actor,
            entityType: 'opportunity',
            entityId: $opportunity->id,
            action: $action,
            oldStatus: $oldStatus,
            newStatus: $opportunity->status?->value,
            comment: $request->input('comment'),
        );

        return response()->json([
            'data' => OpportunityResource::make($opportunity)->resolve(),
        ]);
    }
}
