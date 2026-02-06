<?php

namespace App\Shared\Infrastructure\Http\Controllers;

use App\Modules\Master\Application\Services\InsuranceProductService;
use App\Modules\Role\Application\Services\RoleService;
use App\Modules\User\Application\UseCases\ProductAgentAccess;
use App\Shared\Application\Services\MasterService;
use App\Shared\Domain\Enums\CustomerSource;
use App\Shared\Domain\Enums\CustomerStatus;
use App\Shared\Domain\Enums\CustomerType;
use App\Shared\Domain\Enums\GenderType;
use App\Shared\Domain\Enums\GenericStatus;
use App\Shared\Domain\ValueObjects\GenericId;
use App\Shared\Infrastructure\Http\Resources\InsuranceProductResource;
use App\Shared\Infrastructure\Http\Resources\RoleResource;
use Symfony\Component\HttpFoundation\Request;

class SettingController
{

    public function __construct(
        protected RoleService $role_service,
        protected InsuranceProductService $insurance_product_service,
        protected MasterService $master_service,
        protected ProductAgentAccess $product_agent_access
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
        $products = $this->insurance_product_service->getAllProduct();

        $accessed = $this->getProductAgentAccessed();

        /* filter only products having true */
        $filtered_products = $products->filter(function ($product) use ($accessed) {
            return isset($accessed[$product->code]) && $accessed[$product->code] === true;
        });

        $filtered_products = $filtered_products->values();

        return response()->json([
            'message' => 'Manage customer prerequisites',
            'data' => [
                'products' => InsuranceProductResource::collection($filtered_products),
                'statuses' => CustomerStatus::toDropdownArray(),
                'types' => CustomerType::toDropdownArray()
            ]
        ]);
    }

    public function upsertCustomer(Request $request)
    {
        $countryCodes = $this->master_service->getPhoneCountryCode();

        $accessed = $this->getProductAgentAccessed();

        /* removed the insurance product having false value */
        $accessed = array_filter($accessed, function ($value) {
            return $value === true;
        });

        /* set the default values to false */
        foreach ($accessed as $key => $value) {
            $accessed[$key] = false;
        }

        return response()->json([
            'message' => 'Manage upsert customer prerequisites',
            'data' => [
                'statuses' => CustomerStatus::toDropdownArray(),
                'types' => CustomerType::toDropdownArray(),
                'customer_sources' => CustomerSource::toDropdownArray(),
                'genders' => GenderType::toDropdownArray(),
                'country_codes' => $countryCodes,
                'accessed' => $accessed
            ]
        ]);
    }

    private function getProductAgentAccessed(): array
    {
        $user = getAuthenticatedUser();

        $agentId = GenericId::fromId($user->id);
        $accessed = $this->product_agent_access->execute($agentId);

        if ($user->isAdmin() || $user->isSuper()) {
            foreach ($accessed as $key => $value) {
                $accessed[$key] = true;
            }
        }

        return $accessed;
    }
}
