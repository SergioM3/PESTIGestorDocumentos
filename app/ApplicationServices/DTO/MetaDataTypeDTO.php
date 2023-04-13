<?php

namespace App\ApplicationServices\DTO;

class MetaDataTypeDTO
{
    public string $description;

    public function __construct(string $description)
    {
        $this->description = $description;
    }
}
