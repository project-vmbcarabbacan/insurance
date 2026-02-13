<?php

namespace App\Modules\Customer\Application\UseCases;

use App\Modules\Customer\Application\DTOs\CustomerInformationDto;
use App\Modules\Customer\Application\Exceptions\CustomerNotFoundException;
use App\Modules\Customer\Application\Services\CustomerService;

class UpdatePartialCustomerUseCase
{
    public function __construct(
        protected CustomerService $customerService
    ) {}

    public function execute(CustomerInformationDto $customerInformationDto)
    {
        $customer = $this->customerService->getById($customerInformationDto->customer_id);

        if (! $customer) {
            throw new CustomerNotFoundException();
        }

        if ($customerInformationDto->email->value()) {
            $this->customerService->ensureEmailIsUnique($customerInformationDto->email, $customerInformationDto->customer_id);
        }

        if ($customerInformationDto->phone->value()) {
            $this->customerService->ensurePhoneIsUnique($customerInformationDto->phone, $customerInformationDto->customer_id);
        }

        $this->customerService->updatePartialCustomer($customerInformationDto);
    }
}
