<?php

namespace App\Modules\Customer\Infrastructure\Http\Requests;

use App\Shared\Domain\ValueObjects\GenericId;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UuidCustomerRequest extends FormRequest
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
        ];
    }

    public function customerId(): GenericId
    {
        return GenericId::fromId($this->customer_id);
    }
}
