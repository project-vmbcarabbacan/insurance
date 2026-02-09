<?php

namespace App\Modules\User\Infrastructure\Http\Requests;

use App\Modules\User\Application\DTOs\UpdatePasswordDto;
use App\Shared\Domain\ValueObjects\GenericId;
use App\Shared\Domain\ValueObjects\Password;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTeamPasswordRequest extends FormRequest
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
                'user_id' => decodedExact($this->route('team'))
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
            'password' => [
                'required',
                'string',
                'min:8',                     // Minimum 8 characters
                'regex:/[A-Z]/',             // At least one uppercase letter
                'regex:/[a-zA-Z]/',          // At least one letter (redundant with above but ensures alpha)
                'regex:/[0-9]/',             // At least one number
                'regex:/[\W_]/',             // At least one special character
            ],
        ];
    }

    public function toDTO(): UpdatePasswordDto
    {
        return new UpdatePasswordDto(
            user_id: GenericId::fromId($this->user_id),
            password: Password::fromPlain($this->password),
            confirm_password: Password::fromPlain($this->password),
        );
    }
}
