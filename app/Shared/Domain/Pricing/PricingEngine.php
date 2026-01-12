<?php

namespace App\Shared\Domain\Pricing;

class PricingEngine
{
    /**
     * @param PricingRule[] $rules
     */
    public function calculate(float $basePrice, array $context, array $rules): float
    {
        foreach ($rules as $rule) {
            if ($rule->applies($context)) {
                $basePrice = $rule->apply($basePrice);
            }
        }

        return round($basePrice, 2);
    }
}
