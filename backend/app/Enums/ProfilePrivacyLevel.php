<?php

declare(strict_types=1);

namespace App\Enums;

enum ProfilePrivacyLevel: string
{
    case Private = 'private';
    case ContactsOnly = 'contacts_only';
    case PlatformVisible = 'platform_visible';
}
