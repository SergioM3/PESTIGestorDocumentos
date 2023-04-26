<?php

namespace App\ApplicationServices\IServices;

interface IDocumentTypeService
{
    /**
     * Returns List of all document types
     *
     * @return array    Type of DocumentTypeDTO
     */
    public function getAllDocumentTypes(): array;
}
