<?php

namespace App\Shared\Domain\Enums;

use App\Shared\Domain\Exceptions\InvalidValueException;

/**
 * Relationship enum
 *
 * Represents Relationship types used across the domain.
 */
enum Relationship: string
{
    case SELF = 'self';
    case SPOUSE = 'spouse';
    case DAUGHTER = 'daughter';
    case SON = 'son';
    case FATHER = 'father';
    case MOTHER = 'mother';

    /**
     * Get human-readable label for the enum value.
     */
    public function label(): string
    {
        return match ($this) {
            self::SELF => 'Self',
            self::SPOUSE => 'Spouse',
            self::DAUGHTER => 'Daughter',
            self::SON => 'Son',
            self::FATHER => 'Father',
            self::MOTHER => 'Mother',
        };
    }

    /**
     * Validate and return Relationship enum from string value.
     *
     * @param string $value
     * @return self
     *
     * @throws InvalidValueException If the value does not match any enum case
     */
    public static function fromValue(string $value): self
    {
        return self::tryFrom(strtolower($value))
            ?? throw InvalidValueException::withMessage(
                "Invalid claim history value: {$value}"
            );
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
     * Return enum as an array of label/value pairs.
     * Useful for dropdowns or API responses.
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
     * Return enum as an associative array.
     * Useful for mappings or select inputs.
     */
    public static function toLabelArray(): array
    {
        $result = [];

        foreach (self::cases() as $case) {
            $result[$case->value] = $case->label();
        }

        return $result;
    }
}
