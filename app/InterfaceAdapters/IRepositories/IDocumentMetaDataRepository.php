<?php

namespace App\InterfaceAdapters\IRepositories;

interface IDocumentMetadataRepository
{
    public function getDocumentMetadataById(int $id);
    public function insertDocumentMetadata($documentMetadata);
    public function deleteDocumentMetadata(int $id);
}
