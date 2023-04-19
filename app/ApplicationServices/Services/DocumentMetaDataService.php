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

    public function getDocumentMetadataById(int $id): array
    {
        $documentMetadataList = $this->repo->getDocumentMetadataById($id);
        $documentMetadataDTOs = [];

        foreach ($documentMetadataList as $documentMetadata) {
            $documentMetadataDTOs[] = $this->mapper->toDTO($documentMetadata);
        }

        return $documentMetadataDTOs;
    }

    public function insertDocumentMetadata($documentMetadataList, $documentId)
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
     * Deletes all the metadata from the document given by $documentId
     *
     * @param  mixed $documentId
     * @return void
     */
    public function deleteDocumentMetadata($documentId)
    {
        return $this->repo->deleteDocumentMetadata($documentId);
    }

    public function editDocumentMetadata($documentMetadataList, $document)
    {
        /*$keywords = "";
        foreach ($document_metadataCollection as $document_metadata) {
            $document_metadata = DocumentMetadataMapper::toDTO($document_metadata);
            switch ($document_metadata->metadata_type->id) {
                case 1:
                    $this->document_title = $document_metadata->value;
                    break;
                case 2:
                    $this->document_abstract = $document_metadata->value;
                    break;
                case 3:
                    $keywords .= $document_metadata->value . ', ';
                    break;
                default:
                    break;
            }
            $this->document_keywords = rtrim($keywords, ', ');
        }*/

        $keywordIds = "";
        $keywords = [];
        $abstract = [];
        $title = [];
        foreach ($documentMetadataList as $documentMetadataItem) {
            switch ($documentMetadataItem['metadata_type']['id']) {
                case 1: // Title
                    $title[] = $documentMetadataItem;
                    break;
                case 2: // Abstract
                    $abstract[] = $documentMetadataItem;
                    break;
                case 3: // Keywords
                    $keywords[] = $documentMetadataItem;
                    break;
                default:
                    break;
            }
        }

        foreach ($document->documentMetadata as $documentMetadataItem) {
            switch ($documentMetadataItem->metadata_type_id) {
                case 1: // Title
                    $titleId = $documentMetadataItem->id;
                    break;
                case 2: // Abstract
                    $abstractId = $documentMetadataItem->id;
                    break;
                case 3: // Keywords
                    $keywordIds .= $documentMetadataItem->id;
                    break;
                default:
                    break;
            }
        }

        if (isset($titleId)) {
            return "1";
        } else {
            !empty($title) ? $this->insertDocumentMetadata($title, $document->id) : "";
        }

        if (isset($abstractId)) {
            return "1";
        } else {
            !empty($abstract) ? $this->insertDocumentMetadata($abstract, $document->id) : "";
        }

        if (isset($keywordIds)) {
            return "1";
        } else {
            !empty($keywords) ? $this->insertDocumentMetadata($keywords, $document->id) : "";
        }


        return $document;
        /*$documentMetadata = new DocumentMetadata([
            'document_id' => $documentId,
            'value' => $documentMetadataItem['value'],
            'metadata_type_id' => $documentMetadataItem['metadata_type']['id']
        ]);
        $this->repo->insertDocumentMetadata($documentMetadata);*/
    }
}
