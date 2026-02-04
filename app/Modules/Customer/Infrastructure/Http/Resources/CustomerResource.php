<?php

namespace App\Modules\Customer\Infrastructure\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class CustomerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'uuid' => encrypt($this->id),
            'first_name' => Str::of($this->first_name)->lower()->ucfirst(),
            'last_name' => Str::of($this->last_name)->lower()->ucfirst(),
            'phone_country_code' => $this->phone_country_code,
            'phone_number' => $this->phone_number,
            'email' => $this->email,
            'status' => $this->status,
            'gender' => $this->gender,
            'dob' => $this->dob,
            'type' => $this->type
        ];
    }
}
