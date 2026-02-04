<?php

namespace App\Modules\Customer\Domain\Entities;

use App\Shared\Domain\Enums\CustomerSource;
use App\Shared\Domain\Enums\CustomerType;
use App\Shared\Domain\Enums\GenderType;
use App\Shared\Domain\ValueObjects\Email;
use App\Shared\Domain\ValueObjects\GenericDate;
use App\Shared\Domain\ValueObjects\LowerText;
use App\Shared\Domain\ValueObjects\Phone;

class CustomerEntity
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

    public function toArray()
    {
        return [
            'phone_country_code' => $this->phone->countryCode(),
            'phone_number' => $this->phone->phoneNumber(),
            'email' => $this->email->value(),
            'type' => $this->type->value,
        ];
    }

    public function toInformation(): array
    {
        return match ($this->type) {
            CustomerType::INDIVIDUAL => [
                'first_name' => $this->first_name?->value(),
                'last_name' => $this->last_name?->value(),
                'dob' => $this->dob?->toString(),
                'gender' => $this->gender?->value,
                'customer_source' => $this->customer_source->value,
            ],
            CustomerType::CORPORATE => [
                'company_name' => $this->company_name?->value(),
                'contact_person' => $this->contact_person?->value(),
                'registration_no' => $this->registration_no,
                'customer_source' => $this->customer_source->value,
            ],
            default => [],
        };
    }
}
