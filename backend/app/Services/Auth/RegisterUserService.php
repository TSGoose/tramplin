<?php

declare(strict_types=1);

namespace App\Services\Auth;

use App\Enums\UserRole;
use App\Models\User;

final readonly class RegisterUserService
{
    /**
     * @param array{
     *     display_name: string,
     *     email: string,
     *     password: string
     * } $payload
     */
    public function handle(array $payload, UserRole $role): User
    {
        return User::query()->create([
            'display_name' => $payload['display_name'],
            'email' => mb_strtolower($payload['email']),
            'password' => $payload['password'],
            'role' => $role,
        ]);
    }
}
