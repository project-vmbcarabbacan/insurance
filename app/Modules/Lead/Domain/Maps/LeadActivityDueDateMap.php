<?php

namespace App\Modules\Lead\Domain\Maps;

use App\Shared\Domain\Enums\LeadActivityType;
use DateInterval;

final class LeadActivityDueDateMap
{
    public static function dueIn(LeadActivityType $type): ?DateInterval
    {
        return match ($type) {
            LeadActivityType::LEAD_CREATED => new DateInterval('PT30M'),
            LeadActivityType::LEAD_ASSIGNED => new DateInterval('PT15M'),

            LeadActivityType::CONTACTED => new DateInterval('P2D'),
            LeadActivityType::QUOTE_REQUESTED => new DateInterval('P1D'),
            LeadActivityType::QUOTE_SENT => new DateInterval('P3D'),

            LeadActivityType::DOCUMENT_REQUESTED => new DateInterval('P5D'),
            LeadActivityType::DOCUMENT_RECEIVED => new DateInterval('P1D'),

            default => null,
        };
    }
}
