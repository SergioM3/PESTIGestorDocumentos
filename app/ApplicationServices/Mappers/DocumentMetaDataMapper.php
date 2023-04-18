<?php

namespace App\ApplicationServices\Mappers;

use App\Domain\Aggregates\Metadata\DocumentMetadata;
use App\ApplicationServices\DTO\DocumentMetadataDTO;

class DocumentMetadataMapper
{
    public static function toDTO(DocumentMetadata $document_metadata): DocumentMetadataDTO
    {
        return new DocumentMetadataDTO(
            $document_metadata->id,
            $document_metadata->value,
            $document_metadata->metadataType
        );
    }
}
