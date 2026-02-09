<?php

namespace App\Modules\Lead\Infrastructure\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

class VehicleLeadUpdateResource extends JsonResource
{
    public function toArray(Request $request): array
    {

        return [
            'vehicle_make_id' => $this->vehicle_make_id ?? null,
            'vehicle_year' => $this->vehicle_year ?? null,
            'vehicle_model_id' => $this->vehicle_model_id ?? null,
            'vehicle_trim_id' => $this->vehicle_trim_id ?? null,
            'vin' => $this->vin ?? null,
            'plate_number' => $this->plate_number ?? null,
            'engine_number' => $this->engine_number ?? null,
            'vehicle_value' => $this->vehicle_value ?? null,
            'vehicle_specification' => $this->vehicle_specification ?? null,
            'first_name' => $this->driver_first_name ?? null,
            'last_name' => $this->driver_last_name ?? null,
            'dob' => $this->driver_dob ?? null,
            'nationality' => $this->driver_nationality ?? null,
            'driving_experience' => $this->driving_experience ?? null,
            'driver_license_number' => $this->driver_license_number ?? null,
            'registration_emirate' => $this->registration_emirate ?? null,
            'last_claim_history' => $this->last_claim_history ?? null,
            'policy_type' => $this->policy_type ?? null,
            'policy_expired' => $this->policy_expired ?? null,
        ];
    }
}
