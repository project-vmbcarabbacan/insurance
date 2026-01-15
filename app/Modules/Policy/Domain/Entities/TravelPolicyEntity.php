<?php

namespace App\Modules\Policy\Domain\Entities;

use App\Shared\Domain\Enums\TripType;
use App\Shared\Domain\ValueObjects\Amount;
use App\Shared\Domain\ValueObjects\GenericId;

final class TravelPolicyEntity
{
    public function __construct(
        public readonly GenericId $policy_id,
        public readonly string $destination_country,
        public readonly TripType $trip_type,
        public readonly Amount $coverage_amount
    ) {}

    public function toArray()
    {
        return [
            'policy_id' => $this->policy_id->value(),
            'destination_country' => $this->destination_country,
            'trip_type' => $this->trip_type->value,
            'coverage_amount' => $this->coverage_amount->amount(),
            'currency' => $this->coverage_amount->currency(),
        ];
    }
}
