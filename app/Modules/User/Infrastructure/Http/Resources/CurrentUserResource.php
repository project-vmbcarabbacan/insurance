<?php

namespace App\Modules\User\Infrastructure\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CurrentUserResource extends JsonResource
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
            'initials' => get_initials($this->name),
            'email' => $this->email,
            'status' => $this->status,
            'role_slug' => $this->role->slug,
            'role_name' => $this->role->name,
            'permissions' => PermissionResource::collection($this->role->permissions)

        ];
    }
}
