<?php

namespace App\Modules\Lead\Domain\Entities;

use App\Shared\Domain\Enums\LeadActivityType;
use App\Shared\Domain\ValueObjects\GenericId;
use App\Shared\Domain\ValueObjects\LowerText;

class LeadActivityEntity
{
    public function __construct(
        protected readonly GenericId $lead_id,
        protected readonly LeadActivityType $type,
        protected readonly LowerText $performed_by_name,
        protected readonly ?string $notes = '',
        protected readonly ?GenericId $performed_by_id = null,
    ) {}

    public function toArray()
    {
        return [
            'lead_id' => $this->lead_id->value(),
            'type' => $this->type->value,
            'notes' => $this->notes,
            'performed_by_name' => $this->performed_by_name->value(),
            'performed_by_id' => $this->performed_by_id ? $this->performed_by_id->value() : null
        ];
    }
}
