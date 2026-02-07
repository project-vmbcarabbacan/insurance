<?php

namespace App\Modules\Lead\Domain\Entities;

use App\Models\Lead;
use App\Shared\Domain\Enums\CustomerSource;
use App\Shared\Domain\Enums\LeadStatus;
use App\Shared\Domain\ValueObjects\GenericDate;
use App\Shared\Domain\ValueObjects\GenericId;
use App\Shared\Domain\ValueObjects\LowerText;

class LeadEntity
{
    public function __construct(
        public readonly LowerText $code,
        public readonly CustomerSource $source,
        public readonly LeadStatus $status,
        public readonly GenericId $customer_id,
        public readonly GenericDate $due_date,
        public readonly ?GenericId $assigned_agent_id = null
    ) {}

    public function toArray()
    {
        return [
            'uuid' => generate_unique_uuid(Lead::class),
            'insurance_product_code' => $this->code->value(),
            'source' => $this->source->value,
            'status' => $this->status->value,
            'customer_id' => $this->customer_id->value(),
            'due_date' => $this->due_date->toDateTimeString(),
            'assigned_agent_id' => $this->assigned_agent_id ? $this->assigned_agent_id->value() : null
        ];
    }
}
