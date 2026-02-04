<?php

namespace App\Shared\Infrastructure\Http\Controllers;

use App\Modules\Master\Application\Services\InsuranceProductService;
use App\Modules\Role\Application\Services\RoleService;
use App\Shared\Application\Services\MasterService;
use App\Shared\Domain\Enums\CustomerStatus;
use App\Shared\Domain\Enums\CustomerType;
use App\Shared\Domain\Enums\GenericStatus;
use App\Shared\Infrastructure\Http\Resources\InsuranceProductResource;
use App\Shared\Infrastructure\Http\Resources\RoleResource;
use Symfony\Component\HttpFoundation\Request;

class SettingController
{

    public function __construct(
        protected RoleService $role_service,
        protected InsuranceProductService $insurance_product_service,
        protected MasterService $master_service
    ) {}

    public function manageTeams(Request $request)
    {
        $roles = $this->role_service->getAllRoles();

        return response()->json([
            'message' => 'Manage team prerequisites',
            'data' => [
                'roles' => RoleResource::collection($roles),
                'statuses' => GenericStatus::toDropdownArray()
            ]
        ]);
    }

    public function assignProduct(Request $request)
    {
        $products = $this->insurance_product_service->getAllProduct();

        return response()->json([
            'message' => 'Assign product prerequisites',
            'data' => [
                'products' => InsuranceProductResource::collection($products)
            ]
        ]);
    }

    public function manageCustomers(Request $request)
    {
        return response()->json([
            'message' => 'Manage customer prerequisites',
            'data' => [
                'statuses' => CustomerStatus::toDropdownArray(),
                'types' => CustomerType::toDropdownArray()
            ]
        ]);
    }

    public function upsertCustomer(Request $request)
    {
        $countryCodes = $this->master_service->getPhoneCountryCode();

        return response()->json([
            'message' => 'Manage upsert customer prerequisites',
            'data' => [
                'statuses' => CustomerStatus::toDropdownArray(),
                'types' => CustomerType::toDropdownArray(),
                'country_codes' => $countryCodes
            ]
        ]);
    }
}
