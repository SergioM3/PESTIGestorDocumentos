<?php

namespace App\InterfaceAdapters\IRepositories;

use App\Domain\Aggregates\Document\Document;
use App\ApplicationServices\DTO\DocumentSubmitDTO;

interface IDocumentRepository
{
    public function getAllDocuments();
    public function getDocumentById(int $id);
    public function searchPublishedDocumentsByFilter();
    public function searchDocumentsByFilter();
    public function insertNewDocument(Document $document);
    public function editDocument($documentSubmitDTO, Document $document);
    public function deleteDocument(int $id);
}
