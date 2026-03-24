<?php

declare(strict_types=1);

namespace App\Enums;

enum UserRole: string
{
    case Applicant = 'applicant';
    case Employer = 'employer';
    case Curator = 'curator';
    case Admin = 'admin';

    public function isPubliclyRegisterable(): bool
    {
        return match ($this) {
            self::Applicant, self::Employer => true,
            self::Curator, self::Admin => false,
        };
    }

    public function canUsePublicLogin(): bool
    {
        return match ($this) {
            self::Applicant, self::Employer => true,
            self::Curator, self::Admin => false,
        };
    }

    public function canUseCuratorLogin(): bool
    {
        return match ($this) {
            self::Curator, self::Admin => true,
            self::Applicant, self::Employer => false,
        };
    }
}
