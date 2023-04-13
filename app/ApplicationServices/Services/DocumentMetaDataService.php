<?php

namespace App\ApplicationServices\Services;

use App\ApplicationServices\IServices\IDocumentMetaDataService;
use App\InterfaceAdapters\IRepositories\IDocumentMetaDataRepository;
use App\ApplicationServices\Mappers\DocumentMetaDataMapper;

class DocumentMetaDataService implements IDocumentMetaDataService
{
    private $repo;
    private $mapper;

    public function __construct(IDocumentMetaDataRepository $repo, DocumentMetaDataMapper $mapper)
    {
        $this->repo = $repo;
        $this->mapper = $mapper;
    }

    public function getDocumentMetaDataById(int $id): array
    {
        $documentMetaDataList = $this->repo->getDocumentMetaDataById($id);
        $documentMetaDataDTOs = [];

        foreach ($documentMetaDataList as $documentMetaData) {
            $documentMetaDataDTOs[] = $this->mapper->toDTO($documentMetaData);
        }

        return $documentMetaDataDTOs;
    }
}
