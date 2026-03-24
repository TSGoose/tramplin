<?php

declare(strict_types=1);

namespace App\Enums;

enum UserRole: string
{
    case Applicant = 'applicant';
    case Employer = 'employer';
    case Curator = 'curator';
    case Admin = 'admin';

    public function label(): string
    {
        return match ($this) {
            self::Applicant => 'Соискатель',
            self::Employer => 'Работодатель',
            self::Curator => 'Куратор',
            self::Admin => 'Администратор',
        };
    }
}
