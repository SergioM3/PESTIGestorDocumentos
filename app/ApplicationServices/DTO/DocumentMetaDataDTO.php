<?php

namespace App\ApplicationServices\DTO;

use App\Domain\Aggregates\Metadata\MetadataType;
use App\ApplicationServices\Mappers\MetadataTypeMapper;

class DocumentMetadataDTO
{
    public int $id;
    public string $value;
    public MetadataTypeDTO $metadata_type;

    public function __construct(int $id, string $value, MetadataType $metadata_type)
    {
        $this->id = $id;
        $this->value = $value;
        $this->metadata_type = MetadataTypeMapper::toDTO($metadata_type);
    }
}
