<?php

namespace App\Modules\Customer\Application\UseCases;

use App\Modules\Customer\Application\DTOs\CustomerInformationDto;
use App\Modules\Customer\Application\Exceptions\CustomerNotFoundException;
use App\Modules\Customer\Application\Services\CustomerService;

class UpdatePartialCustomerUseCase
{
    public function __construct(
        protected CustomerService $customer_service
    ) {}

    public function execute(CustomerInformationDto $customerInformationDto)
    {
        $customer = $this->customer_service->getById($customerInformationDto->customer_id);

        if (! $customer) {
            throw new CustomerNotFoundException();
        }

        if ($customerInformationDto->email->value()) {
            $this->customer_service->ensureEmailIsUnique($customerInformationDto->email, $customerInformationDto->customer_id);
        }

        if ($customerInformationDto->phone->value()) {
            $this->customer_service->ensurePhoneIsUnique($customerInformationDto->phone, $customerInformationDto->customer_id);
        }

        $this->customer_service->updatePartialCustomer($customerInformationDto);
    }
}
