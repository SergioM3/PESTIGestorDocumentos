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
            $document->user_id,
            $document->document_type_id,
            $document->publish_date,
            $document->documentMetadata,
            $document->temp_document_folder,
            $document->document_filename
        );
    }

    public function toListItemDTO(Document $document): DocumentListItemDTO
    {
        return new DocumentListItemDTO(
            $document->id,
            $document->user_id,
            $document->publish_date,
            $document->documentType,
            $document->documentMetadata
        );
    }
}
