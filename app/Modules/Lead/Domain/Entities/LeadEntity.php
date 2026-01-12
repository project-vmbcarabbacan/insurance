<?php

namespace App\Modules\Lead\Domain\Entities;

use App\Models\Lead;
use App\Shared\Domain\Enums\LeadSource;
use App\Shared\Domain\Enums\LeadStatus;
use App\Shared\Domain\ValueObjects\GenericId;
use App\Shared\Domain\ValueObjects\LowerText;

class LeadEntity
{
    public function __construct(
        public readonly LowerText $code,
        public readonly LeadSource $source,
        public readonly LeadStatus $status,
        public readonly GenericId $assigned_agent_id
    ) {}

    public function toArray()
    {
        return [
            'uuid' => generate_unique_uuid(Lead::class),
            'code' => $this->code->value(),
            'source' => $this->source->value,
            'status' => $this->status->value,
            'assigned_agent_id' => $this->assigned_agent_id->value()
        ];
    }
}
