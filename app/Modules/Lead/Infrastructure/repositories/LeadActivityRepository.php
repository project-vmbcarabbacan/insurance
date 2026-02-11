<?php

namespace App\Modules\Lead\Infrastructure\repositories;

use App\Models\LeadActivity;
use App\Modules\Lead\Domain\Contracts\LeadActivityRepositoryContract;
use App\Modules\Lead\Domain\Entities\LeadActivityEntity;
use App\Shared\Domain\ValueObjects\GenericId;
use Illuminate\Database\Eloquent\Collection;

class LeadActivityRepository implements LeadActivityRepositoryContract
{
    public function addLeadActivity(LeadActivityEntity $leadActivityEntity): void
    {
        LeadActivity::create($leadActivityEntity->toArray());
    }

    public function getAllLeadActivityByLeadId(GenericId $leadId): Collection
    {
        return LeadActivity::lead($leadId->value())
            ->orderBy('created_at', 'DESC')
            ->get();
    }
}
