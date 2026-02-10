<?php

namespace App\Modules\Lead\Domain\Maps;

use App\Shared\Domain\Enums\LeadActivityResponse;
use App\Shared\Domain\Enums\LeadActivityType;

final class LeadActivityTypeMap
{
    public static function transition(LeadActivityResponse $response): ?LeadActivityType
    {
        return match ($response) {
            // Contact attempts
            LeadActivityResponse::NO_ANSWER => LeadActivityType::FIRST_CONTACT_ATTEMPTED,
            LeadActivityResponse::CALL_BACK_REQUESTED => LeadActivityType::CONTACTED,
            LeadActivityResponse::WRONG_NUMBER => LeadActivityType::LEAD_CLOSED_LOST,

            // Qualification
            LeadActivityResponse::INTERESTED => LeadActivityType::CONTACTED,
            LeadActivityResponse::NEEDS_MORE_INFORMATION => LeadActivityType::FOLLOW_UP_SCHEDULED,

            // Quotation
            LeadActivityResponse::REQUESTED_QUOTATION => LeadActivityType::QUOTE_REQUESTED,
            LeadActivityResponse::PRICE_TOO_HIGH => LeadActivityType::FOLLOW_UP_SCHEDULED,
            LeadActivityResponse::ACCEPTED_QUOTE => LeadActivityType::QUOTE_SENT,
            LeadActivityResponse::REJECTED_QUOTE => LeadActivityType::LEAD_CLOSED_LOST,

            // Documents
            LeadActivityResponse::DOCUMENTS_PENDING => LeadActivityType::DOCUMENT_REQUESTED,
            LeadActivityResponse::DOCUMENTS_RECEIVED => LeadActivityType::DOCUMENT_RECEIVED,

            // Lost scenarios
            LeadActivityResponse::NOT_INTERESTED => LeadActivityType::LEAD_CLOSED_LOST,
            LeadActivityResponse::PURCHASED_FROM_COMPETITOR => LeadActivityType::LEAD_CLOSED_LOST,
            LeadActivityResponse::NOT_ELIGIBLE => LeadActivityType::LEAD_CLOSED_LOST,

            // Postponed / follow-up
            LeadActivityResponse::POSTPONED => LeadActivityType::FOLLOW_UP_SCHEDULED,

            // Conversion
            LeadActivityResponse::CONVERTED_TO_POLICY => LeadActivityType::LEAD_CLOSED_WON,
        };
    }
}
