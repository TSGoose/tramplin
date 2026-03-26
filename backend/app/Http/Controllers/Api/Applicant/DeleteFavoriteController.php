<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Applicant;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use App\Models\Opportunity;
use App\Services\Applicant\EnsureApplicantUserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class DeleteFavoriteController extends Controller
{
    public function __invoke(
        Request $request,
        Opportunity $opportunity,
        EnsureApplicantUserService $ensureApplicantUserService,
    ): JsonResponse {
        $user = $ensureApplicantUserService->handle($request->user());

        Favorite::query()
            ->where('user_id', $user->id)
            ->where('opportunity_id', $opportunity->id)
            ->delete();

        return response()->json([], 204);
    }
}
