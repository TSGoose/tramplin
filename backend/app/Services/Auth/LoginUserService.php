<?php

declare(strict_types=1);

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpKernel\Exception\HttpException;

final readonly class LoginUserService
{
    public function attempt(string $email, string $password): User
    {
        /** @var User|null $user */
        $user = User::query()
            ->where('email', mb_strtolower($email))
            ->first();

        if ($user === null || !Hash::check($password, $user->password)) {
            throw new HttpException(422, 'Неверный email или пароль.');
        }

        return $user;
    }
}
