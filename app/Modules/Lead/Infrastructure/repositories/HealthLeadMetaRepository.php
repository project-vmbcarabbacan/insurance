<?php

namespace App\Modules\Lead\Infrastructure\repositories;

use App\Shared\Domain\ValueObjects\GenericId;
use Illuminate\Support\Facades\DB;


/**
 * Repository implementation for Health lead meta data.
 *
 * Provides access to health-specific lead meta entries that are
 * stored using a common key prefix convention.
 */
class HealthLeadMetaRepository extends LeadMetaRepository
{

    /**
     * Retrieve all health member-related meta keys for a given lead.
     *
     * Health member meta entries are identified by the `health_member_` key prefix.
     * This method returns only the distinct keys associated with the lead.
     *
     * @param GenericId $leadId Lead identifier
     *
     * @return array List of health member meta keys
     */
    public function getMemberKeys(GenericId $leadId): array
    {
        return DB::table('lead_metas')
            ->select(DB::raw('`key`'))
            ->where(DB::raw('`key`'), 'LIKE', 'health_member_%')
            ->where('lead_id', $leadId->value())
            ->distinct()
            ->pluck('key')
            ->all();
    }
}
