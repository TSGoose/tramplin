<?php

declare(strict_types=1);

namespace App\Services\Applicant;

use App\Enums\UserRole;
use App\Models\User;
use Symfony\Component\HttpKernel\Exception\HttpException;

final readonly class EnsureApplicantUserService
{
    public function handle(User $user): User
    {
        if ($user->role !== UserRole::Applicant) {
            throw new HttpException(403, 'Действие доступно только для роли applicant.');
        }

        return $user;
    }
}
