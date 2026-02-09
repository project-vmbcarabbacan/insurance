<?php

namespace App\Modules\Lead\Infrastructure\repositories;

use App\Models\Lead;
use App\Models\LeadMeta;
use App\Modules\Lead\Domain\Contracts\LeadMetaRepositoryContract;
use App\Modules\Lead\Domain\Entities\LeadMetaEntity;
use App\Shared\Domain\Enums\AuditAction;
use App\Shared\Domain\ValueObjects\GenericId;
use App\Shared\Domain\ValueObjects\Uuid;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use stdClass;

abstract class LeadMetaRepository implements LeadMetaRepositoryContract
{
    /**
     * Retrieve all meta keys associated with a lead.
     *
     * @param GenericId $leadId Lead identifier
     *
     * @return array List of meta keys
     */
    abstract public function getMemberKeys(GenericId $leadId): array;

    /**
     * Retrieve a lead with its meta data by customer ID.
     *
     * This method builds a pivoted meta subquery based on the provided map,
     * joins it with the leads table, and returns all matching records.
     *
     * @param GenericId $customerId Customer identifier
     * @param array     $map        List of meta keys to pivot/select
     *
     * @return array Result set as an array
     */
    public function getLeadByCustomerId(GenericId $customerId, array $map): array
    {
        $pivot = $this->pivotQuery($map);

        $query = $this->mainQuery($pivot)
            ->where('customer_id', $customerId->value())
            ->get()
            ->toArray();

        return $query;
    }

    /**
     * Retrieve a single lead with its meta data by lead UUID.
     *
     * @param Uuid  $leadUuid Lead UUID
     * @param array $map      List of meta keys to pivot/select
     *
     * @return stdClass|null Lead record or null if not found
     */
    public function getLeadByLeadId(Uuid $leadUuid, array $map): stdClass | null
    {
        $pivot = $this->pivotQuery($map);

        return $this->mainQuery($pivot)
            ->where('uuid', $leadUuid->value())
            ->first();
    }

    /**
     * Build a pivot-style subquery for lead meta values.
     *
     * Converts key/value meta rows into selectable columns grouped by lead_id.
     *
     * @param array $fields Meta keys to include in the pivot
     *
     * @return Builder
     */
    public function pivotQuery(array $fields = []): Builder
    {
        $selects = ['lead_id'];

        foreach ($fields as $field) {
            $selects[] = keyValue($field);
        }

        return DB::table('lead_metas')
            ->select($selects)
            ->groupBy('lead_id');
    }

    /**
     * Persist multiple lead meta entries for a lead.
     *
     * This method:
     * - Filters out null values
     * - Inserts meta records in bulk
     * - Assumes validation and business rules are handled upstream
     *
     * @param GenericId $leadId Lead identifier
     * @param array     $data   Key-value meta data
     *
     * @return void
     */
    public function addLeadMeta(GenericId $leadId, array $data): void
    {
        $payload = array_non_null_values($data);

        $rows = [];
        foreach ($payload as $key => $value) {
            $rows[] = [
                'lead_id' => $leadId->value(),
                'key' => $key,
                'value' => $value,
            ];
        }

        DB::table('lead_metas')->insert($rows);
    }

    /**
     * Update existing lead meta values or create them if missing.
     *
     * Only changed values are persisted and audited.
     *
     * @param Lead  $lead Lead model instance (used for audit context)
     * @param array $data New meta values
     *
     * @return void
     */
    public function updateLeadMeta(Lead $lead, array $data): void
    {
        $leadId = GenericId::fromId($lead->id);

        $existingMeta = DB::table('lead_metas')
            ->where('lead_id', $leadId->value())
            ->pluck('value', 'key')
            ->toArray();

        $newMeta = array_non_null_values($data);

        $oldValue = [];
        $newValue = [];

        foreach ($newMeta as $key => $value) {
            $existingValue = $existingMeta[$key] ?? null;

            if ($existingValue !== $value) {
                $oldValue[$key] = $existingValue;
                $newValue[$key] = $value;

                DB::table('lead_metas')->updateOrInsert(
                    [
                        'lead_id' => $leadId->value(),
                        'key' => $key,
                    ],
                    [
                        'value' => $value,
                    ]
                );
            }
        }

        // Only audit if something actually changed
        if (!empty($oldValue)) {
            insurance_audit(
                $lead,
                AuditAction::LEAD_META_UPDATED,
                $oldValue,
                $newValue
            );
        }
    }

    /**
     * Delete specific meta keys for a lead.
     *
     * @param GenericId $leadId Lead identifier
     * @param array     $keys   Meta keys to delete
     *
     * @return void
     */
    public function deleteLeadMeta(GenericId $leadId, array $keys): void
    {
        DB::table('lead_metas')
            ->where('lead_id', $leadId->value())
            ->whereIn('key', $keys)
            ->delete();
    }

    /**
     * Build the base lead query with joined meta and agent data.
     *
     * @param Builder $pivot Pivoted meta subquery
     *
     * @return Builder
     */
    private function mainQuery(Builder $pivot): Builder
    {
        return DB::table('leads as l')
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
            );
    }
}
