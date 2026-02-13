<?php

namespace App\Shared\Infrastructure\Http\Requests;

use App\Shared\Application\DTOs\PolicyProviderFilterDto;
use App\Shared\Domain\Enums\GenericStatus;
use App\Shared\Domain\ValueObjects\LowerText;
use Illuminate\Foundation\Http\FormRequest;

class PolicyProviderFilterRequest extends FormRequest
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
                'keyword' => $this->query('keyword', ''),
                'status' => $this->query('status', ''),
                'per_page' => $this->query('per_page', 25)
            ]);
        } catch (\Throwable $e) {
            abort(404, 'Invalid filter identifier');
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
            'keyword' => [
                'nullable',
                'string',
                'max:100'
            ],
            'status' => [
                'nullable',
                'string',
                'max:20'
            ],
            'per_page' => [
                'nullable',
                'int'
            ],
        ];
    }

    public function toDto()
    {
        return new PolicyProviderFilterDto(
            keyword: !empty($this->keyword) ? LowerText::fromString($this->keyword) : null,
            status: !empty($this->status) ? GenericStatus::fromValue($this->status) : null,
            per_page: $this->per_page
        );
    }
}
