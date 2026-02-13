<?php

namespace App\Shared\Domain\Entities;

use App\Shared\Domain\Enums\GenericStatus;
use App\Shared\Domain\ValueObjects\LowerText;

class PolicyProviderFilterEntity
{
    public function __construct(
        public readonly ?LowerText $keyword = null,
        public readonly ?GenericStatus $status = null,
        public readonly ?int $per_page = 25
    ) {}
}
