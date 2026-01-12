<?php

namespace App\Modules\Authentication\Infrastructure\Http\Requests;

use App\Modules\Authentication\Application\DTOs\RegisterDto;
use App\Shared\Domain\ValueObjects\Email;
use App\Shared\Domain\ValueObjects\Password;
use App\Shared\Domain\ValueObjects\GenericId;
use App\Shared\Domain\ValueObjects\IpAddress;
use App\Shared\Domain\ValueObjects\LowerText;
use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
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
            'password' => 'required|string|min:6',
            'confirm_password' => 'required|string|same:password',
            'role_id' => 'required|integer|exists:roles,id'
        ];
    }

    public function toDTO(): RegisterDto
    {
        return new RegisterDto(
            name: LowerText::fromString($this->name),
            email: Email::fromString($this->email),
            password: Password::fromPlain($this->password),
            confirm_password: Password::fromPlain($this->confirm_password),
            role_id: GenericId::fromId($this->role_id),
            status: LowerText::fromString($this->status),
            ip_address: IpAddress::fromString($this->ip())
        );
    }
}
