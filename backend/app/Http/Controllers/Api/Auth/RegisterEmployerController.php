<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Auth;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\RegisterEmployerRequest;
use App\Http\Resources\CurrentUserResource;
use App\Services\Auth\RegisterUserService;
use Illuminate\Http\JsonResponse;

final class RegisterEmployerController extends Controller
{
    public function __invoke(
        RegisterEmployerRequest $request,
        RegisterUserService $registerUserService,
    ): JsonResponse {
        $user = $registerUserService->handle(
            $request->validated(),
            UserRole::Employer,
        );

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'data' => CurrentUserResource::make($user)->resolve(),
        ], 201);
    }
}
