<?php

namespace App\Modules\Customer\Infrastructure\Http\Requests;

use App\Modules\Customer\Application\DTOs\CustomerDto;
use App\Modules\Lead\Application\DTOs\CreateLeadDto;
use App\Modules\Master\Application\Services\InsuranceProductService;
use App\Shared\Domain\Enums\CustomerSource;
use App\Shared\Domain\Enums\CustomerType;
use App\Shared\Domain\Enums\GenderType;
use App\Shared\Domain\Enums\LeadStatus;
use App\Shared\Domain\ValueObjects\Email;
use App\Shared\Domain\ValueObjects\GenericDate;
use App\Shared\Domain\ValueObjects\LowerText;
use App\Shared\Domain\ValueObjects\Phone;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateCustomerRequest extends FormRequest
{

    public function __construct(
        protected InsuranceProductService $insurance_product_service
    ) {}
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $products = $this->insurance_product_service->getAllProduct()->toArray();

        return [
            'phone_number' => ['required', 'string', 'regex:/^[0-9]{7,15}$/'],
            'phone_country_code' => ['required', 'string', 'regex:/^\+[0-9]{1,4}$/'],
            'email' => [
                'required',
                'email:rfc,dns',
                Rule::unique('customers', 'email'),
            ],
            'type' => [
                'required',
                'string',
                Rule::in(array_column(CustomerType::cases(), 'value')),
            ],
            'customer_source' => [
                'required',
                'string',
                Rule::in(array_column(CustomerSource::cases(), 'value')),
            ],
            'first_name' => ['nullable', 'string', 'max:100'],
            'last_name'  => ['nullable', 'string', 'max:100'],
            'dob' => ['nullable', 'date'],
            'gender' => [
                'nullable',
                'string',
                Rule::in(array_column(GenderType::cases(), 'value')),
            ],
            'company_name'  => ['nullable', 'string', 'max:150'],
            'contact_person'  => ['nullable', 'string', 'max:100'],
            'registration_no'  => ['nullable', 'string', 'max:100'],
            // Added accessed_with field
            'accessed_with' => ['nullable', 'array'],
            'accessed_with.vehicle' => ['nullable', 'boolean', function ($attribute, $value, $fail) use ($products) {
                if (!$this->insurance_product_service->isValidCode('vehicle', $products)) {
                    $fail('The car code is invalid.');
                }
            }],
            'accessed_with.health' => ['nullable', 'boolean', function ($attribute, $value, $fail) use ($products) {
                if (!$this->insurance_product_service->isValidCode('health', $products)) {
                    $fail('The health code is invalid.');
                }
            }],
            'accessed_with.travel' => ['nullable', 'boolean', function ($attribute, $value, $fail) use ($products) {
                if (!$this->insurance_product_service->isValidCode('travel', $products)) {
                    $fail('The travel code is invalid.');
                }
            }],
            'accessed_with.pet' => ['nullable', 'boolean', function ($attribute, $value, $fail) use ($products) {
                if (!$this->insurance_product_service->isValidCode('pet', $products)) {
                    $fail('The pet code is invalid.');
                }
            }],
            'accessed_with.home' => ['nullable', 'boolean', function ($attribute, $value, $fail) use ($products) {
                if (!$this->insurance_product_service->isValidCode('home', $products)) {
                    $fail('The home code is invalid.');
                }
            }],
        ];
    }

    public function toDTO(): CustomerDto
    {
        return new CustomerDto(
            type: CustomerType::fromValue($this->type),
            customer_source: CustomerSource::fromValue($this->customer_source),
            phone: Phone::fromString($this->phone_number, $this->phone_country_code),
            email: Email::fromString($this->email),
            first_name: $this->first_name ? LowerText::fromString($this->first_name) : null,
            last_name: $this->last_name ? LowerText::fromString($this->last_name) : null,
            dob: $this->dob ? GenericDate::fromString($this->dob, true) : null,
            gender: $this->gender ? GenderType::fromValue($this->gender) : null,
            company_name: $this->company_name ? LowerText::fromString($this->company_name) : null,
            contact_person: $this->contact_person ? LowerText::fromString($this->contact_person) : null,
            registration_no: $this->registration_no ?? null
        );
    }

    public function leadDto(): array
    {
        return array_map(function ($code) {
            return new CreateLeadDto(
                code: LowerText::fromString($code),
                source: CustomerSource::fromValue($this->customer_source),
                status: LeadStatus::NEW,
                due_date: GenericDate::fromString(lead_new_due_date()),
                assigned_agent_id: getAgentId()
            );
        }, array_keys($this->accessed()));
    }

    private function accessed()
    {
        return array_filter($this->accessed, function ($value) {
            return $value === true; // only keep the keys with true values
        });
    }
}
