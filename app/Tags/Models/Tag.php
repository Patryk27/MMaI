<?php

namespace App\Tags\Models;

use App\Pages\Models\Page;
use App\Websites\Models\Website;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Model;

/**
 * This model describes a single tag.
 *
 * -----
 *
 * @property-read int $id
 * @property int $website_id
 * @property string $name
 * @property-read Carbon $created_at
 * @property-read Carbon $updated_at
 *
 * -----
 *
 * @property-read EloquentCollection|Page[] $pages
 * @property-read Website $website
 */
class Tag extends Model {

    /** @var string[] */
    protected $fillable = [
        'website_id',
        'name',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function pages() {
        return $this->belongsToMany(Page::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function website() {
        return $this->belongsTo(Website::class);
    }

}
