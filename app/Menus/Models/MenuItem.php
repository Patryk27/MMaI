<?php

namespace App\Menus\Models;

use App\Routes\Models\Route;
use App\Websites\Models\Website;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * This model describes a single main menu's item.
 *
 * -----
 *
 * @property-read int $id
 * @property int $website_id
 * @property int $position
 * @property string $url
 * @property string $title
 * @property-read Carbon $created_at
 * @property-read Carbon $updated_at
 *
 * -----
 *
 * @property-read Route|null $route
 * @property-read Website $website
 */
class MenuItem extends Model {
    /** @var string[] */
    protected $fillable = [
        'website_id',
        'position',
        'url',
        'title',
    ];

    /** @var string[] */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function website() {
        return $this->belongsTo(Website::class);
    }
}
