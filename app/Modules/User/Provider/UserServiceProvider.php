<?php

namespace App\Modules\User\Provider;

use App\Modules\User\Domain\Contracts\UserRepositoryContract;
use App\Modules\User\Infrastructure\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;

class UserServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(UserRepositoryContract::class, UserRepository::class);
    }

    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/../Infrastructure/Routes/api.php');
    }
}
