<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * This model describes a single tag.
 *
 * -----
 *
 * @property-read int $id
 * @property int $language_id
 * @property string $name
 * @property-read Carbon $created_at
 * @property-read Carbon $updated_at
 *
 * -----
 *
 * @property-read Language $language
 */
class Tag extends Model
{

    /**
     * @var string[]
     */
    protected $fillable = [
        'language_id',
        'name',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function language()
    {
        return $this->belongsTo(Language::class);
    }

}