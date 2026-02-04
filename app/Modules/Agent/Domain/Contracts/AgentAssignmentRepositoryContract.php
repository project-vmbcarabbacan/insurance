<?php

namespace App\Modules\Agent\Domain\Contracts;

use App\Models\AgentAssignment;
use App\Models\AgentAssignmentQueue;
use App\Models\AgentAssignmentSetting;
use App\Shared\Domain\ValueObjects\GenericId;

interface AgentAssignmentRepositoryContract
{
    public function assignmentSetting(string $insuranceCode): ?AgentAssignmentSetting;
    public function assignmentRoundRobinQueue(string $insuranceCode): ?AgentAssignmentQueue;
    public function assignmentLeastLoadedQueue(string $insuranceCode, int $maxActiveLeads): ?AgentAssignmentQueue;
    public function assignmentManualQueue(GenericId $agentId, string $insuranceCode): ?AgentAssignmentQueue;
    public function assignmentAgent(GenericId $leadId, GenericId $agentId, string $insuranceCode): ?AgentAssignment;
}
