<?php

namespace App\Modules\Customer\Application\UseCases;

use App\Modules\Customer\Application\DTOs\CustomerDto;
use App\Modules\Customer\Application\Services\CustomerService;

class AddCustomerUsesCase
{
    public function __construct(
        protected CustomerService $customerService,
    ) {}

    public function execute(CustomerDto $customerDto)
    {
        $this->customerService->ensureEmailIsUnique($customerDto->email);
        $this->customerService->ensurePhoneIsUnique($customerDto->phone);

        $customerEntity = $this->customerService->addCustomer($customerDto);

        $this->customerService->addCustomerInformation($customerEntity->getCustomerId(), $customerEntity);

        return $this->customerService->getModelById($customerEntity->getCustomerId());
    }
}
