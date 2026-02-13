<?php

namespace App\Modules\Customer\Application\UseCases;

use App\Modules\Customer\Application\DTOs\CustomerDto;
use App\Modules\Customer\Application\Exceptions\CustomerNotFoundException;
use App\Modules\Customer\Application\Services\CustomerService;
use App\Shared\Domain\ValueObjects\GenericId;

class UpdateCustomerUseCase
{
    public function __construct(
        protected CustomerService $customerService
    ) {}

    public function execute(GenericId $customerId, CustomerDto $customerDto)
    {
        $customer = $this->customerService->getById($customerId);

        if (! $customer) {
            throw new CustomerNotFoundException();
        }

        $this->customerService->ensureEmailIsUnique($customerDto->email, $customerId);
        $this->customerService->ensurePhoneIsUnique($customerDto->phone, $customerId);

        $this->customerService->updateCustomer($customerId, $customerDto);
    }
}
