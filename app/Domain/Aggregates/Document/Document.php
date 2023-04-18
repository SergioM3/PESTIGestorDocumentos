<?php

namespace App\Domain\Aggregates\Document;

use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use App\Domain\Aggregates\Document\DocumentType;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Domain\Aggregates\Metadata\DocumentMetaData;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Document extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'document_type_id',
        'document_state',
        'publish_date',
        'create_date',
        'update_date',
        'delete_date',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'document_type_id' => 'integer',
        'publish_date' => 'datetime',
        'create_date' => 'datetime',
        'update_date' => 'datetime',
        'delete_date' => 'datetime',
    ];

    public function documentType(): BelongsTo
    {
        return $this->belongsTo(DocumentType::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function documentMetadataDownloadRequests(): HasMany
    {
        return $this->hasMany(DocumentMetadataDownloadRequest::class);
    }

    public function documentMetadata(): HasMany
    {
        return $this->hasMany(DocumentMetadata::class);
    }
}
