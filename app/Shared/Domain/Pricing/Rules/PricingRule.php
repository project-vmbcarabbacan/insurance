<?php

namespace App\Shared\Domain\Pricing\Rules;

interface PricingRule
{
    public function applies(array $context): bool;

    public function apply(float $price): float;
}
