<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Applicant;

use App\Http\Controllers\Controller;
use App\Http\Resources\ApplicantProfileResource;
use App\Services\Applicant\EnsureApplicantProfileService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class ApplicantProfileController extends Controller
{
    public function __invoke(
        Request $request,
        EnsureApplicantProfileService $ensureApplicantProfileService,
    ): JsonResponse {
        $profile = $ensureApplicantProfileService->handle($request->user());

        return response()->json([
            'data' => ApplicantProfileResource::make($profile)->resolve(),
        ], 200);
    }
}
