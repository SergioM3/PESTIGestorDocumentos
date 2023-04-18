<?php

namespace App\ApplicationServices\IServices;

use App\ApplicationServices\DTO\DocumentSubmitDTO;

interface IDocumentService
{
    public function getAllDocuments();
    public function getDocumentById(int $id);
    public function submitNewDocument(DocumentSubmitDTO $documentSubmitDTO);
}
