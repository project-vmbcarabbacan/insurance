<?php

namespace App\Modules\Policy\Infrastructure\Repositories;

use App\Models\HomePolicy;
use App\Modules\Policy\Domain\Contracts\HomePolicyRepositoryContract;
use App\Shared\Domain\Enums\AuditAction;
use App\Shared\Domain\ValueObjects\GenericId;

class HomePolicyRepository extends PolicyRepository implements HomePolicyRepositoryContract
{

    public function addProductPolicy(array $data): void
    {
        $homePolicy = HomePolicy::create($data);

        insurance_audit(
            $homePolicy,
            AuditAction::POLICY_HOME_CREATED,
            null,
            ["type" => "Policy home created"]
        );
    }

    public function updateProductPolicy(GenericId $id, array $data): void
    {
        $home = $this->findHomeById($id);

        $updates = array_non_null_values($data);

        if ($updates === []) {
            return;
        }

        $oldValues = array_old_values($home, $updates);

        $home->update($updates);

        insurance_audit(
            $home,
            AuditAction::POLICY_HOME_UPDATED,
            $oldValues,
            $updates
        );
    }

    public function findHomePolicyById(GenericId $home_id): ?HomePolicy
    {
        return HomePolicy::find($home_id->value());
    }

    public function findHomeById(GenericId $policy_id): ?HomePolicy
    {
        return HomePolicy::policy($policy_id)->first();
    }
}
