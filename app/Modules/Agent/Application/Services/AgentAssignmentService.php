<?php

namespace App\Modules\Agent\Application\Services;

use App\Models\AgentAssignment;
use App\Modules\Agent\Domain\Contracts\AgentAssignmentRepositoryContract;
use App\Shared\Domain\Enums\AssignmentStrategy;
use App\Shared\Domain\ValueObjects\GenericId;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class AgentAssignmentService
{
    public function __construct(
        protected AgentAssignmentRepositoryContract $agent_assignment_repository_contract
    ) {}

    public function assign(GenericId $leadId, string $insuranceProductCode, ?GenericId $manualAgentId = null): AgentAssignment
    {
        return DB::transaction(function () use ($leadId, $insuranceProductCode, $manualAgentId) {

            $setting = $this->agent_assignment_repository_contract->assignmentSetting($insuranceProductCode);


            return match ($setting->strategy) {
                AssignmentStrategy::ROUND_ROBIN->value =>
                $this->roundRobin($leadId, $insuranceProductCode),

                AssignmentStrategy::LEAST_LOADED->value =>
                $this->leastLoaded(
                    $leadId,
                    $insuranceProductCode,
                    $setting->max_active_leads_per_agent
                ),

                AssignmentStrategy::MANUAL->value =>
                $this->manual($leadId, $insuranceProductCode, $manualAgentId),

                default => throw new RuntimeException('Invalid strategy'),
            };
        });
    }

    private function roundRobin(GenericId $leadId, string $insuranceProductCode): AgentAssignment
    {
        $queue = $this->agent_assignment_repository_contract->assignmentRoundRobinQueue($insuranceProductCode);

        if (!$queue) throw new RuntimeException('No agent available.');

        $assignment = $this->agent_assignment_repository_contract->assignmentAgent(
            $leadId,
            GenericId::fromId($queue->agent_id),
            $insuranceProductCode
        );

        $queue->update(['last_assigned_at' => now()]);

        return $assignment;
    }

    private function leastLoaded(GenericId $leadId, string $insuranceProductCode, int $maxActiveLeads): AgentAssignment
    {
        // Count active leads per agent
        $agent = $this->agent_assignment_repository_contract->assignmentLeastLoadedQueue($insuranceProductCode, $maxActiveLeads);

        if (!$agent) throw new RuntimeException('No agent available (all at max capacity).');

        return $this->agent_assignment_repository_contract->assignmentAgent(
            $leadId,
            GenericId::fromId($agent->agent_id),
            $insuranceProductCode
        );
    }

    private function manual(GenericId $leadId, string $insuranceProductCode, GenericId $agentId): AgentAssignment
    {
        // Confirm agent has access
        $queue = $this->agent_assignment_repository_contract->assignmentManualQueue($agentId, $insuranceProductCode);

        if (!$queue) throw new RuntimeException('Agent not available for this product.');

        return $this->agent_assignment_repository_contract->assignmentAgent(
            $leadId,
            $agentId,
            $insuranceProductCode
        );
    }
}
