<?php

namespace App\Modules\Lead\Infrastructure\repositories;

use App\Models\Lead;
use App\Modules\Lead\Domain\Contracts\LeadRepositoryContract;
use App\Modules\Lead\Domain\Entities\LeadEntity;
use App\Shared\Domain\Enums\AuditAction;
use App\Shared\Domain\Enums\LeadStatus;
use App\Shared\Domain\ValueObjects\GenericId;
use App\Shared\Domain\ValueObjects\Uuid;
use Illuminate\Database\Eloquent\Collection;

class LeadRepository implements LeadRepositoryContract
{
    public function addLead(LeadEntity $leadEntity): void
    {
        $lead = Lead::create($leadEntity->toArray());

        // Record audit log for user creation
        insuranceAudit(
            $lead,
            AuditAction::LEAD_CREATED,
            null,
            ['type' => 'new']
        );
    }

    public function updateLeadStatus(Uuid $uuid, LeadStatus $leadStatus): void
    {
        $lead = $this->findByUuid($uuid);

        $oldValues = ['status' => $lead->status];

        $lead->update([
            'status' => $leadStatus->value
        ]);

        insuranceAudit(
            $lead,
            AuditAction::LEAD_STATUS_UPDATED,
            $oldValues,
            ['type' => $leadStatus->value]
        );
    }

    public function findByCustomerId(GenericId $customerId): Collection
    {
        return Lead::query()
            ->with('meta')
            ->whereHas(
                'meta',
                fn($q) =>
                $q->where('key', 'customer_id')
                    ->where('value', (string) $customerId->value())
            )
            ->get();
    }


    public function findByUuid(Uuid $uuid): ?Lead
    {
        return Lead::uuid($uuid->value())->first();
    }
}
