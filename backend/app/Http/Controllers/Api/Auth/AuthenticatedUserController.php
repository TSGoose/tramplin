<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\CurrentUserResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

final class AuthenticatedUserController extends Controller
{
    public function __invoke(Request $request): JsonResource
    {
        return CurrentUserResource::make($request->user());
    }
}
