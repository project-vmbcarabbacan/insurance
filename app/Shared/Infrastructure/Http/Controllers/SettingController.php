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

/**
 * Controller to manage various application settings
 * such as roles, products, and customer-related prerequisites.
 */
class SettingController
{
    public function __construct(
        protected RoleService $role_service,
        protected InsuranceProductService $insurance_product_service,
        protected MasterService $master_service,
        protected ProductAgentAccess $product_agent_access
    ) {}

    /**
     * Get prerequisites for managing teams, including roles and status options.
     */
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

    /**
     * Get prerequisites for assigning products.
     */
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

    /**
     * Get prerequisites for managing customers, including products
     * that the current agent has access to, and status/type dropdowns.
     */
    public function manageCustomers(Request $request)
    {
        $products = $this->insurance_product_service->getAllProduct();
        $filtered_products = $this->filterAccessibleProducts($products);

        return response()->json([
            'message' => 'Manage customer prerequisites',
            'data' => [
                'products' => InsuranceProductResource::collection($filtered_products),
                'statuses' => CustomerStatus::toDropdownArray(),
                'types' => CustomerType::toDropdownArray()
            ]
        ]);
    }

    /**
     * Get prerequisites for upserting a customer, including country codes,
     * dropdowns, and default access flags for insurance products.
     */
    public function upsertCustomer(Request $request)
    {
        $countryCodes = $this->master_service->getPhoneCountryCode();

        // Set default access to false for all products the agent can access
        $accessed = array_fill_keys(
            array_keys(array_filter($this->getProductAgentAccessed(), fn($v) => $v === true)),
            false
        );

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

    /**
     * Get prerequisites for viewing customer details,
     * including accessible products and country codes.
     */
    public function detailCustomer(Request $request)
    {
        $countryCodes = $this->master_service->getPhoneCountryCode();
        $products = $this->insurance_product_service->getAllProduct();
        $filtered_products = $this->filterAccessibleProducts($products);

        return response()->json([
            'message' => 'Manage customer detail settings',
            'data' => [
                'country_codes' => $countryCodes,
                'products' => InsuranceProductResource::collection($filtered_products)
            ]
        ]);
    }

    /**
     * Retrieve products that the authenticated agent has access to.
     * Admins and super users have access to all products.
     */
    private function getProductAgentAccessed(): array
    {
        $user = getAuthenticatedUser();
        $agentId = GenericId::fromId($user->id);
        $accessed = $this->product_agent_access->execute($agentId);

        // Grant full access for admin/super users
        if ($user->isAdmin() || $user->isSuper()) {
            foreach ($accessed as $key => $_) {
                $accessed[$key] = true;
            }
        }

        return $accessed;
    }

    /**
     * Filter products based on agent access.
     */
    private function filterAccessibleProducts($products)
    {
        $accessed = $this->getProductAgentAccessed();

        return $products
            ->filter(fn($product) => !empty($accessed[$product->code]))
            ->values();
    }
}
