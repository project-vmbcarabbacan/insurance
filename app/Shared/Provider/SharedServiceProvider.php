<?php

namespace App\Shared\Provider;

use App\Shared\Domain\Contracts\AuditRepositoryContract;
use App\Shared\Domain\Contracts\CountryRepositoryContract;
use App\Shared\Domain\Contracts\PolicyProviderRepositoryContract;
use App\Shared\Infrastructure\Repositories\AuditRepository;
use App\Shared\Infrastructure\Repositories\CountryRepository;
use App\Shared\Infrastructure\Repositories\PolicyProviderRepository;
use Illuminate\Support\ServiceProvider;

class SharedServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(CountryRepositoryContract::class, CountryRepository::class);
        $this->app->bind(AuditRepositoryContract::class, AuditRepository::class);
        $this->app->bind(PolicyProviderRepositoryContract::class, PolicyProviderRepository::class);
    }

    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/../Infrastructure/Routes/api.php');
    }
}
