<?php

namespace App\Modules\Agent\Infrastructure\Repositories;

use App\Models\AgentAssignment;
use App\Models\AgentAssignmentQueue;
use App\Models\AgentAssignmentSetting;
use App\Modules\Agent\Domain\Contracts\AgentAssignmentRepositoryContract;
use App\Shared\Domain\Enums\AssignmentStatus;
use App\Shared\Domain\ValueObjects\GenericId;

use function Symfony\Component\Clock\now;

class AgentAssignmentRepository implements AgentAssignmentRepositoryContract
{
    public function assignmentSetting(string $insuranceCode): ?AgentAssignmentSetting
    {
        return AgentAssignmentSetting::code($insuranceCode)
            ->active()
            ->firstOrFail();
    }

    public function assignmentRoundRobinQueue(string $insuranceCode): ?AgentAssignmentQueue
    {
        return AgentAssignmentQueue::query()
            ->code($insuranceCode)
            ->active()
            ->orderBy('position')
            ->orderBy('last_assigned_at')
            ->lockForUpdate()
            ->first();
    }

    public function assignmentLeastLoadedQueue(string $insuranceCode, int $maxActiveLeads): ?AgentAssignmentQueue
    {
        return AgentAssignmentQueue::query()
            ->code($insuranceCode)
            ->active()
            ->withCount(['assignments as active_leads_count' => function ($q) {
                $q->whereIn('status', [AssignmentStatus::ASSIGNED, AssignmentStatus::CONTACTED]);
            }])
            ->having('active_leads_count', '<', $maxActiveLeads)
            ->orderBy('active_leads_count')
            ->lockForUpdate()
            ->first();
    }

    public function assignmentManualQueue(GenericId $agentId, string $insuranceCode): ?AgentAssignmentQueue
    {
        return AgentAssignmentQueue::code($insuranceCode)
            ->agent($agentId)
            ->active()
            ->first();
    }

    public function assignmentAgent(GenericId $leadId, GenericId $agentId, string $insuranceCode): ?AgentAssignment
    {
        return AgentAssignment::create([
            'lead_id' => $leadId->value(),
            'agent_id' => $agentId->value(),
            'insurance_product_code' => $insuranceCode,
            'status' => AssignmentStatus::ASSIGNED,
            'assigned_at' => now()
        ]);
    }
}
