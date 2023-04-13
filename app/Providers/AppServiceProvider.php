<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\ApplicationServices\IServices\IDocumentTypeService;
use App\ApplicationServices\IServices\IDocumentMetaDataService;
use App\ApplicationServices\Services\DocumentTypeService;
use App\ApplicationServices\Services\DocumentMetaDataService;
use App\InterfaceAdapters\IRepositories\IDocumentTypeRepository;
use App\InterfaceAdapters\IRepositories\IDocumentMetaDataRepository;
use App\InterfaceAdapters\Repositories\DocumentTypeRepository;
use App\InterfaceAdapters\Repositories\DocumentMetaDataRepository;
use App\ApplicationServices\Mappers\DocumentTypeMapper;
use App\ApplicationServices\Mappers\DocumentMetaDataMapper;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        /**
         * This is where to bind implementations to interfaces.
         * When on the controller the interface is injected in the constructor ex :
         * __construct(ServiceInterface $service)
         * ServiceService must be bound to whatever Service Implementation you want to use
         * ex : $this->app->bind(ServiceInterface::class, function() {
         *          return new Service();
         *      });
         *
         * Only then, laravel works it's "Magic"
         */

        $this->app->bind(IDocumentTypeRepository::class, function () {
            return new DocumentTypeRepository();
        });

        $this->app->bind(IDocumentTypeService::class, function () {
            $repo = $this->app->make(IDocumentTypeRepository::class);
            return new DocumentTypeService($repo, new DocumentTypeMapper());
        });

        $this->app->bind(IDocumentMetaDataRepository::class, function () {
            return new DocumentMetaDataRepository();
        });

        $this->app->bind(IDocumentMetaDataService::class, function () {
            $repo = $this->app->make(IDocumentMetaDataRepository::class);
            return new DocumentMetaDataService($repo, new DocumentMetaDataMapper());
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
