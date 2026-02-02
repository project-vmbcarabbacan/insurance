<?php

namespace App\Modules\Agent\Infrastructure\Repositories;

use App\Models\AgentProductAccess;
use App\Modules\Agent\Domain\Contracts\AgentProductRepositoryContract;
use App\Shared\Domain\ValueObjects\GenericId;
use Illuminate\Database\Eloquent\Collection;

class AgentProductRepository implements AgentProductRepositoryContract
{

    public function findProductsByAgentId(GenericId $agentId): Collection
    {
        return AgentProductAccess::agentId($agentId->value())->get();
    }

    public function findProductByAgentIdAndCode(GenericId $agentid, string $code): ?AgentProductAccess
    {
        return AgentProductAccess::agentId($agentid->value())
            ->code($code)
            ->first();
    }

    public function deleteProductsByAgentId(GenericId $agentId): void
    {
        AgentProductAccess::agentId($agentId->value())->delete();
    }

    public function addProductsByAgentId(array $data): void
    {
        AgentProductAccess::inser($data);
    }

    public function upsertAgentAccessed(array $data, array $search, array $update): void
    {
        AgentProductAccess::upsert(
            $data,
            $search,
            $update
        );
    }
}
