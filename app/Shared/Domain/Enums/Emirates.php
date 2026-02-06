<?php

namespace App\Shared\Domain\Enums;

use App\Shared\Domain\Exceptions\InvalidValueException;

/**
 * Emirates enum
 *
 * Represents Emirates types used across the domain.
 */
enum Emirates: string
{
    case ABU_DHABI = 'abu_dhabi';
    case DUBAI = 'dubai';
    case SHARJAH = 'sharjah';
    case AJMAN = 'ajman';
    case UAQ = 'uaq';
    case RAK = 'rak';
    case FUJAIRAH = 'fujairah';

    /**
     * Get human-readable label for the enum value.
     */
    public function label(): string
    {
        return match ($this) {
            self::ABU_DHABI => 'Abu Dhabi',
            self::DUBAI => 'Dubai',
            self::SHARJAH => 'Sharjah',
            self::AJMAN => 'Ajman',
            self::UAQ => 'Umm Al Quwain',
            self::RAK => 'Ras Al Khaimah',
            self::FUJAIRAH => 'Fujairah',
        };
    }

    /**
     * Validate and return Emirates enum from string value.
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
