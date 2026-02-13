<?php

namespace App\Modules\User\Application\UseCases;

use App\Modules\Agent\Application\Services\AgentProductService;
use App\Modules\Master\Application\Services\InsuranceProductService;
use App\Shared\Domain\ValueObjects\GenericId;

class ProductAgentAccess
{
    public function __construct(
        protected InsuranceProductService $insuranceProductService,
        protected AgentProductService $agentProductService
    ) {}

    public function execute(GenericId $agentId)
    {
        $products = $this->insuranceProductService->getAllProduct();
        $accessed = $this->agentProductService->getAccessByAgentId($agentId);

        return $this->checkAccessedProducts($products, $accessed);
    }

    // Method to initialize the result with all products set to false
    private function initializeResult($products)
    {
        $result = [];
        foreach ($products as $product) {
            $result[$product['code']] = false;
        }
        return $result;
    }

    // Method to check the accessed products and return the updated result
    private function checkAccessedProducts($products, $accessed)
    {
        // Initialize result with all products set to false
        $result = $this->initializeResult($products);

        // Loop through the products and check if each has been accessed
        foreach ($products as $product) {
            foreach ($accessed as $access) {
                if ($product['code'] === $access['insurance_product_code']) {
                    $result[$product['code']] = $access['is_active'];
                    break;
                }
            }
        }

        return $result;
    }
}
