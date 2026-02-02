<?php

namespace App\Modules\Role\Provider;

use App\Modules\Role\Domain\Contracts\PermissionRepositoryContract;
use App\Modules\Role\Domain\Contracts\RolePermissionRepositoryContract;
use App\Modules\Role\Domain\Contracts\RoleRepositoryContract;
use App\Modules\Role\Infrastructure\Repositories\PermissionRepository;
use App\Modules\Role\Infrastructure\Repositories\RolePermissionRepository;
use App\Modules\Role\Infrastructure\Repositories\RoleRepository;
use Illuminate\Support\ServiceProvider;

class RoleServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(RoleRepositoryContract::class, RoleRepository::class);
        $this->app->bind(PermissionRepositoryContract::class, PermissionRepository::class);
        $this->app->bind(RolePermissionRepositoryContract::class, RolePermissionRepository::class);
    }
}
