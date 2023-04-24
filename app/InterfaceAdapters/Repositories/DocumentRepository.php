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
    /**
     * Gets all Documents - Just here for debuging/testing... will remove soon
     *
     * @return void
     */
    public function getAllDocuments()
    {
        return Document::with('documentType', 'documentMetadata')
                            ->get();
    }

    /**
     * Returns a list of documents filtered by the get parameters
     * (DOES NOT Allow not published documents)
     *
     * @return
     */
    public function searchPublishedDocumentsByFilter()
    {
        $query = Document::with('documentType', 'documentMetadata');
        $query = $this->addSearchQueryFilters($query);
        $query = $this->addPublishedFilter($query);

        return $query->get();
    }

    /**
     * Returns a list of documents filtered by the get parameters
     * (Allows not published documents)
     *
     * @return
     */
    public function searchDocumentsByFilter()
    {
        $query = Document::with('documentType', 'documentMetadata');
        $query = $this->addSearchQueryFilters($query);

        return $query->get();
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

    /**
     * Adds to a query of documents, the condition to return just published documents.
     * This way enforcing it only to show the published documents on the functions where it should
     * be the case
     *
     * @param  mixed $query
     * @return object
     */
    private function addPublishedFilter($query)
    {
        return $query->whereDate('publish_date', '<=', Carbon::now());
    }
    /**
     * Adds to a query of documents, the filters allowed to be searchable and returns it.
     *
     * @param  mixed $query
     * @return object
     */
    private function addSearchQueryFilters($query)
    {
        return $query->whereHas('documentMetadata', function ($query) {
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
        });
    }
}
