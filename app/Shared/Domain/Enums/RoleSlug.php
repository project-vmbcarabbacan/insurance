<?php

namespace App\Shared\Domain\Enums;

use App\Shared\Domain\Exceptions\InvalidValueException;

enum RoleSlug: string
{
    case SUPER_ADMIN = 'super_admin';
    case ADMIN = 'admin';
    case AGENT = 'agent';
    case TEAM_LEAD = 'team_lead';
    case CUSTOMER = 'customer';
    case PARTNER = 'partner';
    case UNDERWRITER = 'underwriter';
    case CLAIMS_OFFICER = 'claims_officer';
    case FINANCE = 'finance';
    case SUPPORT = 'support';


    /**
     * Validate and return RoleSlug enum from string value.
     *
     * @param string $value
     * @return self
     *
     * @throws InvalidValueException
     */
    public static function fromValue(string $value): self
    {
        return self::tryFrom(strtolower($value))
            ?? throw InvalidValueException::withMessage("Invalid role slug value {$value}");
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
     *   ['label' => 'Super_admin', 'value' => 'super_admin'],
     *   ['label' => 'Admin', 'value' => 'admin'],
     *   ['label' => 'Agent', 'value' => 'agent']
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
     *   'Super_admin' => 'super_admin',
     *   'Admin' => 'admin',
     *   'Agent' => 'agent'
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
