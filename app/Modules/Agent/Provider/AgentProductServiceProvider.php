<?php

namespace App\Modules\Agent\Provider;

use App\Modules\Agent\Domain\Contracts\AgentProductRepositoryContract;
use App\Modules\Agent\Infrastructure\Repositories\AgentProductRepository;
use Illuminate\Support\ServiceProvider;

class AgentProductServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(AgentProductRepositoryContract::class, AgentProductRepository::class);
    }
}
