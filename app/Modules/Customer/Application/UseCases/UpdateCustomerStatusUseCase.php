<?php

namespace App\Modules\Customer\Application\UseCases;

use App\Modules\Customer\Application\Exceptions\CustomerNotFoundException;
use App\Modules\Customer\Application\Services\CustomerService;
use App\Shared\Domain\Enums\CustomerStatus;
use App\Shared\Domain\ValueObjects\GenericId;

class UpdateCustomerStatusUseCase
{
    public function __construct(
        protected CustomerService $customer_service
    ) {}

    public function execute(GenericId $customerId, CustomerStatus $customerStatus)
    {
        $customer = $this->customer_service->getById($customerId);


        if (! $customer) {
            throw new CustomerNotFoundException();
        }

        /**
         * Avoid unnecessary update
         */
        if ($customer->status === $customerStatus->value) {
            return;
        }

        $this->customer_service->updateCustomerStatus($customerId, $customerStatus);
    }
}
