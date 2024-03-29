<?php

namespace App\ApplicationServices\Services;

use App\Domain\Aggregates\Metadata\DocumentMetaData;
use App\ApplicationServices\Mappers\DocumentMetadataMapper;
use App\ApplicationServices\IServices\IDocumentMetadataService;
use App\InterfaceAdapters\IRepositories\IDocumentMetadataRepository;

class DocumentMetadataService implements IDocumentMetadataService
{
    private $repo;
    private $mapper;

    public function __construct(IDocumentMetadataRepository $repo, DocumentMetadataMapper $mapper)
    {
        $this->repo = $repo;
        $this->mapper = $mapper;
    }

    /**
     * Returns Metadata list of a document by it's id
     *
     * @param  int $id
     * @return array
     */
    public function getDocumentMetadataById(int $id): array
    {
        $documentMetadataList = $this->repo->getDocumentMetadataById($id);
        $documentMetadataDTOs = [];

        foreach ($documentMetadataList as $documentMetadata) {
            $documentMetadataDTOs[] = $this->mapper->toDTO($documentMetadata);
        }

        return $documentMetadataDTOs;
    }

    /**
     * Inserts given metadata into a document by it's id
     *
     * @param  array $documentMetadataList
     * @param  int $documentId
     * @return void
     */
    public function insertDocumentMetadata(array $documentMetadataList, int $documentId): void
    {
        foreach ($documentMetadataList as $documentMetadataItem) {
            $documentMetadata = new DocumentMetadata([
                'document_id' => $documentId,
                'value' => $documentMetadataItem['value'],
                'metadata_type_id' => $documentMetadataItem['metadata_type']['id']
            ]);
            $this->repo->insertDocumentMetadata($documentMetadata);
        }
    }

    /**
     * Deletes all the metadata from the document given by document Id
     *
     * @param  int $id
     * @return void
     */
    public function deleteDocumentMetadata(int $id): void
    {
        $this->repo->deleteDocumentMetadata($id);
    }
}
