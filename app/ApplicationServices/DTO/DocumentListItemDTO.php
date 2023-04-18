<?php

namespace App\ApplicationServices\DTO;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use App\Domain\Aggregates\Document\DocumentType;
use App\Domain\Aggregates\Metadata\DocumentMetaData;
use App\ApplicationServices\Mappers\DocumentTypeMapper;
use App\ApplicationServices\Mappers\DocumentMetadataMapper;

class DocumentListItemDTO extends DTOAbstract
{
    public int $id;
    public int $user_id;
    public string $document_state;
    public string $publish_date;
    public string $document_type;
    public string $document_title;
    public ?string $document_abstract = null;
    public ?string $document_keywords = null;

    public function __construct(
        int $id,
        int $user_id,
        ?string $publish_date,
        DocumentType $document_type,
        Collection $document_metadataCollection
    ) {
        $this->id = $id;
        $this->user_id = $user_id;
        $this->document_state = ($publish_date > Carbon::now()) ? 'Pending' : 'Published';
        $this->publish_date = $publish_date;
        $this->document_type = DocumentTypeMapper::toDTO($document_type)->description;


        $keywords = "";
        foreach ($document_metadataCollection as $document_metadata) {
            $document_metadata = DocumentMetadataMapper::toDTO($document_metadata);
            switch ($document_metadata->metadata_type->id) {
                case 1:
                    $this->document_title = $document_metadata->value;
                    break;
                case 2:
                    $this->document_abstract = $document_metadata->value;
                    break;
                case 3:
                    $keywords .= $document_metadata->value . ', ';
                    break;
                default:
                    break;
            }
            $this->document_keywords = rtrim($keywords, ', ');
        }


    }
}
