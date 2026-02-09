<?php

namespace App\Modules\Lead\Infrastructure\repositories;

use App\Shared\Domain\ValueObjects\GenericId;

/**
 * Repository implementation for Vehicle lead meta data.
 *
 * Vehicle leads do not have member-level meta entries.
 * This repository therefore returns an empty result for member key lookups.
 */
class VehicleLeadMetaRepository extends LeadMetaRepository
{
    /**
     * Retrieve member-related meta keys for a vehicle lead.
     *
     * Vehicle leads do not support member-based meta data,
     * so this method intentionally returns an empty array.
     *
     * @param GenericId $leadId Lead identifier
     *
     * @return array Always empty
     */
    public function getMemberKeys(GenericId $leadId): array
    {
        return [];
    }
}
