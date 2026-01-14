<?php

namespace App\Modules\Policy\Domain\Contracts;

use App\Models\Policy;
use App\Modules\Policy\Domain\Entities\AddPolicyEntity;
use App\Modules\Policy\Domain\Entities\UpdatePolicyEntity;
use App\Shared\Domain\Enums\PolicyStatus;
use App\Shared\Domain\ValueObjects\GenericId;

interface PolicyRepositoryContract
{
    public function addPolicy(AddPolicyEntity $addPolicyEntity): void;
    public function updatePolicy(GenericId $policyId, UpdatePolicyEntity $updatePolicyEntity): void;
    public function findById(GenericId $policyId): ?Policy;
    public function policyActive(GenericId $policyId, PolicyStatus $policyStatus);
    public function policyExpired(GenericId $policyId, PolicyStatus $policyStatus);
    public function policySuspended(GenericId $policyId, PolicyStatus $policyStatus);
    public function policyCancelled(GenericId $policyId, PolicyStatus $policyStatus);
    public function policyReinstated(GenericId $policyId, PolicyStatus $policyStatus);
    public function policyRenewalInitiated(GenericId $policyId, PolicyStatus $policyStatus);
    public function policyRenewed(GenericId $policyId, PolicyStatus $policyStatus);
    public function policyNonRenewed(GenericId $policyId, PolicyStatus $policyStatus);
    public function policyEndorsed(GenericId $policyId, PolicyStatus $policyStatus);
    public function policyCoverageUpdated(GenericId $policyId, PolicyStatus $policyStatus);
}
