<?php

namespace App\ApplicationServices\Mappers;

use App\ApplicationServices\DTO\MetadataTypeDTO;
use App\Domain\Aggregates\Metadata\MetadataType;

class MetadataTypeMapper
{
    public static function toDTO(MetadataType $metadata_type): MetadataTypeDTO
    {
        return new MetadataTypeDTO(
            $metadata_type->id,
            $metadata_type->description
        );
    }
}
