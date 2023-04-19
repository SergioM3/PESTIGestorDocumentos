<?php

namespace App\ApplicationServices\DTO;

use App\Domain\Aggregates\Document\DocumentType;
use App\Domain\Aggregates\Metadata\DocumentMetaData;
use App\ApplicationServices\Mappers\DocumentTypeMapper;
use App\ApplicationServices\Mappers\DocumentMetadataMapper;
use Illuminate\Database\Eloquent\Collection;

class DocumentDTO extends DTOAbstract
{
    public int $id;
    public ?int $user_id;
    public string $document_state;
    public string $publish_date;
    public string $create_date;
    public ?string $update_date; // Allows null
    public ?string $delete_date; // Allows null
    public DocumentTypeDTO $document_type;
    public array $document_metadata;
    public $document_media; // Document Media instance
    public $document_file = null;  // Document File binary encoded in base64 (should default to null and be set in the service after decrypting)

    public function __construct(
        int $id,
        ?int $user_id,
        string $document_state,
        ?string $publish_date,
        ?string $create_date,
        ?string $update_date,
        ?string $delete_date,
        DocumentType $document_type,
        Collection $document_metadataCollection,
        $document_media
    ) {
        $this->id = $id;
        $this->user_id = $user_id;
        $this->document_state = $document_state;
        $this->publish_date = $publish_date;
        $this->create_date = $create_date;
        $this->update_date = $update_date;
        $this->delete_date = $delete_date;
        $this->document_type = DocumentTypeMapper::toDTO($document_type);

        $this->document_metadata = [];
        foreach ($document_metadataCollection as $document_metadata) {
            array_push($this->document_metadata, DocumentMetadataMapper::toDTO($document_metadata));
        }

        $this->document_media = $document_media;
    }
}
