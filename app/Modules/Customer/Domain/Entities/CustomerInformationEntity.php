<?php

namespace App\Modules\Customer\Domain\Entities;

use App\Shared\Domain\ValueObjects\Email;
use App\Shared\Domain\ValueObjects\GenericId;
use App\Shared\Domain\ValueObjects\Phone;

class CustomerInformationEntity
{
    public function __construct(
        public readonly GenericId $customer_id,
        public readonly Email $email,
        public readonly Phone $phone
    ) {}

    public function toArray()
    {
        return [
            'phone_country_code' => $this->phone->countryCode(),
            'phone_number' => $this->phone->phoneNumber(),
            'email' => $this->email->value(),
        ];
    }

    public function customerId()
    {
        return $this->customer_id;
    }
}
