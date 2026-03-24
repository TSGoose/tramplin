<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\CuratorLoginRequest;
use App\Http\Resources\CurrentUserResource;
use App\Services\Auth\LoginUserService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class CuratorLoginController extends Controller
{
    public function __invoke(
        CuratorLoginRequest $request,
        LoginUserService $loginUserService,
    ): JsonResponse {
        $user = $loginUserService->attempt(
            $request->string('email')->toString(),
            $request->string('password')->toString(),
        );

        if (!$user->role->canUseCuratorLogin()) {
            throw new HttpException(403, 'Вход куратора доступен только для куратора и администратора.');
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'data' => CurrentUserResource::make($user)->resolve(),
        ]);
    }
}
