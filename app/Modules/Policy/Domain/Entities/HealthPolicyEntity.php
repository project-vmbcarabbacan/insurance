<?php

namespace App\Modules\Policy\Domain\Entities;

use App\Shared\Domain\ValueObjects\Amount;
use App\Shared\Domain\ValueObjects\GenericId;
use App\Shared\Domain\ValueObjects\LowerText;

final class HealthPolicyEntity
{
    public function __construct(
        public readonly GenericId $policy_id,
        public readonly LowerText $coverage_type,
        public readonly LowerText $hospital_network,
        public readonly Amount $max_coverage,
        public readonly ?LowerText $pre_existing_conditions
    ) {}

    public function toArray()
    {
        return [
            'policy_id' => $this->policy_id->value(),
            'coverage_type' => $this->coverage_type->value(),
            'hospital_network' => $this->hospital_network->value(),
            'max_coverage' => $this->max_coverage->amount(),
            'currency' => $this->max_coverage->currency(),
            'pre_existing_conditions' => $this->pre_existing_conditions->value()
        ];
    }
}
