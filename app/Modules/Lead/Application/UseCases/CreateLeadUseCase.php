<?php

namespace App\Modules\Lead\Application\UseCases;

use App\Models\Customer;
use App\Modules\Agent\Application\Services\AgentAssignmentService;
use App\Modules\Lead\Application\DTOs\CreateLeadDto;
use App\Modules\Lead\Application\Services\LeadMetaService;
use App\Modules\Lead\Application\Services\LeadService;
use App\Modules\Lead\Domain\Enums\LeadProductType;
use App\Modules\User\Application\Services\UserService;
use App\Shared\Domain\ValueObjects\GenericId;

class CreateLeadUseCase
{

    public function __construct(
        protected LeadService $lead_service,
        protected LeadMetaService $lead_meta_service,
        protected AgentAssignmentService $agent_assignment_service,
        protected UserService $user_service
    ) {}

    public function execute(Customer $customer, CreateLeadDto $createLeadDto)
    {
        $customerId = GenericId::fromId($customer->id);
        $code = LeadProductType::fromValue($createLeadDto->code->value());

        $active = $this->lead_service->activeLead($customerId, $code);
        if ($active) return $active;

        $createLeadDto = $createLeadDto->withCustomerId($customerId);
        $lead = $this->lead_service->createLead($createLeadDto);

        $leadId = GenericId::fromId($lead->id);
        $data = [
            'customer_id' => $customerId->value()
        ];

        if (!empty($lead->assigned_agent_id)) return $lead;

        $this->lead_meta_service->addMeta($leadId, $data, $code);
        $agentAssignment = $this->agent_assignment_service->assign($leadId, $createLeadDto->code->value(), $createLeadDto->assigned_agent_id);

        $agentId = GenericId::fromId($agentAssignment->agent_id);
        $user = $this->user_service->getById($agentId);

        $this->lead_service->agentAssignment($leadId, $user);

        return $lead;
    }
}
