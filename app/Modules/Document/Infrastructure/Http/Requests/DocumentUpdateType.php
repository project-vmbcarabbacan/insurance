<?php

namespace App\Modules\Document\Infrastructure\Http\Requests;

use App\Shared\Domain\ValueObjects\Uuid;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DocumentUpdateType extends FormRequest
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
                'document_uuid' => $this->route('document')
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
            'document_uuid' => [
                'required',
                Rule::exists('documents', 'uuid')
            ],
            'lead_uuid' => [
                'required',
                Rule::exists('leads', 'uuid')
            ],
            'document_type_id' => [
                'required',
                Rule::exists('document_types', 'id')
            ]
        ];
    }

    public function lead_uuid(): Uuid
    {
        return Uuid::fromString($this->lead_uuid);
    }

    public function document_uuid(): Uuid
    {
        return Uuid::fromString($this->document_uuid);
    }
}
