<?php

namespace App\Shared\Domain\Pricing\Rules;


class VehicleValueRule implements PricingRule
{
    public function __construct(
        private array $rule
    ) {}

    public function applies(array $context): bool
    {
        return isset($context['vehicle_value'])
            && $context['vehicle_value'] >= $this->rule['min']
            && $context['vehicle_value'] <= $this->rule['max'];
    }

    public function apply(float $price): float
    {
        return match ($this->rule['adjustment']['type']) {
            'multiplier' => $price * $this->rule['adjustment']['value'],
            'percentage' => $price + ($price * $this->rule['adjustment']['value'] / 100),
            default      => $price,
        };
    }
}
