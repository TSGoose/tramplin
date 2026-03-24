<?php

declare(strict_types=1);

namespace App\Enums;

enum OpportunityWorkFormat: string
{
    case Remote = 'remote';
    case Hybrid = 'hybrid';
    case Office = 'office';
    case Online = 'online';
}
