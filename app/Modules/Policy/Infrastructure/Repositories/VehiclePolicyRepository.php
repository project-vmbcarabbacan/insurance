<?php

namespace App\Modules\Policy\Infrastructure\Repositories;

use App\Models\VehiclePolicy;
use App\Modules\Policy\Domain\Contracts\VehiclePolicyRepositoryContract;
use App\Shared\Domain\Enums\AuditAction;
use App\Shared\Domain\ValueObjects\GenericId;

class VehiclePolicyRepository extends PolicyRepository implements VehiclePolicyRepositoryContract
{

    public function addProductPolicy(array $data): void
    {
        $vehiclePolicy = VehiclePolicy::create($data);

        $type = $data['type'];
        insurance_audit(
            $vehiclePolicy,
            AuditAction::POLICY_VEHICLE_CREATED,
            null,
            ["type" => "Policy {$type} created"]
        );
    }

    public function updateProductPolicy(GenericId $id, array $data): void
    {
        $vehicle = $this->findVehicleById($id);

        $updates = array_non_null_values($data);

        if ($updates === []) {
            return;
        }

        $oldValues = array_old_values($vehicle, $updates);

        $vehicle->update($updates);

        insurance_audit(
            $vehicle,
            AuditAction::POLICY_VEHICLE_UPDATED,
            $oldValues,
            $updates
        );
    }

    public function findVehiclePolicyById(GenericId $vehicle_id): ?VehiclePolicy
    {
        return VehiclePolicy::find($vehicle_id->value());
    }

    public function findVehicleById(GenericId $policy_id): ?VehiclePolicy
    {
        return VehiclePolicy::policy($policy_id)->first();
    }
}
