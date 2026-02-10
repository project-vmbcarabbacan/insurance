<?php

namespace App\Modules\Lead\Infrastructure\Http\Requests;

use App\Shared\Domain\Enums\CommunicationPreference;
use App\Shared\Domain\Enums\LeadActivityResponse;
use App\Shared\Domain\ValueObjects\Uuid;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LeadActivityRequest extends FormRequest
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
            'uuid' => ['required', Rule::exists('leads', 'uuid')],
            'communication_preference' => [
                'required',
                Rule::in(array_column(CommunicationPreference::cases(), 'value'))
            ],
            'activity_response' => [
                'required',
                Rule::in(array_column(LeadActivityResponse::cases(), 'value'))
            ],
            'notes' => [
                'nullable',
                'string',
                'max:255'
            ]
        ];
    }

    public function leadActivityResponse()
    {
        return LeadActivityResponse::fromValue($this->activity_response);
    }

    public function uuid()
    {
        return Uuid::fromString($this->uuid);
    }

    public function notes()
    {
        $communication_preference = CommunicationPreference::fromValue($this->communication_preference);
        $lead_activity_response = $this->leadActivityResponse();
        return [
            'communication_preference' => $communication_preference->label(),
            'lead_activity_response' => $lead_activity_response->label(),
            'notes' => $this->notes
        ];
    }
}
