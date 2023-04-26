<?php

namespace App\ApplicationServices\IServices;

interface IDocumentMetadataService
{
    /**
     * Returns Metadata list of a document by it's id
     *
     * @param  int $id
     * @return array
     */
    public function getDocumentMetadataById(int $id): array;

    /**
     * Inserts given metadata into a document by it's id
     *
     * @param  array $documentMetadataList
     * @param  int $documentId
     * @return void
     */
    public function insertDocumentMetadata(array $documentMetadataList, int $documentId): void;

    /**
     * Deletes all the metadata from the document given by document Id
     *
     * @param  int $id
     * @return void
     */
    public function deleteDocumentMetadata(int $id): void;
}
