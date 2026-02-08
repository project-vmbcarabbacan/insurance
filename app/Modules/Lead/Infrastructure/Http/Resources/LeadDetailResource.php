<?php

namespace App\Modules\Lead\Infrastructure\Http\Resources;

use App\Modules\Lead\Domain\Enums\LeadProductType;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

class LeadDetailResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        $productLabel = null;
        try {
            $product = LeadProductType::fromValue($this->insurance_product_code);
            $productLabel = $product->label();
        } catch (\Throwable $e) {
            $productLabel = $this->insurance_product_code;
        }

        return [
            'uuid' => $this->uuid,
            'product' => $this->insurance_product_code,
            'lead_details' => trim($productLabel . ' - ' . ($this->lead_details ?? '')),
            'due_date' => format_fe_date_time($this->due_date) ?? 'No Due Date',
            'status' => $this->status
        ];
    }
}
