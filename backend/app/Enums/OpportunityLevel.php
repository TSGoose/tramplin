<?php

declare(strict_types=1);

namespace App\Enums;

enum OpportunityLevel: string
{
    case Junior = 'junior';
    case Middle = 'middle';
    case Senior = 'senior';
    case Trainee = 'trainee';
}
