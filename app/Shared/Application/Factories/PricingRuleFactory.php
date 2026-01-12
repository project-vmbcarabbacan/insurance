<?php

namespace App\Shared\Application\Factories;

use App\Shared\Domain\Pricing\Rules\AgeRule;
use App\Shared\Domain\Pricing\Rules\LocationRule;
use App\Shared\Domain\Pricing\Rules\PricingRule;
use App\Shared\Domain\Pricing\Rules\VehicleValueRule;

class PricingRuleFactory
{
    public static function make(string $type, array $value): PricingRule
    {
        return match ($type) {
            'age_range'     => new AgeRule($value),
            'vehicle_value' => new VehicleValueRule($value),
            'location'      => new LocationRule($value),
            default         => throw new \DomainException("Unknown rule type: {$type}")
        };
    }
}
