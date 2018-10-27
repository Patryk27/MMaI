<?php

namespace App\Attachments\Models;

use App\Attachments\Models\Interfaces\Attachable;
use App\Attachments\Presenters\AttachmentPresenter;
use App\Core\Models\HasPresenter;
use App\Core\Models\Presentable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property-read int $id
 * @property string|null $attachable_type
 * @property int|null $attachable_id
 * @property string $name
 * @property string $mime
 * @property int $size
 * @property string $path
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * -----
 *
 * @property-read Model|Attachable|null $attachable
 *
 * -----
 *
 * @method AttachmentPresenter getPresenter()
 */
class Attachment extends Model implements Presentable
{

    use HasPresenter;

    /**
     * @var string[]
     */
    protected $fillable = [
        'attachable_type',
        'attachable_id',
        'name',
        'mime',
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

    /**
     * @inheritDoc
     */
    public static function getPresenterClass(): string
    {
        return AttachmentPresenter::class;
    }

}
