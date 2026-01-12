<?php

namespace App\Shared\Domain\Enums;

use App\Shared\Domain\Exceptions\InvalidValueException;

enum PropertyType: string
{
    case APARTMENT = 'apartment';
    case VILLA = 'villa';
    case HOUSE = 'house';


    /**
     * Validate and return PropertyType enum from string value.
     *
     * @param string $value
     * @return self
     *
     * @throws InvalidValueException
     */
    public static function fromValue(string $value): self
    {
        return self::tryFrom(strtolower($value))
            ?? throw InvalidValueException::withMessage("Invalid property type value {$value}");
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
     *   ['label' => 'Apartment', 'value' => 'apartment'],
     *   ['label' => 'Villa', 'value' => 'villa'],
     *   ['label' => 'House', 'value' => 'house'],
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
     *   'Apartment' => 'apartment',
     *   'Villa' => 'villa',
     *   'House' => 'house',
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
