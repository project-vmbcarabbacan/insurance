<?php

namespace App\Shared\Infrastructure\Http\Requests;

use App\Shared\Domain\Enums\GenericStatus;
use App\Shared\Domain\ValueObjects\Uuid;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PolicyProviderStatusRequest extends FormRequest
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
        if (! $this->filled('uuid')) {
            return;
        }

        try {
            $this->merge([
                'policy_provider_id' => decodedExact($this->input('uuid')),
            ]);
        } catch (\Throwable) {
            abort(404, 'Invalid policy provider uuid identifier');
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
            'uuid' => [
                'required',
                'string',
            ],
            'policy_provider_id' => [
                'required',
                Rule::exists('policy_providers', 'id')
            ],
            'status' => [
                'required',
                Rule::in(array_column(GenericStatus::cases(), 'value')),
            ]
        ];
    }
}
