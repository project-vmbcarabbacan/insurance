<?php

namespace App\Modules\Master\Provider;

use App\Modules\Master\Domain\Contracts\InsuranceProductRepositoryContract;
use App\Modules\Master\Domain\Contracts\VehiclePrerequisiteRepositoryContract;
use App\Modules\Master\Infrastructure\Repositories\InsuranceProductRepository;
use App\Modules\Master\Infrastructure\Repositories\VehiclePrerequisiteRepository;
use Illuminate\Support\ServiceProvider;

class MasterServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(InsuranceProductRepositoryContract::class, InsuranceProductRepository::class);
        $this->app->bind(VehiclePrerequisiteRepositoryContract::class, VehiclePrerequisiteRepository::class);
    }
}
