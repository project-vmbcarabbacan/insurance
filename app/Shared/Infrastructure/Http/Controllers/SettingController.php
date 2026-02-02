<?php

namespace App\Shared\Infrastructure\Http\Controllers;

use App\Modules\Master\Application\Services\InsuranceProductService;
use App\Modules\Role\Application\Services\RoleService;
use App\Shared\Domain\Enums\GenericStatus;
use App\Shared\Infrastructure\Http\Resources\InsuranceProductResource;
use App\Shared\Infrastructure\Http\Resources\RoleResource;
use Symfony\Component\HttpFoundation\Request;

class SettingController
{

    public function __construct(
        protected RoleService $role_service,
        protected InsuranceProductService $insurance_product_service
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
}
