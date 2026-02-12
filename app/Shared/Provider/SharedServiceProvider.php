<?php

namespace App\Shared\Provider;

use App\Shared\Domain\Contracts\AuditRepositoryContract;
use App\Shared\Domain\Contracts\CountryRepositoryContract;
use App\Shared\Infrastructure\Repositories\AuditRepository;
use App\Shared\Infrastructure\Repositories\CountryRepository;
use Illuminate\Support\ServiceProvider;

class SharedServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(CountryRepositoryContract::class, CountryRepository::class);
        $this->app->bind(AuditRepositoryContract::class, AuditRepository::class);
    }

    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/../Infrastructure/Routes/api.php');
    }
}
