<?php

namespace App\Shared\Infrastructure\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class PolicyProviderResource extends JsonResource
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
            'code' => $this->code,
            'name' => Str::headline($this->name),
            'email' => $this->contact_email,
            'phone' => $this->contact_phone,
            'status' => Str::headline($this->status->value),
        ];
    }
}
