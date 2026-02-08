<?php

namespace App\Shared\Domain\Enums;

use App\Shared\Domain\Exceptions\InvalidValueException;

enum LeadActivityType: string
{
    case LEAD_CREATED = 'lead_created';
    case LEAD_ASSIGNED = 'lead_assigned';

    case FIRST_CONTACT_ATTEMPTED = 'first_contact_attempted';
    case CONTACTED = 'contacted';

    case QUOTE_REQUESTED = 'quote_requested';
    case QUOTE_SENT = 'quote_sent';

    case FOLLOW_UP_SCHEDULED = 'follow_up_scheduled';

    case DOCUMENT_REQUESTED = 'document_requested';
    case DOCUMENT_RECEIVED = 'document_received';

    case LEAD_CLOSED_WON = 'lead_closed_won';
    case LEAD_CLOSED_LOST = 'lead_closed_lost';

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
                'label' => ucwords(strtolower(str_replace('_', ' ', $case->value))),
                'value' => strtolower($case->value)
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
