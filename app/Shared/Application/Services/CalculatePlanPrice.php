<?php

namespace App\Shared\Application\Services;

use App\Shared\Application\Factories\PricingRuleFactory;
use App\Shared\Domain\Pricing\PricingEngine;

class CalculatePlanPrice
{
    public function __construct(
        private PlanPricingRuleRepository $repository,
        private PricingEngine $engine
    ) {}

    public function execute(
        int $planId,
        float $basePrice,
        array $context
    ): float {
        $rules = $this->repository->getByPlan($planId);

        $domainRules = $rules->map(
            fn($rule) =>
            PricingRuleFactory::make(
                $rule->rule_type,
                $rule->rule_value
            )
        )->all();

        return $this->engine->calculate($basePrice, $context, $domainRules);
    }
}
