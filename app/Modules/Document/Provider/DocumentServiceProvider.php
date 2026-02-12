<?php

namespace App\Modules\Document\Provider;

use App\Modules\Document\Domain\Contracts\DocumentRepositoryContract;
use App\Modules\Document\Domain\Contracts\DocumentTypeRepositoryContract;
use App\Modules\Document\Infrastructure\repositories\DocumentRepository;
use App\Modules\Document\Infrastructure\repositories\DocumentTypeRepository;
use Illuminate\Support\ServiceProvider;

class DocumentServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(DocumentRepositoryContract::class, DocumentRepository::class);
        $this->app->bind(DocumentTypeRepositoryContract::class, DocumentTypeRepository::class);
    }

    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/../Infrastructure/Routes/api.php');
    }
}
