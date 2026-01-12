<?php

namespace App\Shared\Domain\Enums;

use App\Shared\Domain\Exceptions\InvalidValueException;

enum LeadActivityType: string
{
    case CALL = 'call';
    case EMAIL = 'email';
    case SMS = 'sms';
    case WHATSAPP = 'whatsapp';

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
