<?php

namespace App\Modules\Lead\Infrastructure\Http\Resources;

use App\Modules\Lead\Application\Services\LeadMetaService;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

class HealthLeadUpdateResource extends JsonResource
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


        // Convert to stdClass objects for leadHealthMetaToArrayColumns
        $members = $this->leadMetaService->leadHealthMetaToArrayColumns(
            collect($leadArray)
                ->map(fn($value, $key) => (object)['key' => $key, 'value' => $value])
                ->all()
        );

        return [
            'insurance_for' => $leadArray['insurance_for'] ?? null,
            'emirates' => $leadArray['emirates'] ?? null,
            'nationality' => $leadArray['nationality'] ?? null,
            'existing_insurance' => $leadArray['existing_insurance'] ?? null,
            'has_medical_condition' => $leadArray['has_medical_condition'] ?? null,
            'insure_to' => $leadArray['insure_to'] ?? null,
            'salary' => $leadArray['salary'] ?? null,
            'medical_conditions' => $leadArray['medical_conditions'] ?? null,
            'gender' => $leadArray['gender'] ?? null,
            'members' => $members,
        ];
    }
}
