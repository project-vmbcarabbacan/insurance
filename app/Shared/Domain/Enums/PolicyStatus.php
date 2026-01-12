<?php

namespace App\Shared\Domain\Enums;

use App\Shared\Domain\Exceptions\InvalidValueException;

enum PolicyStatus: string
{
    case DRAFT = 'draft';
    case ACTIVE = 'active';
    case EXPIRED = 'expired';
    case CANCELLED = 'cancelled';

    /**
     * Validate and return PolicyStatus enum from string value.
     *
     * @param string $value
     * @return self
     *
     * @throws InvalidValueException
     */
    public static function fromValue(string $value): self
    {
        return self::tryFrom(strtolower($value))
            ?? throw InvalidValueException::withMessage("Invalid policy status value {$value}");
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
     *   ['label' => 'Draft', 'value' => 'draft'],
     *   ['label' => 'Active', 'value' => 'active'],
     *   ['label' => 'Expired', 'value' => 'expired'],
     *   ['label' => 'cancelled', 'value' => 'cancelled']
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
     *   'Draft' => 'draft',
     *   'Active' => 'active',
     *   'Expired' => 'expired',
     *   'Cancelled' => 'cancelled'
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
