<?php

namespace App\ApplicationServices\IServices;

interface IDocumentMetadataService
{
    public function getDocumentMetadataById(int $id);
    public function insertDocumentMetadata($documentMetadataList, $documentId);
    public function editDocumentMetadata($documentMetadataList, $document);
    public function deleteDocumentMetadata($documentId);
}
