<?php

namespace App\Modules\User\Infrastructure\Http\Requests;

use App\Modules\User\Application\DTOs\UpdateUserDto;
use App\Shared\Domain\ValueObjects\Email;
use App\Shared\Domain\ValueObjects\GenericId;
use App\Shared\Domain\ValueObjects\LowerText;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTeamRequest extends FormRequest
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
                'user_id' => decrypt($this->route('team'))
            ]);
        } catch (\Throwable $e) {
            abort(404, 'Invalid team identifier');
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
            'user_id' => ['required', Rule::exists('users', 'id')],
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($this->user_id),
            ],
            'role_slug' => 'required|string|max:50',
        ];
    }

    public function toDTO(): UpdateUserDto
    {
        return new UpdateUserDto(
            user_id: GenericId::fromId($this->user_id),
            name: LowerText::fromString($this->name),
            email: Email::fromString($this->email),
            role: LowerText::fromString($this->role_slug)
        );
    }
}
