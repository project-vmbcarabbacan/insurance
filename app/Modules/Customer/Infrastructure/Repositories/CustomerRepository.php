<?php

namespace App\Modules\Customer\Infrastructure\Repositories;

use App\Models\Customer;
use App\Modules\Customer\Domain\Contracts\CustomerRepositoryContract;
use App\Modules\Customer\Domain\Entities\CustomerEntity;
use App\Modules\Customer\Application\Exceptions\CustomerNotFoundException;
use App\Modules\Customer\Application\Exceptions\PhoneNumberExistsException;
use App\Modules\User\Application\Exceptions\EmailAlreadyExistsException;
use App\Shared\Domain\Enums\AuditAction;
use App\Shared\Domain\Enums\CustomerStatus;
use App\Shared\Domain\ValueObjects\Email;
use App\Shared\Domain\ValueObjects\GenericId;
use App\Shared\Domain\ValueObjects\Phone;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class CustomerRepository implements CustomerRepositoryContract
{
    /**
     * Find customer by ID
     */
    public function findById(GenericId $customerId): ?Customer
    {
        return Customer::find($customerId->value());
    }

    /**
     * Find customer by email
     */
    public function findByEmail(Email $email): ?Customer
    {
        return Customer::email($email->value())->first();
    }

    /**
     * Find customer by phone number
     */
    public function findByPhone(Phone $phone): ?Customer
    {
        return Customer::phone($phone->phoneNumber(), $phone->countryCode())->first();
    }

    /**
     * Retrieve all customers
     */
    public function getAllCustomers(): Collection
    {
        return Customer::query()->get();
    }

    /**
     * Create a new customer
     *
     * @throws PhoneNumberExistsException
     * @throws EmailAlreadyExistsException
     */
    public function createCustomer(CustomerEntity $CustomerEntity): void
    {
        /**
         * Persist only non-null entity values
         */
        $payload = array_filter(
            $CustomerEntity->toArray(),
            static fn($value) => ! is_null($value)
        );

        $customer = Customer::create($payload);

        insurance_audit(
            $customer,
            AuditAction::CUSTOMER_CREATED,
            null,
            ['created_at' => Carbon::now()]
        );
    }

    /**
     * Update customer details
     *
     * @throws CustomerNotFoundException
     * @throws PhoneNumberExistsException
     * @throws EmailAlreadyExistsException
     */
    public function updateCustomer(GenericId $customerId, CustomerEntity $CustomerEntity): void
    {
        $customer = $this->findById($customerId);

        /**
         * Extract only non-null values
         */
        $updates = array_non_null_values($CustomerEntity->toArray());

        /**
         * No changes — avoid unnecessary DB hit
         */
        if ($updates === []) {
            return;
        }

        /**
         * Capture original values for audit
         */
        $oldValues = array_old_values($customer, $updates);

        $customer->update($updates);

        insurance_audit(
            $customer,
            AuditAction::CUSTOMER_UPDATED,
            $oldValues,
            $updates
        );
    }

    /**
     * Assign a user to a customer
     */
    public function updateUserId(GenericId $customerId, GenericId $userId): void
    {
        $customer = $this->findById($customerId);

        $customer->update([
            'user_id' => $userId->value(),
        ]);

        insurance_audit(
            $customer,
            AuditAction::CUSTOMER_UPDATED,
            null,
            ['user_id' => $userId->value()]
        );
    }

    /**
     * Update customer status
     *
     * @throws CustomerNotFoundException
     */
    public function updateCustomerStatus(GenericId $customerId, CustomerStatus $customerStatus): void
    {
        $customer = $this->findById($customerId);

        $oldValues = [
            'status' => $customer->status,
        ];

        $customer->update([
            'status' => $customerStatus->value,
        ]);

        insurance_audit(
            $customer,
            AuditAction::CUSTOMER_UPDATED,
            $oldValues,
            ['status' => $customerStatus->value]
        );
    }
}
