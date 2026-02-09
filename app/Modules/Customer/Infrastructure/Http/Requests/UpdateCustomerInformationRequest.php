<?php

namespace App\Modules\Customer\Infrastructure\Http\Requests;

use App\Modules\Customer\Application\DTOs\CustomerInformationDto;
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

class UpdateCustomerInformationRequest extends FormRequest
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
                'nullable',
                'string',
                'regex:/^[0-9]{7,15}$/',
                Rule::unique('customers')
                    ->ignore($this->customer_id)
                    ->where(function ($query) {
                        return $query->where('phone_country_code', $this->phone_country_code);
                    }),
            ],
            'phone_country_code' => ['nullable', 'string', 'regex:/^\+[0-9]{1,4}$/'],
            'email' => [
                'nullable',
                'email:rfc,dns',
                Rule::unique('customers', 'email')->ignore($this->customer_id),
            ],

        ];
    }

    public function toDTO(): CustomerInformationDto
    {
        return new CustomerInformationDto(
            customer_id: GenericId::fromId($this->customer_id),
            phone: Phone::fromString($this->phone_number, $this->phone_country_code),
            email: Email::fromString($this->email),

        );
    }
}
