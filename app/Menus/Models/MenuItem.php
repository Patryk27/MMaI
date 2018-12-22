<?php

namespace App\Menus\Models;

use App\Languages\Models\Language;
use App\Routes\Models\Route;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * This model describes a single main menu's item.
 *
 * -----
 *
 * @property-read int $id
 * @property int $language_id
 * @property int $position
 * @property string $url
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
    /** @var string[] */
    protected $fillable = [
        'language_id',
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
    public function language()
    {
        return $this->belongsTo(Language::class);
    }
}
