<?php

namespace App\Modules\Lead\Domain\Contracts;

use App\Models\Lead;
use App\Shared\Domain\ValueObjects\GenericId;
use App\Shared\Domain\ValueObjects\Uuid;
use stdClass;

interface LeadMetaRepositoryContract
{
    public function getLeadByCustomerId(GenericId $customerId, array $map): array;
    public function getLeadByLeadId(Uuid $leadUuid, array $map): stdClass | null;
    public function getMemberKeys(GenericId $leadId): array;
    public function addLeadMeta(GenericId $leadId, array $data): void;
    public function updateLeadMeta(Lead $lead, array $data): void;
}
