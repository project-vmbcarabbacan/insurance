<?php

namespace App\Modules\Customer\Application\DTOs;

use App\Shared\Domain\Enums\CustomerSource;
use App\Shared\Domain\Enums\CustomerType;
use App\Shared\Domain\Enums\GenderType;
use App\Shared\Domain\ValueObjects\Email;
use App\Shared\Domain\ValueObjects\GenericDate;
use App\Shared\Domain\ValueObjects\LowerText;
use App\Shared\Domain\ValueObjects\Phone;

class CustomerDto
{
    public function __construct(
        public readonly CustomerType $type,
        public readonly CustomerSource $customer_source,
        public readonly Phone $phone,
        public readonly Email $email,
        public readonly ?LowerText $first_name = null,
        public readonly ?LowerText $last_name = null,
        public readonly ?GenericDate $dob = null,
        public readonly ?GenderType $gender = null,
        public readonly ?LowerText $company_name = null,
        public readonly ?LowerText $contact_person = null,
        public readonly ?string $registration_no = null
    ) {}
}
