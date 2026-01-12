<?php

namespace App\Shared\Domain\Enums;

use App\Shared\Domain\Exceptions\InvalidValueException;

enum Currency: string
{
    case PHP = 'PHP';
    case AED = 'AED';
    case USD = 'USD';


    /**
     * Validate and return Currency enum from string value.
     *
     * @param string $value
     * @return self
     *
     * @throws InvalidValueException
     */
    public static function fromValue(string $value): self
    {
        return self::tryFrom(strtolower($value))
            ?? throw InvalidValueException::withMessage("Invalid currency value {$value}");
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
     *   ['label' => 'PHP', 'value' => 'PHP'],
     *   ['label' => 'AED', 'value' => 'AED'],
     *   ['label' => 'USD', 'value' => 'USD']
     * ]
     */
    public static function toDropdownArray(): array
    {
        return array_map(
            fn(self $case) => [
                'label' => $case->value,
                'value' => $case->value
            ],
            self::cases()
        );
    }

    /**
     * Convert enum to array for dropdowns / mapping
     * [
     *   'PHP' => 'PHP',
     *   'AED' => 'AED',
     *   'USD' => 'USD'
     * ]
     */
    public static function toLabelArray(): array
    {
        $result = [];

        foreach (self::cases() as $case) {
            // replace underscores with spaces and capitalize each word
            $label = $case->value;
            $result[$case->value] = $label;
        }

        return $result;
    }
}
