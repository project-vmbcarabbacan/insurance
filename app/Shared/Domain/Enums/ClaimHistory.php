<?php

namespace App\Shared\Domain\Enums;

use App\Shared\Domain\Exceptions\InvalidValueException;

/**
 * ClaimHistory enum
 *
 * Represents specification types used across the domain.
 */
enum ClaimHistory: string
{
    case CLAIMED_LAST_YEAR = 'claimed_last_year';
    case NO_CLAIM_1_YEAR = 'no_claim_1_year';
    case NO_CLAIM_2_YEARS = 'no_claim_2_years';
    case NEVER_CLAIMED = 'never_claimed';

    /**
     * Get human-readable label for the enum value.
     */
    public function label(): string
    {
        return match ($this) {
            self::CLAIMED_LAST_YEAR => 'Claimed last year',
            self::NO_CLAIM_1_YEAR => 'No claims for 1 year',
            self::NO_CLAIM_2_YEARS => 'No claims for 2 years',
            self::NEVER_CLAIMED => 'Never claimed',
        };
    }

    /**
     * Validate and return ClaimHistory enum from string value.
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
