<?php

namespace App\Modules\User\Infrastructure\Http\Requests;

use App\Modules\User\Application\DTOs\CreateUserDto;
use App\Shared\Domain\ValueObjects\Email;
use App\Shared\Domain\ValueObjects\Password;
use App\Shared\Domain\ValueObjects\LowerText;
use Illuminate\Foundation\Http\FormRequest;

class CreateTeamRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => [
                'required',
                'string',
                'min:8',                     // Minimum 8 characters
                'regex:/[A-Z]/',             // At least one uppercase letter
                'regex:/[a-zA-Z]/',          // At least one letter (redundant with above but ensures alpha)
                'regex:/[0-9]/',             // At least one number
                'regex:/[\W_]/',             // At least one special character
            ],
            'role_slug' => 'required|string|max:50',
        ];
    }

    public function toDTO(): CreateUserDto
    {
        return new CreateUserDto(
            name: LowerText::fromString($this->name),
            email: Email::fromString($this->email),
            password: Password::fromPlain($this->password),
            role: LowerText::fromString($this->role_slug)
        );
    }
}
