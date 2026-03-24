<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Auth;

use App\Http\Resources\CurrentUserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class AuthenticatedUserController
{
    public function __invoke(Request $request): JsonResponse
    {
        return response()->json([
            'data' => new CurrentUserResource($request->user()),
        ]);
    }
}
