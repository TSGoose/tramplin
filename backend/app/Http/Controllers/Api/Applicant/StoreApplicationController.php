<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Applicant;

use App\Enums\ApplicationStatus;
use App\Enums\OpportunityStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Applicant\CreateApplicationRequest;
use App\Http\Resources\ApplicationResource;
use App\Models\Application;
use App\Models\Opportunity;
use App\Services\Applicant\EnsureApplicantProfileService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class StoreApplicationController extends Controller
{
    public function __invoke(
        CreateApplicationRequest $request,
        EnsureApplicantProfileService $ensureApplicantProfileService,
    ): JsonResponse {
        $profile = $ensureApplicantProfileService->handle($request->user());

        /** @var Opportunity $opportunity */
        $opportunity = Opportunity::query()->findOrFail($request->integer('opportunity_id'));

        if ($opportunity->status !== OpportunityStatus::Published) {
            throw new HttpException(404, 'Невозможно откликнуться на неопубликованную возможность.');
        }

        $application = Application::query()->firstOrCreate(
            [
                'opportunity_id' => $opportunity->id,
                'applicant_profile_id' => $profile->id,
            ],
            [
                'cover_letter' => $request->input('cover_letter'),
                'status' => ApplicationStatus::New,
            ],
        );

        $application->refresh();
        $application->load(['opportunity.company', 'opportunity.tags']);

        return response()->json([
            'data' => ApplicationResource::make($application)->resolve(),
        ], 201);
    }
}
