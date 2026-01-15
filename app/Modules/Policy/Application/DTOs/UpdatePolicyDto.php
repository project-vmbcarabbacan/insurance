<?php

namespace App\Modules\Policy\Application\DTOs;

use App\Shared\Domain\ValueObjects\Amount;
use App\Shared\Domain\ValueObjects\GenericDate;
use App\Shared\Domain\ValueObjects\GenericId;
use App\Shared\Domain\ValueObjects\LowerText;

class UpdatePolicyDto
{
    public function __construct(
        public readonly GenericId $policy_id,
        public readonly Amount $premium_amount,
        public readonly Amount $vat,
        public readonly ?LowerText $policy_number,
        public readonly ?GenericDate $start_date,
        public readonly ?GenericDate $end_date,
    ) {}
}
