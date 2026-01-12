<?php

namespace App\Shared\Domain\Enums;

use App\Shared\Domain\Exceptions\InvalidValueException;

enum ClaimStatus: string
{
    case SUBMITTED = 'submitted';
    case REVIEWED = 'reviewed';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
    case PAID = 'paid';


    /**
     * Validate and return ClaimStatus enum from string value.
     *
     * @param string $value
     * @return self
     *
     * @throws InvalidValueException
     */
    public static function fromValue(string $value): self
    {
        return self::tryFrom(strtolower($value))
            ?? throw InvalidValueException::withMessage("Invalid claim status value {$value}");
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
     *   ['label' => 'Submitted', 'value' => 'submitted'],
     *   ['label' => 'Approved', 'value' => 'approved'],
     *   ['label' => 'Rejected', 'value' => 'rejected'],
     *   ['label' => 'Paid', 'value' => 'paid']
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
     *   'Submitted' => 'submitted',
     *   'Approved' => 'approved',
     *   'Rejected' => 'rejected',
     *   'Paid' => 'paid'
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
