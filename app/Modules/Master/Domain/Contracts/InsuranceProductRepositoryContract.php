<?php

namespace App\Modules\Master\Domain\Contracts;

use App\Models\InsuranceProduct;
use Illuminate\Database\Eloquent\Collection;

interface InsuranceProductRepositoryContract
{
    public function insuranceProducts(): Collection;
    public function getInsuranceProductByCode(string $code): InsuranceProduct;
}
