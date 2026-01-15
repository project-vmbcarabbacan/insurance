<?php

namespace App\Modules\Policy\Domain\Entities;

use App\Shared\Domain\Enums\VehicleType;
use App\Shared\Domain\ValueObjects\Amount;
use App\Shared\Domain\ValueObjects\GenericId;

final class VehiclePolicyEntity
{
    public function __construct(
        public readonly GenericId $policy_id,
        public readonly VehicleType $vehicle_type,
        public readonly string $make,
        public readonly string $model,
        public readonly int $year,
        public readonly string $identifier_type,
        public readonly string $plate_number,
        public readonly string $engine_number,
        public readonly Amount $current_value
    ) {}

    public function toArray()
    {
        return [
            'policy_id' => $this->policy_id->value(),
            'vehicle_type' => $this->vehicle_type->value,
            'make' => $this->make,
            'model' => $this->model,
            'year' => $this->year,
            'identifier_type' => $this->identifier_type,
            'plate_number' => $this->plate_number,
            'engine_number' => $this->engine_number,
            'current_value' => $this->current_value->amount(),
            'currency' => $this->current_value->currency(),
        ];
    }
}
