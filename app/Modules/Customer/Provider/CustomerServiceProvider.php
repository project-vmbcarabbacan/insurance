<?php

namespace App\Modules\Customer\Provider;

use App\Modules\Customer\Domain\Contracts\CustomerRepositoryContract;
use App\Modules\Customer\Infrastructure\Repositories\CustomerRepository;
use Illuminate\Support\ServiceProvider;

class CustomerServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(CustomerRepositoryContract::class, CustomerRepository::class);
    }

    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/../Infrastructure/Routes/api.php');
    }
}
