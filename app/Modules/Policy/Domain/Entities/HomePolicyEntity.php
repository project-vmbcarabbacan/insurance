<?php

namespace App\Modules\Policy\Domain\Entities;

use App\Shared\Domain\Enums\PropertyType;
use App\Shared\Domain\ValueObjects\Amount;
use App\Shared\Domain\ValueObjects\GenericId;
use App\Shared\Domain\ValueObjects\LowerText;

final class HomePolicyEntity
{
    public function __construct(
        public readonly GenericId $policy_id,
        public readonly PropertyType $property_type,
        public readonly LowerText $address,
        public readonly int $year_built,
        public readonly Amount $property_value,
    ) {}

    public function toArray()
    {
        return [
            'policy_id' => $this->policy_id->value(),
            'property_type' => $this->property_type->value,
            'address' => $this->address->value(),
            'year_built' => $this->year_built,
            'property_value' => $this->property_value->amount(),
            'currency' => $this->property_value->currency()
        ];
    }
}
