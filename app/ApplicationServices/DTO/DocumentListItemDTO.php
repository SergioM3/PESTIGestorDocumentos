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
    public ?string $doi;
    public ?string $url;
    public ?string $source;
    public ?string $document_abstract = null;
    public ?array $document_keywords = null;
    public ?array $document_authors = null;

    public function __construct(
        int $id,
        int $user_id,
        string $publish_date = null,
        DocumentType $document_type,
        string $doi = null,
        string $url = null,
        string $source = null,
        string $document_title = null,
        string $document_abstract = null,
        array $document_keywords = null,
        array $document_authors = null
    ) {
        $this->id = $id;
        $this->user_id = $user_id;
        $this->document_state = ($publish_date > Carbon::now()) ? 'Pending' : 'Published';
        $this->publish_date = $publish_date;
        $this->document_type = DocumentTypeMapper::toDTO($document_type)->description;

        $this->doi = isset($doi) ? $doi : null;
        $this->url = isset($url) ? $url : null;
        $this->source = isset($source) ? $source : null;

        // Document Meta
        $this->document_title    = isset($document_title)    ? $document_title      : null;
        $this->document_abstract = isset($document_abstract) ? $document_abstract   : null;
        $this->document_keywords = isset($document_keywords) ? $document_keywords   : null;
        $this->document_authors  = isset($document_authors)  ? $document_authors    : null;
    }
}
