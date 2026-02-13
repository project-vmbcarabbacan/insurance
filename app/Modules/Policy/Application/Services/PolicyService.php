<?php

namespace App\Modules\Policy\Application\Services;

use App\Modules\Policy\Application\DTOs\AddPolicyDto;
use App\Modules\Policy\Application\DTOs\UpdatePolicyDto;
use App\Modules\Policy\Domain\Contracts\PolicyRepositoryContract;
use App\Modules\Policy\Domain\Entities\AddPolicyEntity;
use App\Modules\Policy\Domain\Entities\UpdatePolicyEntity;
use App\Shared\Domain\ValueObjects\GenericId;

class PolicyService
{
    public function __construct(
        protected PolicyRepositoryContract $policyRepositoryContract
    ) {}

    public function getPolicyById(GenericId $policyId)
    {
        return $this->policyRepositoryContract->findById($policyId);
    }

    public function createPolicy(AddPolicyDto $addPolicyDto)
    {

        $addPolicyEntity = new AddPolicyEntity(
            lead_id: $addPolicyDto->lead_id,
            customer_id: $addPolicyDto->customer_id,
            insurance_product_code: $addPolicyDto->insurance_product_code,
            quotation_id: $addPolicyDto->quotation_id,
            provider_id: $addPolicyDto->provider_id,
            plan_id: $addPolicyDto->plan_id
        );

        return $this->policyRepositoryContract->addPolicy($addPolicyEntity);
    }

    public function updatePolicy(UpdatePolicyDto $updatePolicyDto)
    {

        $updatePolicyEntity = new UpdatePolicyEntity(
            premium_amount: $updatePolicyDto->premium_amount,
            vat: $updatePolicyDto->vat,
            policy_number: $updatePolicyDto->policy_number,
            start_date: $updatePolicyDto->start_date,
            end_date: $updatePolicyDto->end_date
        );

        return $this->policyRepositoryContract->updatePolicy($updatePolicyDto->policy_id, $updatePolicyEntity);
    }
}
