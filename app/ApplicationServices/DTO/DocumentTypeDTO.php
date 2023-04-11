<?php

namespace App\ApplicationServices\DTO;

class DocumentTypeDTO
{
    public int $id;
    public string $description;
    
    public function __construct(int $id, string $description)
    {
        $this->id = $id;
        $this->description = $description;
    }
}
