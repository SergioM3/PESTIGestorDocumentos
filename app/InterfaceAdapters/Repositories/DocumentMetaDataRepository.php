<?php

namespace App\InterfaceAdapters\Repositories;

use App\Domain\Aggregates\Metadata\DocumentMetadata;
use App\InterfaceAdapters\IRepositories\IDocumentMetadataRepository;

class DocumentMetadataRepository implements IDocumentMetadataRepository
{
    public function getDocumentMetadataById(int $id)
    {
        return DocumentMetadata::with('metadataType')->where('document_id', $id)->get();
    }

    public function insertDocumentMetadata($documentMetadata): void
    {
        $documentMetadata->save();
    }

    public function deleteDocumentMetadata(int $id)
    {
        DocumentMetadata::where('document_id', $id)->delete();
    }
}
