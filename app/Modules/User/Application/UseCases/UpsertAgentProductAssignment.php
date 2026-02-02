<?php

namespace App\Modules\User\Application\UseCases;

use App\Modules\Agent\Application\Services\AgentProductService;
use App\Modules\User\Application\Exceptions\UserNotFoundException;
use App\Modules\User\Application\Services\UserService;
use App\Shared\Domain\ValueObjects\GenericId;

class UpsertAgentProductAssignment
{
    public function __construct(
        protected UserService $user_service,
        protected AgentProductService $agent_product_service
    ) {}

    public function execute(GenericId $agentId, array $products)
    {
        $user = $this->user_service->getById($agentId);

        if (!$user) {
            throw new UserNotFoundException();
        }

        $data = [];
        foreach ($products as $key => $value) {
            $data[] = [
                'agent_id' => $agentId->value(),
                'insurance_product_code' => $key,
                'is_active' => $value,
                'priority' => 1
            ];
        }

        $this->agent_product_service->upsertAccessed(
            $data,
            ['agent_id', 'insurance_product_code'],
            ['is_active', 'priority']
        );
    }
}
