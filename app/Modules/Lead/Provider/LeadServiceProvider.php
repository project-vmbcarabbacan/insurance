<?php

namespace App\Modules\Lead\Provider;

use App\Modules\Lead\Domain\Contracts\LeadActivityRepositoryContract;
use App\Modules\Lead\Domain\Contracts\LeadMetaRepositoryContract;
use App\Modules\Lead\Domain\Contracts\LeadRepositoryContract;
use App\Modules\Lead\Infrastructure\repositories\LeadActivityRepository;
use App\Modules\Lead\Infrastructure\repositories\LeadMetaRepository;
use App\Modules\Lead\Infrastructure\repositories\LeadRepository;
use App\Modules\Lead\Infrastructure\repositories\VehicleLeadMetRepository;
use Illuminate\Support\ServiceProvider;

class LeadServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(LeadRepositoryContract::class, LeadRepository::class);
        $this->app->bind(LeadActivityRepositoryContract::class, LeadActivityRepository::class);
        // $this->app->bind(LeadMetaRepositoryContract::class, LeadMetaRepository::class);
        $this->app->bind(LeadMetaRepositoryContract::class, VehicleLeadMetRepository::class);
    }

    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/../Infrastructure/Routes/api.php');
    }
}
