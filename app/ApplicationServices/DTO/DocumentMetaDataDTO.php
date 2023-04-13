<?php

namespace App\ApplicationServices\DTO;

use App\Domain\Aggregates\Metadata\MetaDataType;
use App\ApplicationServices\Mappers\MetaDataTypeMapper;

class DocumentMetaDataDTO
{
    public int $id;
    public string $value;
    public MetaDataTypeDTO $metadata_type;

    public function __construct(int $id, string $value, MetaDataType $metadata_type)
    {
        $this->id = $id;
        $this->value = $value;
        $this->metadata_type = MetaDataTypeMapper::toDTO($metadata_type);
    }
}
