<?php

namespace App\Modules\Policy\Application\DTOs;

use App\Shared\Domain\Enums\PolicyStatus;
use App\Shared\Domain\ValueObjects\GenericId;
use App\Shared\Domain\ValueObjects\LowerText;

class AddPolicyDto
{
    public function __construct(
        public readonly GenericId $lead_id,
        public readonly GenericId $customer_id,
        public readonly LowerText $insurance_product_code,
        public readonly GenericId $quotation_id,
        public readonly GenericId $provider_id,
        public readonly GenericId $plan_id,
        public readonly PolicyStatus $policy_status,
    ) {}
}
