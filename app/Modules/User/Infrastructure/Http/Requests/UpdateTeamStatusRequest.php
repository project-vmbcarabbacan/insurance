<?php

namespace App\Modules\User\Infrastructure\Http\Requests;

use App\Modules\User\Application\DTOs\CreateUserDto;
use App\Modules\User\Application\DTOs\UserStatusDto;
use App\Shared\Domain\Enums\GenericStatus;
use App\Shared\Domain\ValueObjects\GenericId;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class UpdateTeamStatusRequest extends FormRequest
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
            'status' => ['required', 'string'],

            'uuid' => [
                'nullable',
                'string',
                function ($attribute, $value, $fail) {
                    try {
                        $id = decrypt($value); // 👈 your decrypt logic

                        if (!DB::table('users')->where('id', $id)->exists()) {
                            $fail('The selected user does not exist.');
                        }
                    } catch (\Throwable $e) {
                        $fail('The uuid is invalid.');
                    }
                },
            ],

            'uuids' => [
                'nullable',
                'array',
                function ($attribute, $value, $fail) {
                    foreach ($value as $uuid) {
                        try {
                            $id = decrypt($uuid);

                            if (!DB::table('users')->where('id', $id)->exists()) {
                                $fail('One or more users do not exist.');
                                return;
                            }
                        } catch (\Throwable $e) {
                            $fail('One or more uuids are invalid.');
                            return;
                        }
                    }
                },
            ],
        ];
    }

    /**
     * Return validated status and decrypted user id(s)
     */
    public function toDto(): array
    {
        $statusEnum = GenericStatus::fromValue($this->input('status'));

        if ($this->filled('uuid')) {

            return [
                new UserStatusDto(
                    user_id: GenericId::fromId(decrypt($this->input('uuid'))),
                    status: $statusEnum
                )
            ];
        }

        if ($this->filled('uuids')) {
            return collect($this->input('uuids'))
                ->map(fn($uuid) => new UserStatusDto(
                    user_id: GenericId::fromId(decrypt($uuid)),
                    status: $statusEnum
                ))
                ->values()
                ->all();
        }

        return [];
    }
}
