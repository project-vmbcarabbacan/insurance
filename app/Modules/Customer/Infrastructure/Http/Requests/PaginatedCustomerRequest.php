<?php

namespace App\Modules\Customer\Infrastructure\Http\Requests;

use App\Modules\Customer\Application\DTOs\PaginatedCustomerDto;
use App\Shared\Domain\Enums\CustomerStatus;
use App\Shared\Domain\Enums\CustomerType;
use Illuminate\Foundation\Http\FormRequest;

class PaginatedCustomerRequest extends FormRequest
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
            'per_page' => 'required|int',
        ];
    }

    public function toDTO(): PaginatedCustomerDto
    {
        return new PaginatedCustomerDto(
            per_page: $this->per_page,
            status: $this->status ? CustomerStatus::fromValue($this->status) : null,
            type: $this->type ? CustomerType::fromValue($this->type) : null,
            keyword: $this->keyword,
            dates: $this->dates
        );
    }
}
