<?php

namespace App\InterfaceAdapters\Repositories;

use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use App\Domain\Aggregates\Document\Document;
use Illuminate\Database\Eloquent\Builder;
use App\Domain\Aggregates\Document\DocumentType;
use App\Domain\Aggregates\Metadata\DocumentMetadata;
use App\InterfaceAdapters\IRepositories\IDocumentRepository;

class DocumentRepository implements IDocumentRepository
{
    /**
     * Returns a list of all documents owned by a user
     *
     * @param  int $userId
     * @return Illuminate\Pagination\LengthAwarePaginator
     */
    public function getDocumentsByUserId(int $userId)
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
     * @return Illuminate\Pagination\LengthAwarePaginator
     */
    public function getPublishedDocumentsByFilter()
    {
        $query = Document::with('documentType', 'documentMetadata');
        $query = $this->addSearchQueryFilters($query);
        $query = $this->addPublishedFilter($query);
        $query = $this->addOrderBy($query);
        //dd(gettype($query));
        return $query->paginate(request()->page_size ?? 20); // Paginate according to page_size param, otherwise 20
    }

    /**
     * Returns a list of documents filtered by the get parameters
     * (Allows not published documents)
     *
     * @return Illuminate\Pagination\LengthAwarePaginator
     */
    public function getDocumentsByFilter()
    {
        $query = Document::with('documentType', 'documentMetadata');
        $query = $this->addSearchQueryFilters($query);
        $query = $this->addOrderBy($query);

        return $query->paginate(request()->page_size ?? 20); // Paginate according to page_size param, otherwise 20
    }

    /**
     * Returns a Document from database by it's id
     *
     * @param  int $id
     * @return Document
     */
    public function getDocumentById(int $id): Document
    {
        return Document::with('documentType', 'documentMetadata')
                        ->where('id', $id)->first();
    }

    /**
     * Persists a given Document
     *
     * @param  Document $document
     * @return Document
     */
    public function insertNewDocument(Document $document): void
    {
        $document->save();
    }

    /**
     * Edit a document and returns it
     *
     * @param  array $documentSubmitDTO # JSON of the request mapped as a documentSubmitDTO object
     * @param  Document $document # Document to be edited
     * @return Document
     */
    public function editDocument(array $documentSubmitDTO, Document $document): Document
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

    /**
     * HARD deletes document by it's id
     *
     * @param  int $id
     * @return void
     */
    public function deleteDocument(int $id): void
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
