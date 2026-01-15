<?php

namespace App\Modules\Policy\Infrastructure\Repositories;

use App\Models\PetPolicy;
use App\Modules\Policy\Domain\Contracts\PetPolicyRepositoryContract;
use App\Shared\Domain\Enums\AuditAction;
use App\Shared\Domain\ValueObjects\GenericId;

class PetPolicyRepository extends PolicyRepository implements PetPolicyRepositoryContract
{

    public function addProductPolicy(array $data): void
    {
        $petPolicy = PetPolicy::create($data);

        insurance_audit(
            $petPolicy,
            AuditAction::POLICY_PET_CREATED,
            null,
            ["type" => "Policy pet created"]
        );
    }

    public function updateProductPolicy(GenericId $id, array $data): void
    {
        $pet = $this->findPetById($id);

        $updates = array_non_null_values($data);

        if ($updates === []) {
            return;
        }

        $oldValues = array_old_values($pet, $updates);

        $pet->update($updates);

        insurance_audit(
            $pet,
            AuditAction::POLICY_PET_UPDATED,
            $oldValues,
            $updates
        );
    }

    public function findPetPolicyById(GenericId $pet_id): ?PetPolicy
    {
        return PetPolicy::find($pet_id->value());
    }

    public function findPetById(GenericId $policy_id): ?PetPolicy
    {
        return PetPolicy::policy($policy_id)->first();
    }
}
