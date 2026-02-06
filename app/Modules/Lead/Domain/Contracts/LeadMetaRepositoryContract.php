<?php

namespace App\Modules\Lead\Domain\Contracts;

use App\Models\Lead;
use App\Shared\Domain\ValueObjects\GenericId;
use stdClass;

interface LeadMetaRepositoryContract
{
    public function getLeadByCustomerId(GenericId $customeriD): array;
    public function getLeadByLeadId(GenericId $leadId): stdClass | null;
    public function addLeadMeta(GenericId $leadId, array $data): void;
    public function updateLeadMeta(Lead $lead, array $data): void;
}
