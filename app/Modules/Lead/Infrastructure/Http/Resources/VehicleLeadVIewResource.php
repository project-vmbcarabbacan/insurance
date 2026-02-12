<?php

namespace App\Modules\Lead\Infrastructure\Http\Resources;

use App\Modules\Lead\Domain\Enums\LeadProductType;
use App\Shared\Domain\Enums\ClaimHistory;
use App\Shared\Domain\Enums\Currency;
use App\Shared\Domain\Enums\Emirates;
use App\Shared\Domain\Enums\LeadStatus;
use App\Shared\Domain\Enums\PolicyType;
use App\Shared\Domain\Enums\SpecificationType;
use App\Shared\Domain\Enums\YesNo;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class VehicleLeadVIewResource extends JsonResource
{

    public function __construct($resource)
    {
        parent::__construct($resource);
    }

    public function toArray(Request $request): array
    {
        // Convert stdClass or model to array
        $leadArray = is_array($this->resource) ? $this->resource : (array) $this->resource;

        $enumFields = [
            'insurance_product_code' => LeadProductType::class,
            'vehicle_specification' => SpecificationType::class,
            'registration_emirate' => Emirates::class,
            'last_claim_history' => ClaimHistory::class,
            'policy_type' => PolicyType::class,
            'policy_expired' => YesNo::class,
        ];

        $getEnumLabel = fn($enumClass, $value) => $value
            ? $enumClass::tryFrom($value)?->label() ?? $value
            : null;

        $enumValues = [];
        foreach ($enumFields as $field => $enumClass) {
            $enumValues[$field] = $getEnumLabel($enumClass, $leadArray[$field] ?? null);
        }

        $currency = Currency::AED->value;
        $leadStatus = LeadStatus::fromValue($leadArray['status']);

        return [
            'product' => Str::headline($leadArray['insurance_product_code']) ?? null,
            'lead_details' => trim(($enumValues['insurance_product_code'] ?? '') . ' - ' . ($leadArray['lead_details'] ?? '')),
            'due_date' => $leadArray['due_date'] ? format_fe_date_time($leadArray['due_date']) : 'No Due Date',
            'status' => $leadArray['status'] ?? null,
            'status_name' => $leadStatus->label() ?? null,
            'vin' => $leadArray['vin'] ?? null,
            'plate_number' => $leadArray['plate_number'] ?? null,
            'vehicle_value' => $currency . ' ' . number_format($this->vehicle_value, 2),
            'vehicle_specification' => $enumValues['vehicle_specification'] ?? null,
            'driver_full_name' => $leadArray['driver_full_name'] ?? null,
            'driver_dob' => $leadArray['driver_dob'] ? format_fe_date($leadArray['driver_dob']) : null,
            'driver_nationality' => $leadArray['driver_nationality_name'] ?? null,
            'driving_experience' => (int) $leadArray['driving_experience'] ?? 0,
            'driver_license_number' => $leadArray['driver_license_number'] ?? null,
            'registration_emirate' => $enumValues['registration_emirate'] ?? null,
            'last_claim_history' => $enumValues['last_claim_history'] ?? null,
            'policy_type' => $enumValues['policy_type'] ?? null,
            'policy_expired' => $enumValues['policy_expired'] ?? null,
            'agent_name' => Str::headline($leadArray['agent_name']) ?? null,
        ];
    }
}
