<?php

namespace App\Modules\Lead\Domain\Contracts;

use App\Modules\Lead\Domain\Entities\LeadActivityEntity;
use App\Shared\Domain\ValueObjects\GenericId;
use Illuminate\Database\Eloquent\Collection;

interface LeadActivityRepositoryContract
{
    public function addLeadActivity(LeadActivityEntity $leadActivityEntity): void;
    public function getAllLeadActivityByLeadId(GenericId $leadId): Collection;
}
