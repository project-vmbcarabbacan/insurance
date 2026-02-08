<?php

namespace App\Modules\Lead\Infrastructure\repositories;

use App\Modules\Lead\Domain\Maps\LeadKeyMap;
use App\Shared\Domain\ValueObjects\GenericId;
use App\Shared\Domain\ValueObjects\Uuid;
use Illuminate\Support\Facades\DB;
use stdClass;

class VehicleLeadMetaRepository extends LeadMetaRepository
{
    /**
     * Get lead by customer ID for Vehicle.
     *
     * @param GenericId $customerId
     * @return array
     */
    public function getLeadByCustomerId(GenericId $customerId, array $map): array
    {
        $pivot = self::pivotQuery($map);

        $query = self::mainQuery($pivot)
            ->where('customer_id', $customerId->value())
            ->get()
            ->toArray();

        return $query;
    }

    /**
     * Get lead by lead ID for Vehicle.
     *
     * @param GenericId $leadId
     * @return array
     */
    public function getLeadByLeadId(Uuid $leadUuid, array $map): stdClass | null
    {
        // Implement logic for fetching vehicle leads by lead ID
        $pivot = self::pivotQuery($map);

        return self::mainQuery($pivot)
            ->where('uuid', $leadUuid->value())
            ->first();
    }

    public function getMemberKeys(GenericId $leadId): array
    {
        // what needs to put if no action to be taken
        return [];
    }

    private static function pivotQuery(array $fields = [])
    {
        $selects = ['lead_id'];

        foreach ($fields as $field) {
            $selects[] = keyValue($field);
        }

        return DB::table('lead_metas')
            ->select($selects)
            ->groupBy('lead_id');
    }

    private static function mainQuery($pivot)
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
