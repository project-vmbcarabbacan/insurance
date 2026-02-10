<?php

namespace App\Modules\Lead\Domain\Maps;

use App\Shared\Domain\Enums\LeadActivityResponse;
use App\Shared\Domain\Enums\LeadStatus;

final class LeadTransitionMap
{
    public static function transition(LeadActivityResponse $response): ?LeadStatus
    {
        return match ($response) {
            // No contact / follow-up needed
            LeadActivityResponse::NO_ANSWER => LeadStatus::UNRESPONSIVE,
            LeadActivityResponse::CALL_BACK_REQUESTED => LeadStatus::CONTACTED,
            LeadActivityResponse::WRONG_NUMBER => LeadStatus::INVALID,

            // Early interest / qualification
            LeadActivityResponse::INTERESTED => LeadStatus::QUALIFIED,
            LeadActivityResponse::NEEDS_MORE_INFORMATION => LeadStatus::QUALIFIED,
            LeadActivityResponse::REQUESTED_QUOTATION => LeadStatus::QUOTED,

            // Quotation & negotiation
            LeadActivityResponse::PRICE_TOO_HIGH => LeadStatus::NEGOTIATING,
            LeadActivityResponse::ACCEPTED_QUOTE => LeadStatus::PENDING_PAYMENT,
            LeadActivityResponse::REJECTED_QUOTE => LeadStatus::LOST,

            // Document flow
            LeadActivityResponse::DOCUMENTS_PENDING => LeadStatus::PENDING_PAYMENT,
            LeadActivityResponse::DOCUMENTS_RECEIVED => LeadStatus::PENDING_PAYMENT,

            // Conversion
            LeadActivityResponse::CONVERTED_TO_POLICY => LeadStatus::CONVERTED,

            // Lost leads
            LeadActivityResponse::NOT_INTERESTED => LeadStatus::LOST,
            LeadActivityResponse::PURCHASED_FROM_COMPETITOR => LeadStatus::LOST,
            LeadActivityResponse::POSTPONED => LeadStatus::CONTACTED,

            // Invalid leads
            LeadActivityResponse::NOT_ELIGIBLE => LeadStatus::INVALID,
        };
    }
}
