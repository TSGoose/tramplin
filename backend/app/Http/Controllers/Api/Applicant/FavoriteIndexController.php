<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Applicant;

use App\Http\Controllers\Controller;
use App\Http\Resources\FavoriteResource;
use App\Models\Favorite;
use App\Services\Applicant\EnsureApplicantUserService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Request;

final class FavoriteIndexController extends Controller
{
    public function __invoke(
        Request $request,
        EnsureApplicantUserService $ensureApplicantUserService,
    ): AnonymousResourceCollection {
        $user = $ensureApplicantUserService->handle($request->user());

        $favorites = Favorite::query()
            ->with(['opportunity.company', 'opportunity.tags'])
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        return FavoriteResource::collection($favorites);
    }
}
