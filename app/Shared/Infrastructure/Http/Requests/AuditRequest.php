<?php

namespace App\Shared\Infrastructure\Http\Requests;

use App\Shared\Domain\Enums\MorphType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AuditRequest extends FormRequest
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
                'morph' => $this->route('morph'),
                'uuid' => $this->route('uuid')
            ]);
        } catch (\Throwable $e) {
            abort(404, 'Invalid audit identifier');
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
            'morph' => [
                'required',
                Rule::in(array_column(MorphType::cases(), 'value'))
            ],
            'uuid' => [
                'required',
                'string'
            ],
        ];
    }
}
