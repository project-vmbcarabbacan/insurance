<?php

namespace App\Modules\Master\Infrastructure\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VehicleTrimRequest extends FormRequest
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
                'year' => (int) $this->route('year'),
                'make_id' => (int) $this->route('make_id'),
                'model_id' => (int) $this->route('model_id'),
            ]);
        } catch (\Throwable $e) {
            abort(404, 'Invalid trim identifier');
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
            'year' => ['required', Rule::exists('vehicle_makes', 'year')],
            'make_id' => ['required', Rule::exists('vehicle_makes', 'reference_id')],
            'model_id' => ['required', Rule::exists('vehicle_models', 'reference_id')],
        ];
    }
}
