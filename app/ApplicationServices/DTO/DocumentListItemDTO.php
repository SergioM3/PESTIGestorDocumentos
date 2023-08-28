<?php

namespace App\ApplicationServices\DTO;

use Carbon\Carbon;
use App\Domain\Aggregates\Document\DocumentType;
use App\ApplicationServices\Mappers\DocumentTypeMapper;

class DocumentListItemDTO extends DTOAbstract
{
    public int $id;
    public int $user_id;
    public string $document_state;
    public ?string $publish_date;
    public string $document_type;
    public ?string $document_title;
    public ?string $source;
    public ?string $document_abstract = null;
    public ?array $document_keywords = null;
    public ?array $document_authors = null;
    public ?string $document_doi = null;
    public ?string $document_zenodo_id = null;
    public ?string $document_doi_badge = null;
    public ?string $document_doi_url = null;

    public function __construct(
        int $id,
        int $user_id,
        string $publish_date = null,
        DocumentType $document_type,
        string $source = null,
        string $document_title = null,
        string $document_abstract = null,
        array $document_keywords = null,
        array $document_authors = null,
        string $document_doi = null,
        string $document_zenodo_id = null,
        string $document_doi_badge = null,
        string $document_doi_url = null
    ) {
        $this->id = $id;
        $this->user_id = $user_id;
        $this->document_state = ($publish_date > Carbon::now()) ? 'Pending' : 'Published';
        $this->publish_date = $publish_date;
        $this->document_type = DocumentTypeMapper::toDTO($document_type)->description;
        $this->source = isset($source) ? $source : null;

        // Document Meta
        $this->document_title       = isset($document_title)        ? $document_title       : null;
        $this->document_abstract    = isset($document_abstract)     ? $document_abstract    : null;
        $this->document_keywords    = isset($document_keywords)     ? $document_keywords    : null;
        $this->document_authors     = isset($document_authors)      ? $document_authors     : null;
        $this->document_doi         = isset($document_doi)          ? $document_doi         : null;
        $this->document_zenodo_id   = isset($document_zenodo_id)    ? $document_zenodo_id   : null;
        $this->document_doi_badge   = isset($document_doi_badge)    ? $document_doi_badge   : null;
        $this->document_doi_url     = isset($document_doi_url)      ? $document_doi_url     : null;
    }
}
