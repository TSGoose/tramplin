<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Enums\UserRole;
use PHPUnit\Framework\TestCase;

final class UserRoleTest extends TestCase
{
    public function test_it_contains_expected_roles(): void
    {
        $roles = array_map(
            static fn (UserRole $role): string => $role->value,
            UserRole::cases()
        );

        self::assertContains('applicant', $roles);
        self::assertContains('employer', $roles);
        self::assertContains('curator', $roles);
        self::assertContains('admin', $roles);
    }
}
