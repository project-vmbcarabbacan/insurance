<?php

namespace App\Modules\Document\Infrastructure\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DocumentStoreRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'documents' => [
                'required',
                'array'
            ],
            'documents.*' => [
                'file',
                'max:5120',
                'mimes:pdf,doc,docx,jpg,jpeg,png'
            ],
            'lead_uuid' => [
                'nullable',
                Rule::exists('leads', 'uuid')
            ]
        ];
    }
}
