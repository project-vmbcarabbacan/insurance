<?php

namespace App\Shared\Application\DTOs;

use App\Shared\Domain\Enums\GenericStatus;
use App\Shared\Domain\ValueObjects\LowerText;

class PolicyProviderFilterDto
{
    public function __construct(
        public readonly ?LowerText $keyword = '',
        public readonly ?GenericStatus $status = '',
        public readonly ?int $per_page = 25
    ) {}
}
