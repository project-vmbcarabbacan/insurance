<?php

namespace App\Modules\Policy\Domain\Contracts;

use App\Models\HealthMember;
use App\Models\HealthPolicy;
use App\Modules\Policy\Domain\Entities\HealthMemberEntity;
use App\Shared\Domain\ValueObjects\GenericId;
use Illuminate\Database\Eloquent\Collection;

interface HealthPolicyRepositoryContract
{
    public function findHealthPolicyById(GenericId $health_id): ?HealthPolicy;
    public function findHealthById(GenericId $policy_id): ?HealthPolicy;
    public function findHealthMemberByHealthId(GenericId $health_id): ?Collection;
    public function findHealthMemberById(GenericId $member_id): ?HealthMember;
    public function addHealthMember(HealthMemberEntity $healthMemberEntity): void;
    public function updateHealthMember(GenericId $member_id, HealthMemberEntity $healthMemberEntity): void;
}
