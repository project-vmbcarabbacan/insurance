<?php

namespace App\Modules\Lead\Application\DTOs;

use App\Shared\Domain\ValueObjects\GenericId;
use App\Shared\Domain\ValueObjects\LowerText;

class LeadMetaDto
{
    public function __construct(
        public readonly GenericId $lead_id,
        public readonly LowerText $key,
        public readonly ?string $value
    ) {}
}
