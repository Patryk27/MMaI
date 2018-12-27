<?php

namespace App\Languages\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * This model describes a single language.
 *
 * Example:
 *   iso639_code  | pl
 *   iso3166_code | pl
 *   english_name | Poland
 *   native_nane  | Polska
 *
 * -----
 *
 * @property-read int $id
 * @property string $iso639_code
 * @property string $iso3166_code
 * @property string $english_name
 * @property string $native_name
 * @property-read Carbon $created_at
 * @property-read Carbon $updated_at
 */
class Language extends Model
{
    /** @var string[] */
    protected $fillable = [
        'iso639_code',
        'iso3166_code',
        'english_name',
        'native_name',
    ];

    /** @var string[] */
    protected $dates = [
        'created_at',
        'updated_at',
    ];
}
