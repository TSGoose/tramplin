<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Curator;

use App\Http\Controllers\Controller;
use App\Http\Resources\AuditLogResource;
use App\Models\AuditLog;
use App\Services\Curator\EnsureCuratorUserService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Request;

final class CuratorAuditLogIndexController extends Controller
{
    public function __invoke(
        Request $request,
        EnsureCuratorUserService $ensureCuratorUserService,
    ): AnonymousResourceCollection {
        $ensureCuratorUserService->handle($request->user());

        return AuditLogResource::collection(
            AuditLog::query()
                ->with('actor')
                ->latest()
                ->limit(50)
                ->get()
        );
    }
}
