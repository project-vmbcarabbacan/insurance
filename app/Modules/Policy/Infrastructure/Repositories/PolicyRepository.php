<?php

namespace App\Modules\Policy\Infrastructure\Repositories;

use App\Models\Policy;
use App\Modules\Policy\Domain\Contracts\PolicyRepositoryContract;
use App\Modules\Policy\Domain\Entities\AddPolicyEntity;
use App\Modules\Policy\Domain\Entities\UpdatePolicyEntity;
use App\Shared\Domain\Enums\AuditAction;
use App\Shared\Domain\Enums\PolicyStatus;
use App\Shared\Domain\ValueObjects\GenericId;

abstract class PolicyRepository implements PolicyRepositoryContract
{
    abstract public function addProductPolicy(array $data): void;

    public function addPolicy(AddPolicyEntity $addPolicyEntity): void
    {
        $policy = Policy::create($addPolicyEntity->toArray());

        insurance_audit(
            $policy,
            AuditAction::POLICY_DRAFT_CREATED,
            null,
            ['status' => 'draft']
        );
    }

    public function updatePolicy(GenericId $policyId, UpdatePolicyEntity $updatePolicyEntity): void
    {
        $policy = $this->findById($policyId);

        /**
         * Extract only non-null values
         */
        $updates = array_non_null_values($updatePolicyEntity->toArray());

        /**
         * No changes — avoid unnecessary DB hit
         */
        if ($updates === []) {
            return;
        }

        /**
         * Capture original values for audit
         */
        $oldValues = array_old_values($policy, $updates);

        $policy->update($updates);

        insurance_audit(
            $policy,
            AuditAction::POLICY_UPDATED,
            $oldValues,
            $updates
        );
    }


    public function updateStatus(GenericId $policyId, PolicyStatus $policyStatus)
    {
        $policy = $this->findById($policyId);

        $oldValues = ['status' => $policy->status];

        $policy->update([
            'status' => $policyStatus->value
        ]);

        insurance_audit(
            $policy,
            AuditAction::POLICY_UPDATED,
            $oldValues,
            ['status' => $policyStatus->value]
        );
    }

    public function findById(GenericId $policyId): ?Policy
    {
        return Policy::find($policyId->value());
    }
}
