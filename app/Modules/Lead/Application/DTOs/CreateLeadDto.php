<?php

namespace App\Modules\Lead\Application\DTOs;

use App\Shared\Domain\Enums\LeadSource;
use App\Shared\Domain\Enums\LeadStatus;
use App\Shared\Domain\ValueObjects\GenericId;
use App\Shared\Domain\ValueObjects\LowerText;

class CreateLeadDto
{
    public function __construct(
        public readonly LowerText $code,
        public readonly LeadSource $source,
        public readonly LeadStatus $status,
        public readonly GenericId $assigned_agent_id
    ) {}
}
