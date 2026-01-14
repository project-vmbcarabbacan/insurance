<?php

namespace App\Modules\Lead\Infrastructure\repositories;

use App\Models\LeadMeta;
use App\Modules\Lead\Domain\Contracts\LeadMetaRepositoryContract;
use App\Modules\Lead\Domain\Entities\LeadMetaEntity;
use App\Shared\Domain\Enums\AuditAction;
use App\Shared\Domain\ValueObjects\GenericId;

class LeadMetaRepository implements LeadMetaRepositoryContract
{
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
    public function addLeadMeta(LeadMetaEntity $leadMetaEntity): void
    {
        LeadMeta::create($leadMetaEntity->toArray());
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
    public function updateLeadMeta(LeadMetaEntity $leadMetaEntity): void
    {
        // Fetch existing record (if any) to access previous audits
        $existingLeadMeta = LeadMeta::where(
            $leadMetaEntity->uniqueCheck()
        )->first();

        // Persist changes
        $leadMeta = LeadMeta::updateOrCreate(
            $leadMetaEntity->uniqueCheck(),
            $leadMetaEntity->updateValue()
        );

        /**
         * Audit trail
         */
        if ($leadMeta->wasRecentlyCreated) {
            insurance_audit(
                $leadMeta,
                AuditAction::LEAD_META_CREATED,
                null,
                null
            );
            return;
        }

        /**
         * Updated audit with access to the previous audit record
         */
        insurance_audit(
            $leadMeta,
            AuditAction::LEAD_META_UPDATED,
            ['value' => $existingLeadMeta?->value],
            $leadMeta->getChanges()

        );
    }
}
