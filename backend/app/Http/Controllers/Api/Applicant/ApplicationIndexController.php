<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Applicant;

use App\Http\Controllers\Controller;
use App\Http\Resources\ApplicationResource;
use App\Services\Applicant\EnsureApplicantProfileService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Request;

final class ApplicationIndexController extends Controller
{
    public function __invoke(
        Request $request,
        EnsureApplicantProfileService $ensureApplicantProfileService,
    ): AnonymousResourceCollection {
        $profile = $ensureApplicantProfileService->handle($request->user());

        $applications = $profile->applications()
            ->with(['opportunity.company', 'opportunity.tags'])
            ->latest()
            ->get();

        return ApplicationResource::collection($applications);
    }
}
