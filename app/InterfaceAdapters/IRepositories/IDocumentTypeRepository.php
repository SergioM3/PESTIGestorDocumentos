<?php

namespace App\InterfaceAdapters\IRepositories;

use App\Domain\Aggregates\Document\DocumentType;

interface IDocumentTypeRepository
{
    public function getAllDocumentTypes();
}
