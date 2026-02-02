<?php

namespace App\Shared\Provider;

use Illuminate\Support\ServiceProvider;

class SharedServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/../Infrastructure/Routes/api.php');
    }
}
