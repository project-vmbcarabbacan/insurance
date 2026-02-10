<?php

namespace App\Shared\Domain\Enums;

use App\Shared\Domain\Exceptions\InvalidValueException;

enum LeadActivityResponse: string
{
    case NO_ANSWER = 'no_answer';
    case CALL_BACK_REQUESTED = 'call_back_requested';
    case WRONG_NUMBER = 'wrong_number';

    case INTERESTED = 'interested';
    case REQUESTED_QUOTATION = 'requested_quotation';
    case NEEDS_MORE_INFORMATION = 'needs_more_information';

    case NOT_INTERESTED = 'not_interested';
    case PURCHASED_FROM_COMPETITOR = 'purchased_from_competitor';
    case POSTPONED = 'postponed';

    case NOT_ELIGIBLE = 'not_eligible';

    case PRICE_TOO_HIGH = 'price_too_high';
    case ACCEPTED_QUOTE = 'accepted_quote';
    case REJECTED_QUOTE = 'rejected_quote';

    case DOCUMENTS_PENDING = 'documents_pending';
    case DOCUMENTS_RECEIVED = 'documents_received';

    case CONVERTED_TO_POLICY = 'converted_to_policy';

    /**
     * Validate and return LeadActivityType enum from string value.
     *
     * @param string $value
     * @return self
     *
     * @throws InvalidValueException
     */
    public static function fromValue(string $value): self
    {
        return self::tryFrom(strtolower($value))
            ?? throw InvalidValueException::withMessage("Invalid lead activity value {$value}");
    }

    public function label(): string
    {
        return ucwords(strtolower(str_replace('_', ' ', $this->value)));
    }

    /**
     * Check if the given value exists in the enum.
     *
     * @param string $value
     * @return bool
     */
    public static function exists(string $value): bool
    {
        return self::tryFrom(strtolower($value)) !== null;
    }

    /**
     * Return enum as array of objects for dropdowns
     * [
     *   ['label' => 'Call', 'value' => 'call'],
     *   ['label' => 'Email', 'value' => 'email'],
     *   ['label' => 'Sms', 'value' => 'sms'],
     *   ['label' => 'Whatsapp', 'value' => 'whatsapp']
     *   ...
     * ]
     */
    public static function toDropdownArray(): array
    {
        return array_map(
            fn(self $case) => [
                'label' => $case->label(),
                'value' => $case->value,
            ],
            self::cases()
        );
    }

    /**
     * Convert enum to array for dropdowns / mapping
     * [
     *   'Call' => 'call',
     *   'Email' => 'email',
     *   'Sms' => 'sms',
     *   'Whatsapp' => 'whatsapp'
     *   ...
     * ]
     */
    public static function toLabelArray(): array
    {
        $result = [];

        foreach (self::cases() as $case) {
            // replace underscores with spaces and capitalize each word
            $label = ucwords(strtolower(str_replace('_', ' ', $case->value)));
            $result[$case->value] = $label;
        }

        return $result;
    }
}
