<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Applicant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Applicant\UpdateApplicantProfileRequest;
use App\Http\Resources\ApplicantProfileResource;
use App\Services\Applicant\EnsureApplicantProfileService;
use Illuminate\Http\Resources\Json\JsonResource;

final class UpdateApplicantProfileController extends Controller
{
    public function __invoke(
        UpdateApplicantProfileRequest $request,
        EnsureApplicantProfileService $ensureApplicantProfileService,
    ): JsonResource {
        $profile = $ensureApplicantProfileService->handle($request->user());

        $profile->fill($request->validated());
        $profile->save();

        return ApplicantProfileResource::make($profile->fresh());
    }
}
