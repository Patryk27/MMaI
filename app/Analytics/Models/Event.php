<?php

namespace App\Analytics\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property-read int $id
 * @property string $type
 * @property array $payload
 * @property-read Carbon $created_at
 * @property-read Carbon $updated_at
 */
class Event extends Model
{

    public const
        TYPE_ATTACHMENT_DOWNLOADED = 'attachment-downloaded',
        TYPE_EXCEPTION_CAUGHT = 'exception-caught',
        TYPE_PAGE_VISITED = 'page-visited',
        TYPE_TERM_SEARCHED = 'term-searched',
        TYPE_REQUEST_FAILED = 'request-failed',
        TYPE_REQUEST_SERVED = 'request-served';

    /**
     * @var string[]
     */
    protected $fillable = [
        'type',
        'payload',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'payload' => 'json',
    ];

    /**
     * @var string[]
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

}
