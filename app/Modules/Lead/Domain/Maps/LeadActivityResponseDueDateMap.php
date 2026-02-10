<?php

namespace App\Modules\Lead\Domain\Maps;

use App\Shared\Domain\Enums\LeadActivityResponse;
use DateInterval;

final class LeadActivityResponseDueDateMap
{
    public static function dueIn(LeadActivityResponse $response): ?DateInterval
    {
        return match ($response) {
            // No response / retry
            LeadActivityResponse::NO_ANSWER => new DateInterval('P1D'),
            LeadActivityResponse::CALL_BACK_REQUESTED => new DateInterval('PT60M'),
            LeadActivityResponse::WRONG_NUMBER => null,

            // Interested / qualification
            LeadActivityResponse::INTERESTED => new DateInterval('P1D'),
            LeadActivityResponse::NEEDS_MORE_INFORMATION => new DateInterval('P2D'),
            LeadActivityResponse::REQUESTED_QUOTATION => new DateInterval('P1D'),

            // Quote & negotiation
            LeadActivityResponse::PRICE_TOO_HIGH => new DateInterval('P3D'),
            LeadActivityResponse::ACCEPTED_QUOTE => new DateInterval('P1D'),
            LeadActivityResponse::REJECTED_QUOTE => null,

            // Documents
            LeadActivityResponse::DOCUMENTS_PENDING => new DateInterval('P2D'),
            LeadActivityResponse::DOCUMENTS_RECEIVED => new DateInterval('P1D'),

            // Lost / inactive
            LeadActivityResponse::NOT_INTERESTED => null,
            LeadActivityResponse::PURCHASED_FROM_COMPETITOR => null,
            LeadActivityResponse::POSTPONED => new DateInterval('P7D'),

            // Invalid
            LeadActivityResponse::NOT_ELIGIBLE => null,

            // Final
            LeadActivityResponse::CONVERTED_TO_POLICY => null,
        };
    }
}
