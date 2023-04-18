<?php

namespace App\ApplicationServices\Mappers;

use App\ApplicationServices\DTO\DocumentTypeDTO;
use App\Domain\Aggregates\Document\DocumentType;

class DocumentTypeMapper
{
    public static function toDTO(DocumentType $document_type): DocumentTypeDTO
    {
        return new DocumentTypeDTO(
            $document_type->id,
            $document_type->description
        );
    }
}
