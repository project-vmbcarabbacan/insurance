<?php

namespace App\Modules\Customer\Infrastructure\Http\Requests;

use App\Modules\Customer\Application\DTOs\CustomerDto;
use App\Shared\Domain\Enums\CustomerSource;
use App\Shared\Domain\Enums\CustomerType;
use App\Shared\Domain\Enums\GenderType;
use App\Shared\Domain\ValueObjects\Email;
use App\Shared\Domain\ValueObjects\GenericDate;
use App\Shared\Domain\ValueObjects\GenericId;
use App\Shared\Domain\ValueObjects\LowerText;
use App\Shared\Domain\ValueObjects\Phone;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCustomerRequest extends FormRequest
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
        try {
            $this->merge([
                'customer_id' => decodedExact($this->route('customer'))
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
            'phone_number' => [
                'required',
                'string',
                'regex:/^[0-9]{7,15}$/',
                Rule::unique('customers')
                    ->ignore($this->customer_id)
                    ->where(function ($query) {
                        return $query->where('phone_country_code', $this->phone_country_code);
                    }),
            ],
            'phone_country_code' => ['required', 'string', 'regex:/^\+[0-9]{1,4}$/'],
            'email' => [
                'required',
                'email:rfc,dns',
                Rule::unique('customers', 'email')->ignore($this->customer_id),
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

    public function customerId(): GenericId
    {
        return GenericId::fromId($this->customer_id);
    }
}
