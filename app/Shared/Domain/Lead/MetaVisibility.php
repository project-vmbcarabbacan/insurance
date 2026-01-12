<?php

namespace App\Shared\Domain\Lead;

use App\Models\InsuranceProduct;

final class MetaVisibility
{
    public static function allowedKeys(InsuranceProduct $product): array
    {
        return match ($product) {
            InsuranceProduct::VEHICLE => [
                'vehicle_year',
                'vehicle_model',
                'vehicle_type',
            ],

            InsuranceProduct::HEALTH => [
                'age',
                'gender',
                'pre_existing_condition',
            ],

            InsuranceProduct::TRAVEL => [
                'destination',
                'travel_start_date',
                'travel_end_date',
            ],

            default => [],
        };
    }

    public static function hiddenKeys(): array
    {
        return [
            'customer_id',
            'chassis_number',
            'engine_number',
            'vin',
        ];
    }
}
