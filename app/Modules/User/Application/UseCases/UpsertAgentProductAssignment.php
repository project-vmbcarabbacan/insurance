<?php

namespace App\Modules\User\Application\UseCases;

use App\Modules\Agent\Application\Services\AgentProductService;
use App\Modules\User\Application\Exceptions\UserNotFoundException;
use App\Modules\User\Application\Services\UserService;
use App\Shared\Domain\Enums\AuditAction;
use App\Shared\Domain\ValueObjects\GenericId;

class UpsertAgentProductAssignment
{
    public function __construct(
        protected UserService $userService,
        protected AgentProductService $agentProductService
    ) {}

    public function execute(GenericId $agentId, array $products)
    {
        $user = $this->userService->getById($agentId);

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

        $this->agentProductService->upsertAccessed(
            $data,
            ['agent_id', 'insurance_product_code'],
            ['is_active', 'priority']
        );

        insurance_audit(
            $user,
            AuditAction::PRODUCT_ASSIGNMENT,
            null,
            $products
        );
    }
}
