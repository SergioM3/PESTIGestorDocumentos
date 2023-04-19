<?php

namespace App\ApplicationServices\Services;

use Exception;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use App\ApplicationServices\DTO\DocumentDTO;
use App\Domain\Aggregates\Document\Document;
use Illuminate\Database\Eloquent\Collection;
use App\Domain\Aggregates\Document\DocumentType;
use App\Domain\Aggregates\Document\TemporaryFile;
use App\ApplicationServices\DTO\DocumentSubmitDTO;
use App\ApplicationServices\Mappers\DocumentMapper;
use App\ApplicationServices\DTO\DocumentExternalDTO;
use App\ApplicationServices\DTO\DocumentListItemDTO;
use App\ApplicationServices\IServices\IMediaService;
use App\ApplicationServices\IServices\IDocumentService;
use App\ApplicationServices\IServices\IZenodoAPIService;
use App\ApplicationServices\IServices\IDocumentTypeService;
use App\InterfaceAdapters\IRepositories\IDocumentRepository;
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

    public function getDocumentList()
    {
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
    }


    /**
     * ToDo : This function is not meant to return a $documentListItemDTO but instead a
     * documentDTO. However, mapping is not as easy, thus this is going to be pondered if
     * there's a need to implement (only absolutely necessary if there's a need to open
     * the document in our Frontend API, instead of linking to zenodo page)
     *
     * @param  mixed $id
     * @return void
     */
    public function getDocumentById(int $id)
    {
        $response = Http::withoutVerifying()->get('https://zenodo.org/api/records/' . $id, [
            'access_token' => 'hkaMojxKIRnMTND1vrouq4PS3lBv4dfZxXbPyGOKZUbOYV0v7FvApfgTjkng',
        ]);
        $document = $response->json();
        $documentListItemDTO = $this->mapToDocumentListItemDTO($document);
        return $documentListItemDTO;
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
