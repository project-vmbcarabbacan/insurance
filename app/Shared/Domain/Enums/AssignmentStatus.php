<?php

namespace App\Shared\Domain\Enums;

enum AssignmentStatus: string
{
    case ASSIGNED = 'assigned';
    case CONTACTED = 'contacted';
    case ACCEPTED = 'accepted';
    case REJECTED = 'rejected';
    case EXPIRED = 'expired';
}
