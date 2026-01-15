<?php

namespace App\Modules\Policy\Domain\Contracts;

use App\Models\VehiclePolicy;
use App\Shared\Domain\ValueObjects\GenericId;

interface VehiclePolicyRepositoryContract
{
    public function findVehiclePolicyById(GenericId $vehicle_id): ?VehiclePolicy;
    public function findVehicleById(GenericId $policy_id): ?VehiclePolicy;
}
