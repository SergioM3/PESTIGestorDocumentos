<?php

namespace App\InterfaceAdapters\Repositories;

use Carbon\Carbon;
use App\Domain\Aggregates\Document\Document;
use App\ApplicationServices\DTO\DocumentSubmitDTO;
use App\InterfaceAdapters\IRepositories\IDocumentRepository;

class DocumentRepository implements IDocumentRepository
{
    public function getAllDocuments()
    {
        return Document::with('documentType', 'documentMetadata')->get();
    }

    public function getDocumentById(int $id)
    {
        return Document::with('documentType', 'documentMetadata')
                        ->where('id', $id)->first();
    }

    public function insertNewDocument(Document $document)
    {
        $document->save();

        return $document;
    }
}
