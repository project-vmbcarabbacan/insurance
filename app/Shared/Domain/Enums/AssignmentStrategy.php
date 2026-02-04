<?php

namespace App\Shared\Domain\Enums;

enum AssignmentStrategy: string
{
    case ROUND_ROBIN = 'round_robin';
    case LEAST_LOADED = 'least_loaded';
    case MANUAL = 'manual';
}
