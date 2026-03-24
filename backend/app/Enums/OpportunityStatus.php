<?php

declare(strict_types=1);

namespace App\Enums;

enum OpportunityStatus: string
{
    case Draft = 'draft';
    case PendingModeration = 'pending_moderation';
    case Published = 'published';
    case NeedsRevision = 'needs_revision';
    case Rejected = 'rejected';
    case Archived = 'archived';
    case Expired = 'expired';
}
