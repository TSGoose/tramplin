<?php

declare(strict_types=1);

namespace App\Enums;

enum OpportunityEmploymentType: string
{
    case FullTime = 'full_time';
    case PartTime = 'part_time';
    case Project = 'project';
    case Flexible = 'flexible';
}
