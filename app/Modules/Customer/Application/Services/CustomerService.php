<?php

namespace App\Modules\Customer\Application\Services;

use App\Models\Customer;
use App\Modules\Customer\Application\DTOs\CustomerDto;
use App\Modules\Customer\Application\DTOs\CustomerInformationDto;
use App\Modules\Customer\Application\Exceptions\PhoneNumberExistsException;
use App\Modules\Customer\Domain\Contracts\CustomerRepositoryContract;
use App\Modules\Customer\Domain\Entities\CustomerEntity;
use App\Modules\Customer\Domain\Entities\CustomerInformationEntity;
use App\Modules\Customer\Domain\Entities\PaginatedCustomerEntity;
use App\Modules\User\Application\Exceptions\EmailAlreadyExistsException;
use App\Shared\Domain\Enums\CustomerStatus;
use App\Shared\Domain\ValueObjects\Email;
use App\Shared\Domain\ValueObjects\GenericId;
use App\Shared\Domain\ValueObjects\Phone;
use Illuminate\Pagination\LengthAwarePaginator;

class CustomerService
{

    public function __construct(
        protected CustomerRepositoryContract $customer_repository_contract,
    ) {}

    public function getPaginatedCustomer(PaginatedCustomerEntity $entity): LengthAwarePaginator
    {
        return $this->customer_repository_contract->paginatedCustomer($entity);
    }

    public function getById(GenericId $customerId)
    {
        return $this->customer_repository_contract->findById($customerId);
    }

    public function getModelById(GenericId $customerId)
    {
        return $this->customer_repository_contract->modelById($customerId);
    }

    public function getByEmail(Email $email)
    {
        return $this->customer_repository_contract->findByEmail($email);
    }

    public function getByPhone(Phone $phone)
    {
        return $this->customer_repository_contract->findByPhone($phone);
    }

    public function getCustomers()
    {
        return $this->customer_repository_contract->getAllCustomers();
    }

    public function addCustomer(CustomerDto $customerDto)
    {
        $customerEntity = new CustomerEntity(
            type: $customerDto->type,
            customer_source: $customerDto->customer_source,
            phone: $customerDto->phone,
            email: $customerDto->email,
            first_name: $customerDto->first_name,
            last_name: $customerDto->last_name,
            dob: $customerDto->dob,
            gender: $customerDto->gender,
            company_name: $customerDto->company_name,
            contact_person: $customerDto->contact_person,
            registration_no: $customerDto->registration_no
        );

        $customer = $this->customer_repository_contract->createCustomer($customerEntity);

        $customerEntityWithId = $customerEntity->setId(GenericId::fromId($customer->id));

        return $customerEntityWithId;
    }

    public function addCustomerInformation(GenericId $customerId, CustomerEntity $customerEntity)
    {
        $this->customer_repository_contract->createCustomerInformation($customerId, $customerEntity);
    }

    public function updateCustomer(GenericId $customerId, CustomerDto $customerDto)
    {
        $customerEntity = new CustomerEntity(
            type: $customerDto->type,
            customer_source: $customerDto->customer_source,
            phone: $customerDto->phone,
            email: $customerDto->email,
            first_name: $customerDto->first_name,
            last_name: $customerDto->last_name,
            dob: $customerDto->dob,
            gender: $customerDto->gender,
            company_name: $customerDto->company_name,
            contact_person: $customerDto->contact_person,
            registration_no: $customerDto->registration_no
        );

        $this->customer_repository_contract->updateCustomer($customerId, $customerEntity);
        $this->customer_repository_contract->updateCustomerInformation($customerId, $customerEntity);
    }

    public function updatePartialCustomer(CustomerInformationDto $customerInformationDto)
    {

        $information = new CustomerInformationEntity(
            customer_id: $customerInformationDto->customer_id,
            email: $customerInformationDto->email,
            phone: $customerInformationDto->phone
        );

        $this->customer_repository_contract->updatePartialCustomer($information);
    }

    public function updateUserId(GenericId $customerId, GenericId $userId)
    {
        $this->customer_repository_contract->updateUserId($customerId, $userId);
    }

    public function updateCustomerStatus(GenericId $customerId, CustomerStatus $customerStatus)
    {
        $this->customer_repository_contract->updateCustomerStatus($customerId, $customerStatus);
    }

    /**
     * Ensure email is unique
     */
    public function ensureEmailIsUnique(
        Email $email,
        ?GenericId $ignoreCustomerId = null
    ): void {
        $existing = $this->getByEmail($email);

        if (
            $existing &&
            (! $ignoreCustomerId || $existing->id !== $ignoreCustomerId->value())
        ) {
            throw new EmailAlreadyExistsException();
        }
    }

    /**
     * Ensure phone number is unique
     */
    public function ensurePhoneIsUnique(
        Phone $phone,
        ?GenericId $ignoreCustomerId = null
    ): void {
        $existing = $this->getByPhone($phone);

        if (
            $existing &&
            (! $ignoreCustomerId || $existing->id !== $ignoreCustomerId->value())
        ) {
            throw new PhoneNumberExistsException();
        }
    }
}
