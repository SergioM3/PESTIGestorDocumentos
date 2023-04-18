<?php

namespace App\Domain\Aggregates\Metadata;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentMetadata extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'document_id',
        'metadata_type_id',
        'value',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'document_id' => 'integer',
        'metadata_type_id' => 'integer',
    ];

    public function documentMetadataType(): BelongsTo
    {
        return $this->belongsTo(DocumentMetadataType::class);
    }

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }

    public function metadataType(): BelongsTo
    {
        return $this->belongsTo(MetadataType::class);
    }
}
