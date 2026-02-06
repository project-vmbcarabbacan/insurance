<?php

namespace App\Shared\Domain\Enums;

use App\Shared\Domain\Exceptions\InvalidValueException;

/**
 * MedicalCondition enum
 *
 * Represents MedicalCondition types used across the domain.
 */
enum MedicalCondition: string
{
    case HYPERTENSION = 'hypertension';
    case DIABETES = 'diabetes';
    case HEART_CONDITION = 'heart_condition';
    case RESPITORY = 'respitory';
    case OTHER = 'other';

    /**
     * Get human-readable label for the enum value.
     */
    public function label(): string
    {
        return match ($this) {
            self::HYPERTENSION => 'Hypertension',
            self::DIABETES => 'Diabetes',
            self::HEART_CONDITION => 'Heart Condition',
            self::RESPITORY => 'Respiratory or Lung disorders',
            self::OTHER => 'Other',
        };
    }

    /**
     * Validate and return MedicalCondition enum from string value.
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
