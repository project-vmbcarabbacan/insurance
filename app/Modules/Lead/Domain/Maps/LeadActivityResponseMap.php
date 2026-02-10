<?php

namespace App\Modules\Lead\Domain\Maps;

use App\Shared\Domain\Enums\LeadActivityResponse;
use App\Shared\Domain\Enums\LeadStatus;

final class LeadActivityResponseMap
{
    public static function map(LeadStatus $status): ?array
    {
        return match ($status) {
            LeadStatus::NEW => [
                LeadActivityResponse::NO_ANSWER,
                LeadActivityResponse::CALL_BACK_REQUESTED,
                LeadActivityResponse::WRONG_NUMBER,
                LeadActivityResponse::INTERESTED,
                LeadActivityResponse::NOT_ELIGIBLE,
            ],

            LeadStatus::CONTACTED => [
                LeadActivityResponse::NO_ANSWER,
                LeadActivityResponse::CALL_BACK_REQUESTED,
                LeadActivityResponse::INTERESTED,
                LeadActivityResponse::NEEDS_MORE_INFORMATION,
                LeadActivityResponse::NOT_INTERESTED,
                LeadActivityResponse::POSTPONED,
                LeadActivityResponse::WRONG_NUMBER,
            ],

            LeadStatus::UNRESPONSIVE => [
                LeadActivityResponse::NO_ANSWER,
                LeadActivityResponse::CALL_BACK_REQUESTED,
                LeadActivityResponse::WRONG_NUMBER,
                LeadActivityResponse::INTERESTED,
                LeadActivityResponse::NOT_INTERESTED,
            ],

            LeadStatus::QUALIFIED => [
                LeadActivityResponse::REQUESTED_QUOTATION,
                LeadActivityResponse::NEEDS_MORE_INFORMATION,
                LeadActivityResponse::NOT_INTERESTED,
                LeadActivityResponse::POSTPONED,
            ],

            LeadStatus::QUOTED => [
                LeadActivityResponse::PRICE_TOO_HIGH,
                LeadActivityResponse::ACCEPTED_QUOTE,
                LeadActivityResponse::REJECTED_QUOTE,
                LeadActivityResponse::NEEDS_MORE_INFORMATION,
            ],

            LeadStatus::NEGOTIATING => [
                LeadActivityResponse::PRICE_TOO_HIGH,
                LeadActivityResponse::ACCEPTED_QUOTE,
                LeadActivityResponse::REJECTED_QUOTE,
                LeadActivityResponse::POSTPONED,
            ],

            LeadStatus::PENDING_PAYMENT => [
                LeadActivityResponse::DOCUMENTS_PENDING,
                LeadActivityResponse::DOCUMENTS_RECEIVED,
                LeadActivityResponse::CONVERTED_TO_POLICY,
            ],

            LeadStatus::CONVERTED => [
                // usually no further activity responses
            ],

            LeadStatus::LOST => [
                // frozen state – no responses allowed
            ],

            LeadStatus::INVALID => [
                // frozen state – no responses allowed
            ],
        };
    }
}
