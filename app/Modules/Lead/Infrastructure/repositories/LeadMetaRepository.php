<?php

namespace App\Modules\Lead\Infrastructure\repositories;

use App\Models\Lead;
use App\Models\LeadMeta;
use App\Modules\Lead\Domain\Contracts\LeadMetaRepositoryContract;
use App\Modules\Lead\Domain\Entities\LeadMetaEntity;
use App\Shared\Domain\Enums\AuditAction;
use App\Shared\Domain\ValueObjects\GenericId;
use App\Shared\Domain\ValueObjects\Uuid;
use Illuminate\Support\Facades\DB;
use stdClass;

abstract class LeadMetaRepository implements LeadMetaRepositoryContract
{
    /**
     * Get lead by customer ID.
     * @param GenericId $customerId
     * @return array
     */
    abstract public function getLeadByCustomerId(GenericId $customerId, array $map): array;

    /**
     * Get lead by lead ID.
     * @param GenericId $leadId
     * @return stdClass | null
     */
    abstract public function getLeadByLeadId(Uuid $leadUuid, array $map): stdClass | null;

    /**
     * Get all member keys
     * @param GenericId $leadId
     */
    abstract public function getMemberKeys(GenericId $leadId): array;

    /**
     * Persist a new lead meta record.
     *
     * This method is intentionally simple and side-effect focused:
     * - Assumes all validation and uniqueness checks are handled in the Use Case
     * - Converts the domain entity into a persistence-friendly array
     * - Triggers audit logging after successful creation
     *
     * @param LeadMetaEntity $leadMetaEntity Domain entity containing lead meta data
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
     * Create or update a lead meta record based on lead_id and key.
     *
     * This method is intentionally side-effect focused:
     * - No validation or business rules are applied here
     * - Uniqueness is enforced via updateOrCreate
     * - Auditing is handled after persistence
     *
     * @param GenericId       $leadMetaId     Identifier used for audit context
     * @param LeadMetaEntity  $leadMetaEntity Domain entity containing meta data
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

        foreach ($newMeta as $key => $value) {
            $existingValue = $existingMeta[$key] ?? null;

            if ($existingValue !== $value) {
                $oldValue[$key] = $existingValue;
                $newValue[$key] = $value;
            }

            DB::table('lead_metas')->updateOrInsert(
                [
                    'lead_id' => $leadId->value(),
                    'key' => $key
                ],
                [
                    'value' => $value
                ]
            );

            insurance_audit(
                $lead,
                AuditAction::LEAD_META_UPDATED,
                $oldValue,
                $newValue
            );
        }
    }
}
