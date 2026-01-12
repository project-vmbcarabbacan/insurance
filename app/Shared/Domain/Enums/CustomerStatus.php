<?php

namespace App\Shared\Domain\Enums;

use App\Shared\Domain\Exceptions\InvalidValueException;

enum CustomerStatus: string
{
    case LEAD = 'lead';
    case PROSPECT = 'prospect';
    case CUSTOMER = 'customer';
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
    case RENEWAL_DUE = 'renewal_due';
    case SUSPENDED = 'suspended';
    case CHURNED = 'churned';
    case BLACKLISTED = 'blacklisted';


    /**
     * Validate and return CustomerStatus enum from string value.
     *
     * @param string $value
     * @return self
     *
     * @throws InvalidValueException
     */
    public static function fromValue(string $value): self
    {
        return self::tryFrom(strtolower($value))
            ?? throw InvalidValueException::withMessage("Invalid customer status value {$value}");
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
     *   ['label' => 'Lead', 'value' => 'lead'],
     *   ['label' => 'Prospect', 'value' => 'prospect'],
     *   ['label' => 'Customer', 'value' => 'customer'],
     *   ['label' => 'active', 'value' => 'active']
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
     *   'Lead' => 'lead',
     *   'Prospect' => 'prospect',
     *   'Customer' => 'customer',
     *   'Active' => 'active'
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
