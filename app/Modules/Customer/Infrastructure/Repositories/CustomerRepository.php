<?php

namespace App\Modules\Customer\Infrastructure\Repositories;

use App\Models\Customer;
use App\Modules\Customer\Domain\Contracts\CustomerRepositoryContract;
use App\Modules\Customer\Domain\Entities\CustomerEntity;
use App\Modules\Customer\Application\Exceptions\CustomerNotFoundException;
use App\Modules\Customer\Application\Exceptions\PhoneNumberExistsException;
use App\Modules\Customer\Domain\Entities\CustomerInformationEntity;
use App\Modules\Customer\Domain\Entities\PaginatedCustomerEntity;
use App\Modules\User\Application\Exceptions\EmailAlreadyExistsException;
use App\Shared\Domain\Enums\AuditAction;
use App\Shared\Domain\Enums\CustomerStatus;
use App\Shared\Domain\ValueObjects\Email;
use App\Shared\Domain\ValueObjects\GenericId;
use App\Shared\Domain\ValueObjects\Phone;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use stdClass;

class CustomerRepository implements CustomerRepositoryContract
{
    /**
     * Find customer by ID
     */
    public function findById(GenericId $customerId): ?stdClass
    {
        // Pivot customer_information for all relevant keys
        $customerInfo = self::pivotCustomerInformation();

        // Main query
        $query = self::mainCustomerQuery($customerInfo)
            ->where('id', $customerId->value());

        return $query->first();
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
    public function createCustomer(CustomerEntity $CustomerEntity): ?Customer
    {
        /**
         * Persist only non-null entity values
         */
        $payload = array_non_null_values($CustomerEntity->toArray());


        $customer = Customer::create($payload);

        insurance_audit(
            $customer,
            AuditAction::CUSTOMER_CREATED,
            null,
            ['created_at' => Carbon::now()]
        );

        return $customer;
    }

    public function createCustomerInformation(GenericId $customerId, CustomerEntity $customerEntity): void
    {
        $payload = array_non_null_values($customerEntity->toInformation());

        $rows = [];
        foreach ($payload as $key => $value) {
            $rows[] = [
                'customer_id' => $customerId->value(),
                'meta_key' => $key,
                'meta_value' => $value,
            ];
        }

        DB::table('customer_information')->insert($rows);
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
        $customer = $this->modelById($customerId);

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
     * Update customer details
     *
     * @throws CustomerNotFoundException
     * @throws PhoneNumberExistsException
     * @throws EmailAlreadyExistsException
     */
    public function updatePartialCustomer(CustomerInformationEntity $customerInformationEntity): void
    {
        $customer = $this->modelById($customerInformationEntity->customerId());

        /**
         * Extract only non-null values
         */
        $updates = array_non_null_values($customerInformationEntity->toArray());

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

    public function updateCustomerInformation(GenericId $customerId, CustomerEntity $customerEntity): void
    {
        $customer = $this->modelById($customerId);

        $existingMeta = DB::table('customer_information')
            ->where('customer_id', $customerId->value())
            ->pluck('meta_value', 'meta_key')
            ->toArray();

        $newMeta = array_non_null_values($customerEntity->toInformation());

        foreach ($newMeta as $key => $value) {
            $existingValue = $existingMeta[$key] ?? null;

            if ($existingValue !== $value) {
                $oldValue[$key] = $existingValue;
                $newValue[$key] = $value;
            }

            DB::table('customer_information')->updateOrInsert(
                [
                    'customer_id' => $customerId->value(),
                    'meta_key' => $key
                ],
                [
                    'meta_value' => $value
                ]
            );
        }

        insurance_audit(
            $customer,
            AuditAction::CUSTOMER_UPDATED,
            $oldValue,
            $newValue
        );
    }

    /**
     * Assign a user to a customer
     */
    public function updateUserId(GenericId $customerId, GenericId $userId): void
    {
        $customer = $this->modelById($customerId);

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
        $customer = $this->modelById($customerId);

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

    public function paginatedCustomer(PaginatedCustomerEntity $entity): ?LengthAwarePaginator
    {
        // Pivot customer_information for all relevant keys
        $customerInfo = self::pivotCustomerInformation();

        // Main query
        $query = self::mainCustomerQuery($customerInfo);

        // Date filter
        $query->when($entity->dates, function ($q, $dates) {
            [$from, $to] = $dates;
            $q->where('c.created_at', '>=', $from)
                ->where('c.created_at', '<', Carbon::parse($to)->addDay());
        });

        // Keyword search
        $query->when($entity->keyword, function ($q, $keyword) {
            $keyword = "%{$keyword}%";
            $q->where(function ($sub) use ($keyword) {
                $sub->where('ci.first_name', 'like', $keyword)
                    ->orWhere('ci.last_name', 'like', $keyword)
                    ->orWhere('ci.company_name', 'like', $keyword)
                    ->orWhere('ci.contact_person', 'like', $keyword)
                    ->orWhere('c.email', 'like', $keyword)
                    ->orWhere('c.phone_number', 'like', $keyword)
                    ->orWhereRaw("CONCAT(ci.first_name, ' ', ci.last_name) LIKE ?", [$keyword])
                    ->orWhereRaw("CONCAT(ci.company_name, ' ', ci.contact_person) LIKE ?", [$keyword])
                    ->orWhereRaw("CONCAT(c.phone_country_code, c.phone_number) LIKE ?", [$keyword]);
            });
        });

        // Filter by status
        $query->when($entity->status, fn($q, $status) => $q->where('c.status', $status->value));

        // Filter by type
        $query->when($entity->type, fn($q, $type) => $q->where('c.type', $type->value));

        return $query->paginate($entity->per_page);
    }

    public function modelById(GenericId $customerId): ?Customer
    {
        return Customer::find($customerId->value());
    }

    private static function pivotCustomerInformation()
    {
        return DB::table('customer_information')
            ->select(
                'customer_id',
                metaKeyValue('first_name'),
                metaKeyValue('last_name'),
                metaKeyValue('gender'),
                metaKeyValue('dob'),
                metaKeyValue('company_name'),
                metaKeyValue('contact_person'),
                metaKeyValue('registration_no'),
                metaKeyValue('customer_source')
            )
            ->groupBy('customer_id');
    }

    private static function mainCustomerQuery($pivot)
    {
        return DB::table('customers as c')
            ->leftJoinSub($pivot, 'ci', 'ci.customer_id', '=', 'c.id')
            ->select(
                'c.id',
                'c.status',
                'c.phone_country_code',
                'c.phone_number',
                'c.email',
                'c.type',
                'ci.first_name',
                'ci.last_name',
                'ci.gender',
                'ci.dob',
                'ci.company_name',
                'ci.contact_person',
                'ci.registration_no',
                'ci.customer_source'
            );
    }
}
