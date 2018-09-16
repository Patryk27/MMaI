<?php

namespace App\Attachments\Models;

use App\Attachments\Models\Interfaces\Attachable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property-read int $id
 * @property string|null $attachable_type
 * @property int|null $attachable_id
 * @property string $status
 * @property string $name
 * @property int $size
 * @property string $path
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * -----
 *
 * @property-read Model|Attachable|null $attachable
 */
class Attachment extends Model
{

    public const
        STATUS_HIDDEN = 'hidden',
        STATUS_VISIBLE = 'visible';

    /**
     * @var string[]
     */
    protected $fillable = [
        'attachable_type',
        'attachable_id',
        'status',
        'name',
        'size',
        'path',
    ];

    /**
     * @var string[]
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function attachable()
    {
        return $this->morphTo();
    }

    /**
     * @return string
     */
    public function getSizeForHumans(): string
    {
        $size = $this->size;

        foreach (['B', 'KB', 'MB', 'GB'] as $unitIdx => $unit) {
            if ($size < 1024) {
                if ($size === (int)$size) {
                    return sprintf('%d %s', $size, $unit);
                }

                return sprintf('%.2f %s', $size, $unit);
            }

            if ($size >= 1024) {
                $size /= 1024;
            }
        }

        return sprintf('%.2f TB', $size);
    }

}
