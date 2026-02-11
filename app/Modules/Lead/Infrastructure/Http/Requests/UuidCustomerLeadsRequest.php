<?php

namespace App\Modules\Lead\Infrastructure\Http\Requests;

use App\Shared\Domain\ValueObjects\GenericId;
use App\Shared\Domain\ValueObjects\LowerText;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UuidCustomerLeadsRequest extends FormRequest
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
            'keyword' => [
                'nullable',
                'string'
            ],
            'per_page' => [
                'nullable',
                'integer',
                'min:1',
                'max:100',
            ]
        ];
    }

    public function customerId(): GenericId
    {
        return GenericId::fromId($this->customer_id);
    }

    public function keyword(): ?LowerText
    {
        return $this->keyword ? LowerText::fromString($this->keyword) : null;
    }

    public function per_page(): ?int
    {
        return $this->per_page ?? 5;
    }
}
