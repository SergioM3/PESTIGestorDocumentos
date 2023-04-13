<?php

namespace App\ApplicationServices\Services;

use App\ApplicationServices\IServices\IDocumentTypeService;
use App\InterfaceAdapters\IRepositories\IDocumentTypeRepository;
use App\ApplicationServices\Mappers\DocumentTypeMapper;

class DocumentTypeService implements IDocumentTypeService
{
    private $repo;
    private $mapper;

    public function __construct(IDocumentTypeRepository $repo, DocumentTypeMapper $mapper)
    {
        $this->repo = $repo;
        $this->mapper = $mapper;
    }

    /**
     * getAllDocumentTypes
     *
     * @return array    Type of DocumentTypeDTO
     */
    public function getAllDocumentTypes(): array
    {
        $documentTypes = $this->repo->getAllDocumentTypes();
        $documentTypeDTOs = [];

        foreach ($documentTypes as $documentType) {
            $documentTypeDTOs[] = $this->mapper->toDTO($documentType);
        }

        return $documentTypeDTOs;
    }
}
