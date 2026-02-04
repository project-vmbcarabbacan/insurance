<?php

namespace App\Modules\Customer\Application\UseCases;

use App\Modules\Customer\Application\DTOs\PaginatedCustomerDto;
use App\Modules\Customer\Application\Services\CustomerService;
use App\Modules\Customer\Domain\Entities\PaginatedCustomerEntity;

class PaginatedCustomer
{

    public function __construct(
        protected CustomerService $customer_service
    ) {}

    public function execute(PaginatedCustomerDto $paginatedCustomerDto)
    {
        $entity = new PaginatedCustomerEntity(
            per_page: $paginatedCustomerDto->per_page,
            status: $paginatedCustomerDto->status,
            type: $paginatedCustomerDto->type,
            keyword: $paginatedCustomerDto->keyword,
            dates: $paginatedCustomerDto->dates
        );

        return $this->customer_service->getPaginatedCustomer($entity);
    }
}
