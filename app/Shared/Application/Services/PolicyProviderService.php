<?php

namespace App\Shared\Application\Services;

use App\Shared\Application\DTOs\PolicyProviderDto;
use App\Shared\Domain\Contracts\PolicyProviderRepositoryContract;
use App\Shared\Domain\Entities\PolicyProviderEntity;

class PolicyProviderService
{
    public function __construct(
        protected PolicyProviderRepositoryContract $policy_provider_repository_contract
    ) {}

    public function upsertPolicyProvider(PolicyProviderDto $policyProviderDto)
    {
        $entity = new PolicyProviderEntity(
            code: $policyProviderDto->code,
            name: $policyProviderDto->name,
            email: $policyProviderDto->email,
            phone: $policyProviderDto->phone
        );

        if (empty($policyProviderDto->policy_provider_id->value())) {
            $this->policy_provider_repository_contract->addPolicyProvider($entity);
        } else {
            $this->policy_provider_repository_contract->updatePolicyProvider($policyProviderDto->policy_provider_id, $entity);
        }
    }
}
