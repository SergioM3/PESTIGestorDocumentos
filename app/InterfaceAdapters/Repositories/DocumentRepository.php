<?php

namespace App\InterfaceAdapters\Repositories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Schema;
use App\Domain\Aggregates\Document\Document;
use App\ApplicationServices\DTO\DocumentSubmitDTO;
use App\InterfaceAdapters\IRepositories\IDocumentRepository;

class DocumentRepository implements IDocumentRepository
{
    public function getAllDocuments()
    {
        return Document::with('documentType', 'documentMetadata')
                            ->get();
    }

    public function searchDocumentsByFilter()
    {
        return Document::with('documentType', 'documentMetadata')
                        ->whereHas('documentMetadata', function ($query) {
                            if (request()->has('title')) {
                                $query->where('metadata_type_id', 1)
                                      ->where('value', 'LIKE', '%' . request()->input('title') . '%');
                            }
                        })
                        ->whereHas('documentMetadata', function ($query) {
                            if (request()->has('abstract')) {
                                $query->where('metadata_type_id', 2)
                                      ->where('value', 'LIKE', '%' . request()->input('abstract') . '%');
                            }
                        })
                        ->whereHas('documentType', function ($query) {
                            if (request()->has('document_type')) {
                                $query->where('description', 'LIKE', '%' . request()->input('document_type') . '%');
                            }
                        })
                        ->when(request()->has('document_state'), function ($query) {
                            switch (request()->input('document_state')) {
                                case ('Pending'):
                                    $query->whereDate('publish_date', '>', Carbon::now());
                                    break;
                                case ('Published'):
                                    $query->whereDate('publish_date', '<=', Carbon::now());
                                    break;
                                default:
                                    break;
                            }
                        })
                        ->when(request()->has('publish_date_from'), function ($query) {
                            $query->whereDate('publish_date', '>=', request()->input('publish_date_from'));
                        })
                        ->when(request()->has('publish_date_to'), function ($query) {
                            $query->whereDate('publish_date', '<=', request()->input('publish_date_to'));
                        })->get();
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
