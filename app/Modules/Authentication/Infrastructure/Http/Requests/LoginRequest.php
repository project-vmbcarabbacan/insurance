<?php

namespace App\Modules\Authentication\Infrastructure\Http\Requests;

use App\Modules\Authentication\Application\DTOs\LoginDto;
use App\Shared\Domain\ValueObjects\Email;
use App\Shared\Domain\ValueObjects\Password;
use App\Shared\Domain\ValueObjects\IpAddress;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:6',
        ];
    }

    public function toDTO(): LoginDto
    {
        return new LoginDto(
            email: Email::fromString($this->email),
            password: Password::fromPlain($this->password),
            ip_address: IpAddress::fromString($this->ip())
        );
    }
}
