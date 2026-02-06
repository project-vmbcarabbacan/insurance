<?php

namespace App\Modules\Lead\Domain\Contracts;

use App\Models\Lead;
use App\Models\User;
use App\Modules\Lead\Domain\Entities\LeadEntity;
use App\Modules\Lead\Domain\Enums\LeadProductType;
use App\Shared\Domain\Enums\LeadStatus;
use App\Shared\Domain\ValueObjects\GenericId;
use App\Shared\Domain\ValueObjects\Uuid;
use Illuminate\Database\Eloquent\Collection;

interface LeadRepositoryContract
{
    public function addLead(LeadEntity $leadEntity): ?Lead;
    public function assignAgentId(GenericId $leadId, User $user): void;
    public function findLeadById(GenericId $leadId): ?Lead;
    public function updateLeadStatus(Uuid $uuid, LeadStatus $leadStatus): void;
    public function findByUuid(Uuid $uuid): ?Lead;
    public function findByCustomerId(GenericId $customerId): Collection;
    public function activeLead(GenericId $customerId, LeadProductType $code): ?Lead;
}
