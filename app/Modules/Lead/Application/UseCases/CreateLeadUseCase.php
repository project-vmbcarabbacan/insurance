<?php

namespace App\Modules\Lead\Application\UseCases;

use App\Models\Customer;
use App\Models\User;
use App\Modules\Agent\Application\Services\AgentAssignmentService;
use App\Modules\Lead\Application\DTOs\CreateLeadDto;
use App\Modules\Lead\Application\DTOs\LeadActivityDto;
use App\Modules\Lead\Application\Services\LeadActivityService;
use App\Modules\Lead\Application\Services\LeadMetaService;
use App\Modules\Lead\Application\Services\LeadService;
use App\Modules\Lead\Domain\Enums\LeadProductType;
use App\Modules\Lead\Domain\Maps\LeadActivityDueDateMap;
use App\Modules\User\Application\Services\UserService;
use App\Shared\Domain\Enums\LeadActivityType;
use App\Shared\Domain\ValueObjects\GenericId;

class CreateLeadUseCase
{

    public function __construct(
        protected LeadService $lead_service,
        protected LeadMetaService $lead_meta_service,
        protected LeadActivityService $lead_activity_service,
        protected AgentAssignmentService $agent_assignment_service,
        protected UserService $user_service
    ) {}

    public function execute(Customer $customer, CreateLeadDto $createLeadDto)
    {
        $customerId = GenericId::fromId($customer->id);
        $code = LeadProductType::fromValue($createLeadDto->code->value());

        $active = $this->lead_service->activeLead($customerId, $code);
        if ($active) return $active;

        // Create new lead
        $createLeadDto = $createLeadDto->withCustomerId($customerId);
        $lead = $this->lead_service->createLead($createLeadDto);
        $leadId = GenericId::fromId($lead->id);

        // Record lead creation activity
        $this->generateLeadAcitivity($leadId, LeadActivityType::LEAD_CREATED);

        // Record lead creation activity
        if (!empty($lead->assigned_agent_id)) return $lead;

        // Add metadata
        $this->lead_meta_service->addMeta(
            $leadId,
            [
                'customer_id' => $customerId->value()
            ],
            $code
        );

        // Assign agent
        $agentAssignment = $this->agent_assignment_service->assign($leadId, $createLeadDto->code->value(), $createLeadDto->assigned_agent_id);

        $agentId = GenericId::fromId($agentAssignment->agent_id);
        $user = $this->user_service->getById($agentId);

        $this->lead_service->agentAssignment($leadId, $user);

        // Generate lead assigned activity and set due date
        $this->generateLeadAcitivity($leadId, LeadActivityType::LEAD_ASSIGNED, getAuthenticatedUser());

        $lead->due_date = now()->add(LeadActivityDueDateMap::dueIn(LeadActivityType::LEAD_ASSIGNED));
        $lead->save();

        return $lead;
    }

    private function generateLeadAcitivity(GenericId $leadId, LeadActivityType $leadActivityType, ?User $user = null, ?string $notes = null)
    {
        $dto = $this->lead_activity_service->generateLeadDto($leadId, $leadActivityType, $user, $notes);
        $this->lead_activity_service->addLeadActivity($dto);
    }
}
