<?php

namespace App\Shared\Domain\Enums;

use App\Shared\Domain\Exceptions\InvalidValueException;

enum PricingRuleType: string
{
    case AGE_RANGE       = 'age_range';
    case VEHICLE_VALUE   = 'vehicle_value';
    case LOCATION        = 'location';
    case FIXED_FEE       = 'fixed_fee';
    case COVERAGE_AMOUNT = 'coverage_amount';
    case VEHICLE_AGE     = 'vehicle_age';
    case PET_BREED       = 'pet_breed';
    case TRIP_DURATION   = 'trip_duration';
    case CLAIM_HISTORY   = 'claim_history';
    case DISCOUNT        = 'discount';


    /**
     * Validate and return PricingRule enum from string value.
     *
     * @param string $value
     * @return self
     *
     * @throws InvalidValueException
     */
    public static function fromValue(string $value): self
    {
        return self::tryFrom(strtolower($value))
            ?? throw InvalidValueException::withMessage("Invalid pricing rule value {$value}");
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
     *   ['label' => 'Age Range', 'value' => 'age_range'],
     *   ['label' => 'Fixed Fee', 'value' => 'fixed_fee'],
     *   ['label' => 'Discount', 'value' => 'discount'],
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
     *   'Age_range' => 'age_range',
     *   'Fixed Fee' => 'fixed_fee',
     *   'Discount' => 'discount',
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
