<?php

declare(strict_types=1);

namespace App\Services\Employer;

use App\Enums\CompanyVerificationStatus;
use App\Models\Company;
use App\Models\User;

final readonly class EnsureEmployerCompanyService
{
    public function handle(User $user): Company
    {
        return Company::query()->firstOrCreate(
            ['owner_user_id' => $user->id],
            [
                'name' => $user->display_name.' Company',
                'verification_status' => CompanyVerificationStatus::Draft,
            ],
        );
    }
}
