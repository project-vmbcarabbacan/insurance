<?php

namespace App\Modules\Lead\Infrastructure\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LeadActivityResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'type' => $this->type->label(),
            'icon' => $this->type->icon(),
            'performed_by' => Str::headline($this->performed_by_name),
            'communication_preference' => $this->notes ? $this->notes['communication_preference'] : null,
            'lead_activity_response' => $this->notes ? $this->notes['lead_activity_response'] : null,
            'notes' => $this->notes ? $this->notes['notes'] : null,
            'created_at' => format_fe_date_time($this->created_at)
        ];
    }
}
