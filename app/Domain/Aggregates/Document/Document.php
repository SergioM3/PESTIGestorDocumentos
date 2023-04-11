<?php

namespace App\Domain\Aggregates\Document;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Document extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'documenttype_id',
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
        'publish_date' => 'datetime',
        'create_date' => 'datetime',
        'update_date' => 'datetime',
        'delete_date' => 'datetime',
    ];

    public function documentType(): HasOne
    {
        return $this->hasOne(DocumentType::class);
    }

    public function documentMetaDataDownloadRequests(): HasMany
    {
        return $this->hasMany(DocumentMetaDataDownloadRequest::class);
    }
}
