<?php

namespace App\ApplicationServices\Mappers;

use App\Domain\Aggregates\Metadata\DocumentMetaData;
use App\ApplicationServices\DTO\DocumentMetaDataDTO;

class DocumentMetaDataMapper
{
    public function toDTO(DocumentMetaData $documentType): DocumentMetaDataDTO
    {
        return new DocumentMetaDataDTO(
            $documentType->id,
            $documentType->value,
            $documentType->metadataType
        );
    }
}
