<?php

namespace App\Modules\Document\Infrastructure\Http\Requests;

use App\Shared\Domain\ValueObjects\Uuid;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UuidDocumentRequest extends FormRequest
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
                'uuid' => $this->route('document')
            ]);
        } catch (\Throwable $e) {
            abort(404, 'Invalid document uuid identifier');
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
            'uuid' => ['required', Rule::exists('documents', 'uuid')],
        ];
    }

    public function uuid(): Uuid
    {
        return Uuid::fromString($this->uuid);
    }
}
