<?php

namespace App\Modules\Lead\Domain\Contracts;

use App\Models\Lead;
use App\Models\User;
use App\Modules\Lead\Domain\Entities\LeadEntity;
use App\Modules\Lead\Domain\Enums\LeadProductType;
use App\Shared\Domain\Enums\LeadStatus;
use App\Shared\Domain\ValueObjects\GenericDate;
use App\Shared\Domain\ValueObjects\GenericId;
use App\Shared\Domain\ValueObjects\LowerText;
use App\Shared\Domain\ValueObjects\Uuid;
use Illuminate\Database\Query\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use stdClass;

interface LeadRepositoryContract
{
    public function addLead(LeadEntity $leadEntity): ?Lead;
    public function assignAgentId(GenericId $leadId, User $user): void;
    public function findLeadById(GenericId $leadId): ?Lead;
    public function updateLeadStatus(Uuid $uuid, LeadStatus $leadStatus): void;
    public function updateLeadDueDate(Uuid $uuid, ?GenericDate $dueDate): void;
    public function findByUuid(Uuid $uuid): ?Lead;
    public function findByCustomerId(GenericId $customerId, ?LowerText $keyword, ?int $per_page = 5): ?LengthAwarePaginator;
    public function activeLead(GenericId $customerId, LeadProductType $code, Builder $pivot, ?array $conditions = []): ?stdClass;
}
