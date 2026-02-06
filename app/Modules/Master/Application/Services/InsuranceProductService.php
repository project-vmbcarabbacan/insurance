<?php

namespace App\Modules\Master\Application\Services;

use App\Models\InsuranceProduct;
use App\Modules\Master\Domain\Contracts\InsuranceProductRepositoryContract;
use Illuminate\Database\Eloquent\Collection;

class InsuranceProductService
{

    public function __construct(
        protected InsuranceProductRepositoryContract $insurance_product_repository_contract
    ) {}

    public function getAllProduct(): Collection
    {
        return $this->insurance_product_repository_contract->insuranceProducts();
    }

    public function getAllProductCode(): array
    {
        return $this->insurance_product_repository_contract->getInsuranceCode();
    }

    public function isValidCode(string $code, array $codes): bool
    {
        return in_array($code, $codes);
    }

    public function getProductByCode(string $code): InsuranceProduct
    {
        return $this->insurance_product_repository_contract->getInsuranceProductByCode($code);
    }
}
