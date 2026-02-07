<?php

namespace App\Modules\Lead\Infrastructure\repositories;

use App\Shared\Domain\ValueObjects\GenericId;
use Illuminate\Support\Facades\DB;
use stdClass;

class HealthLeadMetaRepository extends LeadMetaRepository
{
    /**
     * Get lead by customer ID for Vehicle.
     *
     * @param GenericId $customerId
     * @return array
     */
    public function getLeadByCustomerId(GenericId $customerId): array
    {
        $pivot = self::pivotQuery();

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
    public function getLeadByLeadId(GenericId $leadId): stdClass | null
    {
        // Implement logic for fetching vehicle leads by lead ID
        return DB::table('vehicle_leads')
            ->where('id', $leadId->value())
            ->first();
    }

    private static function pivotQuery()
    {
        return DB::table('lead_metas')
            ->select(
                'lead_id',
                keyValue('customer_id'),
                keyValue('vehicle_make'),
                keyValue('vehicle_make_id'),
                keyValue('vehicle_model'),
                keyValue('vehicle_model_id'),
                keyValue('vehicle_trim'),
                keyValue('vehicle_trim_id'),
                keyValue('vehicle_year'),
                keyValue('identifier_type'),
                keyValue('plate_number'),
                keyValue('engine_number'),
                keyValue('vehicle_value'),
                keyValue('lead_description'),
            )
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
                'lm.customer_id',
                'lm.vehicle_make',
                'lm.vehicle_model',
                'lm.vehicle_year',
                'lm.identifier_type',
                'lm.plate_number',
                'lm.engine_number',
                'lm.vehicle_value',
                'lm.lead_description',
                'u.name as agent_name'
            );
    }
}
