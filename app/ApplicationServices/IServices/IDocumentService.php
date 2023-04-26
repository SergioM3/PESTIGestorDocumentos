<?php

namespace App\ApplicationServices\IServices;

use App\ApplicationServices\DTO\DocumentDTO;
use App\ApplicationServices\DTO\DocumentSubmitDTO;

interface IDocumentService
{
    /**
     * Returns list of internal Documents
     * If user is admin, returns pending too, otherwise returns just published documents
     *
     * @return array
     */
    public function getDocumentById(int $id): DocumentDTO;

    /**
     * Returns Document details (DocumentDTO) of a document, including file binary, by id
     *
     * @param  int $id
     * @return DocumentDTO
     */
    public function getDocumentsByUserId(int $userId): array;

    /**
     * Returns list of all documents submitted by user
     *
     * @param  int $userId
     * @return array
     */
    public function getDocumentsByFilter(): array;

    /**
     * Submits a new document according to documentSubmitDTO request data
     * requires that temporary file gets created first by calling api/temp_file
     *
     * @param  DocumentSubmitDTO $documentSubmitDTO
     * @return string
     */
    public function submitNewDocument(DocumentSubmitDTO $documentSubmitDTO): string;

    /**
     * Edits document of user, or any document if user is admin
     *
     * @param  array $documentSubmitDTO #JSON of the request mapped as DocumentSubmitDTO
     * @param  int $id # Id of the document to edit
     * @return string
     */
    public function editDocument(array $documentSubmitDTO, int $id): string;

    /**
     * HARD Deletes a document owned by the user, or any if user is admin
     *
     * @param  int $id # Document id of the document to be deleted
     * @return string
     */
    public function deleteDocument(int $id): string;
}
