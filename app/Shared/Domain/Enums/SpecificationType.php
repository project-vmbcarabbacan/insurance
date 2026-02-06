<?php

namespace App\Shared\Domain\Enums;

use App\Shared\Domain\Exceptions\InvalidValueException;

/**
 * SpecificationType enum
 *
 * Represents specification types used across the domain.
 */
enum SpecificationType: string
{
    case GCC = 'gcc';
    case NON_GCC = 'non_gcc';

    /**
     * Get human-readable label for the enum value.
     */
    public function label(): string
    {
        return match ($this) {
            self::GCC => 'GCC Spec',
            self::NON_GCC => 'Non-GCC Spec / Modified',
        };
    }

    /**
     * Validate and return SpecificationType enum from string value.
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
                "Invalid specification type value: {$value}"
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
     *
     * Example:
     * [
     *   ['label' => 'GCC Spec', 'value' => 'gcc'],
     *   ['label' => 'Non-GCC Spec / Modified', 'value' => 'non_gcc'],
     * ]
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
     *
     * Example:
     * [
     *   'gcc' => 'GCC Spec',
     *   'non_gcc' => 'Non-GCC Spec / Modified',
     * ]
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
