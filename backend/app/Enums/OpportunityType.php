<?php

declare(strict_types=1);

namespace App\Enums;

enum OpportunityType: string
{
    case Vacancy = 'vacancy';
    case Internship = 'internship';
    case Mentorship = 'mentorship';
    case Event = 'event';
}
