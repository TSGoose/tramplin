<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Applicant;

use App\Http\Controllers\Controller;
use App\Http\Resources\FavoriteResource;
use App\Models\Favorite;
use App\Models\Opportunity;
use App\Services\Applicant\EnsureApplicantUserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class StoreFavoriteController extends Controller
{
    public function __invoke(
        Request $request,
        Opportunity $opportunity,
        EnsureApplicantUserService $ensureApplicantUserService,
    ): JsonResponse {
        $user = $ensureApplicantUserService->handle($request->user());

        $favorite = Favorite::query()->firstOrCreate([
            'user_id' => $user->id,
            'opportunity_id' => $opportunity->id,
        ]);

        $favorite->load(['opportunity.company', 'opportunity.tags']);

        return response()->json([
            'data' => FavoriteResource::make($favorite)->resolve(),
        ], 201);
    }
}
