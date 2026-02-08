<?php

namespace App\Modules\Customer\Application\DTOs;

use App\Shared\Domain\ValueObjects\Email;
use App\Shared\Domain\ValueObjects\GenericId;
use App\Shared\Domain\ValueObjects\Phone;

class CustomerInformationDto
{
    public function __construct(
        public readonly GenericId $customer_id,
        public readonly Phone $phone,
        public readonly Email $email,
    ) {}
}
