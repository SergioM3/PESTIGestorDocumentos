<?php

namespace App\ApplicationServices\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use App\Domain\Aggregates\Document\DocumentType;
use App\ApplicationServices\DTO\DocumentListItemDTO;
use App\ApplicationServices\IServices\IZenodoAPIService;
use App\ApplicationServices\IServices\IDocumentTypeService;
use App\ApplicationServices\IServices\IDocumentMetadataService;

class ZenodoAPIService implements IZenodoAPIService
{
    private $documentMetadataService;
    private $documentTypeService;

    public function __construct(
        IDocumentMetadataService $documentMetadataService,
        IDocumentTypeService $documentTypeService
    ) {
        $this->documentMetadataService = $documentMetadataService;
        $this->documentTypeService = $documentTypeService;
    }

    /**
     * Returns an array of zenodo documents from it's API get request formated as a DocumentListDTO
     * This array is cached by the number of seconds configured in the .env file
     *
     * @return DocumentListDTO[]
     */
    public function getDocumentList()
    {
        //Cache::forget('zenodoDocumentList');
        return Cache::remember('zenodoDocumentList', env("CACHE_INTERVAL_ZENODO_DOCUMENT_LIST"), function () {
            $response = Http::withoutVerifying()->get('https://zenodo.org/api/records', [
                'access_token' => 'hkaMojxKIRnMTND1vrouq4PS3lBv4dfZxXbPyGOKZUbOYV0v7FvApfgTjkng',
                'size' => 10,
            ]);
            $documents = $response->json()["hits"]['hits'];
            $documentListDTO = [];

            foreach ($documents as $document) {
                $documentListDTO[] = $this->mapToDocumentListItemDTO($document);
            }
            return $documentListDTO;
        });
    }


    /**
     * ToDo : This function is not meant to return a $documentListItemDTO but instead a
     * documentDTO. However, mapping is not as easy, thus this is going to be pondered if
     * there's a need to implement
     *
     * @param  mixed $id
     * @return void
     */
    public function getDocumentById(int $id)
    {
        return Cache::remember('zenodoDocumentItem' . $id, env("CACHE_INTERVAL_ZENODO_DOCUMENT_ITEM"), function () use ($id) {
            $response = Http::withoutVerifying()->get('https://zenodo.org/api/records/' . $id, [
                'access_token' => 'hkaMojxKIRnMTND1vrouq4PS3lBv4dfZxXbPyGOKZUbOYV0v7FvApfgTjkng',
            ]);
            $document = $response->json();
            $documentListItemDTO = $this->mapToDocumentListItemDTO($document);
            return $documentListItemDTO;
        });
    }

    /**
     * Returns a zenodo document formatted in DocumentListItemDTO.
     * Given this is an external API, the response format might change and
     * depending on what is changed this function may have to be adapted.
     * On the Readme file is an example of a response to the request to get a zenodo document
     * by the time this function has been implemented
     *
     * @param  string $zenodoDocument
     * @return void
     */
    private function mapToDocumentListItemDTO($zenodoDocument) : DocumentListItemDTO
    {
        $title = $zenodoDocument['metadata']['title'] ?? null;
        $abstract = $zenodoDocument['metadata']['description'] ?? null;
        $keywords = $zenodoDocument['metadata']['keywords'] ?? [];
        $authors = [];
        foreach ($zenodoDocument['metadata']['creators'] as $author) {
            if (isset($author["name"]) && isset($author["affiliation"])) {
                $authors[] = $author["name"] . ' - ' . $author["affiliation"];
            } else if (isset($author["name"])) {
                $authors[] = $author["name"];
            }
        }

        $doi = $zenodoDocument['doi'] ?? null;
        $url = $zenodoDocument['links']['html'] ?? null;

        $user_id = 1;
        $publish_date = $zenodoDocument['metadata']['publication_date'] ?? null;
        $document_type = new DocumentType();
        $document_type->id = 1;
        $document_type->description = $zenodoDocument['metadata']['resource_type']['type'] ?? null;
        return new DocumentListItemDTO($zenodoDocument["conceptrecid"] ?? null, $user_id, $publish_date, $document_type, $doi, $url, "Zenodo", $title, $abstract, $keywords, $authors);
    }
}
