<?php

namespace App\Modules\Lead\Domain\Entities;

use App\Shared\Domain\ValueObjects\GenericId;
use App\Shared\Domain\ValueObjects\LowerText;

class LeadMetaEntity
{
    public function __construct(
        public readonly GenericId $lead_id,
        public readonly LowerText $key,
        public readonly ?string $value
    ) {}

    public function toArray()
    {
        return [
            'lead_id' => $this->lead_id->value(),
            'key' => $this->key->value(),
            'value' => $this->value
        ];
    }

    public function uniqueCheck()
    {
        return [
            'lead_id' => $this->lead_id->value(),
            'key' => $this->key->value()
        ];
    }

    public function updateValue()
    {
        return [
            'value' => $this->value
        ];
    }
}
