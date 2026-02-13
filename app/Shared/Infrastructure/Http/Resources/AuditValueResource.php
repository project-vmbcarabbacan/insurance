<?php

namespace App\Shared\Infrastructure\Http\Resources;

use App\Modules\Lead\Application\Services\LeadMetaService;
use App\Modules\Lead\Domain\Enums\LeadProductType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Shared\Application\Services\MasterService;
use App\Shared\Domain\Enums\ClaimHistory;
use App\Shared\Domain\Enums\Currency;
use App\Shared\Domain\Enums\CustomerSource;
use App\Shared\Domain\Enums\Emirates as EnumsEmirates;
use App\Shared\Domain\Enums\GenderType;
use App\Shared\Domain\Enums\HealthExistingInsurance as EnumsHealthExistingInsurance;
use App\Shared\Domain\Enums\HealthInsuranceFor as EnumsHealthInsuranceFor;
use App\Shared\Domain\Enums\HealthInsureTo;
use App\Shared\Domain\Enums\LeadStatus;
use App\Shared\Domain\Enums\MedicalCondition;
use App\Shared\Domain\Enums\PolicyType;
use App\Shared\Domain\Enums\Salary;
use App\Shared\Domain\Enums\SpecificationType;
use App\Shared\Domain\Enums\YesNo;
use Illuminate\Support\Facades\DB;

class AuditValueResource extends JsonResource
{
    protected bool $isNew;

    public function __construct($resource, bool $isNew = false)
    {
        parent::__construct($resource);
        $this->isNew = $isNew;
    }

    public function toArray(Request $request): array
    {
        if (empty($this->resource)) {
            return [];
        }

        $values = collect($this->resource);

        /*
        |--------------------------------------------------------------------------
        | 1. Extract health_member_* fields
        |--------------------------------------------------------------------------
        */

        $healthFields = $values->filter(
            fn($value, $key) => str_starts_with($key, 'health_member_')
        );

        $healthMembersOutput = [];

        if ($healthFields->isNotEmpty()) {

            $leadMetaService = app(LeadMetaService::class);

            $members = $leadMetaService->leadHealthMetaToArrayColumns(
                $healthFields
                    ->map(fn($value, $key) => (object)[
                        'key'   => $key,
                        'value' => $value,
                    ])
                    ->values()
                    ->all()
            );

            $healthMembersOutput[] = [
                'field' => 'members',
                'value' => $members,
            ];

            // Remove original member_* keys
            $values = $values->reject(
                fn($value, $key) => str_starts_with($key, 'health_member_')
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 2. Transform remaining fields
        |--------------------------------------------------------------------------
        */

        // Remove vehicle_* keys
        $values = $values->reject(
            fn($value, $key) => str_starts_with($key, 'vehicle_make_id') ||
                str_starts_with($key, 'vehicle_model_id') ||
                str_starts_with($key, 'vehicle_trim_id') ||
                str_starts_with($key, 'driver_first_name') ||
                str_starts_with($key, 'driver_last_name') ||
                str_starts_with($key, 'driver_nationality')
        );

        $mapped = $values->map(function ($value, $key) {

            $value = $this->transformValue($key, $value);

            return [
                'field' => $key,
                'value' => $value,
            ];
        })->values()->toArray();



        return array_merge($mapped, $healthMembersOutput);
    }

    /*
    |--------------------------------------------------------------------------
    | Transform Specific Fields
    |--------------------------------------------------------------------------
    */
    protected function transformValue(string $key, $value)
    {
        if (is_null($value)) {
            return null;
        }

        return match ($key) {

            'insurance_product_code' => LeadProductType::tryFrom($value)?->label() ?? $value,
            'has_medical_condition' => YesNo::tryFrom($value)?->label() ?? $value,
            'insure_to' => HealthInsureTo::tryFrom($value)?->label() ?? $value,
            'salary' => Salary::tryFrom($value)?->label() ?? $value,
            'medical_conditions' => MedicalCondition::tryFrom($value)?->label() ?? $value,
            'gender' => GenderType::tryFrom($value)?->label() ?? $value,
            'existing_insurance' => EnumsHealthExistingInsurance::tryFrom($value)?->label() ?? $value,
            'emirates' => EnumsEmirates::tryFrom($value)?->label() ?? $value,
            'insurance_for' => EnumsHealthInsuranceFor::tryFrom($value)?->label() ?? $value,
            'vehicle_specification' => SpecificationType::tryFrom($value)?->label() ?? $value,
            'last_claim_history' => ClaimHistory::tryFrom($value)?->label() ?? $value,
            'policy_type' => PolicyType::tryFrom($value)?->label() ?? $value,
            'policy_expired' => YesNo::tryFrom($value)?->label() ?? $value,
            'status' => LeadStatus::tryFrom($value)?->label() ?? $value,
            'utm_source' => CustomerSource::tryFrom($value)?->label() ?? $value,

            'nationality' => $this->mapNationality($value),
            'document_type' => $this->mapDocumentType($value),
            'uploaded_by' => $this->mapUser($value),
            'vehicle_value' => $this->mapNumberFormat($value),
            'size' => $this->mapNumberFormat($value, false),
            'driver_dob' => $this->mapDateFormat($value, false),
            'driving_experience' => $this->mapYears($value),

            'file_path' => asset('storage/' . $value),

            default => $value,
        };
    }

    /*
    |--------------------------------------------------------------------------
    | Nationality Mapping
    |--------------------------------------------------------------------------
    */
    protected function mapNationality(string $value): ?string
    {
        $masterService = app(MasterService::class);

        $nationality = $masterService->findCountryByValue($value);

        return $nationality['label'] ?? $value;
    }

    /*
    |--------------------------------------------------------------------------
    | Document Type Mapping
    |--------------------------------------------------------------------------
    */
    protected function mapDocumentType($id): string
    {
        if (!$id) {
            return 'Not Assigned';
        }

        $documentType = DB::table('document_types')->where('id', $id)->first();

        if (!$documentType) return 'Not Assigned';

        return $documentType?->name ?? 'Not Assigned';
    }

    /*
    |--------------------------------------------------------------------------
    | Document Type Mapping
    |--------------------------------------------------------------------------
    */
    protected function mapUser($id): string
    {
        if (!$id) {
            return 'Not Assigned';
        }

        $documentType = DB::table('users')->where('id', $id)->first();

        return $documentType?->name ?? 'System';
    }

    /*
    |--------------------------------------------------------------------------
    | Number Format Mapping
    |--------------------------------------------------------------------------
    */
    protected function mapNumberFormat($value, $isCurrency = true)
    {
        $currency = Currency::AED->value;
        $format = number_format($value, 2);
        if ($isCurrency) return "$currency $format";
        else return number_format($value);
    }

    /*
    |--------------------------------------------------------------------------
    | Number Format Mapping
    |--------------------------------------------------------------------------
    */
    protected function mapDateFormat($value, $isTimeDate = true)
    {
        if ($isTimeDate) return format_fe_date_time($value);
        else return format_fe_date($value);
    }

    /*
    |--------------------------------------------------------------------------
    | Number Format Mapping
    |--------------------------------------------------------------------------
    */
    protected function mapYears($value)
    {
        $type = "year";
        if ($value > 1) $type = "years";

        return "$value $type";
    }
}
