<?php

namespace App\Modules\Policy\Domain\Entities;

use App\Shared\Domain\ValueObjects\Amount;
use App\Shared\Domain\ValueObjects\GenericDate;
use App\Shared\Domain\ValueObjects\LowerText;

final class UpdatePolicyEntity
{
    public function __construct(
        public readonly Amount $premium_amount,
        public readonly Amount $vat,
        public readonly ?LowerText $policy_number,
        public readonly ?GenericDate $start_date,
        public readonly ?GenericDate $end_date,
    ) {}

    public function toArray()
    {
        return [
            'premium_amount' => $this->premium_amount->amount(),
            'vat' => $this->vat->amount(),
            'currency' => $this->premium_amount->currency(),
            'policy_number' => $this->policy_number->value(),
            'start_date' => $this->start_date->value(),
            'end_date' => $this->end_date->value()
        ];
    }
}
