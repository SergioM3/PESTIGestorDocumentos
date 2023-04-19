<?php

namespace App\InterfaceAdapters\Repositories;

use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
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

    public function editDocument($documentSubmitDTO, $document)
    {
        // Create an array of columns to be updated based on the $documentSubmitDTO object
        $updateData = [];

        foreach ($documentSubmitDTO as $key => $value) {
            // Only add the column to the updateData array if it exists in the table
            if (Schema::hasColumn('documents', $key)) {
                $updateData[$key] = $value;
            }
        }

        // Update the record with the new values
        $document->update($updateData);

        return $document;
    }

    public function deleteDocument($id)
    {
        Document::where('id', $id)->delete();
    }
}
