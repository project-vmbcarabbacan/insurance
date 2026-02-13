<?php

namespace App\Shared\Domain\Contracts;

use App\Models\PolicyProvider;
use App\Shared\Domain\Entities\PolicyProviderEntity;
use App\Shared\Domain\Entities\PolicyProviderFilterEntity;
use App\Shared\Domain\ValueObjects\GenericId;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface PolicyProviderRepositoryContract
{
    public function addPolicyProvider(PolicyProviderEntity $policyProviderEntity): void;
    public function updatePolicyProvider(GenericId $policyProviderId, PolicyProviderEntity $policyProviderEntity): void;
    public function findPolicyProvider(GenericId $policyProviderId): ?PolicyProvider;
    public function getAllPolicyProvider(PolicyProviderFilterEntity $policyProviderFilterEntity): ?LengthAwarePaginator;
    public function activateProvider(GenericId $policyProviderId): void;
    public function inactivateProvider(GenericId $policyProviderId): void;
    public function activePolicyProvider(): ?Collection;
}
