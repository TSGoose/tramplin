<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\CurrentUserResource;
use App\Models\User;
use App\Services\Curator\EnsureCuratorUserService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Request;

final class AdminUserIndexController extends Controller
{
    public function __invoke(
        Request $request,
        EnsureCuratorUserService $ensureCuratorUserService,
    ): AnonymousResourceCollection {
        $ensureCuratorUserService->ensureAdmin($request->user());

        $query = User::query()->latest();

        $role = $request->string('role')->toString();

        if ($role !== '') {
            $query->where('role', $role);
        }

        return CurrentUserResource::collection($query->get());
    }
}
