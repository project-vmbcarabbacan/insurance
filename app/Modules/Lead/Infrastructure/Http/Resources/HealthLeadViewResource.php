<?php

namespace App\Modules\Lead\Infrastructure\Http\Resources;

use App\Modules\Lead\Application\Services\LeadMetaService;
use App\Modules\Lead\Domain\Enums\LeadProductType;
use App\Shared\Domain\Enums\Emirates;
use App\Shared\Domain\Enums\GenderType;
use App\Shared\Domain\Enums\HealthExistingInsurance;
use App\Shared\Domain\Enums\HealthInsuranceFor;
use App\Shared\Domain\Enums\HealthInsureTo;
use App\Shared\Domain\Enums\MedicalCondition;
use App\Shared\Domain\Enums\Salary;
use App\Shared\Domain\Enums\YesNo;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

class HealthLeadViewResource extends JsonResource
{
    protected LeadMetaService $leadMetaService;

    public function __construct($resource, LeadMetaService $leadMetaService)
    {
        parent::__construct($resource);
        $this->leadMetaService = $leadMetaService;
    }

    public function toArray(Request $request): array
    {
        // Convert stdClass or model to array
        $leadArray = is_array($this->resource) ? $this->resource : (array) $this->resource;

        $enumFields = [
            'insurance_product_code' => LeadProductType::class,
            'insurance_for' => HealthInsuranceFor::class,
            'emirates' => Emirates::class,
            'existing_insurance' => HealthExistingInsurance::class,
            'has_medical_condition' => YesNo::class,
            'insure_to' => HealthInsureTo::class,
            'salary' => Salary::class,
            'medical_conditions' => MedicalCondition::class,
            'gender' => GenderType::class,
        ];

        $getEnumLabel = fn($enumClass, $value) => $value
            ? $enumClass::tryFrom($value)?->label() ?? $value
            : null;

        $enumValues = [];
        foreach ($enumFields as $field => $enumClass) {
            $enumValues[$field] = $getEnumLabel($enumClass, $leadArray[$field] ?? null);
        }

        // Convert to stdClass objects for leadHealthMetaToArrayColumns
        $members = $this->leadMetaService->leadHealthMetaToArrayColumns(
            collect($leadArray)
                ->map(fn($value, $key) => (object)['key' => $key, 'value' => $value])
                ->all()
        );

        return [
            'product' => $leadArray['insurance_product_code'] ?? null,
            'lead_details' => trim(($enumValues['insurance_product_code'] ?? '') . ' - ' . ($leadArray['lead_details'] ?? '')),
            'due_date' => format_fe_date_time($leadArray['due_date'] ?? null) ?? 'No Due Date',
            'status' => $leadArray['status'] ?? null,
            'insurance_for' => $enumValues['insurance_for'] ?? null,
            'emirates' => $enumValues['emirates'] ?? null,
            'nationality' => $leadArray['nationality_name'] ?? null,
            'existing_insurance' => $enumValues['existing_insurance'] ?? null,
            'has_medical_condition' => $enumValues['has_medical_condition'] ?? null,
            'insure_to' => $enumValues['insure_to'] ?? null,
            'salary' => $enumValues['salary'] ?? null,
            'medical_conditions' => $enumValues['medical_conditions'] ?? null,
            'gender' => $enumValues['gender'] ?? null,
            'members' => $members,
        ];
    }
}
