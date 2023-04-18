<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\ApplicationServices\IServices\IMediaService;
use App\ApplicationServices\Services\MediaService;
use App\ApplicationServices\Mappers\DocumentMapper;
use App\ApplicationServices\Services\DocumentService;
use App\ApplicationServices\IServices\IDocumentService;
use App\ApplicationServices\Mappers\DocumentTypeMapper;
use App\ApplicationServices\Services\DocumentTypeService;
use App\InterfaceAdapters\Repositories\DocumentRepository;
use App\ApplicationServices\IServices\IDocumentTypeService;
use App\ApplicationServices\Mappers\DocumentMetadataMapper;
use App\InterfaceAdapters\IRepositories\IDocumentRepository;
use App\ApplicationServices\Services\DocumentMetadataService;
use App\InterfaceAdapters\Repositories\DocumentTypeRepository;
use App\ApplicationServices\IServices\IDocumentMetadataService;
use App\InterfaceAdapters\IRepositories\IDocumentTypeRepository;
use App\InterfaceAdapters\Repositories\DocumentMetadataRepository;
use App\InterfaceAdapters\IRepositories\IDocumentMetadataRepository;

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
            $documentTypeMapper = $this->app->make(DocumentTypeMapper::class);
            return new DocumentTypeService($repo, $documentTypeMapper);
        });

        $this->app->bind(IDocumentMetadataRepository::class, function () {
            return new DocumentMetadataRepository();
        });

        $this->app->bind(IDocumentMetadataService::class, function () {
            $repo = $this->app->make(IDocumentMetadataRepository::class);
            $documentMetadataMapper = $this->app->make(DocumentMetadataMapper::class);
            return new DocumentMetadataService($repo, $documentMetadataMapper);
        });

        $this->app->bind(IDocumentRepository::class, function () {
            return new DocumentRepository();
        });

        $this->app->bind(IDocumentService::class, function () {
            $repo = $this->app->make(IDocumentRepository::class);
            $documentMapper = $this->app->make(DocumentMapper::class);
            $documentMetadataService = $this->app->make(IDocumentMetadataService::class);
            $mediaService = $this->app->make(IMediaService::class);
            return new DocumentService($repo, $documentMapper, $documentMetadataService, $mediaService);
        });

        $this->app->bind(IMediaService::class, function () {
            return new MediaService();
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
