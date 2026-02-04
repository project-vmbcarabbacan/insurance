<?php

namespace App\Modules\Customer\Infrastructure\Http\Requests;

use App\Modules\Customer\Application\DTOs\CustomerDto;
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
                'customer_id' => decrypt($this->route('customer'))
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
            'first_name' => ['required', 'string', 'max:100'],
            'last_name'  => ['required', 'string', 'max:100'],
            'phone_number' => ['required', 'string', 'regex:/^[0-9]{7,15}$/'],
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
            'dob' => ['nullable', 'date'],
            'gender' => [
                'nullable',
                'string',
                Rule::in(array_column(GenderType::cases(), 'value')),
            ],

        ];
    }

    public function toDTO(): CustomerDto
    {
        return new CustomerDto(
            first_name: LowerText::fromString($this->first_name),
            last_name: LowerText::fromString($this->last_name),
            phone: Phone::fromString($this->phone_number, $this->phone_country_code),
            email: Email::fromString($this->email),
            type: CustomerType::fromValue($this->type),
            dob: GenericDate::fromString($this->dob),
            gender: GenderType::fromValue($this->gender)
        );
    }

    public function customerId(): GenericId
    {
        return GenericId::fromId($this->customer_id);
    }
}
