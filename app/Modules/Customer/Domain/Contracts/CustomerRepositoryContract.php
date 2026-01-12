<?php

namespace  App\Modules\Customer\Domain\Contracts;

use App\Models\Customer;
use App\Modules\Customer\Domain\Entities\CustomerEntity;
use App\Shared\Domain\Enums\CustomerStatus;
use App\Shared\Domain\ValueObjects\Email;
use App\Shared\Domain\ValueObjects\GenericId;
use App\Shared\Domain\ValueObjects\Phone;
use Illuminate\Database\Eloquent\Collection;

interface CustomerRepositoryContract
{
    public function findById(GenericId $customerId): ?Customer;
    public function findByEmail(Email $email): ?Customer;
    public function findByPhone(Phone $phone): ?Customer;
    public function getAllCustomers(): Collection;
    public function createCustomer(CustomerEntity $CustomerEntity): void;
    public function updateCustomer(GenericId $customerId, CustomerEntity $CustomerEntity): void;
    public function updateUserId(GenericId $customerId, GenericId $userId): void;
    public function updateCustomerStatus(GenericId $customerId, CustomerStatus $customerStatus): void;
}
