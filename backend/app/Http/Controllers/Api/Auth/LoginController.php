<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Resources\CurrentUserResource;
use App\Services\Auth\LoginUserService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class LoginController extends Controller
{
    public function __invoke(
        LoginRequest $request,
        LoginUserService $loginUserService,
    ): JsonResponse {
        $user = $loginUserService->attempt(
            $request->string('email')->toString(),
            $request->string('password')->toString(),
        );

        if (!$user->role->canUsePublicLogin()) {
            throw new HttpException(403, 'Для этой роли используйте отдельный контур входа.');
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'data' => CurrentUserResource::make($user)->resolve(),
        ]);
    }
}
