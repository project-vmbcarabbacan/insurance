<?php

namespace App\Modules\Lead\Infrastructure\Http\Requests;

use App\Modules\Lead\Application\DTOs\CreateLeadDto;
use App\Modules\Lead\Domain\Enums\LeadProductType;
use App\Shared\Domain\Enums\ClaimHistory;
use App\Shared\Domain\Enums\CustomerSource;
use App\Shared\Domain\Enums\Emirates;
use App\Shared\Domain\Enums\LeadStatus;
use App\Shared\Domain\Enums\PolicyType;
use App\Shared\Domain\Enums\SpecificationType;
use App\Shared\Domain\Enums\YesNo;
use App\Shared\Domain\ValueObjects\GenericId;
use App\Shared\Domain\ValueObjects\LowerText;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VehicleUpsertRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if (!$this->filled('customer_id')) {
            abort(404, 'Customer identifier is required');
        }

        try {
            $this->merge([
                'customer_id' => decrypt($this->customer_id)
            ]);
        } catch (\Throwable $e) {
            abort(404, 'Invalid customer identifier');
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'customer_id' => ['required', Rule::exists('customers', 'id')],
            'vehicle_make_id' => ['required', Rule::exists('vehicle_makes', 'reference_id')],
            'vehicle_year' => ['required', Rule::exists('vehicle_makes', 'year')],
            'vehicle_model_id' => ['required', Rule::exists('vehicle_models', 'reference_id')],
            'vehicle_trim_id' => ['required', Rule::exists('vehicle_trims', 'reference_id')],
            'vin' => ['required', 'string', 'max:20'],
            'plate_number' => ['required', 'string', 'max:10'],
            'vehicle_value' => ['required', 'string', 'max:15'],
            'vehicle_specification' => [
                'required',
                Rule::in(array_column(SpecificationType::cases(), 'value')),
            ],
            'first_name' => ['nullable', 'string', 'max:100'],
            'last_name'  => ['nullable', 'string', 'max:100'],
            'dob' => ['nullable', 'date'],
            'nationality' => ['required', 'string', 'max:100'],
            'driving_experience' => ['required'],
            'driver_license_number' => ['required', 'string', 'max:20'],
            'registration_emirate' => [
                'required',
                Rule::in(array_column(Emirates::cases(), 'value')),
            ],
            'last_claim_history' => [
                'required',
                Rule::in(array_column(ClaimHistory::cases(), 'value')),
            ],
            'policy_type' => [
                'required',
                Rule::in(array_column(PolicyType::cases(), 'value')),
            ],
            'policy_expired' => [
                'required',
                Rule::in(array_column(YesNo::cases(), 'value')),
            ],
            'utm_source'  => ['required', 'string', 'max:150'],
            'utm_medium'  => ['required', 'string', 'max:100'],
            'utm_campaign'  => ['nullable', 'string', 'max:100'],
            'utm_term'  => ['nullable', 'string', 'max:100'],
            'utm_content'  => ['nullable', 'string', 'max:100'],

        ];
    }

    public function leadDto(): CreateLeadDto
    {
        return new CreateLeadDto(
            code: LowerText::fromString(LeadProductType::VEHICLE->value),
            source: CustomerSource::fromValue($this->utm_source),
            status: LeadStatus::NEW,
            assigned_agent_id: getAgentId()
        );
    }

    public function arrayData()
    {
        return [
            'customer_id' => $this->customer_id,
            'vehicle_make_id' => $this->vehicle_make_id,
            'vehicle_year' => $this->vehicle_year,
            'vehicle_model_id' => $this->vehicle_model_id,
            'vehicle_trim_id' => $this->vehicle_trim_id,
            'vin' => $this->vin,
            'plate_number' => $this->plate_number,
            'vehicle_value' => $this->vehicle_value,
            'vehicle_specification' => $this->vehicle_specification,
            'driver_first_name' => $this->first_name,
            'driver_last_name' => $this->last_name,
            'driver_full_name' => trim("{$this->first_name} {$this->last_name}"),
            'driver_dob' => $this->dob,
            'driver_nationality' => $this->nationality,
            'driving_experience' => $this->driving_experience,
            'driver_license_number' => $this->driver_license_number,
            'registration_emirate' => $this->registration_emirate,
            'last_claim_history' => $this->last_claim_history,
            'policy_type' => $this->policy_type,
            'policy_expired' => $this->policy_expired,
            'utm_source' => $this->utm_source,
            'utm_medium' => $this->utm_medium,
            'utm_campaign' => $this->utm_campaign ?? '',
            'utm_term' => $this->utm_term ?? '',
            'utm_content' => $this->utm_content ?? '',
        ];
    }

    public function customerId(): GenericId
    {
        return GenericId::fromId($this->customer_id);
    }
}
