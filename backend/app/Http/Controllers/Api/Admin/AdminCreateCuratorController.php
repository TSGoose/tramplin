<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Admin\CreateCuratorRequest;
use App\Http\Resources\CurrentUserResource;
use App\Services\Auth\RegisterUserService;
use App\Services\Curator\CreateAuditLogService;
use App\Services\Curator\EnsureCuratorUserService;
use Illuminate\Http\JsonResponse;

final class AdminCreateCuratorController extends Controller
{
    public function __invoke(
        CreateCuratorRequest $request,
        EnsureCuratorUserService $ensureCuratorUserService,
        RegisterUserService $registerUserService,
        CreateAuditLogService $createAuditLogService,
    ): JsonResponse {
        $actor = $ensureCuratorUserService->ensureAdmin($request->user());

        $user = $registerUserService->handle(
            $request->validated(),
            UserRole::Curator,
        );

        $createAuditLogService->handle(
            actor: $actor,
            entityType: 'user',
            entityId: $user->id,
            action: 'created_curator',
            oldStatus: null,
            newStatus: UserRole::Curator->value,
            comment: 'Создан новый куратор',
        );

        return response()->json([
            'data' => CurrentUserResource::make($user)->resolve(),
        ], 201);
    }
}
