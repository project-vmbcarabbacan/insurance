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
use App\Shared\Domain\ValueObjects\GenericDate;
use App\Shared\Domain\ValueObjects\GenericId;
use App\Shared\Domain\ValueObjects\LowerText;
use App\Shared\Domain\ValueObjects\Uuid;
use Illuminate\Database\Query\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use stdClass;

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

    public function updateLeadDueDate(Uuid $uuid, ?GenericDate $dueDate): void
    {
        $lead = $this->findByUuid($uuid);

        $lead->update([
            'due_date' => $dueDate ? $dueDate->toDateTimeString() : null
        ]);
    }

    public function findByCustomerId(GenericId $customerId, ?LowerText $keyword = null, ?int $per_page = 4): ?LengthAwarePaginator
    {
        $query = DB::table('leads as l')
            ->leftJoin('lead_metas as lm', function ($join) {
                $join->on('lm.lead_id', '=', 'l.id')
                    ->where('lm.key', '=', 'lead_details');
            })
            ->select(
                'l.id',
                'l.uuid',
                'l.insurance_product_code',
                'l.status',
                'l.due_date',
                'lm.value as lead_details'
            )
            ->where('l.customer_id', $customerId->value());

        if ($keyword && $keyword->value()) {
            $query->whereRaw('LOWER(lm.value) LIKE ?', [
                '%' . strtolower($keyword->value()) . '%'
            ]);
        }

        return $query->orderByRaw("
                CASE WHEN l.due_date IS NULL THEN 1 ELSE 0 END,
                l.due_date ASC
            ")
            ->paginate($per_page);
    }


    public function findByUuid(Uuid $uuid): ?Lead
    {
        return Lead::uuid($uuid->value())->first();
    }

    public function activeLead(GenericId $customerId, LeadProductType $code, Builder $pivot, ?array $conditions = []): ?stdClass
    {
        $activeStatuses = [
            LeadStatus::NEW->value,
            LeadStatus::CONTACTED->value,
            LeadStatus::QUOTED->value,
        ];

        $query = DB::table('leads as l')
            ->leftJoinSub($pivot, 'lm', 'lm.lead_id', '=', 'l.id')
            ->leftJoin('users as u', 'u.id', '=', 'l.assigned_agent_id')
            ->select(
                'l.uuid',
                'l.insurance_product_code',
                'l.status',
                'l.assigned_agent_id',
                'l.due_date',
                'lm.*',
                'u.name as agent_name'
            )
            ->whereIn('l.status', $activeStatuses)
            ->where('l.insurance_product_code', $code->value)
            ->where('lm.customer_id', $customerId->value());

        foreach ($conditions as $key => $value) {
            $query->where($key, $value);
        }

        return $query->first();
    }
}
