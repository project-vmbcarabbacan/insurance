<?php

namespace App\Modules\Master\Provider;

use App\Modules\Master\Domain\Contracts\InsuranceProductRepositoryContract;
use App\Modules\Master\Infrastructure\Repositories\InsuranceProductRepository;
use Illuminate\Support\ServiceProvider;

class MasterServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(InsuranceProductRepositoryContract::class, InsuranceProductRepository::class);
    }
}
