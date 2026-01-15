<?php

namespace App\Modules\Policy\Domain\Entities;

use App\Shared\Domain\Enums\PolicyStatus;
use App\Shared\Domain\ValueObjects\GenericId;
use App\Shared\Domain\ValueObjects\LowerText;

final class AddPolicyEntity
{
    public function __construct(
        public readonly GenericId $lead_id,
        public readonly GenericId $customer_id,
        public readonly LowerText $insurance_product_code,
        public readonly GenericId $quotation_id,
        public readonly GenericId $provider_id,
        public readonly GenericId $plan_id,
    ) {}

    public function toArray()
    {
        return [
            'lead_id' => $this->lead_id->value(),
            'customer_id' => $this->customer_id->value(),
            'insurance_product_code' => $this->insurance_product_code->value(),
            'quotation_id' => $this->quotation_id->value(),
            'provider_id' => $this->provider_id->value(),
            'plan_id' => $this->plan_id->value(),
        ];
    }
}
