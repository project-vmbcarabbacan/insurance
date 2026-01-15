<?php

namespace App\Modules\Policy\Infrastructure\Repositories;

use App\Models\HealthMember;
use App\Models\HealthPolicy;
use App\Modules\Policy\Domain\Contracts\HealthPolicyRepositoryContract;
use App\Modules\Policy\Domain\Entities\HealthMemberEntity;
use App\Shared\Domain\Enums\AuditAction;
use App\Shared\Domain\ValueObjects\GenericId;
use Illuminate\Database\Eloquent\Collection;

class HealthPolicyRepository extends PolicyRepository implements HealthPolicyRepositoryContract
{
    public function addProductPolicy(array $data): void
    {
        $vehiclePolicy = HealthPolicy::create($data);

        insurance_audit(
            $vehiclePolicy,
            AuditAction::POLICY_HEALTH_CREATED,
            null,
            ["type" => "Policy health created"]
        );
    }

    public function updateProductPolicy(GenericId $id, array $data): void
    {
        $health = $this->findHealthById($id);

        $updates = array_non_null_values($data);

        if ($updates === []) {
            return;
        }

        $oldValues = array_old_values($health, $updates);

        $health->update($updates);

        insurance_audit(
            $health,
            AuditAction::POLICY_HEALTH_UPDATED,
            $oldValues,
            $updates
        );
    }

    public function findHealthPolicyById(GenericId $health_id): ?HealthPolicy
    {
        return HealthPolicy::find($health_id->value());
    }

    public function findHealthById(GenericId $policy_id): ?HealthPolicy
    {
        return HealthPolicy::policy($policy_id)->first();
    }


    public function addHealthMember(HealthMemberEntity $healthMemberEntity): void
    {
        $healthMember = HealthMember::create($healthMemberEntity->toArray());

        insurance_audit(
            $healthMember,
            AuditAction::POLICY_HEALTH_MEMBER_CREATED,
            null,
            ['type' => 'member created']
        );
    }

    public function updateHealthMember(GenericId $member_id, HealthMemberEntity $healthMemberEntity): void
    {
        $healthMember = $this->findHealthMemberById($member_id);

        $updates = array_non_null_values($healthMember->toArray());

        if ($updates === []) {
            return;
        }

        $oldValues = array_old_values($healthMember, $updates);

        $healthMember->update($updates);

        insurance_audit(
            $healthMember,
            AuditAction::POLICY_HEALTH_MEMBER_UPDATED,
            $oldValues,
            $updates
        );
    }

    public function findHealthMemberByHealthId(GenericId $health_id): ?Collection
    {
        return HealthMember::health($health_id)->get();
    }

    public function findHealthMemberById(GenericId $member_id): ?HealthMember
    {
        return HealthMember::find($member_id->value());
    }
}
