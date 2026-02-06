<?php

namespace App\Providers;

use App\Modules\Agent\Provider\AgentProductServiceProvider;
use App\Modules\Authentication\Provider\AuthenticationServiceProvider;
use App\Modules\Customer\Provider\CustomerServiceProvider;
use App\Modules\Lead\Provider\LeadServiceProvider;
use App\Modules\Master\Provider\MasterServiceProvider;
use App\Modules\Role\Provider\RoleServiceProvider;
use App\Modules\User\Provider\UserServiceProvider;
use App\Shared\Domain\Enums\MorphType;
use App\Shared\Provider\SharedServiceProvider;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->register(AuthenticationServiceProvider::class);
        $this->app->register(UserServiceProvider::class);
        $this->app->register(SharedServiceProvider::class);
        $this->app->register(RoleServiceProvider::class);
        $this->app->register(MasterServiceProvider::class);
        $this->app->register(AgentProductServiceProvider::class);
        $this->app->register(CustomerServiceProvider::class);
        $this->app->register(LeadServiceProvider::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Relation::enforceMorphMap(MorphType::morphMap());
    }
}
