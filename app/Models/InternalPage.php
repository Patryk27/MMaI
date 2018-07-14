<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * This model describes an internal page.
 *
 * Internal pages are pages which have to exist, but are not modifiable by the
 * user (e.g. home page, search page).
 *
 * -----
 *
 * @property-read int $id
 * @property string $type
 * @property-read Carbon $created_at
 * @property-read Carbon $updated_at
 */
class InternalPage extends Model implements Interfaces\Morphable
{

    const
        TYPE_HOME = 'home',
        TYPE_SEARCH = 'search';

    /**
     * @var string[]
     */
    protected $fillable = [
        'type',
    ];

    /**
     * @var string[]
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /**
     * @inheritDoc
     */
    public function getMorphableId(): int
    {
        return $this->id;
    }

    /**
     * @inheritDoc
     */
    public static function getMorphableType(): string
    {
        return 'internal-page';
    }

}