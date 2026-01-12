<?php

namespace App\Shared\Domain\Enums;

use App\Shared\Domain\Exceptions\InvalidValueException;

enum LeadStatus: string
{
    case NEW = 'new';
    case CONTACTED = 'contacted';
    case UNRESPONSIVE = 'unresponsive';
    case QUALIFIED = 'qualified';
    case QUOTED = 'quoted';
    case NEGOTIATING = 'negotiating';
    case PENDING_PAYMENT = 'pending_payment';
    case CONVERTED = 'converted';
    case LOST = 'lost';
    case INVALID = 'invalid';


    /**
     * Validate and return LeadStatus enum from string value.
     *
     * @param string $value
     * @return self
     *
     * @throws InvalidValueException
     */
    public static function fromValue(string $value): self
    {
        return self::tryFrom(strtolower($value))
            ?? throw InvalidValueException::withMessage("Invalid lead status value {$value}");
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
     *   ['label' => 'New', 'value' => 'new'],
     *   ['label' => 'Contacted', 'value' => 'contacted'],
     *   ['label' => 'Unresponsive', 'value' => 'unresponsive'],
     *   ['label' => 'qualified', 'value' => 'qualified']
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
     *   'New' => 'new',
     *   'Contacted' => 'contacted',
     *   'Unresponsive' => 'unresponsive',
     *   'Qualified' => 'qualified'
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
