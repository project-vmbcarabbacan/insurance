<?php

namespace App\Modules\Master\Infrastructure\Repositories;

use App\Models\InsuranceProduct;
use App\Modules\Master\Domain\Contracts\InsuranceProductRepositoryContract;
use Illuminate\Database\Eloquent\Collection;

class InsuranceProductRepository implements InsuranceProductRepositoryContract
{
    public function insuranceProducts(): Collection
    {
        return InsuranceProduct::query()->get();
    }

    public function getInsuranceProductByCode(string $code): InsuranceProduct
    {
        return InsuranceProduct::code($code)->first();
    }
}
