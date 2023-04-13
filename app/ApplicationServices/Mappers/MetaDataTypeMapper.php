<?php

namespace App\ApplicationServices\Mappers;

use App\ApplicationServices\DTO\MetaDataTypeDTO;
use App\Domain\Aggregates\Metadata\MetaDataType;

class MetaDataTypeMapper
{
    public static function toDTO(MetaDataType $metadata_type): MetaDataTypeDTO
    {
        return new MetaDataTypeDTO(
            $metadata_type->description
        );
    }
}
