<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Curator;

use App\Enums\CompanyVerificationStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\CompanyResource;
use App\Models\Company;
use App\Services\Curator\EnsureCuratorUserService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Request;

final class CuratorCompanyIndexController extends Controller
{
    public function __invoke(
        Request $request,
        EnsureCuratorUserService $ensureCuratorUserService,
    ): AnonymousResourceCollection {
        $ensureCuratorUserService->handle($request->user());

        return CompanyResource::collection(
            Company::query()
                ->latest()
                ->whereIn('verification_status', [
                    CompanyVerificationStatus::PendingVerification,
                    CompanyVerificationStatus::NeedsRevision,
                ])
                ->get()
        );
    }
}
