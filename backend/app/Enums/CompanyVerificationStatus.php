<?php

declare(strict_types=1);

namespace App\Enums;

enum CompanyVerificationStatus: string
{
    case Draft = 'draft';
    case PendingVerification = 'pending_verification';
    case Verified = 'verified';
    case NeedsRevision = 'needs_revision';
    case Rejected = 'rejected';
}
