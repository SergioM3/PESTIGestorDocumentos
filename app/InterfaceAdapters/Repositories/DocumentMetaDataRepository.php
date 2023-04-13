<?php

namespace App\InterfaceAdapters\Repositories;

use App\Domain\Aggregates\Metadata\DocumentMetaData;
use App\InterfaceAdapters\IRepositories\IDocumentMetaDataRepository;

class DocumentMetaDataRepository implements IDocumentMetaDataRepository
{
    public function getDocumentMetaDataById(int $id)
    {
        return DocumentMetaData::with('metadataType')->where('document_id', $id)->get();
    }
}
