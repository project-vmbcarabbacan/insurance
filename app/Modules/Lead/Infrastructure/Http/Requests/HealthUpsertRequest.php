<?php

namespace App\Modules\Lead\Infrastructure\Http\Requests;

use App\Modules\Lead\Application\DTOs\CreateLeadDto;
use App\Modules\Lead\Domain\Enums\LeadProductType;
use App\Modules\Lead\Domain\Maps\LeadActivityDueDateMap;
use App\Shared\Domain\Enums\CustomerSource;
use App\Shared\Domain\Enums\Emirates;
use App\Shared\Domain\Enums\GenderType;
use App\Shared\Domain\Enums\HealthExistingInsurance;
use App\Shared\Domain\Enums\HealthInsuranceFor;
use App\Shared\Domain\Enums\HealthInsureTo;
use App\Shared\Domain\Enums\LeadActivityType;
use App\Shared\Domain\Enums\LeadStatus;
use App\Shared\Domain\Enums\MedicalCondition;
use App\Shared\Domain\Enums\Relationship;
use App\Shared\Domain\Enums\Salary;
use App\Shared\Domain\Enums\YesNo;
use App\Shared\Domain\ValueObjects\GenericDate;
use App\Shared\Domain\ValueObjects\GenericId;
use App\Shared\Domain\ValueObjects\LowerText;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class HealthUpsertRequest extends FormRequest
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

            'insurance_for' => [
                'required',
                Rule::in(array_column(HealthInsuranceFor::cases(), 'value')),
            ],
            'emirates' => [
                'required',
                Rule::in(array_column(Emirates::cases(), 'value')),
            ],
            'nationality' => [
                'required',
                'string',
                'max: 10',
            ],

            'has_medical_condition' => [
                'required',
                Rule::in(array_column(YesNo::cases(), 'value')),
            ],
            'utm_source'  => ['required', 'string', 'max:150'],
            'utm_medium'  => ['required', 'string', 'max:100'],

            'members' => [
                'required',
                'array'
            ],

            'members.*.first_name' => [
                'required',
                'string',
                'max:100'
            ],

            'members.*.last_name' => [
                'required',
                'string',
                'max:100'
            ],

            'members.*.dob' => [
                'required',
                'date',
                'before:today',
                'after:' . now()->subYears(100)->toDateString(),
            ],

            'members.*.gender' => [
                'required',
                Rule::in(array_column(GenderType::cases(), 'value')),
            ],

            'members.*.relationship' => [
                'nullable',
                Rule::in(array_column(Relationship::cases(), 'value')),
            ],

            'insure_to'  => [
                'nullable',
                'string',
                'max:20',
                Rule::in(array_column(HealthInsureTo::cases(), 'value')),
            ],
            'salary'  => [
                'nullable',
                'string',
                'max:20',
                Rule::in(array_column(Salary::cases(), 'value')),
            ],
            'medical_conditions'  => [
                'nullable',
                'string',
                'max:20',
                Rule::in(array_column(MedicalCondition::cases(), 'value')),
            ],
            'gender'  => [
                'nullable',
                'string',
                'max:20',
                Rule::in(array_column(GenderType::cases(), 'value')),
            ],
            'existing_insurance' => [
                'nullable',
                'string',
                Rule::in(array_column(HealthExistingInsurance::cases(), 'value')),
            ],

            'utm_campaign'  => ['nullable', 'string', 'max:100'],
            'utm_term'  => ['nullable', 'string', 'max:100'],
            'utm_content'  => ['nullable', 'string', 'max:100'],

        ];
    }

    public function messages(): array
    {
        return [
            'members.required' => 'At least one member is required.',
            'members.array' => 'Members must be a valid list.',

            'members.*.first_name.required' => 'First name is required for each member.',
            'members.*.last_name.required' => 'Last name is required for each member.',

            'members.*.dob.required' => 'Date of birth is required for each member.',
            'members.*.dob.date' => 'Date of birth must be a valid date.',
            'members.*.dob.before' => 'Date of birth must be in the past.',

            'members.*.gender.required' => 'Gender is required for each member.',
            'members.*.gender.in' => 'Invalid gender selected for a member.',

            'members.*.relationship.in' => 'Invalid relationship selected for a member.',
        ];
    }

    public function leadDto(): CreateLeadDto
    {
        $dueAt = now()->add(LeadActivityDueDateMap::dueIn(LeadActivityType::LEAD_CREATED));

        return new CreateLeadDto(
            customer_id: GenericId::fromId($this->customer_id),
            code: LowerText::fromString(LeadProductType::HEALTH->value),
            source: CustomerSource::fromValue($this->utm_source),
            status: LeadStatus::NEW,
            due_date: GenericDate::fromString($dueAt),
            assigned_agent_id: getAgentId()
        );
    }

    public function arrayData()
    {
        $data = [
            'insurance_for' => $this->insurance_for,
            'emirates' => $this->emirates ?? '',
            'nationality' => $this->nationality ?? '',
            'existing_insurance' => $this->existing_insurance ?? '',
            'has_medical_condition' => $this->has_medical_condition ?? '',
            'insure_to' => $this->insure_to ?? '',
            'salary' => $this->salary ?? '',
            'medical_conditions' => $this->medical_conditions ?? '',
            'gender' => $this->medical_conditions ?? '',

            'utm_source' => $this->utm_source,
            'utm_medium' => $this->utm_medium,
            'utm_campaign' => $this->utm_campaign ?? '',
            'utm_term' => $this->utm_term ?? '',
            'utm_content' => $this->utm_content ?? '',
        ];

        $array = $this->flatMMembers($data);

        return $array;
    }

    public function flatMMembers($data)
    {
        foreach ($this->members as $index => $member) {
            $i = $index + 1;

            $data += [
                "health_member_first_name_{$i}" => $member['first_name'],
                "health_member_last_name_{$i}" => $member['last_name'],
                "health_member_dob_{$i}" => $member['dob'],
                "health_member_gender_{$i}" => $member['gender'],
                "health_member_relationship_{$i}" => $member['relationship'],
            ];
        }

        return $data;
    }

    public function members()
    {
        return array_map(function ($member, $index) {
            $i = $index + 1;

            return [
                "health_member_first_name_{$i}" => $member['first_name'],
                "health_member_last_name_{$i}" => $member['last_name'],
                "health_member_dob_{$i}" => $member['dob'],
                "health_member_gender_{$i}" => $member['gender'],
                "health_member_relationship_{$i}" => $member['relationship'],
            ];
        }, $this->members, array_keys($this->members));
    }

    public function customerId(): GenericId
    {
        return GenericId::fromId($this->customer_id);
    }
}
