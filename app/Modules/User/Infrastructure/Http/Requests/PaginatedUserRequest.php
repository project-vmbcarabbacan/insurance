<?php

namespace App\Modules\User\Infrastructure\Http\Requests;

use App\Modules\User\Application\DTOs\PaginatedUserDto;
use App\Shared\Domain\Enums\GenericStatus;
use Illuminate\Foundation\Http\FormRequest;

class PaginatedUserRequest extends FormRequest
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
            'status' => 'required',
            'per_page' => 'required|int',
        ];
    }

    public function toDTO(): PaginatedUserDto
    {
        return new PaginatedUserDto(
            status: GenericStatus::fromValue($this->status),
            per_page: $this->per_page,
            keyword: $this->keyword,
            role_slug: $this->role_slug
        );
    }
}
