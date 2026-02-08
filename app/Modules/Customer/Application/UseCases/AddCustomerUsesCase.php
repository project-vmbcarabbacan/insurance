<?php

namespace App\Modules\Customer\Application\UseCases;

use App\Modules\Customer\Application\DTOs\CustomerDto;
use App\Modules\Customer\Application\Services\CustomerService;

class AddCustomerUsesCase
{
    public function __construct(
        protected CustomerService $customer_service,
    ) {}

    public function execute(CustomerDto $customerDto)
    {
        $this->customer_service->ensureEmailIsUnique($customerDto->email);
        $this->customer_service->ensurePhoneIsUnique($customerDto->phone);

        $customerEntity = $this->customer_service->addCustomer($customerDto);

        $this->customer_service->addCustomerInformation($customerEntity->getCustomerId(), $customerEntity);

        return $this->customer_service->getModelById($customerEntity->getCustomerId());
    }
}
