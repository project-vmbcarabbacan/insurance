<?php

namespace App\Modules\Lead\Infrastructure\repositories;

use App\Models\Lead;
use App\Models\User;
use App\Modules\Lead\Application\Exceptions\LeadUuidNotFoundException;
use App\Modules\Lead\Domain\Contracts\LeadRepositoryContract;
use App\Modules\Lead\Domain\Entities\LeadEntity;
use App\Modules\Lead\Domain\Enums\LeadProductType;
use App\Shared\Domain\Enums\AuditAction;
use App\Shared\Domain\Enums\LeadStatus;
use App\Shared\Domain\ValueObjects\GenericId;
use App\Shared\Domain\ValueObjects\Uuid;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use PDO;

class LeadRepository implements LeadRepositoryContract
{
    public function addLead(LeadEntity $leadEntity): ?Lead
    {
        $lead = Lead::create($leadEntity->toArray());

        // Record audit log for user creation
        insurance_audit(
            $lead,
            AuditAction::LEAD_CREATED,
            null,
            ['status' => 'created']
        );

        return $lead;
    }

    public function assignAgentId(GenericId $leadId, User $user): void
    {
        $lead = $this->findLeadById($leadId);

        if (!$lead) {
            throw new LeadUuidNotFoundException;
        }

        $lead->update([
            'assigned_agent_id' => $user->id
        ]);

        insurance_audit(
            $lead,
            AuditAction::LEAD_ASSIGNED,
            null,
            ['assigned' => $user->name]
        );
    }

    public function findLeadById(GenericId $leadId): ?Lead
    {
        return Lead::find($leadId->value());
    }

    public function updateLeadStatus(Uuid $uuid, LeadStatus $leadStatus): void
    {
        $lead = $this->findByUuid($uuid);

        $oldValues = ['status' => $lead->status];

        $lead->update([
            'status' => $leadStatus->value
        ]);

        insurance_audit(
            $lead,
            AuditAction::LEAD_STATUS_UPDATED,
            $oldValues,
            ['status' => $leadStatus->value]
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

    public function activeLead(GenericId $customerId, LeadProductType $code): ?Lead
    {
        return Lead::where('insurance_product_code', $code->value)
            ->whereIn('status', [
                LeadStatus::NEW->value,
                LeadStatus::CONTACTED->value,
                LeadStatus::QUOTED->value
            ])
            ->whereHas('metas', function ($q) use ($customerId) {
                $q->where('key', 'customer_id')
                    ->where('value', (string) $customerId->value());
            })
            ->first();
    }
}
