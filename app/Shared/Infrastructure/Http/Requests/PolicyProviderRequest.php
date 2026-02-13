<?php

namespace App\Shared\Infrastructure\Http\Requests;

use App\Shared\Application\DTOs\PolicyProviderDto;
use App\Shared\Domain\ValueObjects\Email;
use App\Shared\Domain\ValueObjects\GenericId;
use App\Shared\Domain\ValueObjects\LowerText;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PolicyProviderRequest extends FormRequest
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
        if ($this->route()->hasParameter('provider')) {
            $decoded = decodedExact($this->route('provider'));

            $this->merge([
                'provider_id' => $decoded,
            ]);
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
            'provider_id' => [
                $this->isMethod('PUT') || $this->isMethod('PATCH') ? 'required' : 'nullable',
                'int',
                Rule::exists('policy_providers', 'id'),
            ],
            'code' => [
                'required',
                'string',
                'max:20',
                Rule::unique('policy_providers', 'code')
                    ->ignore($this->provider_id),
            ],
            'name' => [
                'required',
                'string',
                'max:150',
            ],
            'email' => [
                'nullable',
                'email',
            ],
            'phone' => [
                'nullable',
                'string',
                'max:20',
            ],
        ];
    }

    public function toDto(): PolicyProviderDto
    {
        return new PolicyProviderDto(
            code: $this->code,
            name: LowerText::fromString($this->name),
            email: $this->email ? Email::fromString($this->email) : null,
            phone: $this->phone,
            policy_provider_id: $this->provider_id
                ? GenericId::fromId($this->provider_id)
                : null
        );
    }
}
