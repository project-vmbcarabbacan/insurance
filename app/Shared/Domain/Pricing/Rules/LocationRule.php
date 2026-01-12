<?php

namespace App\Shared\Domain\Pricing\Rules;

class LocationRule implements PricingRule
{
    public function __construct(
        private array $rule
    ) {}

    public function applies(array $context): bool
    {
        return isset($context['location'])
            && in_array($context['location'], $this->rule['locations'], true);
    }

    public function apply(float $price): float
    {
        return $price + ($price * $this->rule['adjustment']['value'] / 100);
    }
}
