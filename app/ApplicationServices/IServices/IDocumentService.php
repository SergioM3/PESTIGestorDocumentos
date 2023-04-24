<?php

namespace App\ApplicationServices\IServices;

use App\ApplicationServices\DTO\DocumentSubmitDTO;

interface IDocumentService
{
    public function getDocumentList($sortBy, $sortDir, $docsPerPage, $pageNumber);
    public function getDocumentById(int $id);
    public function searchDocumentsByFilter();
    public function submitNewDocument(DocumentSubmitDTO $documentSubmitDTO);
    public function editDocument($documentSubmitDTO, $id);
    public function deleteDocument($id);
}
