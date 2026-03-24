<?php

declare(strict_types=1);

namespace App\Enums;

enum ProfileModerationStatus: string
{
    case Unreviewed = 'unreviewed';
    case Approved = 'approved';
    case NeedsRevision = 'needs_revision';
    case Rejected = 'rejected';
}
