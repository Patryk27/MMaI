<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * This model describes a single language.
 *
 * It contains the language's slug (e.g. "pl"), ISO name (e.g. "pl-PL") and its
 * translated name (e.g. "Polski" or "English").
 *
 * -----
 *
 * @property-read int $id
 * @property string $slug
 * @property string $iso_name
 * @property string $english_name
 * @property string $translated_name
 * @property-read Carbon $created_at
 * @property-read Carbon $updated_at
 */
class Language extends Model
{

    /**
     * @var string[]
     */
    protected $fillable = [
        'slug',
        'iso_name',
        'english_name',
        'translated_name',
    ];

    /**
     * @var string[]
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

}