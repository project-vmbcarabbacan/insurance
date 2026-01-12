<?php

namespace App\Providers;

use App\Modules\Authentication\Provider\AuthenticationServiceProvider;
use App\Shared\Domain\Enums\MorphType;
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
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Relation::enforceMorphMap(MorphType::morphMap());
    }
}
