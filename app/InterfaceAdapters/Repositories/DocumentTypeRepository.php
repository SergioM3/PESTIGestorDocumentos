<?php

namespace App\InterfaceAdapters\Repositories;

use App\Domain\Aggregates\Document\DocumentType;
use App\InterfaceAdapters\IRepositories\IDocumentTypeRepository;

class DocumentTypeRepository implements IDocumentTypeRepository
{
    public function getAllDocumentTypes() {
        return DocumentType::all();
    }
}
