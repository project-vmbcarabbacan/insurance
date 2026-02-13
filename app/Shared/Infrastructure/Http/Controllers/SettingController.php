<?php

namespace App\Shared\Infrastructure\Http\Controllers;

use App\Modules\Lead\Application\Services\LeadService;
use App\Modules\Lead\Domain\Maps\LeadActivityResponseMap;
use App\Modules\Lead\Infrastructure\Http\Requests\UuidLeadRequest;
use App\Modules\Master\Application\Services\InsuranceProductService;
use App\Modules\Role\Application\Services\RoleService;
use App\Modules\User\Application\UseCases\ProductAgentAccess;
use App\Shared\Application\Services\MasterService;
use App\Shared\Domain\Enums\CommunicationPreference;
use App\Shared\Domain\Enums\CustomerSource;
use App\Shared\Domain\Enums\CustomerStatus;
use App\Shared\Domain\Enums\CustomerType;
use App\Shared\Domain\Enums\GenderType;
use App\Shared\Domain\Enums\GenericStatus;
use App\Shared\Domain\Enums\LeadActivityResponse;
use App\Shared\Domain\Enums\LeadStatus;
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
        protected RoleService $roleService,
        protected InsuranceProductService $insuranceProductService,
        protected MasterService $masterService,
        protected ProductAgentAccess $productAgentAccess,
        protected LeadService $leadService
    ) {}

    /**
     * Get prerequisites for managing teams, including roles and status options.
     */
    public function manageTeams(Request $request)
    {
        $roles = $this->roleService->getAllRoles();

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
        $products = $this->insuranceProductService->getAllProduct();

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
        $products = $this->insuranceProductService->getAllProduct();
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
        $countryCodes = $this->masterService->getPhoneCountryCode();

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
        $countryCodes = $this->masterService->getPhoneCountryCode();
        $products = $this->insuranceProductService->getAllProduct();
        $filtered_products = $this->filterAccessibleProducts($products);

        return response()->json([
            'message' => 'Manage customer detail settings',
            'data' => [
                'country_codes' => $countryCodes,
                'products' => InsuranceProductResource::collection($filtered_products),
                'communication_preferences' => CommunicationPreference::toDropdownArray()
            ]
        ]);
    }

    public function leadActivity(UuidLeadRequest $request)
    {
        $lead = $this->leadService->getLeadByUuid($request->uuid());

        $responses = LeadActivityResponseMap::map($lead->status);

        $activityResponses = array_map(
            fn(LeadActivityResponse $case) => [
                'label' => ucwords(strtolower(str_replace('_', ' ', $case->value))),
                'value' => $case->value,
            ],
            $responses
        );

        return response()->json([
            'message' => 'Manage lead activity',
            'data' => [
                'activity_responses' => $activityResponses,
                'communication_preferences' => CommunicationPreference::toDropdownArray()
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
        $accessed = $this->productAgentAccess->execute($agentId);

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
