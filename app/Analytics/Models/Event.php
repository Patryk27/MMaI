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
        TYPE_QUERY_SEARCHED = 'query-searched',
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
