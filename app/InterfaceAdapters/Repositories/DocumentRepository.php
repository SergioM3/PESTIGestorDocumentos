<?php

namespace App\InterfaceAdapters\Repositories;

use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use App\Domain\Aggregates\Document\Document;
use Illuminate\Database\Eloquent\Builder;
use App\Domain\Aggregates\Document\DocumentType;
use App\ApplicationServices\DTO\DocumentSubmitDTO;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Domain\Aggregates\Metadata\DocumentMetadata;
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

    public function getDocumentsByUserId($userId)
    {
        $query = Document::with('documentType', 'documentMetadata');
        $query = $this->addSearchQueryFilters($query);
        $query = $this->addOrderBy($query);
        return $query->where('user_id', $userId)->paginate(request()->page_size ?? 20); // Paginate according to page_size param, otherwise 20
    }

    /**
     * Returns a list of documents filtered by the get parameters
     * (DOES NOT Allow not published documents)
     *
     * @return
     */
    public function getPublishedDocumentsByFilter()
    {
        $query = Document::with('documentType', 'documentMetadata');
        $query = $this->addSearchQueryFilters($query);
        $query = $this->addPublishedFilter($query);
        $query = $this->addOrderBy($query);
        return $query->paginate(request()->page_size ?? 20); // Paginate according to page_size param, otherwise 20
    }

    /**
     * Returns a list of documents filtered by the get parameters
     * (Allows not published documents)
     *
     * @return
     */
    public function getDocumentsByFilter()
    {
        $query = Document::with('documentType', 'documentMetadata');
        $query = $this->addSearchQueryFilters($query);
        $query = $this->addOrderBy($query);

        return $query->paginate(request()->page_size ?? 20); // Paginate according to page_size param, otherwise 20
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
     * @param  Builder $query
     * @return Builder
     */
    private function addPublishedFilter(Builder $query): Builder
    {
        return $query->whereDate('publish_date', '<=', Carbon::now());
    }
    /**
     * Adds to a query of documents, the filters allowed to be searchable and returns it.
     *
     * @param  Builder $query
     * @return Builder
     */
    private function addSearchQueryFilters(Builder $query): Builder
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

    /**
     * Adds Sorting functionality to the query
     *
     * @param  Builder $query
     * @return Builder
     */
    private function addOrderBy(Builder $query): Builder
    {
        // If the order by is a column existing in the Documents without needing to query a relation table, then orderBy will suffice
        if (Schema::hasColumn("Documents", request()->orderBy)) {
            return $query->orderBy(request()->orderBy, request()->orderDir ?? 'desc');
        } else {
            switch (request()->orderBy) {
                case "title": // Order By Title
                    return $query->orderBy(DocumentMetadata::select('value')->where('metadata_type_id', 1)->whereColumn('document_id', 'documents.id'), request()->orderDir ?? 'desc');
                case "abstract": // Order By abstract
                    return $query->orderBy(DocumentMetadata::select('value')->where('metadata_type_id', 2)->whereColumn('document_id', 'documents.id'), request()->orderDir ?? 'desc');
                case "document_type": // Order By document_type
                    return $query->orderBy(DocumentType::select('description')->whereColumn('id', 'documents.document_type_id'), request()->orderDir ?? 'desc');
                default:
                    return $query;
            }
        }
    }
}
