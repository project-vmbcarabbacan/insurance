<?php

namespace App\Shared\Domain\Enums;

use App\Shared\Domain\Exceptions\InvalidValueException;

/**
 * Salary enum
 *
 * Represents specification types used across the domain.
 */
enum Salary: string
{
    case UPTO_4000 = 'upto_4000';
    case K4_K5 = 'k4_k5';
    case K5_K6 = 'k5_k6';
    case K6_K7 = 'k6_K7';
    case K7_K8 = 'k7_K8';
    case K8_K9 = 'k8_K9';
    case K9_K10 = 'k9_K10';
    case ABOVE_10001 = 'above_10001';

    /**
     * Get human-readable label for the enum value.
     */
    public function label(): string
    {
        return match ($this) {
            self::UPTO_4000 => 'Upto 4000',
            self::K4_K5 => '4001 - 5000',
            self::K5_K6 => '5001 - 6000',
            self::K6_K7 => '6001 - 7000',
            self::K7_K8 => '7001 - 8000',
            self::K8_K9 => '8001 - 9000',
            self::K9_K10 => '9001 - 10000',
            self::ABOVE_10001 => 'Above 10001',
        };
    }

    /**
     * Validate and return Salary enum from string value.
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
                "Invalid health insurance value: {$value}"
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
