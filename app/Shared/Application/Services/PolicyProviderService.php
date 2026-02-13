<?php

namespace App\Shared\Application\Services;

use App\Shared\Application\DTOs\PolicyProviderDto;
use App\Shared\Application\DTOs\PolicyProviderFilterDto;
use App\Shared\Domain\Contracts\PolicyProviderRepositoryContract;
use App\Shared\Domain\Entities\PolicyProviderEntity;
use App\Shared\Domain\Entities\PolicyProviderFilterEntity;
use App\Shared\Domain\ValueObjects\GenericId;

class PolicyProviderService
{
    public function __construct(
        protected PolicyProviderRepositoryContract $policyProviderRepositoryContract
    ) {}

    public function upsertPolicyProvider(PolicyProviderDto $policyProviderDto)
    {
        $entity = new PolicyProviderEntity(
            code: $policyProviderDto->code,
            name: $policyProviderDto->name,
            email: $policyProviderDto->email,
            phone: $policyProviderDto->phone
        );

        if (empty($policyProviderDto->policy_provider_id)) {
            $this->policyProviderRepositoryContract->addPolicyProvider($entity);
        } else {
            $this->policyProviderRepositoryContract->updatePolicyProvider($policyProviderDto->policy_provider_id, $entity);
        }
    }

    public function paginated(PolicyProviderFilterDto $dto)
    {

        $entity = new PolicyProviderFilterEntity(
            keyword: $dto->keyword,
            status: $dto->status,
            per_page: $dto->per_page,
        );

        return $this->policyProviderRepositoryContract->getAllPolicyProvider($entity);
    }

    public function search(GenericId $policyProviderId)
    {
        return $this->policyProviderRepositoryContract->findPolicyProvider($policyProviderId);
    }

    public function activate(GenericId $policyProviderId)
    {
        $this->policyProviderRepositoryContract->activateProvider($policyProviderId);
    }

    public function inactivate(GenericId $policyProviderId)
    {
        $this->policyProviderRepositoryContract->inactivateProvider($policyProviderId);
    }

    public function activePolicy()
    {
        $this->policyProviderRepositoryContract->activePolicyProvider();
    }
}
