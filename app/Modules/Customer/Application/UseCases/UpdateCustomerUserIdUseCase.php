<?php

namespace App\Modules\Customer\Application\UseCases;

use App\Modules\Customer\Application\Exceptions\CustomerNotFoundException;
use App\Modules\Customer\Application\Services\CustomerService;
use App\Modules\User\Application\Exceptions\UserNotFoundException;
use App\Modules\User\Application\Services\UserService;
use App\Shared\Domain\ValueObjects\GenericId;

class UpdateCustomerUserIdUseCase
{
    public function __construct(
        protected CustomerService $customer_service,
        protected UserService $user_service
    ) {}

    public function execute(GenericId $customerId, GenericId $userId)
    {
        $customer = $this->customer_service->getById($customerId);
        if (! $customer) {
            throw new CustomerNotFoundException();
        }

        $user = $this->user_service->getById($userId);
        if (! $user) {
            throw new UserNotFoundException();
        }

        /**
         * Prevent redundant update
         */
        if ($customer->user_id === $userId->value()) {
            return;
        }

        $this->customer_service->updateUserId($customerId, $userId);
    }
}
