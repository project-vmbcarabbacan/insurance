<?php

namespace App\Modules\Customer\Infrastructure\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class CustomerDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        if (Str::lower($this->type) === 'corporate') {

            $displayName = Str::headline($this->company_name);
        } else {
            $firstName = $this->first_name ? Str::of($this->first_name)->lower()->ucfirst() : '';
            $lastName = $this->last_name ? Str::of($this->last_name)->lower()->ucfirst() : '';

            $displayName = trim($firstName . ' ' . $lastName);
        }

        return [
            'uuid' => encodeIdExact($this->id),
            'name' => $displayName,
            'initials' => get_initials($displayName),
            'contact_person' => $this->contact_person,
            'phone_country_code' => $this->phone_country_code,
            'phone_number' => $this->phone_number,
            'email' => $this->email,
            'status' => Str::headline($this->status),
            'type' => Str::headline($this->type)
        ];
    }
}
