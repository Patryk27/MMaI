<?php

namespace App\Models;

use App\Exceptions\Exception;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * This model describes a single main menu's item.
 *
 * It may point either at any arbitrary URL (utilizing `url` property), or
 * at one of the site's routes (utilizing `route_id` property).
 *
 * -----
 *
 * @property-read int $id
 * @property int $language_id
 * @property int $position
 * @property int|null $route_id
 * @property string|null $url
 * @property string $title
 * @property-read Carbon $created_at
 * @property-read Carbon $updated_at
 *
 * -----
 *
 * @property-read Language $language
 * @property-read Route|null $route
 */
class MenuItem extends Model
{

    /**
     * @var string[]
     */
    protected $fillable = [
        'language_id',
        'position',
        'route_id',
        'url',
        'title',
    ];

    /**
     * @var string[]
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function language()
    {
        return $this->belongsTo(Language::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function route()
    {
        return $this->belongsTo(Route::class);
    }

    /**
     * Returns URL to which this menu item points at.
     *
     * @return string
     *
     * @throws Exception
     */
    public function getTargetUrl(): string
    {
        if (isset($this->url)) {
            return $this->url;
        }

        if (isset($this->route_id)) {
            return $this->route->getTargetUrl();
        }

        throw new Exception(
            sprintf('Menu item with [id = %d] has no valid target URL.', $this->id)
        );
    }

}