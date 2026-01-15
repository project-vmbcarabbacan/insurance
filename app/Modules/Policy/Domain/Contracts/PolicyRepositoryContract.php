<?php

namespace App\Modules\Policy\Domain\Contracts;

use App\Models\Policy;
use App\Modules\Policy\Domain\Entities\AddPolicyEntity;
use App\Modules\Policy\Domain\Entities\UpdatePolicyEntity;
use App\Shared\Domain\ValueObjects\GenericId;

interface PolicyRepositoryContract
{
    public function addProductPolicy(array $data): void;
    public function updateProductPolicy(GenericId $id, array $data): void;
    public function addPolicy(AddPolicyEntity $addPolicyEntity): void;
    public function updatePolicy(GenericId $policyId, UpdatePolicyEntity $updatePolicyEntity): void;
    public function findPolicyById(GenericId $policyId): ?Policy;
}
