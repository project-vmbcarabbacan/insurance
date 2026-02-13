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
        protected CustomerService $customerService,
        protected UserService $userService
    ) {}

    public function execute(GenericId $customerId, GenericId $userId)
    {
        $customer = $this->customerService->getById($customerId);
        if (! $customer) {
            throw new CustomerNotFoundException();
        }

        $user = $this->userService->getById($userId);
        if (! $user) {
            throw new UserNotFoundException();
        }

        /**
         * Prevent redundant update
         */
        if ($customer->user_id === $userId->value()) {
            return;
        }

        $this->customerService->updateUserId($customerId, $userId);
    }
}
