<?php

declare(strict_types=1);

namespace App\Services\Curator;

use App\Enums\UserRole;
use App\Models\User;
use Symfony\Component\HttpKernel\Exception\HttpException;

final readonly class EnsureCuratorUserService
{
    public function handle(User $user): User
    {
        if (!in_array($user->role, [UserRole::Curator, UserRole::Admin], true)) {
            throw new HttpException(403, 'Действие доступно только для куратора или администратора.');
        }

        return $user;
    }

    public function ensureAdmin(User $user): User
    {
        if ($user->role !== UserRole::Admin) {
            throw new HttpException(403, 'Действие доступно только для администратора.');
        }

        return $user;
    }
}
