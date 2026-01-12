<?php

namespace App\Modules\Customer\Application\DTOs;

use App\Shared\Domain\Enums\GenderType;
use App\Shared\Domain\ValueObjects\Email;
use App\Shared\Domain\ValueObjects\GenericDate;
use App\Shared\Domain\ValueObjects\LowerText;
use App\Shared\Domain\ValueObjects\Phone;

class CustomerDto
{
    public function __construct(
        public readonly LowerText $first_name,
        public readonly LowerText $last_name,
        public readonly Phone $phone,
        public readonly Email $email,
        public readonly ?GenericDate $dob,
        public readonly ?GenderType $gender,
    ) {}
}
