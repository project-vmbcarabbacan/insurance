<?php

namespace App\Modules\User\Infrastructure\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeamResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'uuid' => encodeIdExact($this->id),
            'name' => $this->name,
            'email' => $this->email,
            'status' => ucfirst($this->status->value),
            'role_name' => $this->role->name
        ];
    }
}
