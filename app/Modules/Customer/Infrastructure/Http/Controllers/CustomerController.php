<?php

namespace App\Modules\Customer\Infrastructure\Http\Controllers;

use App\Modules\Customer\Application\Services\CustomerService;
use App\Modules\Customer\Application\UseCases\AddCustomerUsesCase;
use App\Modules\Customer\Application\UseCases\PaginatedCustomer;
use App\Modules\Customer\Application\UseCases\UpdateCustomerUseCase;
use App\Modules\Customer\Infrastructure\Http\Requests\CreateCustomerRequest;
use App\Modules\Customer\Infrastructure\Http\Requests\PaginatedCustomerRequest;
use App\Modules\Customer\Infrastructure\Http\Requests\UpdateCustomerRequest;
use App\Modules\Customer\Infrastructure\Http\Requests\UuidCustomerRequest;
use App\Modules\Customer\Infrastructure\Http\Resources\CustomerDetailResource;
use App\Modules\Customer\Infrastructure\Http\Resources\CustomersResource;
use App\Modules\Customer\Infrastructure\Http\Resources\CustomerResource;
use App\Modules\Lead\Application\Services\LeadService;
use App\Modules\Lead\Application\UseCases\CreateLeadUseCase;
use App\Modules\Lead\Infrastructure\Http\Resources\LeadDetailResource;
use Illuminate\Support\Facades\DB;

class CustomerController
{

    public function index(PaginatedCustomerRequest $request, PaginatedCustomer $paginatedCustomer)
    {
        $dto = $request->toDTO();

        $customers = $paginatedCustomer->execute($dto);

        $customers->through(fn($customer) => new CustomersResource($customer));

        return response()->json([
            'message' => 'Manage Customers',
            'data' => $customers
        ]);
    }

    public function store(CreateCustomerRequest $request, AddCustomerUsesCase $addCustomerUsesCase, CreateLeadUseCase $createLeadUseCase)
    {
        DB::transaction(function () use ($request, $addCustomerUsesCase, $createLeadUseCase) {
            $dto = $request->toDTO();
            $leadDtos = $request->leadDto();

            $customer = $addCustomerUsesCase->execute($dto);

            foreach ($leadDtos as $lead) {
                $createLeadUseCase->execute($customer, $lead);
            }
        });

        return response()->json([
            'message' => 'Customer created'
        ], 201);
    }

    public function update(UpdateCustomerRequest $request, UpdateCustomerUseCase $updateCustomerUseCase)
    {
        DB::transaction(function () use ($request, $updateCustomerUseCase) {
            $dto = $request->toDTO();

            $updateCustomerUseCase->execute($request->customerId(), $dto);
        });

        return response()->json([
            'message' => 'Customer updated'
        ], 201);
    }

    public function find(UuidCustomerRequest $request, CustomerService $customerService)
    {
        $customer = $customerService->getById($request->customerId());

        return response()->json([
            'message' => 'customer by id',
            'data' => [
                'customer' => new CustomerResource($customer)
            ]
        ]);
    }

    public function details(UuidCustomerRequest $request, CustomerService $customerService, LeadService $leadService)
    {
        $customer = $customerService->getById($request->customerId());
        $leads = $leadService->getLeadsByCustomerId($request->customerId());

        return response()->json([
            'message' => 'customer by id',
            'data' => [
                'customer' => new CustomerDetailResource($customer),
                'leads' => LeadDetailResource::collection($leads)
            ]
        ]);
    }
}
