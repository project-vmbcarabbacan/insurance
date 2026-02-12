<?php

namespace App\Shared\Infrastructure\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuditResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'actioned_by'    => $this->user?->name ?? 'System',
            'action'         => $this->action,
            'auditable_type' => $this->auditable_type,
            'auditable_id'   => $this->auditable_id,

            'old_values'     => new AuditValueResource($this->old_values),
            'new_values'     => new AuditValueResource($this->new_values, true),

            'created_at'     => format_fe_date_time($this->created_at),
        ];
    }
}
