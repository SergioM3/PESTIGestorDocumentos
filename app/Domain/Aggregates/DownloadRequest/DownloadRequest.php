<?php

namespace App\Domain\Aggregates\DownloadRequest;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DownloadRequest extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'document_id',
        'request_state',
        'accept_date',
        'reject_date',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'accept_date' => 'datetime',
        'reject_date' => 'datetime',
    ];

    public function userDocument(): BelongsTo
    {
        return $this->belongsTo(UserDocument::class);
    }
}
