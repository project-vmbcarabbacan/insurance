<?php

namespace App\Shared\Domain\Enums;

use App\Shared\Domain\Exceptions\InvalidValueException;

enum GenericStatus: string
{
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
    case DRAFT = 'draft';
    case SUSPENDED = 'suspended';
    case DELETED = 'deleted';


    /**
     * Validate and return GenericStatus enum from string value.
     *
     * @param string $value
     * @return self
     *
     * @throws InvalidValueException
     */
    public static function fromValue(string $value): self
    {
        return self::tryFrom(strtolower($value))
            ?? throw InvalidValueException::withMessage("Invalid status value {$value}");
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
     * Example:
     * [
     *   ['label' => 'Active', 'value' => 'active'],
     *   ['label' => 'Inactive', 'value' => 'inactive'],
     *   ['label' => 'Deleted', 'value' => 'deleted'],
     * ]
     */
    public static function toDropdownArray(): array
    {
        return array_map(
            fn(self $case) => [
                'label' => ucfirst($case->value),  // Capitalize label for user-friendly output
                'value' => $case->value
            ],
            self::cases()
        );
    }

    /**
     * Convert enum to associative array for dropdowns / mapping
     * Example:
     * [
     *   'Active' => 'active',
     *   'Inactive' => 'inactive',
     *   'Deleted' => 'deleted',
     * ]
     */
    public static function toLabelArray(): array
    {
        $result = [];

        foreach (self::cases() as $case) {
            // Capitalize each word of the status for user-friendly labels
            $label = ucwords(str_replace('_', ' ', $case->value));
            $result[$label] = $case->value;
        }

        return $result;
    }

    /**
     * Validates if a given status exists in the enum.
     *
     * @param string $statusValue The status value to check.
     * @return bool Returns true if the status exists in the enum, otherwise false.
     */
    public static function isValidStatus(string $statusValue): bool
    {
        // Try to match the status with the available enum cases
        return null !== self::tryFrom($statusValue);
    }

    /**
     * Get the status label by value.
     *
     * @param string $statusValue The status value to get the label for.
     * @return string|null Returns the label for the status, or null if not found.
     */
    public static function getLabelByValue(string $statusValue): ?string
    {
        $status = self::tryFrom($statusValue);

        return $status ? ucwords(str_replace('_', ' ', $status->value)) : null;
    }
}
