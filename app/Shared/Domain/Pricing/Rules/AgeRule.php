<?php

namespace App\Shared\Domain\Pricing\Rules;

class AgeRule implements PricingRule
{
    public function __construct(
        private array $rule
    ) {}

    public function applies(array $context): bool
    {
        return isset($context['age'])
            && $context['age'] >= $this->rule['min']
            && $context['age'] <= $this->rule['max'];
    }

    public function apply(float $price): float
    {
        return $this->adjust($price);
    }

    private function adjust(float $price): float
    {
        return match ($this->rule['adjustment']['type']) {
            'percentage' => $price + ($price * $this->rule['adjustment']['value'] / 100),
            'fixed'      => $price + $this->rule['adjustment']['value'],
            'multiplier' => $price * $this->rule['adjustment']['value'],
            default      => $price,
        };
    }
}
