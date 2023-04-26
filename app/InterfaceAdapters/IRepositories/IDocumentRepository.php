<?php

namespace App\InterfaceAdapters\IRepositories;

use App\Domain\Aggregates\Document\Document;

interface IDocumentRepository
{
    /**
     * Returns a Document from database by it's id
     *
     * @param  int $id
     * @return Document
     */
    public function getDocumentById(int $id): Document;

    /**
     * Returns a list of all documents owned by a user
     *
     * @param  int $userId
     * @return Illuminate\Pagination\LengthAwarePaginator
     */
    public function getDocumentsByUserId(int $userId);

    /**
     * Returns a list of documents filtered by the get parameters
     * (DOES NOT Allow not published documents)
     *
     * @return Illuminate\Pagination\LengthAwarePaginator
     */
    public function getPublishedDocumentsByFilter();

    /**
     * Returns a list of documents filtered by the get parameters
     * (Allows not published documents)
     *
     * @return Illuminate\Pagination\LengthAwarePaginator
     */
    public function getDocumentsByFilter();

    /**
     * Persists a given Document
     *
     * @param  Document $document
     * @return Document
     */
    public function insertNewDocument(Document $document): void;

    /**
     * Edit a document and returns it
     *
     * @param  array $documentSubmitDTO # JSON of the request mapped as a documentSubmitDTO object
     * @param  Document $document # Document to be edited
     * @return Document
     */
    public function editDocument(array $documentSubmitDTO, Document $document): Document;

    /**
     * HARD deletes document by it's id
     *
     * @param  int $id
     * @return void
     */
    public function deleteDocument(int $id);
}
