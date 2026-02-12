<?php

namespace App\Shared\Domain\Enums;

use App\Shared\Domain\Exceptions\InvalidValueException;

enum CustomerSource: string
{
    case CRM = 'crm';
    case WEBSITE = 'website';
    case WALKIN = 'walk_in';
    case REFERRAL = 'referral';
    case SOCIALMEDIA = 'social_media';
    case ADVERTISEMENT = 'advertisement';


    public function label(): string
    {
        return match ($this) {
            self::CRM => 'Crm',
            self::WEBSITE => 'Website',
            self::WALKIN => 'Walk In',
            self::REFERRAL => 'Referral',
            self::SOCIALMEDIA => 'Social Media',
            self::ADVERTISEMENT => 'Advertisement'
        };
    }
    /**
     * Validate and return CustomerStatus enum from string value.
     *
     * @param string $value
     * @return self
     *
     * @throws InvalidValueException
     */
    public static function fromValue(string $value): self
    {
        return self::tryFrom(strtolower($value))
            ?? throw InvalidValueException::withMessage("Invalid customer source value {$value}");
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
     *   ['label' => 'CRM', 'value' => 'crm'],
     *   ['label' => 'Website', 'value' => 'website'],
     *   ['label' => 'referral', 'value' => 'referral']
     *   ...
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
     *   'CRM' => 'crm',
     *   'Website' => 'website',
     *   'Referral' => 'referral',
     *   ...
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
