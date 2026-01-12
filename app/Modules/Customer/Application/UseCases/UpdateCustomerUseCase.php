<?php

namespace App\Modules\Customer\Application\UseCases;

use App\Modules\Customer\Application\DTOs\CustomerDto;
use App\Modules\Customer\Application\Exceptions\CustomerNotFoundException;
use App\Modules\Customer\Application\Services\CustomerService;
use App\Shared\Domain\ValueObjects\GenericId;

class UpdateCustomerUseCase
{
    public function __construct(
        protected CustomerService $customer_service
    ) {}

    public function execute(GenericId $customerId, CustomerDto $customerDto)
    {
        $customer = $this->customer_service->getById($customerId);

        if (! $customer) {
            throw new CustomerNotFoundException();
        }

        $this->customer_service->ensureEmailIsUnique($customerDto->email, $customerId);
        $this->customer_service->ensurePhoneIsUnique($customerDto->phone, $customerId);

        $this->customer_service->updateCustomer($customerId, $customerDto);
    }
}
