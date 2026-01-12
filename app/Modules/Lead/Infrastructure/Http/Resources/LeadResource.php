<?php

namespace App\Modules\Lead\Infrastructure\Http\Resources;

use App\Models\InsuranceProduct;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LeadResource extends JsonResource
{
    // call only inside the controller
    public function toArray(Request $request): array
    {

        /**
         * ipc => Insurance Product Code
         */
        return [
            'uuid' => $this->uuid,
            'ipc' => $this->insurance_product_code,
            'meta' => $this->meta->mapWithKeys(function ($meta) {
                return [$meta->key => $this->castMetaValue($meta->value)];
            })->toArray(),
        ];
    }

    private function castMetaValue(string $value): mixed
    {
        return is_numeric($value) ? (int) $value : $value;
    }
}
