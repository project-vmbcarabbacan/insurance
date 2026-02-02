<?php

namespace App\Modules\Agent\Domain\Contracts;

use App\Models\AgentProductAccess;
use App\Shared\Domain\ValueObjects\GenericId;
use Illuminate\Database\Eloquent\Collection;

interface AgentProductRepositoryContract
{
    public function findProductsByAgentId(GenericId $agentId): Collection;
    public function findProductByAgentIdAndCode(GenericId $agentid, string $code): ?AgentProductAccess;
    public function deleteProductsByAgentId(GenericId $agentId): void;
    public function addProductsByAgentId(array $data): void;
    public function upsertAgentAccessed(array $data, array $search, array $update): void;
}
