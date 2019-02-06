<?php

namespace App\Websites\Models;

use App\Core\Models\Model;
use App\Languages\Models\Language;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property-read int $id
 * @property int $language_id
 * @property string $slug
 * @property string $name
 * @property string $description
 * @property-read Carbon $created_at
 * @property-read Carbon $updated_at
 *
 * -----
 *
 * @property-read Language $language
 */
class Website extends Model {

    /** @var string[] */
    protected $fillable = [
        'language_id',
        'slug',
        'name',
        'description',
    ];

    /** @var string[] */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /**
     * @return BelongsTo
     */
    public function language() {
        return $this->belongsTo(Language::class);
    }

}
