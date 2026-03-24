<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Auth;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\RegisterApplicantRequest;
use App\Http\Resources\CurrentUserResource;
use App\Services\Auth\RegisterUserService;
use Illuminate\Http\JsonResponse;
use App\Services\Applicant\EnsureApplicantProfileService;

final class RegisterApplicantController extends Controller
{
    public function __invoke(
        RegisterApplicantRequest $request,
        RegisterUserService $registerUserService,
        EnsureApplicantProfileService $ensureApplicantProfileService,
    ): JsonResponse {
        $user = $registerUserService->handle(
            $request->validated(),
            UserRole::Applicant,
        );

        $ensureApplicantProfileService->handle($user);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'data' => CurrentUserResource::make($user)->resolve(),
        ], 201);
    }
}
