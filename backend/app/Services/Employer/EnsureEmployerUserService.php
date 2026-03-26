<?php

declare(strict_types=1);

namespace App\Services\Employer;

use App\Enums\UserRole;
use App\Models\User;
use Symfony\Component\HttpKernel\Exception\HttpException;

final readonly class EnsureEmployerUserService
{
    public function handle(User $user): User
    {
        if ($user->role !== UserRole::Employer) {
            throw new HttpException(403, 'Действие доступно только для роли employer.');
        }

        return $user;
    }
}
