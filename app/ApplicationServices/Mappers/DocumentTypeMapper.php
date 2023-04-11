<?php

namespace App\ApplicationServices\Mappers;

use App\Domain\Aggregates\Document\DocumentType;
use App\ApplicationServices\DTO\DocumentTypeDTO;

class DocumentTypeMapper
{
    public function toDTO(DocumentType $documentType): DocumentTypeDTO
    {
        return new DocumentTypeDTO(
            $documentType->id,
            $documentType->description
        );
    }
}
