<?php

namespace App\Modules\Customer\Infrastructure\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class CustomersResource extends JsonResource
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
            'name' => Str::of($this->first_name)->lower()->ucfirst()
                . ' ' .
                Str::of($this->last_name)->lower()->ucfirst(),
            'phone' => $this->phone_country_code . $this->phone_number,
            'email' => $this->email,
            'status' => Str::headline($this->status),
            'type' => Str::headline($this->type)
        ];
    }
}
