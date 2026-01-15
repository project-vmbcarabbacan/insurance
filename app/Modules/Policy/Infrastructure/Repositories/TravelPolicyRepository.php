<?php

namespace App\Modules\Policy\Infrastructure\Repositories;

use App\Models\TravelPolicy;
use App\Modules\Policy\Domain\Contracts\TravelPolicyRepositoryContract;
use App\Shared\Domain\Enums\AuditAction;
use App\Shared\Domain\ValueObjects\GenericId;

class TravelPolicyRepository extends PolicyRepository implements TravelPolicyRepositoryContract
{

    public function addProductPolicy(array $data): void
    {
        $travelPolicy = TravelPolicy::create($data);

        insurance_audit(
            $travelPolicy,
            AuditAction::POLICY_TRAVEL_CREATED,
            null,
            ["type" => "Policy travel created"]
        );
    }

    public function updateProductPolicy(GenericId $id, array $data): void
    {
        $travel = $this->findTravelById($id);

        $updates = array_non_null_values($data);

        if ($updates === []) {
            return;
        }

        $oldValues = array_old_values($travel, $updates);

        $travel->update($updates);

        insurance_audit(
            $travel,
            AuditAction::POLICY_TRAVEL_UPDATED,
            $oldValues,
            $updates
        );
    }

    public function findTravelPolicyById(GenericId $travel_id): ?TravelPolicy
    {
        return TravelPolicy::find($travel_id->value());
    }

    public function findTravelById(GenericId $policy_id): ?TravelPolicy
    {
        return TravelPolicy::policy($policy_id)->first();
    }
}
