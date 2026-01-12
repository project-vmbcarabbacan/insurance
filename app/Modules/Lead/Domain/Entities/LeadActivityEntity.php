<?php

namespace App\Modules\Lead\Domain\Entities;

use App\Shared\Domain\Enums\LeadActivityType;
use App\Shared\Domain\ValueObjects\GenericId;

class LeadActivityEntity
{
    public function __construct(
        protected readonly GenericId $lead_id,
        protected readonly LeadActivityType $type,
        protected readonly string $notes
    ) {}

    public function toArray()
    {
        return [
            'lead_id' => $this->lead_id->value(),
            'type' => $this->type->value,
            'notes' => $this->notes
        ];
    }
}
