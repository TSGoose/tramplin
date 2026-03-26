<?php

declare(strict_types=1);

namespace App\Enums;

enum ApplicationStatus: string
{
    case New = 'new';
    case Reviewing = 'reviewing';
    case Interview = 'interview';
    case Reserve = 'reserve';
    case Accepted = 'accepted';
    case Rejected = 'rejected';
}
