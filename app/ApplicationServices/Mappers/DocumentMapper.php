<?php

namespace App\ApplicationServices\Mappers;

use Illuminate\Support\Collection;
use App\ApplicationServices\DTO\DocumentDTO;
use App\Domain\Aggregates\Document\Document;
use App\ApplicationServices\DTO\MapperAbstract;
use App\ApplicationServices\DTO\DocumentListDTO;
use App\ApplicationServices\DTO\DocumentSubmitDTO;
use App\ApplicationServices\DTO\DocumentListItemDTO;

class DocumentMapper
{
    public function toDTO(Document $document): DocumentDTO
    {
        return new DocumentDTO(
            $document->id,
            $document->user_id,
            $document->document_state,
            $document->publish_date,
            $document->create_date,
            $document->update_date,
            $document->delete_date,
            $document->documentType,
            $document->documentMetadata,
            $document->getFirstMedia()
        );
    }

    public function toSubmitDTO(Document $document): DocumentSubmitDTO
    {
        return new DocumentSubmitDTO(
            $document->document_type_id,
            $document->publish_date,
            $document->documentMetadata,
            $document->temp_document_folder,
            $document->document_filename
        );
    }

    public function toListItemDTO(Document $document): DocumentListItemDTO
    {
        // Maps records in DocumentMetadata of the document to it's metadata strings
        $document_keywords = [];
        $document_authors = [];
        foreach ($document->documentMetadata as $document_metadata) {
            $document_metadata = DocumentMetadataMapper::toDTO($document_metadata);
            switch ($document_metadata->metadata_type->id) {
                case 1:
                    $document_title = $document_metadata->value;
                    break;
                case 2:
                    $document_abstract = $document_metadata->value;
                    break;
                case 3:
                    $document_keywords[] = $document_metadata->value;
                    break;
                case 4:
                    $document_authors[] = $document_metadata->value;
                    break;
                default:
                    break;
            }
        }

        return new DocumentListItemDTO(
            $document->id,
            $document->user_id,
            $document->publish_date,
            $document->documentType,
            "tempDoi", // ToDo - Add Doi to document model and retreive it here
            null, // url of internal document must be null
            "Internal", // Source
            isset($document_title)    ? $document_title     : null,
            isset($document_abstract) ? $document_abstract  : null,
            isset($document_keywords) ? $document_keywords  : null,
            isset($document_authors)  ? $document_authors   : null,
        );
    }
}
