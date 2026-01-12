<?php

namespace App\Modules\Customer\Domain\Entities;

use App\Shared\Domain\Enums\GenderType;
use App\Shared\Domain\ValueObjects\Email;
use App\Shared\Domain\ValueObjects\GenericDate;
use App\Shared\Domain\ValueObjects\LowerText;
use App\Shared\Domain\ValueObjects\Phone;

class CustomerEntity
{
    public function __construct(
        public readonly LowerText $first_name,
        public readonly LowerText $last_name,
        public readonly Phone $phone,
        public readonly Email $email,
        public readonly ?GenericDate $dob,
        public readonly ?GenderType $gender,
    ) {}

    public function toArray()
    {
        return [
            'first_name' => $this->first_name->value(),
            'last_name' => $this->first_name->value(),
            'dob' => $this->dob->value(),
            'gender' => $this->gender->value,
            'phone_country_code' => $this->phone->countryCode(),
            'phone_number' => $this->phone->phoneNumber(),
            'email' => $this->email->value(),
        ];
    }
}
