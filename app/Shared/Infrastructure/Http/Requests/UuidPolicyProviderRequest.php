<?php

namespace App\Shared\Infrastructure\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UuidPolicyProviderRequest extends FormRequest
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
                'policy_provider_id' => decodedExact($this->route('provider'))
            ]);
        } catch (\Throwable $e) {
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
            'policy_provider_id' => ['required', Rule::exists('policy_providers', 'id')],
        ];
    }
}
