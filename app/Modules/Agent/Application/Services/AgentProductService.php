<?php

namespace App\Modules\Agent\Application\Services;

use App\Models\AgentProductAccess;
use App\Modules\Agent\Domain\Contracts\AgentProductRepositoryContract;
use App\Shared\Domain\ValueObjects\GenericId;

class AgentProductService
{
    public function __construct(
        protected AgentProductRepositoryContract $agent_product_repository_contract
    ) {}

    public function getAccessByAgentId(GenericId $agentId)
    {
        return $this->agent_product_repository_contract->findProductsByAgentId($agentId);
    }

    public function getAccedByAgentIdAndCode(GenericId $agentId, string $code): ?AgentProductAccess
    {
        return $this->agent_product_repository_contract->findProductByAgentIdAndCode($agentId, $code);
    }

    public function deleteAccessByAgentId(GenericId $agentId)
    {
        $this->agent_product_repository_contract->deleteProductsByAgentId($agentId);
    }

    public function upsertAccessed(array $data, array $search, array $update): void
    {
        $this->agent_product_repository_contract->upsertAgentAccessed($data, $search, $update);
    }

    public function addAccessByAgentId(GenericId $agentId, array $accesses)
    {
        $data = [];
        foreach ($accesses as $access) {
            $data[] = [
                'agent_id' => $agentId->value(),
                'insurance_product_code' => $access
            ];
        }

        $this->agent_product_repository_contract->addProductsByAgentId($data);
    }
}
