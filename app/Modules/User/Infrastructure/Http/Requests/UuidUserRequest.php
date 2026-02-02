<?php

namespace App\Modules\User\Infrastructure\Http\Requests;

use App\Shared\Domain\ValueObjects\GenericId;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UuidUserRequest extends FormRequest
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

        ];
    }

    public function agentId()
    {
        return GenericId::fromId($this->user_id);
    }
}
