<?php

namespace App\Shared\Application\DTOs;

use App\Shared\Domain\ValueObjects\Email;
use App\Shared\Domain\ValueObjects\GenericId;
use App\Shared\Domain\ValueObjects\LowerText;

class PolicyProviderDto
{
    public function __construct(
        public readonly string $code,
        public readonly LowerText $name,
        public readonly Email $email,
        public readonly string $phone,
        public readonly ?GenericId $policy_provider_id = null
    ) {}
}
