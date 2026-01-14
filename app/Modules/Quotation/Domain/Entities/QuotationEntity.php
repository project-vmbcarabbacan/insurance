<?php

namespace App\Modules\Quotation\Domain\Entities;

use App\Shared\Domain\Enums\Currency;
use App\Shared\Domain\Enums\QuotationStatus;
use App\Shared\Domain\ValueObjects\Amount;
use App\Shared\Domain\ValueObjects\GenericDate;
use App\Shared\Domain\ValueObjects\GenericId;
use App\Shared\Domain\ValueObjects\LowerText;

class QuotationEntity
{
    public function __construct(
        public readonly GenericId $lead_id,
        public readonly GenericId $customer_id,
        public readonly GenericId $provider_id,
        public readonly GenericId $plan_id,
        public readonly LowerText $insurance_product_code,
        public readonly LowerText $product,
        public readonly Amount $price,
        public readonly Amount $vat,
        public readonly QuotationStatus $status,
        public readonly GenericDate $valid_until
    ) {}

    public function toArray()
    {
        return [
            'quote_number' => generate_quote_number($this->product->value()),
            'lead_id' => $this->lead_id->value(),
            'customer_id' => $this->customer_id->value(),
            'provider_id' => $this->provider_id->value(),
            'plan_id' => $this->plan_id->value(),
            'insurance_product_code' => $this->insurance_product_code->value(),
            'price' => $this->price->amount(),
            'vat' => $this->vat->amount(),
            'currency' => $this->price->currency(),
            'status' => $this->status->value,
            'valid_until' => $this->valid_until->value()
        ];
    }
}
